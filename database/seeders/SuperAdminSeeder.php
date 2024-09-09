<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating  Admin User
        $admin= User::create([
            'name' => 'salha', 
            'email' => 'salhamaa@gmail.com',
            'password' => Hash::make('123456789'),
           
            
        ]);
        $admin->assignRole('Admin');
    
        // Creating employee User
        $manager = User::create([
            'name' => 'Syed Ahsan Kamal', 
            'email' => 'ahsan@allphptricks.com',
            'password' => Hash::make('ahsan1234'),
           
        ]);
        $manager->assignRole('Manager');

        // Creating customer User
        $user = User::create([
            'name' => 'Abdul Muqeet', 
            'email' => 'muqeet@allphptricks.com',
            'password' => Hash::make('muqeet1234'),

        ]);
        $user->assignRole('User');
       
       
    }
}
