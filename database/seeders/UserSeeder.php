<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('arjuna123')]
        );

        User::updateOrCreate(
            ['username' => 'Renaldi'],
            ['password' => Hash::make('Renaldi123!@#')]
        );
    }    
}
