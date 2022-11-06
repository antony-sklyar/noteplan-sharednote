<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Http\Request;

class PublishedNoteController extends Controller
{
    private function doDecrypt($content, $password)
    {
        return $content; // TODO
    }

    public function view(string $guid, Request $request)
    {
        $password = $request->input('password');
        if (!$password) {
            return view('published-password');
        }

        $note = PublishedNote::where('guid', $guid)->firstOrFail();
        return view('published-note', [
            'title' => $this->doDecrypt($note->title, $password),
            'content' => $this->doDecrypt($note->content, $password),
            'theme' => $note->theme,
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
        ]);
    }
}
