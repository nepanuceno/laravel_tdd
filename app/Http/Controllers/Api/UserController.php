<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
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
        $usersData = $this->repositoryInterface->paginate();
        return UserResource::collection(collect($usersData->items()))
                ->additional([
                    'meta' => [
                        'total' => $usersData->total(),
                        'current_page' => $usersData->currentPage(),
                        'last_page' => $usersData->lastPage(),
                        'first_page' => $usersData->firstPage(),
                        'per_page' => $usersData->perPage(),
                    ]
                ]);
    }

    public function store(UserStoreRequest $request)
    {
        $user = $this->repositoryInterface->create($request->validated());
        return new UserResource($user);
    }

    public function show($email)
    {
        $user = $this->repositoryInterface->find($email);
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, $email)
    {
        $user = $this->repositoryInterface->update($email, $request->validated());
        return new UserResource($user);
    }

    public function destroy($email)
    {
        $this->repositoryInterface->delete($email);
        return response()->noContent();
    }
}
