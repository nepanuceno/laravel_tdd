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
        $this->assertEquals(40, $response['meta']['total']);
        $this->assertEquals(1, $response['meta']['current_page']);

    }

    public function test_page_two()
    {
        User::factory()->count(20)->create();
        $response = $this->getJson("{$this->endpoint}?page=2");
        $response->assertStatus(Response::HTTP_OK);
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
        $this->assertEquals(20, $response['meta']['total']);
        $this->assertEquals(2, $response['meta']['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_paginate_empty()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
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
        $this->assertEquals(0, $response['meta']['total']);
        $this->assertEquals(1, $response['meta']['current_page']);

    }
}