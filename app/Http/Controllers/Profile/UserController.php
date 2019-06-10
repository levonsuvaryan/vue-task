<?php

namespace App\Http\Controllers\Profile;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserShowResource;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    const PER_PAGE = 10;

    private $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function index()
    {
        $this->checkAccess();

        $users = User::orderByDesc('id')->paginate(self::PER_PAGE);

        return UserListResource::collection($users);
    }

    public function show(User $user)
    {
        $this->checkAccess();

        return new UserShowResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->checkAccess();

        $this->service->update($user, $request);

        return response()->json([
            'message' => trans('crud.edited', ['item' => 'User'])
        ]);
    }

    public function destroy(User $user)
    {
        $this->checkAccess();

        $this->service->delete($user);

        return response()->json([
            'message' => trans('crud.deleted', ['item' => 'User'])
        ]);
    }

    // ROLE

    public function setAdmin(User $user)
    {
        $this->checkAccess();

        $this->service->setAdmin($user);

        return response()->json([
            'message' => trans('crud.role.admin'),
            'role' => $user->roleTypeWithName()
        ]);
    }

    public function setUser(User $user)
    {
        $this->checkAccess();

        $this->service->setUser($user);

        return response()->json([
            'message' => trans('crud.role.user'),
            'role' => $user->roleTypeWithName()
        ]);
    }

    // ACCESS

    private function checkAccess()
    {
        Gate::authorize('manage-users');
    }

}
