<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء دور Admin
        $admin = Role::create(['name' => 'Admin','guard_name' => 'api',]);
        
        // إنشاء دور Manager
        $manager = Role::create(['name' => 'Manager','guard_name' => 'api',]);
        
        // إنشاء دور User
        $user = Role::create(['name' => 'User','guard_name' => 'api',]);

        // الصلاحيات التي يتم منحها للـ Admin
        $adminPermissions = [
            'create-user',
            'edit-user',
            'delete-user',
            'create-task',
            'edit-task',
            'delete-task',
            'assign-task',
            'view-all-tasks',
            'manage-roles',
            'view-reports'
        ];

        // الصلاحيات التي يتم منحها للـ Manager
        $managerPermissions = [
            'create-task',
            'edit-task', 
            'edit-assigned-task',
            'assign-task',
            'view-own-tasks',
        ];

        // الصلاحيات التي يتم منحها للـ User
        $userPermissions = [
            'edit-task',
            'view-assigned-tasks',
            'update-task-status',
        ];

        // إعطاء جميع الصلاحيات لدور الـ Admin
        foreach ($adminPermissions as $permission) {
            if (Permission::where('name', $permission)->doesntExist()) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
        $admin->syncPermissions($adminPermissions);

        // إعطاء الصلاحيات الخاصة بالـ Manager
        foreach ($managerPermissions as $permission) {
            if (Permission::where('name', $permission)->doesntExist()) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
        $manager->syncPermissions($managerPermissions);

        // إعطاء الصلاحيات الخاصة بالـ User
        foreach ($userPermissions as $permission) {
            if (Permission::where('name', $permission)->doesntExist()) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
        $user->syncPermissions($userPermissions);
    }
}
