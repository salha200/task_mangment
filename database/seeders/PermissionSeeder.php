<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // قائمة الأذونات
        $permissions = [
           'create-user',
            'edit-user',
            'delete-user',
            'create-task',
            'edit-task',
            'delete-task',
            'assign-task',
            'view-assigned-tasks',
            'view-all-tasks',
            'view-own-tasks',   // تم تصحيح الاسم هنا
            'update-task-status',
            'view-reports',
            'manage-roles',
            'edit-assigned-task'
        ];

        // التحقق من وجود الإذن قبل إنشائه
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'api']);
            }
        }
    }
}
