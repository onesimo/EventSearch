<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    //use RefreshDatabase;
    
    public function test_users_can_register()
    {
        
        $response = $this->post('/api/register',[
            'name'=> 'Automated Test 2',
            'email' => 'devtest 2 @email.com',
            'password' => '!password@',
            'password_confirmation' => '!password@',
        ]);
       $response->assertCreated();
       
    }
}
