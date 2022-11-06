<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    private function doEncrypt($content, $password)
    {
        return $content; // TODO
    }

    private function doStoreNote(Request $request, PublishedNote $note)
    {
        $password = $request->input('password');
        $note->title = $this->doEncrypt($request->input('title'), $password);
        $note->content = $this->doEncrypt($request->input('content'), $password);
        $note->theme = $request->input('theme');
        $note->save();
    }

    private function noteResponse(PublishedNote $note)
    {
        return response()->json(['url' => url($note->guid)]);
    }

    public function store(Request $request)
    {
        $accessKey = $request->input('access_key');
        $password = $request->input('password');
        $guid = Str::random();

        $note = new PublishedNote();
        $note->guid = $guid;
        $note->access_key = $accessKey;
        $this->doStoreNote($request, $note);

        return $this->noteResponse($note);
    }

   public function update(Request $request)
    {
        $guid = $request->input('guid');
        $accessKey = $request->input('access_key');
        $password = $request->input('password');

        $note = PublishedNote::where([
                    'guid' => $guid,
                    'access_key' => $accessKey
                ])->firstOrFail();
        $this->doStoreNote($request, $note);

        return $this->noteResponse($note);
    }

    public function destroy(Request $request)
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
