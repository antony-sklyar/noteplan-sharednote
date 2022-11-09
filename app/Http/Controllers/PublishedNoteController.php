<?php

namespace App\Http\Controllers;

use App\Models\PublishedNote;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class PublishedNoteController extends Controller
{
    private function doDecrypt($content, $password)
    {
        $newEncrypter = new Encrypter($password, 'AES-256-CBC');
        return $newEncrypter->decrypt($content);
    }

    private function doPreprocessMarkdown($content)
    {
        $content = preg_replace('/%%(([^%]%?)[^%]+)%%/', '<span style="color: gray">$1</span>', $content);
        $content = preg_replace('/::(([^:]:?)[^:]+)::/', '<mark>$1</mark>', $content);
        $content = preg_replace('/~~(([^~]~?)[^~]+)~~/', '<strike>$1</strike>', $content);
        $content = preg_replace('/~(([^~]~?)[^~]+)~/', '<u>$1</u>', $content);
        return Markdown::parse($content);
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
            'content' => $this->doPreprocessMarkdown($this->doDecrypt($note->content, $password)),
            'theme' => $note->theme,
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
        ]);
    }
}
