<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $foods = Food::all();
        return view('home', compact('foods'));
    }
}
