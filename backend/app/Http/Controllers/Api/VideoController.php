<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return response()->json(Video::latest()->get());
    }

    public function store(Request $request)
    {
        $video = Video::create($request->all());
        return response()->json($video, 201);
    }

    public function show(string $id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video);
    }

    public function update(Request $request, string $id)
    {
        $video = Video::findOrFail($id);
        $video->update($request->all());
        return response()->json($video);
    }

    public function destroy(string $id)
    {
        Video::destroy($id);
        return response()->json(null, 204);
    }
}
