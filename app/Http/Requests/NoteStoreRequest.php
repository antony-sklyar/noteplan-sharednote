<?php

namespace App\Http\Requests;

use App\Models\Note;

class NoteStoreRequest extends NoteUpdateRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Note::class);
    }
}
