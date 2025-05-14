<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Club;
use App\Models\Vote;
use App\Models\VoteOption;
use Carbon\Carbon;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_create_vote_page()
    {
        $response = $this->get('/votes/create');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_create_vote_page()
    {
        $user = User::factory()->create();
        $club = Club::factory()->create();

        $response = $this->actingAs($user)->get('/votes/create');
        $response->assertStatus(200);
        $response->assertViewIs('votes.create');
        $response->assertViewHas('clubs');
    }

    public function test_authenticated_user_can_create_vote()
    {
        $user = User::factory()->create();
        $club = Club::factory()->create();

        $postData = [
            'title' => 'Test Vote',
            'description' => 'This is a test vote.',
            'end_date' => Carbon::now()->addDays(5)->toDateTimeString(),
            'club_id' => $club->id,
            'options' => ['Option 1', 'Option 2'],
        ];

        $response = $this->actingAs($user)->post('/votes', $postData);

        $response->assertRedirect();
        $this->assertDatabaseHas('votes', [
            'title' => 'Test Vote',
            'description' => 'This is a test vote.',
            'club_id' => $club->id,
            'user_id' => $user->id,
        ]);

        $vote = Vote::where('title', 'Test Vote')->first();
        $this->assertNotNull($vote);

        $this->assertDatabaseHas('vote_options', [
            'vote_id' => $vote->id,
            'label' => 'Option 1',
        ]);
        $this->assertDatabaseHas('vote_options', [
            'vote_id' => $vote->id,
            'label' => 'Option 2',
        ]);
    }

    public function test_validation_errors_on_create_vote()
    {
        $user = User::factory()->create();

        $postData = [
            'title' => '', // required
            'end_date' => 'invalid-date',
            'club_id' => 9999, // non-existent club
            'options' => ['Only one option'], // minimum 2 options required
        ];

        $response = $this->actingAs($user)->post('/votes', $postData);

        $response->assertSessionHasErrors(['title', 'end_date', 'club_id', 'options']);
    }
}
