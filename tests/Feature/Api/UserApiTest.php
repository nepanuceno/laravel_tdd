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

    public function test_find_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$user->email}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'data' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        );
    }

    public function test_find_user_not_found()
    {

        $response = $this->getJson("{$this->endpoint}/teste@mail.com}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider dataProviderUserUpdate
     */
    public function test_user_update(array $payload, int $statusCode, string $email='')
    {
        $user = User::factory()->create();

        if ($email === '') {
            $email=$user->email;
        }

        $response = $this->putJson("{$this->endpoint}/{$email}", $payload);

        $response->assertStatus($statusCode);
    }


    public function dataProviderUserUpdate(): array
    {
        return [
            'Test Update OK' =>[
                'payload' => [
                    'name' => 'Name Update',
                    'password' => 'new_password'
                ],
                'statusCode' => Response::HTTP_OK,
            ],
            'Test Update Name Less' =>[
                'payload' => [
                    'password' => 'new_password'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            'Test Update Short PassWord' => [
                'payload' => [
                    'name' => 'Name Update',
                    'password' => 'ne'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            'Test Update Long PassWord' => [
                'payload' => [
                    'name' => 'Name Update',
                    'password' => 'nefsrgrfthaerghserthasefgaesrgsergsa'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            'Test Update User Not Found' =>[
                'payload' => [
                    'name' => 'Name Update',
                    'password' => 'new_password'
                ],
                'statusCode' => Response::HTTP_NOT_FOUND,
                'email' => 'Fake_Mail'
            ],
        ];
    }

    public function test_user_delete()
    {
        $user = User::factory()->create();
        $response = $this->deleteJson("{$this->endpoint}/{$user->email}");
        $response->assertNoContent();
    }
}
