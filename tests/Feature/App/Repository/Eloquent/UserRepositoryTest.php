<?php

namespace Tests\Feature\App\Repository\Eloquent;

use Throwable;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\QueryException;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Eloquent\Exception\NotFoundException;
use App\Repository\Eloquent\Exception\NotFountException;
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

    public function test_create_exception()
    {
        $this->expectException(QueryException::class);

        $data = [
            'name' => 'Paulo Roberto Torres',
            'password' => bcrypt('123456'),
        ];

        $this->repositoryUser->create($data);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'New Name',
        ];
        $response = $this->repositoryUser->update($user->email, $data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);

        $this->assertDatabaseHas('users',
        [
            'name' => 'New Name'
        ]);
    }

    public function test_find()
    {
        $user = User::factory()->create();
        $response = $this->repositoryUser->find($user->email);

        $this->assertIsObject($response);
    }

    public function test_find_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->repositoryUser->find('fake@mail');
    }

    public function test_delete()
    {
        $user = User::factory()->create();
        $response = $this->repositoryUser->delete($user->email);

        $this->assertTrue($response);
        $this->assertDatabaseMissing('users',
            [
                'email' => $user->email
            ]
        );
    }

    public function test_delete_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->repositoryUser->delete('fake@mail.com');
    }
}
