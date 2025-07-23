<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Gerson',
            'email' => 'gerheral01@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 1, // Administrador
            'email_verified_at' => now(),
        ]);

        $this->command->info('Usuario administrador creado exitosamente!');
        $this->command->info('Email: gerheral01@gmail.com');
        $this->command->info('Contraseña: 12345678');
    }
} 