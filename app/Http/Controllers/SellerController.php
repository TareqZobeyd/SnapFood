<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SellerController extends Controller
{
    public function showLogin()
    {
        return view('seller.login');
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
        return view('seller.register');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:sellers,email',
            'phone' => 'required|string|min:11',
            'password' => 'required|min:6',
        ]);

        Seller::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect('/login')->with('success', 'Registration successful. You can now log in.');
    }
}
