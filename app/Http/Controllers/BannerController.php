<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = $request->file('image_path')->store('banners', 'public');

        Banner::query()->create([
            'image_path' => $imagePath,
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }}
