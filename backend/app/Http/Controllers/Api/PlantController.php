<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        return response()->json(Plant::latest()->get());
    }

    public function store(Request $request)
    {
        $plant = Plant::create($request->all());
        return response()->json($plant, 201);
    }

    public function show(string $id)
    {
        $plant = Plant::findOrFail($id);
        return response()->json($plant);
    }

    public function update(Request $request, string $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->update($request->all());
        return response()->json($plant);
    }

    public function destroy(string $id)
    {
        Plant::destroy($id);
        return response()->json(null, 204);
    }
}
