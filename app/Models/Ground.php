<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ground extends Model
{
    use HasFactory;

    // Define which fields are mass assignable
    protected $fillable = [
        'name',
        'description',
        'rate',
        'type_id',
        'active',
        'deleted',
        'created_on',
        'updated_on',
    ];

    public $timestamps = false;

    public function storeGround()
    {
        // Create a new ground record
        $ground = Ground::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'rate' => $validatedData['rate'],
            'type_id' => $validatedData['type_id'] ?? null,
            'active' => $validatedData['active'],
            'deleted' => $validatedData['deleted'],
            'created_on' => now(),
            'updated_on' => now(),
        ]);

        return response()->json(['message' => 'Ground created successfully!', 'data' => $ground]);
    }
}
