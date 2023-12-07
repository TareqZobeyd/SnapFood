<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users|email',
            'phone' => 'required|string|min:11',
            'password' => 'required|string',
        ]);

        $user = User::query()->create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'phone' => $fields['phone'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response(['message' => 'you registered successfully', 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()->where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['email or password are incorrect.'],
            ]);
        }
        return response(['message' => 'you are logged in', 'token' => $user->createToken('authToken')->plainTextToken]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response(['Message' => 'logged out'], 200);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string|min:11',
            'password' => 'required|string',
        ]);

        $user = User::query()->find(auth()->user()->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);

        $user->save();

        return response(['message' => 'your personal information updated successfully', 'user' => $user]);
    }
}
