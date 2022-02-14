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
    //use RefreshDatabase;
    use withFaker;
    protected $user;
    protected $contry;
    protected $path;

    public function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->country = $this->faker->country();
        $this->path = '/api/event';

        Sanctum::actingAs($this->user);
    }

    public function test_users_can_search_with_term_and_date()
    {
        $this->getJson($this->path.'?term='.$this->country.'&date='.Carbon::tomorrow()->format('d-m-Y'))
        ->assertJsonStructure(['data'])
        ->assertSuccessful();
    }

    public function test_users_can_search_with_term_only()
    {
        $this->getJson($this->path.'?term='.$this->country)
        ->assertJsonStructure(['data'])
        ->assertSuccessful();
    }

    public function test_users_can_search_with_date_only()
    {
        $this->getJson($this->path.'?date='.Carbon::tomorrow()->format('d-m-Y'))
        ->assertJsonStructure(['data'])
        ->assertSuccessful();
    }

    public function test_users_can_not_search_without_filters()
    {
        $this->getJson($this->path)
        ->assertJsonStructure(['errors']);
    }

    public function test_users_can_not_search_past_dates()
    {
        $this->getJson($this->path.'?date='.Carbon::yesterday()->format('d-m-Y'))
        ->assertJsonValidationErrors(['date']);
    }
 
    public function test_users_can_not_search_date_with_wrong_format()
    {
        $this->getJson($this->path.'?term='.$this->country.'&date='.Carbon::tomorrow()->format('d/m/Y'))
        ->assertJsonValidationErrors(['date']);
    }
}
