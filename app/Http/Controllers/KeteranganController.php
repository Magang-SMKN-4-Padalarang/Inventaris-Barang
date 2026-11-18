<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use App\Models\Kategori;
use App\Support\Kode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KeteranganController extends Controller
{
    private const TABEL = 'barang';
    private const KOLOM = [
        'ruang'  => 'ruangan',
        'tempat' => 'lemari',
    ];
    private const MAKS = 50;

    public function index()
    {
        $kategori = Kategori::withCount('barang')
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.keterangan.index', [
            'kategori'     => $kategori,
            'daftarRuang'  => $this->infoEnum('ruang')['nilai'],
            'daftarLemari' => $this->infoEnum('tempat')['nilai'],
            'pakaiRuang'   => $this->pemakaian('ruang'),
            'pakaiLemari'  => $this->pemakaian('tempat'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori,nama_kategori'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        $kategori = Kategori::create($data);

        $this->catat(
            'tambah',
            'kategori',
            $kategori->id_kategori,
            "Tambah kategori {$kategori->id_kategori} ({$kategori->nama_kategori})"
        );

        return $this->selesai('kategori', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori', 'nama_kategori')->ignore($kategori->id_kategori, 'id_kategori'),
            ],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        $kategori->fill($data);

        if (! $kategori->isDirty()) {
            return redirect()->route('keterangan.index')->with(['info' => 'Data tidak berubah.', 'tab' => 'kategori']);
        }

        $lama = $kategori->getOriginal('nama_kategori');
        $kategori->save();

        $this->catat(
            'ubah',
            'kategori',
            $kategori->id_kategori,
            "ubah Kategori: '{$lama}' → '{$kategori->nama_kategori}'"
        );

        return $this->selesai('kategori', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barang()->exists()) {
            return $this->gagal('kategori', 'Kategori tidak bisa dihapus karena masih dipakai oleh barang.');
        }

        $this->catat(
            'hapus',
            'kategori',
            $kategori->id_kategori,
            "Hapus kategori {$kategori->nama_kategori}"
        );
        $kategori->delete();

        return $this->selesai('kategori', 'Kategori berhasil dihapus.');
    }

    public function ruangStore(Request $request): RedirectResponse
    {
        return $this->enumStore('ruang', $request);
    }

    public function ruangUpdate(Request $request, string $nilai): RedirectResponse
    {
        return $this->enumUpdate('ruang', $nilai, $request);
    }

    public function ruangDestroy(string $nilai): RedirectResponse
    {
        return $this->enumDestroy('ruang', $nilai);
    }

    public function tempatStore(Request $request): RedirectResponse
    {
        return $this->enumStore('tempat', $request);
    }

    public function tempatUpdate(Request $request, string $nilai): RedirectResponse
    {
        return $this->enumUpdate('tempat', $nilai, $request);
    }

    public function tempatDestroy(string $nilai): RedirectResponse
    {
        return $this->enumDestroy('tempat', $nilai);
    }

    private function enumStore(string $jenis, Request $request): RedirectResponse
    {
        $baru = $this->bersihkan((string) $request->input('nilai'));
        if ($baru === '') {
            return $this->gagal($jenis, "Nama {$jenis} wajib diisi.");
        }

        $info = $this->infoEnum($jenis);
        if ($this->sudahAda($jenis, $baru, $info['nilai'])) {
            return $this->gagal($jenis, ucfirst($jenis) . " {$baru} sudah ada.");
        }

        $this->tulisEnum($jenis, [...$info['nilai'], $baru], $info);

        $this->catat('tambah', $jenis, 0, 'Tambah ' . $jenis . ' ' . $baru);

        return $this->selesai($jenis, ucfirst($jenis) . ' berhasil ditambahkan.');
    }

    private function enumUpdate(string $jenis, string $lama, Request $request): RedirectResponse
    {
        $info = $this->infoEnum($jenis);
        if (! in_array($lama, $info['nilai'], true)) {
            return $this->gagal($jenis, ucfirst($jenis) . ' tidak ditemukan.');
        }

        $baru = $this->bersihkan((string) $request->input('nilai'));
        if ($baru === '') {
            return $this->gagal($jenis, "Nama {$jenis} wajib diisi.");
        }
        if ($baru === $lama || $baru === $this->label($jenis, $lama)) {
            return redirect()->route('keterangan.index')->with(['info' => 'Data tidak berubah.', 'tab' => $jenis]);
        }

        $lainnya = array_values(array_filter($info['nilai'], fn ($v) => $v !== $lama));
        if ($this->sudahAda($jenis, $baru, $lainnya)) {
            return $this->gagal($jenis, ucfirst($jenis) . " {$baru} sudah ada.");
        }

        $pakai = (int) ($this->pemakaian($jenis)[$lama] ?? 0);
        if ($pakai > 0) {
            return $this->gagal($jenis, "{$lama} masih dipakai oleh {$pakai} unit sehingga namanya tidak bisa diubah.");
        }

        $nilai = array_map(fn ($v) => $v === $lama ? $baru : $v, $info['nilai']);
        $this->tulisEnum($jenis, $nilai, $info);

        $this->catat('ubah', $jenis, 0, "ubah " . ucfirst($jenis) . ": '{$lama}' → '{$baru}'");

        return $this->selesai($jenis, ucfirst($jenis) . ' berhasil diperbarui.');
    }

    private function enumDestroy(string $jenis, string $nilai): RedirectResponse
    {
        $info = $this->infoEnum($jenis);
        if (! in_array($nilai, $info['nilai'], true)) {
            return $this->gagal($jenis, ucfirst($jenis) . ' tidak ditemukan.');
        }

        $pakai = (int) ($this->pemakaian($jenis)[$nilai] ?? 0);
        if ($pakai > 0) {
            return $this->gagal($jenis, ucfirst($jenis) . " tidak bisa dihapus karena masih dipakai oleh {$pakai} unit barang.");
        }

        $sisa = array_values(array_filter($info['nilai'], fn ($v) => $v !== $nilai));
        if (! $sisa) {
            return $this->gagal($jenis, "Minimal harus ada satu {$jenis}.");
        }

        $this->tulisEnum($jenis, $sisa, $info);

        $this->catat('hapus', $jenis, 0, 'Hapus ' . $jenis . ' ' . $nilai);

        return $this->selesai($jenis, ucfirst($jenis) . ' berhasil dihapus.');
    }

    private function bersihkan(string $teks): string
    {
        return mb_substr(Kode::rapikan($teks), 0, self::MAKS);
    }

    private function label(string $jenis, string $nilai): string
    {
        return $jenis === 'ruang' ? Kode::labelRuang($nilai) : Kode::labelTempat($nilai);
    }

    private function kunci(string $jenis, string $nilai): string
    {
        return Kode::singkat($this->label($jenis, $nilai));
    }

    private function sudahAda(string $jenis, string $baru, array $daftar): bool
    {
        $kunciBaru = $this->kunci($jenis, $baru);

        foreach ($daftar as $v) {
            if ($v === $baru || $this->kunci($jenis, $v) === $kunciBaru) {
                return true;
            }
        }

        return false;
    }

    private function infoEnum(string $jenis): array
    {
        $kolom = self::KOLOM[$jenis];
        $row = DB::selectOne('SHOW COLUMNS FROM `' . self::TABEL . '` WHERE Field = ?', [$kolom]);
        $row = $row ? array_change_key_case((array) $row, CASE_LOWER) : [];

        preg_match_all("/'((?:[^']|'')*)'/", $row['type'] ?? '', $cocok);
        $nilai = array_map(fn ($v) => str_replace("''", "'", $v), $cocok[1] ?? []);

        return [
            'nilai'      => $nilai,
            'boleh_null' => ($row['null'] ?? 'NO') === 'YES',
            'default'    => $row['default'] ?? null,
        ];
    }

    private function tulisEnum(string $jenis, array $nilai, array $info): void
    {
        $kolom = self::KOLOM[$jenis];
        $pdo = DB::getPdo();

        $daftar = implode(',', array_map(fn ($v) => $pdo->quote($v), $nilai));
        $sql = 'ALTER TABLE `' . self::TABEL . "` MODIFY `{$kolom}` ENUM({$daftar}) "
             . ($info['boleh_null'] ? 'NULL' : 'NOT NULL');

        if ($info['default'] !== null && in_array($info['default'], $nilai, true)) {
            $sql .= ' DEFAULT ' . $pdo->quote($info['default']);
        }

        DB::statement($sql);
    }

    private function pemakaian(string $jenis): array
    {
        $kolom = self::KOLOM[$jenis];

        return DB::table(self::TABEL)
            ->select($kolom, DB::raw('COUNT(*) as total'))
            ->whereNotNull($kolom)
            ->groupBy($kolom)
            ->pluck('total', $kolom)
            ->map(fn ($n) => (int) $n)
            ->all();
    }

    private function catat(string $aksi, string $tabel, $id, string $keterangan): void
    {
        try {
            Activity_log::catat_aktivitas_admin($aksi, $tabel, $id, $keterangan);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function selesai(string $tab, string $pesan): RedirectResponse
    {
        return redirect()->route('keterangan.index')->with(['success' => $pesan, 'tab' => $tab]);
    }

    private function gagal(string $tab, string $pesan): RedirectResponse
    {
        return redirect()->route('keterangan.index')->with(['error' => $pesan, 'tab' => $tab]);
    }
}