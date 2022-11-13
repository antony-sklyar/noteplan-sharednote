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
        $content = preg_replace('/\s+>(today|\d{4}\-\d{2}\-\d{2})\n/', " <span class='scheduled'>🗓️ $1</span>\n", $content);
        $content = preg_replace('/\- \[ \]\s+([^\n]+)/', '<li class="task">$1</li>', $content);
        $content = preg_replace('/\- \[x\]\s+([^\n]+)/', '<li class="task completed">$1</li>', $content);
        $content = preg_replace('/\- \[\-\]\s+([^\n]+)/', '<li class="task canceled">$1</li>', $content);
        $content = preg_replace('/\- \[>\]\s+([^\n]+)/', '<li class="task scheduled">$1</li>', $content);
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
