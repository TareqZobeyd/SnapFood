<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AddressController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->addresses;

        if ($addresses->count() === 0) {
            return response(['message' => 'you dont have any addresses.']);
        }

        $formattedAddresses = $addresses->map(function ($address) {
            return [
                'id' => $address->id,
                'title' => $address->title,
                'address' => $address->address,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
            ];
        });

        return response($formattedAddresses);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $fields = $request->validate([
            'title' => 'required|string|unique:addresses,title,NULL,id,addressable_id,' . $user->id,
            'address' => 'required|string|unique:addresses,address,NULL,id,addressable_id,'
                . auth()->user()->id . ',latitude,' . $request->latitude . ',longitude,' . $request->longitude,
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        User::query()->find(auth()->user()->id)->addresses()->create([
            'title' => $fields['title'],
            'address' => $fields['address'],
            'latitude' => $fields['latitude'],
            'longitude' => $fields['longitude'],
        ]);

        return response(['message' => 'your address is submitted successfully']);
    }

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
        return response(['message' => 'your address is updated successfully']);
    }

    public function setActiveAddress($id)
    {
        $specificAddress = Address::query()->findOrFail($id);
        $user = auth()->user();

        if ($user->id === $specificAddress->addressable_id && $specificAddress->addressable_type === 'App\\Models\\User') {
            $user->addresses()->update(['active' => '0']);
            $specificAddress->update(['active' => '1']);
            return response([
                'message' => 'your main address is updated'
            ]);
        }
        return response([
            'message' => "you don't have access to update this address"
        ], 403);
    }
}
