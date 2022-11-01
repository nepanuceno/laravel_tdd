<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Eloquent\Exception\NotFoundException;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(string $email, array $data): object
    {
        $user = $this->find($email)->first();
        $user->update($data);
        $user->refresh();
        return $user;
    }

    public function delete(string $email): bool
    {

        $user = $this->find($email)->first();
        if(!$user) {
            throw new NotFoundException("User not Found");
        }

        return $user->delete();
    }

    public function find(string $email): ?object
    {
        $user = $this->model->where('email', $email)->first();

        if(!$user) {
            throw new NotFoundException("User not Found");
        }

        return $user;
    }
}
