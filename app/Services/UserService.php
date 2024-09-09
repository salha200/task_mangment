<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }
    public function updateUser(User $user, $data)
    {
        // إذا كان كلمة المرور موجودة، يتم تحديثها مع تشفيرها
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // تحديث بيانات المستخدم
        $user->update($data);

       
        return $user;
    }

    public function deleteUser(User $user)
    {
        // Assuming you're using soft deletes
        $user->delete();
    }
}
/////////////
