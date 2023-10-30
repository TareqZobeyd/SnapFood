<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
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

        return redirect('/')->with('success', 'Seller registration successful.');
    }
}
