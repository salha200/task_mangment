<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    { 
        
        
        $this->call([
        PermissionSeeder::class,
        RoleSeeder::class,
        SuperAdminSeeder::class,
    ]);

    }}