<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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

    public function store(StoreAddressRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $user->addresses()->create([
            'title' => $validated['title'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json(['message' => 'Your address is submitted successfully'], ResponseAlias::HTTP_CREATED);
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {

        $address->update($request->validated());

        return response()->json(['message' => 'your address is updated successfully'], ResponseAlias::HTTP_OK);
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
