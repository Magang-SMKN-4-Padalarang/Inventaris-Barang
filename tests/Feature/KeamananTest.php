<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class KeamananTest extends TestCase
{
    use RefreshDatabase;

    private function pinjamanAktif(): Peminjaman
    {
        $k = Kategori::create(['nama_kategori' => 'Elektronik']);
        $b = Barang::create([
            'kode_barcode' => 'BRG-0001', 'nama_barang' => 'Proyektor',
            'id_kategori' => $k->id_kategori, 'status_barang' => 'Dipinjam',
        ]);
        $p = Peminjam::create(['nama_peminjam' => 'Ani', 'tipe_peminjam' => 'Siswa']);

        return Peminjaman::create([
            'id_barang' => $b->id_barang, 'id_peminjam' => $p->id_peminjam,
            'tgl_pinjam' => now(), 'tgl_jatuh_tempo' => now()->addDay(), 'status_peminjaman' => 'Dipinjam',
        ]);
    }

    public function test_pengembalian_dengan_barcode_barang_ditolak(): void
    {
        $pinjam = $this->pinjamanAktif();

        $this->postJson('/peminjam/kembali/cek', ['kode' => 'BRG-0001'])->assertNotFound();
        $this->postJson('/peminjam/kembali/proses', ['kode' => 'BRG-0001'])->assertNotFound();

        $this->assertNull($pinjam->fresh()->tgl_kembali);
        $this->assertSame('Dipinjam', $pinjam->barang->fresh()->status_barang);
    }

    public function test_pengembalian_dengan_kode_struk_berhasil(): void
    {
        $pinjam = $this->pinjamanAktif();

        $this->postJson('/peminjam/kembali/proses', ['kode' => $pinjam->kode_transaksi])
            ->assertOk()->assertJson(['ok' => true]);

        $this->assertNotNull($pinjam->fresh()->tgl_kembali);
        $this->assertSame('Tersedia', $pinjam->barang->fresh()->status_barang);
    }

    public function test_kode_struk_palsu_ditolak(): void
    {
        $pinjam = $this->pinjamanAktif();
        $palsu = preg_replace('/-[A-Z0-9]{12}$/', '-AAAAAAAAAAAA', $pinjam->kode_transaksi);

        $this->postJson('/peminjam/kembali/proses', ['kode' => $palsu])->assertNotFound();
    }

    public function test_header_keamanan_terpasang(): void
    {
        $res = $this->get('/login');

        $res->assertHeader('X-Frame-Options', 'DENY');
        $res->assertHeader('X-Content-Type-Options', 'nosniff');
        $res->assertHeader('Referrer-Policy');
        $this->assertStringContainsString("frame-ancestors 'none'", $res->headers->get('Content-Security-Policy'));
        $this->assertNull($res->headers->get('Strict-Transport-Security'), 'HSTS tidak boleh dikirim lewat HTTP');
    }

    public function test_hsts_hanya_lewat_https(): void
    {
        $this->get('https://localhost/login')->assertHeader('Strict-Transport-Security');
    }

    public function test_seeder_menolak_production_tanpa_password(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['app.admin_password' => null]);

        $this->expectException(RuntimeException::class);
        (new AdminSeeder)->run();
    }

    public function test_seeder_memakai_password_dari_env_dan_tidak_menimpa(): void
    {
        config(['app.admin_password' => 'PasswordKuat123']);
        (new AdminSeeder)->run();

        $admin = Admin::where('username', 'admin')->first();
        $this->assertTrue(Hash::check('PasswordKuat123', $admin->password));

        config(['app.admin_password' => 'YangLain12345']);
        (new AdminSeeder)->run();

        $this->assertTrue(Hash::check('PasswordKuat123', $admin->fresh()->password), 'seed ulang tidak boleh menimpa password');
    }

    public function test_perintah_ganti_password_admin(): void
    {
        Admin::create(['nama_admin' => 'A', 'username' => 'admin', 'password' => 'admin123']);

        $this->artisan('admin:reset-password')
            ->expectsQuestion('Password baru (min. 8 karakter)', 'PasswordBaru99')
            ->expectsQuestion('Ulangi password baru', 'PasswordBaru99')
            ->assertExitCode(0);

        $this->assertTrue(Hash::check('PasswordBaru99', Admin::first()->password));
    }

    public function test_perintah_ganti_password_menolak_password_pendek(): void
    {
        Admin::create(['nama_admin' => 'A', 'username' => 'admin', 'password' => 'admin123']);

        $this->artisan('admin:reset-password')
            ->expectsQuestion('Password baru (min. 8 karakter)', 'pendek')
            ->expectsQuestion('Ulangi password baru', 'pendek')
            ->assertExitCode(1);

        $this->assertTrue(Hash::check('admin123', Admin::first()->password));
    }
}
