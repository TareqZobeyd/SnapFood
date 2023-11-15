<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->addresses->map(function ($address) {
            return [
                'id' => $address->id,
                'title' => $address->title,
                'address' => $address->address,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
            ];
        });
        return response($addresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'address' => 'required|string|unique:addresses',
            'latitude' => 'required|numeric|unique:addresses',
            'longitude' => 'required|numeric|unique:addresses',
        ]);

        $address = User::query()->find(auth()->user()->id)->addresses()->create([
            'title' => $fields['title'],
            'address' => $fields['address'],
            'latitude' => $fields['latitude'],
            'longitude' => $fields['longitude'],
        ]);

        return response(['Message' => 'Your address is submitted successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $address = Address::query()->find($id);
        $request->validate([
            'title' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        $address->update($request->all());
        return response(['Message' => 'Your address is updated successfully']);
    }

    public function setActiveAddress($id)
    {
        $specificAddress = Address::query()->findOrFail($id);
        $user = auth()->user();

        if ($user->id === $specificAddress->addressable_id && $specificAddress->addressable_type === 'App\\Models\\User') {
            $user->addresses()->update(['active' => '0']);
            $specificAddress->update(['active' => '1']);
            return response([
                'Message' => 'Your main address is updated'
            ]);
        }
        return response([
            'Message' => "You don't have access to update this address"
        ], 403);
    }
}
