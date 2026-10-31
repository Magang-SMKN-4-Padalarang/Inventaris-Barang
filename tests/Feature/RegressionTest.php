<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegressionTest extends TestCase
{
    use RefreshDatabase;

    private function barang(): Barang
    {
        $k = Kategori::create(['nama_kategori' => 'Elektronik']);

        return Barang::create([
            'kode_barcode' => 'BRG-0001', 'nama_barang' => 'Proyektor',
            'id_kategori' => $k->id_kategori, 'status_barang' => 'Tersedia',
        ]);
    }

    private function admin(): Admin
    {
        return Admin::create(['nama_admin' => 'A', 'username' => 'admin', 'password' => 'rahasia123']);
    }

    public static function tipe(): array
    {
        return [
            'Siswa'   => ['Siswa', ['nis' => '123', 'kelas' => 'XI RPL']],
            'Guru'    => ['Guru', []],
            'Staff'   => ['Staff', ['bagian' => 'TU']],
            'Lainnya' => ['Lainnya', ['no_hp' => '0812', 'keperluan' => 'Rapat']],
        ];
    }

    #[DataProvider('tipe')]
    public function test_semua_tipe_peminjam_bisa_pinjam(string $tipe, array $extra): void
    {
        $this->barang();

        $this->post('/peminjam/pinjam', array_merge([
            'tipe_peminjam' => $tipe, 'kode_barcode' => 'BRG-0001', 'nama_peminjam' => 'Budi',
        ], $extra))->assertRedirectContains('/peminjam/struk/');

        $this->assertSame($tipe, Peminjam::first()->tipe_peminjam);
    }

    public function test_riwayat_admin_bisa_dibuka(): void
    {
        $this->actingAs($this->admin())->get('/admin/riwayat')->assertOk();
    }

    public function test_catat_cetak_massal_memakai_auth(): void
    {
        $b = $this->barang();

        $this->actingAs($this->admin())
            ->post(route('barang.cetak-massal.catat'), ['ids' => [$b->id_barang]])
            ->assertNoContent();
    }

    public function test_dashboard_admin_menghitung_barang_lewat_jatuh_tempo(): void
    {
        $b = $this->barang();
        $p = Peminjam::create(['nama_peminjam' => 'Ani', 'tipe_peminjam' => 'Siswa']);
        $b->update(['status_barang' => 'Dipinjam']);
        Peminjaman::create([
            'id_barang' => $b->id_barang, 'id_peminjam' => $p->id_peminjam,
            'tgl_pinjam' => now()->subDays(3), 'tgl_jatuh_tempo' => now()->subDays(2),
            'status_peminjaman' => 'Dipinjam',
        ]);

        $this->actingAs($this->admin())->get('/dashboard')
            ->assertViewHas('terlambat', 1)
            ->assertViewHas('sedangDipinjam', 1);

        $this->get('/peminjam')->assertViewHas('terlambat', 1);
    }

    public function test_throttle_login_memakai_ip_asli_di_belakang_proxy(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            $res = $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
                ->withHeaders(['X-Forwarded-For' => "203.0.113.$i"])
                ->post('/login', ['username' => "orang$i", 'password' => 'x']);

            $this->assertNotSame(429, $res->status(), "klien #$i terkena throttle klien lain");
        }
    }
}
