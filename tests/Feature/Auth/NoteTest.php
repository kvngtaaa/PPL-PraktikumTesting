<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_notes_index_displays_notes(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/notes');

        $response->assertStatus(200);
        $response->assertSee($note->title);
    }

    public function test_notes_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/create-notes');

        $response->assertStatus(200);
    }

    public function test_users_can_create_notes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
        ]);

        $response->assertRedirect('/notes');
        $this->assertDatabaseHas('notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
            'user_id' => $user->id,
        ]);
    }

    public function test_users_can_edit_notes(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/notes/{$note->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ]);

        $response->assertRedirect('/notes');
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ]);
    }

    public function test_users_can_delete_notes(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/notes/{$note->id}");

        $response->assertRedirect('/notes');
        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }
}