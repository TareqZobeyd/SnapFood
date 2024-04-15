<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'you registered successfully.',
            'token' => $token
        ], ResponseAlias::HTTP_CREATED);
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
            'phone' => 'required|string|min:11',
        ]);

        $user = User::query()->find(auth()->user()->id);

        $user->name = $request->name;
        $user->phone = $request->phone;

        $user->save();

        return response(['message' => 'your personal information updated successfully']);
    }
}
