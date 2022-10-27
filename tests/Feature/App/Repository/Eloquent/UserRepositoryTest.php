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
    public function test_implements_interface()
    {
        $this->assertInstanceOf(UserRepositoryInterface::class, new UserRepository(new User()));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_find_all_empty()
    {
        $repository = new UserRepository(new User());
        $response = $repository->findAll();
        $this->assertIsArray( $response);
        $this->assertCount(0,  $response);
    }

    public function test_find_all()
    {
        User::factory()->count(10)->create();

        $repository = new UserRepository(new User());
        $response = $repository->findAll();
        $this->assertIsArray( $response);
        $this->assertCount(10,  $response);
    }
}
