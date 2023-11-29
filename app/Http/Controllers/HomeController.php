<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Food;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $foods = Food::all();
        $banners = Banner::all();

        return view('home', compact('foods', 'banners'));
    }
}
