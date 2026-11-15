<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@findit.test'], [
            'name'     => 'Admin Find-It',
            'phone'    => '081200000000',
            'password' => 'password', // otomatis di-hash oleh cast 'hashed'
            'role'     => 'admin',
        ]);

        User::updateOrCreate(['email' => 'user@findit.test'], [
            'name'     => 'Mahasiswa Contoh',
            'phone'    => '081311111111',
            'password' => 'password',
            'role'     => 'user',
        ]);
    }
}
