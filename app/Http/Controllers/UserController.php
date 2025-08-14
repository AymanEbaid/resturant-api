<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Trait\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use apiresponse;

    // عرض بيانات المستخدم الحالي (البروفايل)
    public function show(Request $request)
    {
        return $this->success($request->user(), 'User profile retrieved successfully');
    }

    // تحديث بيانات المستخدم الحالي
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $this->success($user, 'User profile updated successfully');
    }

    // للمشرف: عرض كل المستخدمين
    public function index()
    {
        $users = User::all();
        return $this->success($users, 'Users retrieved successfully');
    }
}
