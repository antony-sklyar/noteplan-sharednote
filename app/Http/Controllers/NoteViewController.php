<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class NoteViewController extends Controller
{
    private $errors = null;

    public function promptPassword(Note $note)
    {
        return Inertia::render('NoteNeedsPassword', [
            'formUrl' => route('note-password', $note),
            'errors' => $this->errors,
        ]);
    }

    public function sendPassword(Request $request, Note $note)
    {
        $note->password = $request->input('password');
        if (!$note->isPasswordValid()) {
            $this->errors = ['password' => 'Incorrect password'];
            return $this->promptPassword($note);
        }
        return $this->viewWithPassword($note, $note->password);
    }

    public function viewWithPassword(Note $note, string $password)
    {
        $note->password = $password;
        return Inertia::render('Note', [
            'title' => $note->title,
            'markdown' => $note->content,
            'html' => $note->htmlContent,
            'updated' => $note->updated_at->toDateTimeString(),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
