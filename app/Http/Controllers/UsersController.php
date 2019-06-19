<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserShowResource;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    const PER_PAGE = 10;

    private $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function index()
    {
        $this->authorize('manage', User::class);

        $users = User::orderByDesc('id')->paginate(self::PER_PAGE);

        return UserListResource::collection($users);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserShowResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->service->update($user, $request);

        return response()->json([
            'message' => trans('crud.edited', ['item' => 'User'])
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->service->delete($user);

        return response()->json([
            'message' => trans('crud.deleted', ['item' => 'User'])
        ]);
    }

    public function setAdmin(User $user)
    {
        $this->authorize('update', $user);

        $this->service->setAdmin($user);

        return response()->json([
            'message' => trans('crud.role.admin'),
            'role' => $user->roleTypeWithName()
        ]);
    }

    public function setUser(User $user)
    {
        $this->authorize('update', $user);

        $this->service->setUser($user);

        return response()->json([
            'message' => trans('crud.role.user'),
            'role' => $user->roleTypeWithName()
        ]);
    }

}
