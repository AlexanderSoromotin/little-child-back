<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin'
        ]);

        User::create([
            'first_name' => 'Александр',
            'last_name' => 'Соромотин',
            'email' => 'a-sorom@mail.ru',
            'role_id' => 1,
            'password' => bcrypt('12341234'),
        ]);

        User::create([
            'first_name' => 'Алексей',
            'last_name' => 'Грибанов',
            'email' => 'boss@findcreek.com',
            'role_id' => 1,
            'password' => bcrypt('12341234'),
        ]);
    }
}
