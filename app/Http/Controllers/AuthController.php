<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\WelcomeUser;
use App\Trait\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use apiresponse;
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'user',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $token = $user->createToken('api-token')->plainTextToken;
        $user->notify(new  WelcomeUser());
        return $this->success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',

        ], 'User registered successfully', 201);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8',
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->error('Invalid credentials', 401);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return $this->success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',

        ], 'User logged in  successfully', 200);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $request->user()->currentAccessToken()->delete();
            return $this->success([], 'User logged out successfully', 200);
        }
        return $this->error('Unauthorized', 401);
    }
}
