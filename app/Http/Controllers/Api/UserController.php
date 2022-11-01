<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    protected $repositoryInterface;

    public function __construct(UserRepositoryInterface $repositoryInterface)
    {
        $this->repositoryInterface = $repositoryInterface;
    }

    public function index()
    {
        $usersData = collect($this->repositoryInterface->findAll());
        return UserResource::collection($usersData);
    }
}
