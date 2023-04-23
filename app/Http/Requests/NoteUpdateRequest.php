<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->note);
    }

    public function rules(): array
    {
        return [
            'password' => 'required|between:5,32',
            'title' => 'required',
            'content' => 'required',
        ];
    }
}
