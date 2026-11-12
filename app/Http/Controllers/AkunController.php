<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    public function index()
    {
        return view('admin/Akun', [
            'user'   => Auth::user(),
            'admins' => Admin::orderBy('nama_admin')->get(),
        ]);
    }

    // Edit nama, username, dan foto milik sendiri
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash',
                           Rule::unique('admin', 'username')->ignore($user->id_admin, 'id_admin')],
            'foto'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required'       => 'Nama wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah dipakai.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan underscore.',
            'foto.image'          => 'File harus berupa gambar.',
            'foto.mimes'          => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'foto.max'            => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user->nama_admin = $data['name'];
        $user->username   = $data['username'];

        if ($request->boolean('hapus_foto') && $user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->foto = null;
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('foto-admin', 'public');
        }

        $user->save();

        return redirect()->route('akun')->with('sukses', 'Profil berhasil diperbarui.');
    }

    // Ganti password milik sendiri
    public function updatePassword(Request $request)
    {
        $request->validateWithBag('password', [
            'password_lama' => ['required', 'current_password'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password_lama.required'         => 'Password lama wajib diisi.',
            'password_lama.current_password' => 'Password lama salah.',
            'password.required'              => 'Password baru wajib diisi.',
            'password.min'                   => 'Password baru minimal 6 karakter.',
            'password.confirmed'             => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();
        $user->password = $request->password; // otomatis di-hash oleh cast
        $user->save();

        return redirect()->route('akun')->with('sukses', 'Password berhasil diganti.');
    }

    // Tambah admin baru
    public function tambah(Request $request)
    {
        $data = $request->validateWithBag('tambah', [
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:admin,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'foto'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required'       => 'Nama wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah dipakai.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan underscore.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'foto.image'          => 'File harus berupa gambar.',
            'foto.mimes'          => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'foto.max'            => 'Ukuran foto maksimal 2 MB.',
        ]);

        $admin = new Admin();
        $admin->nama_admin = $data['name'];
        $admin->username   = $data['username'];
        $admin->password   = $data['password']; // otomatis di-hash oleh cast

        if ($request->hasFile('foto')) {
            $admin->foto = $request->file('foto')->store('foto-admin', 'public');
        }

        $admin->save();

        return redirect()->route('akun')->with('sukses', 'Admin baru berhasil ditambahkan.');
    }

    // Hapus admin lain
    public function hapus(Admin $admin)
    {
        if ($admin->id_admin === Auth::user()->id_admin) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        if ($admin->foto) {
            Storage::disk('public')->delete($admin->foto);
        }

        $admin->delete();

        return back()->with('sukses', 'Admin berhasil dihapus.');
    }
}