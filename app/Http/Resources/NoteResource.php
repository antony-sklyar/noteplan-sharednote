<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'promptUrl' => route('note-prompt', $this->slug),
            'viewUrl' => route('note-view', [$this->slug, $this->password]),
        ];
    }
}
