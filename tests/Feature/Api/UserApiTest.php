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
     * @dataProvider dataProviderPagination
     */
    public function test_paginate(int $total, int $page=1, int $perPage=15)
    {
        User::factory()->count($total)->create();
        $response = $this->getJson("{$this->endpoint}?page={$page}");
        $response->assertOk();
        $response->assertJsonCount($perPage, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ]);

        $response->assertJsonFragment(['total'=> $total]);
        $response->assertJsonFragment(['current_page'=> $page]);
    }

    public function dataProviderPagination(): array
    {
        return [
            'Total test page 1 with 15 registers for page' => ['total'=>40, 'page'=> 1, 'perPage'=> 15],
            'Total test page 2 with 5 registers for page' => ['total'=>20, 'page'=> 2, 'perPage'=> 5],
            'Total test page 1 with 0 registers for page' =>  ['total'=> 0, 'page'=> 1, 'perPage'=> 0],
            'Total test page 4 with 15 registers for page' =>  ['total'=> 100, 'page'=> 4, 'perPage'=> 15],
        ];
    }

    /**
     * @dataProvider dataProviderCreateUser
     */
    public function test_create(array $payload, int $statusCode, array $structureResponse)
    {
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus($statusCode);
        $response->assertJsonStructure($structureResponse);
    }

    public function dataProviderCreateUser(): array
    {
        return [
                    'test_create_user' => [
                        'payload' => [
                            'name' => 'Paulo Roberto Torres',
                            'email' => 'paulo.torres.apps@gmail.com',
                            'password' => '12345678'
                        ],
                        'statusCode' => Response::HTTP_CREATED,
                        'structureResponse' => [
                            'data'=> ['id', 'name', 'email']
                        ]
                    ],
                    'validation_create_user' => [
                        'payload' => [
                            'name' => 'Paulo Roberto Torres',
                            'email' => 'paulo.torres.apps@gmail.com',
                            'password' => '123'
                        ],
                        'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        'structureResponse' => []
                    ]
                ];
    }
}
