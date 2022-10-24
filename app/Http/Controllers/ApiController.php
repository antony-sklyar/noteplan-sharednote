<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function publish(Request $request)
    {
        $note = PublishedNote::create([
            'guid' => Str::random(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),            
        ]);
        return response()->json(['url' => url($note->guid)]);
    }

    public function unpublish()
    {
        return response()->noContent(); // TODO
    }
}
