<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    //use RefreshDatabase;
    use WithFaker; 
    protected static $user = null;
    protected $loginPath;

    public function setUp() : void
    {
        parent::setUp();
        $this->loginPath = route('login');
    }

    public function test_users_can_register()
    {
        $password = $this->faker->password();
        $user = $response = $this->postJson(route('register'), [
            'name'=> $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('token')
                ->has('user')
                ->where('user.name', $user['user']['name'])
                ->where('user.email', $user['user']['email']);
        });
    }

    public function test_users_can_authenticate_using_login()
    {
        $user = $this->mockUser();
        $this->postJson($this->loginPath, [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertSessionHasNoErrors()
        ->assertCreated()
        ->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('token')
                ->has('user')
                ->where('user.name', $user->name)
                ->where('user.email', $user->email);
        });
    }
    
    public function test_users_can_logout()
    {
        Sanctum::actingAs($this->mockUser());
        $this->postJson(route('logout'))
        ->assertSuccessful();
    }

    public function test_users_can_not_register_password_less_than_6_characters()
    { 
        $this->postJson(route('register'), [
            'name'=> $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '12345',
            'password_confirmation' => '12345',
        ])->assertJsonValidationErrors(['password']);
    }

    public function test_users_can_not_register_with_invalid_email()
    {
        $password = $this->faker->password();
        $this->postJson(route('register'), [
            'name'=> $this->faker->name(),
            'email' =>'invalid.email.com',
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertJsonValidationErrors(['email']);
    }

    public function test_users_can_not_authenticate_with_wrong_password()
    {
        $this->postJson($this->loginPath, [
            'email' => $this->mockUser()->email,
            'password' => 'this-is-a-wrong-password',
        ])
        ->assertUnauthorized();
    }

    public function test_users_can_not_authenticate_with_wrong_email()
    {
        $this->postJson($this->loginPath, [
            'email' => 'user@email.com.br',
            'password' => $this->mockUser()->password,
        ])
        ->assertUnauthorized();
    }
    
    public function test_users_can_not_login_without_email()
    {
        $this->postJson($this->loginPath, [
            'password' => $this->mockUser()->email,
        ])
        ->assertJsonValidationErrors(['email']);
    }

    public function test_users_can_not_login_without_password()
    {
        $this->postJson($this->loginPath, [
            'email' => $this->mockUser()->password,
        ])
        ->assertJsonValidationErrors(['password']);
    }
    /**
     * Helpers
     */
    protected function mockUser()
    {
        if (null === self::$user){
            self::$user = User::factory()->create();
        }
        
        return self::$user;
    }
}
