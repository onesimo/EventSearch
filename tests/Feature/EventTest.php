<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class EventTest extends TestCase
{
    //use RefreshDatabase;
    use WithFaker;
    protected $contry;
    protected $path;
    protected static $userAuth = null;

    public function setUp() : void
    {
        parent::setUp();
        $this->country = $this->faker->country();
        $this->path = route('event');
    }
    
    public function test_users_can_search_with_term_and_date()
    {
        $this->userAuth();
        $this->getJson($this->path.'?term='.$this->country.'&date='.Carbon::tomorrow()->format('d-m-Y'))
        ->assertJsonStructure(['data'])
        ->assertSuccessful();
    }

    public function test_users_can_search_with_term_only()
    {
        $this->userAuth();
        $this->getJson($this->path.'?term='.$this->country)
        ->assertJsonStructure(['data'])
        ->assertSuccessful();
    }

    public function test_users_can_search_with_date_only()
    {
        $this->userAuth();
        $this->getJson($this->path.'?date='.Carbon::tomorrow()->format('d-m-Y'))
        ->assertJsonStructure(['data'])
        ->assertSuccessful();
    }

    public function test_users_can_not_search_without_authentication()
    {
        $this->getJson($this->path.'?term='.$this->country.'&date='.Carbon::tomorrow()->format('d-m-Y'))
        ->assertUnauthorized();
    }

    public function test_users_can_not_search_without_filters()
    {
        $this->userAuth();
        $this->getJson($this->path)
        ->assertJsonStructure(['errors']);
    }

    public function test_users_can_not_search_past_dates()
    {   
        $this->userAuth();
        $this->getJson($this->path.'?date='.Carbon::yesterday()->format('d-m-Y'))
        ->assertJsonValidationErrors(['date']);
    }
 
    public function test_users_can_not_search_date_with_wrong_format()
    {
        $this->userAuth();
        $this->getJson($this->path.'?term='.$this->country.'&date='.Carbon::tomorrow()->format('d/m/Y'))
        ->assertJsonValidationErrors(['date']);
    }
    /**
     * Helper - create and authenticate a user
     */
    protected function userAuth()
    {
        if (null === self::$userAuth) {
            self::$userAuth = User::factory()->create();
        }  
        return Sanctum::actingAs(self::$userAuth);
    }
}
