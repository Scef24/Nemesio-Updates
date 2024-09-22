<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'fname' => 'Super',
            'lname' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('fthsstem@24'), // Change this to a secure password
            'role' => 'super_admin',
        ]);
    }
}
