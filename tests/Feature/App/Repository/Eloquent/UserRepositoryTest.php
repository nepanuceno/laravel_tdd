<?php

namespace Tests\Feature\App\Repository\Eloquent;

use Tests\TestCase;
use App\Models\User;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\App\Repository\Eloquent\UserRepositoryTest as EloquentUserRepositoryTest;

class UserRepositoryTest extends TestCase
{
    protected $repositoryUser;

    protected function setUp(): void
    {
        $this->repositoryUser =  new UserRepository(new User());
        parent::setUp();
    }

    public function test_implements_interface()
    {
        $this->assertInstanceOf(UserRepositoryInterface::class,  $this->repositoryUser);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_find_all_empty()
    {
        $response = $this->repositoryUser->findAll();
        $this->assertIsArray( $response);
        $this->assertCount(0,  $response);
    }

    public function test_find_all()
    {
        User::factory()->count(10)->create();

        $response = $this->repositoryUser->findAll();
        $this->assertIsArray( $response);
        $this->assertCount(10,  $response);
    }

    // public function test_store()
    // {

    // }

    public function test_create()
    {
        $data = [
            'name' => 'Paulo Roberto Torres',
            'email' => 'paulo.torres.apps@gmail.com',
            'password' => bcrypt('123456'),
        ];

        $response = $this->repositoryUser->create($data);
        $this->assertNotNull($response);
        $this->assertIsObject($response);

        $this->assertDatabaseHas('users', [
            'email' => 'paulo.torres.apps@gmail.com'
        ]);
    }
}
