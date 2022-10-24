<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;

class PublishedNoteController extends Controller
{
    public function view(string $guid, Request $request)
    {
        $password = $request->input('password', '');
        $note = PublishedNote::where('guid', $guid)->firstOrFail();
        $content = $this->decrypt($note->content, $password);
        return view('published-note', [
            'note' => $note,
            'html' => Markdown::parse($content),
        ]);
    }

    protected function decrypt(string $content, string $password): string
    {
        return $content; // TODO
    }
}
