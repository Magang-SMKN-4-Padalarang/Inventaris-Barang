<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = config('app.admin_password');

        if (blank($password)) {
            if (app()->environment('production')) {
                throw new RuntimeException(
                    'ADMIN_PASSWORD belum diisi. Isi di environment sebelum menjalankan seeder di production.'
                );
            }

            // Hanya untuk pengembangan lokal.
            $password = 'admin123';
        }

        if (strlen($password) < 8) {
            throw new RuntimeException('ADMIN_PASSWORD minimal 8 karakter.');
        }

        // firstOrCreate: menjalankan seeder ulang tidak menimpa password admin
        // yang sudah diganti. Untuk mengganti: php artisan admin:reset-password
        Admin::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama_admin' => 'Administrator',
                'password'   => $password,
            ]
        );
    }
}
