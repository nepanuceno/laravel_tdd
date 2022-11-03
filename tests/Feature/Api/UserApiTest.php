<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    protected string $endpoint = '/api/users';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_paginate()
    {
        User::factory()->count(40)->create();
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
            ]
        ]);

        $response->assertJsonFragment(['total'=> 40]);
        $response->assertJsonFragment(['current_page'=> 1]);
    }

    public function test_page_two()
    {
        User::factory()->count(20)->create();
        $response = $this->getJson("{$this->endpoint}?page=2");
        $response->assertOK();
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
            ]
        ]);

        $response->assertJsonFragment(['total'=> 20]);
        $response->assertJsonFragment(['current_page'=> 2]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_paginate_empty()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertOK();
        $response->assertJsonCount(0, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
            ]
        ]);
        $response->assertJsonFragment(['total'=> 0]);
        $response->assertJsonFragment(['current_page'=> 1]);
    }
}
