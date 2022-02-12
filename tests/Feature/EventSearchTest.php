<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum; 


class EventSearchTest extends TestCase
{
    protected $user;

    public function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }
    public function test_users_can_search_With_term_and_date()
    {
        $this->getJson('/api/event?term=Bahamas&date='.Carbon::tomorrow()->format('d-m-Y'))
        ->assertSuccessful();
    }

    public function test_users_can_search_with_term_only()
    {
        $this->getJson('/api/event?term=Bahamas')
        ->assertSuccessful();
    }

    public function test_users_can_search_with_date_only()
    {
        $this->getJson('/api/event?date='.Carbon::tomorrow()->format('d-m-Y'))
        ->assertSuccessful();
    }
 
    public function test_users_can_not_search_date_with_wrong_format()
    {
        $this->getJson('/api/event?term=Bahamas&date='.Carbon::tomorrow()->format('d/m/Y'))
        ->assertJsonValidationErrors(['date']);
    }

    public function test_users_can_not_past_dates()
    {
        $this->getJson('/api/event?term=Bahamas&date='.Carbon::yesterday()->format('d-m-Y'))
        ->assertJsonValidationErrors(['date']);
    } 
}
