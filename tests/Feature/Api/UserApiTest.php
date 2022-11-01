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
    public function test_get_all()
    {
        User::factory()->count(20)->create();
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(20, 'data');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_empty()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
    }
}
