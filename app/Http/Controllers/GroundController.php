<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroundController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate' => 'required|numeric',
            'type_id' => 'nullable|exists:ground_types,id',
            'active' => 'required|boolean',
            'deleted' => 'required|boolean',
        ]);

    }
}
