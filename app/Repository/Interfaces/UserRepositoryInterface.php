<?php

namespace App\Repository\Interfaces;

interface UserRepositoryInterface
{
    public function findAll(): array;
    public function paginate(int $page=1): PaginationInterface;
    public function create(array $data): object;
    public function update(string $email, array $data): object;
    public function find(string $email): ?object;
    public function delete(string $email): bool;
}
