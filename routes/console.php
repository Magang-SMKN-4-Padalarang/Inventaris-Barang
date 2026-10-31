<?php

use App\Models\Admin;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:reset-password {username=admin}', function (string $username) {
    $admin = Admin::where('username', $username)->first();

    if (! $admin) {
        $this->error("Admin '{$username}' tidak ditemukan.");

        return 1;
    }

    $password   = (string) $this->secret('Password baru (min. 8 karakter)');
    $konfirmasi = (string) $this->secret('Ulangi password baru');

    if (strlen($password) < 8) {
        $this->error('Password minimal 8 karakter.');

        return 1;
    }

    if ($password !== $konfirmasi) {
        $this->error('Konfirmasi password tidak sama.');

        return 1;
    }

    $admin->update(['password' => $password]);

    $this->info("Password admin '{$username}' berhasil diganti.");

    return 0;
})->purpose('Ganti password akun admin');
