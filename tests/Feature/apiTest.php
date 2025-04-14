<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    public function test_get_users_endpoint()
    {
        $response = $this->getJson('/api/olimpiadas');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'email'
                         ]
                     ]
                 ]);
    }

    public function test_create_user_validation()
    {
        $response = $this->postJson('/api/users', []);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email']);
    }
}