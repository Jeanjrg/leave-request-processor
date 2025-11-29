<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'), // Ganti 'password' dengan password kuat di produksi!
            'role' => 'Admin',
            'initial_leave_quota' => 0, // Admin biasanya tidak punya kuota cuti di sistem ini
            'current_leave_quota' => 0,
            // division_id akan null
        ]);

        // 2. Akun HRD
        User::create([
            'name' => 'HRD Manager',
            'email' => 'hrd@email.com',
            'password' => Hash::make('password'),
            'role' => 'HRD',
            'initial_leave_quota' => 12, // HRD memiliki kuota jika mereka juga karyawan
            'current_leave_quota' => 12,
        ]);

        // 3. Akun Ketua Divisi (Contoh)
        User::create([
            'name' => 'Division Leader A',
            'email' => 'leader@email.com',
            'password' => Hash::make('password'),
            'role' => 'Ketua Divisi',
            'initial_leave_quota' => 12,
            'current_leave_quota' => 12,
        ]);
    }
}
