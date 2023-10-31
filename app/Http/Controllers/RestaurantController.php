<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        // List of all foods
    }

    public function create()
    {
        // Show the form to create a new food
    }

    public function store(Request $request)
    {
        // Store a new food in the database
    }

    public function show($id)
    {
        // Display a specific food
    }

    public function edit($id)
    {
        // Show the edit form for a specific food
    }

    public function update(Request $request, $id)
    {
        // Update a specific food in the database
    }

    public function destroy($id)
    {
        // Delete a specific food from the database
    }
}
