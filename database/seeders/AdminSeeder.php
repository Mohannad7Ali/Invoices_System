<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'      =>'SuperAdmin',
            'email'     =>'adminAdmin@gmail.com',
            'password'  =>Hash::make('password'),
        ]);
        User::create([
            'name' => 'Mohannad Ali',
            'email' => 'mohannad@gmail.com',
            'password' => Hash::make('mohannadali'),
        ]);
    }
}
