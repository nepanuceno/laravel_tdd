<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTest extends TestCase
{
    protected function model(): Model
    {
        return new User();
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_traits()
    {
        $traits = array_keys(class_uses($this->model()));
        $expectedTraits = [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class
        ];

        $this->assertEquals($expectedTraits, $traits);
    }

    public function test_filables()
    {
        $fillables = $this->model()->getFillable();

        $expectedFillables = [
            'name',
            'email',
            'password',
        ];

        $this->assertEquals($expectedFillables, $fillables);

    }
}
