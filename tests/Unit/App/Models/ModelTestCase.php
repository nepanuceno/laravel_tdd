<?php

namespace Tests\Unit\App\Models;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function filables(): array;
    abstract protected function casts(): array;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_traits()
    {
        $traits = array_keys(class_uses($this->model()));
        $this->assertEquals($this->traits(), $traits);
    }

    public function test_filables()
    {
        $fillables = $this->model()->getFillable();
        $this->assertEquals($this->filables(), $fillables);
    }

    //Método que avalia algo que está presente em todos o Models por padrão.
    public function test_incrementing_is_false()
    {
        $incrementing = $this->model()->incrementing;
        $this->assertFalse($incrementing);
    }

    public function test_has_casts()
    {
        $casts = $this->model()->getCasts();
        $this->assertEquals($this->casts(), $casts);
    }
}
