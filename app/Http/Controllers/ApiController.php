<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function publish(Request $request)
    {
        $guid = $request->input('guid');
        $accessKey = $request->input('access_key');

        if ($guid) {
            $note = PublishedNote::where([
                        'guid' => $guid,
                        'access_key' => $accessKey
                    ])->firstOrFail();
        } else {
            $guid = Str::random();
            $note = new PublishedNote();
            $note->guid = $guid;
            $note->accessKey = $accessKey;
        }

        $note->fill($request->input());
        $note->save();

        return response()->json(['url' => url($note->guid)]);
    }

    public function unpublish(Request $request)
    {
        $guid = $request->input('guid');
        $accessKey = $request->input('access_key');

        $note = PublishedNote::where([
                    'guid' => $guid,
                    'access_key' => $accessKey
                ])->firstOrFail();
        $note->delete();

        return response()->noContent();
    }
}
