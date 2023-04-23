<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_note_creation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/api/notes', [
                'password' => 'secret123',
                'title' => 'Lorem ipsum',
                'content' => 'Lorem **ipsum** dolor sit amet.',
            ]);

        $response->assertStatus(201)->assertJsonStructure(['promptUrl', 'viewUrl']);
    }

    public function test_note_update(): void
    {
        $user = User::factory()->create();

        $note = Note::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->put('/api/notes/' . $note->slug, [
                'password' => 'secret123',
                'title' => 'Lorem ipsum',
                'content' => 'Lorem **ipsum** dolor sit amet.',
            ]);

        $response->assertStatus(200)->assertJsonStructure(['promptUrl', 'viewUrl']);
    }

    public function test_note_delete(): void
    {
        $user = User::factory()->create();

        $note = Note::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->delete('/api/notes/' . $note->slug);

        $response->assertStatus(204);
    }
}
