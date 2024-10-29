<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Mot de passe sécurisé
            'role' => 'admin', // Rôle de l'utilisateur
            'imgprofile' => null, // Optionnel
            'birthday' => null, // Optionnel
            'gender' => null, // Optionnel
            'address' => null, // Optionnel
        ]);
    }
}
