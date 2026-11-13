<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth_log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Auth_log::catat_login_berhasil(Auth::user());

            return redirect()->intended(route('dashboard'));
        }

        Auth_log::catat_login_gagal($credentials['username']);

        return back()
            ->with('login_error', 'Username atau password salah!')
            ->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        if ($admin = Auth::user()) {
            Auth_log::catat_logout($admin);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(rtrim(url('/'), '/') . '/');
    }
}