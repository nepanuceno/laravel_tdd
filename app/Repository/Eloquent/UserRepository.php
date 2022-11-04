<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Eloquent\Exception\NotFoundException;
use App\Repository\Interfaces\PaginationInterface;
use App\Repository\Presenters\PaginationPresenter;

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

    public function paginate(int $page=1): PaginationInterface
    {
        return new PaginationPresenter($this->model->paginate());
    }

    public function create(array $data): object
    {
        $data['password'] = bcrypt($data['password']);
        return $this->model->create($data);
    }

    public function update(string $email, array $data): object
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = $this->find($email)->first();
        $user->update($data);
        $user->refresh();
        return $user;
    }

    public function delete(string $email): bool
    {
        $user = $this->find($email)->first();
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
