<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Http\Request;

class PublishedNoteController extends Controller
{
    public function view(string $guid, Request $request)
    {
        $note = PublishedNote::where('guid', $guid)->firstOrFail();
        return view('published-note', [
            'note' => $note,
            'password' => $request->input('password'),
        ]);
    }

    protected function decrypt(string $content, string $password): string
    {
        return $content; // TODO
    }
}
