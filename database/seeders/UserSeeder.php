<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário Administrador
        User::firstOrCreate(
            ['email' => 'admin@smt.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );

        // Usuário Comum
        User::firstOrCreate(
            ['email' => 'user@smt.com'],
            [
                'name' => 'Usuário Comum',
                'password' => Hash::make('password'),
            ]
        );
    }
}