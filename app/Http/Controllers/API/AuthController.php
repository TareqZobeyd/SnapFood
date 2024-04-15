<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
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

        return new UserResource($user);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => ['email or password is incorrect.'],
            ]);
        }

        $user = Auth::user();

        return (new UserResource($user))
            ->additional([
                'message' => 'you are logged in.',
            ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response(['message' => 'logged out'], 200);
    }

    public function update(Request $request)
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
