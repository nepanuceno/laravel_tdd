<?php

namespace App\Repository\Interfaces;

interface UserRepositoryInterface
{
    public function findAll(): array;
    public function create(array $data): object;
}
