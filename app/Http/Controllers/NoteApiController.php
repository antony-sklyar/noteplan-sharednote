<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteDestroyRequest;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Support\Str;

class NoteApiController extends Controller
{
    private function storeAndReturnNote(Note $note, NoteUpdateRequest $request): NoteResource
    {
        $note->password = $request->input('password');
        $note->title = $request->input('title');
        $note->content = $request->input('content');
        $note->save();

        return new NoteResource($note);
    }

    public function store(NoteStoreRequest $request)
    {
        $note = new Note();
        $note->slug = Str::random();
        $note->user()->associate($request->user());

        return $this->storeAndReturnNote($note, $request);
    }

    public function update(NoteUpdateRequest $request, Note $note)
    {
        return $this->storeAndReturnNote($note, $request);
    }

    public function destroy(NoteDestroyRequest $request, Note $note)
    {
        $note->delete();

        return response()->noContent();
    }
}
