<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return view('user.restaurants', compact('restaurants'));
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        throw ValidationException::withMessages([
            'email' => ['invalid email or password. Please try again.'],
        ]);
    }

    public function showLogin()
    {
        return view('user.login');
    }

    public function create()
    {
        return view('user.register');
    }

    public function store(StoreUserRequest $request)
    {
        $userData = $request->validated();

        User::query()->create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => Hash::make($userData['password']),
        ]);

        return redirect('user/login')->with('success', 'registration successful. you can now log in.');
    }

    public function showLogout()
    {
        return view('auth.logout');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'شما با موفقیت از حساب کاربری خود خارج شدید.');
    }
}
