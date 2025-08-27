<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el primer administrador si no existe
        if (!User::where('role', 1)->exists()) {
            User::create([
                'name' => 'Administrador Principal',
                'email' => 'gerheral01@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 1, // Administrador
            ]);

            $this->command->info('Administrador principal creado exitosamente.');
            $this->command->info('Email: gerheral01@gmail.com');
            $this->command->info('Contraseña: 12345678');
            $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer login.');
        } else {
            $this->command->info('Ya existe al menos un administrador en el sistema.');
        }
    }
}