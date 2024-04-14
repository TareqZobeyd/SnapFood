<?php

namespace App\Http\Controllers;

use App\Http\Requests\BannerRequest;
use App\Http\Requests\UpdateBannerRequest;
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

    public function store(BannerRequest $request)
    {
        try {
            $imagePath = $request->file('image_path')->store('banners', 'public');
        } catch (\Exception $e) {
            return redirect()->route('admin.banners.create')->with('error', 'Error uploading image.');
        }

        Banner::query()->create([
            'image_path' => $imagePath,
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        try {
            $banner->update([
                'description' => $request->input('description'),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.banners.edit', $banner)->with('error', 'Failed to update banner.');
        }

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
