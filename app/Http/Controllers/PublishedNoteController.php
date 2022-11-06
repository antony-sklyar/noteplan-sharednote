<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class PublishedNoteController extends Controller
{
    private function doDecrypt($content, $password)
    {
        $newEncrypter = new Encrypter($password, 'AES-256-CBC');
        return $newEncrypter->decrypt($content);
    }

    public function view(string $guid, Request $request)
    {
        $password = $request->input('password');
        if (!$password) {
            return view('published-password');
        }

        $note = PublishedNote::where('guid', $guid)->firstOrFail();
        return view('published-note', [
            'title' => $note->title,
            'content' => $this->doDecrypt($note->content, $password),
            'theme' => $note->theme,
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
        ]);
    }
}
