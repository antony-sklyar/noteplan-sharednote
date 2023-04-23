<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    public function view(User $user, Note $note): bool
    {
        // TODO: check whether the note has any "whitelisted emails" access restrictions
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Note $note): bool
    {
        // TODO: check user's teammates
        return $note->user_id == $user->id;
    }

    public function delete(User $user, Note $note): bool
    {
        // TODO: check user's teammates
        return $note->user_id == $user->id;
    }
}
