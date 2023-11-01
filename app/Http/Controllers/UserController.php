<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function showLogin()
    {
        return view('user.login');
    }


    /**
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt([
            'email' => $request->post('email'),
            'password' => $request->post('password')
        ])) {
            return redirect('/');
        }

        throw ValidationException::withMessages([
            'email' => ['Invalid email or password. Please try again.'],
        ]);
    }

    public function create()
    {
        return view('user.register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:sellers,email',
            'phone' => 'required|string|min:11',
            'password' => 'required|min:6',
        ]);

        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
        ]);
        if ($user->is_seller) {
            $sellerRole = Role::query()->where('name', 'user')->first();
            $user->assignRole($sellerRole);
        }

        return redirect('user/login')->with('success', 'Registration successful. You can now log in.');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
