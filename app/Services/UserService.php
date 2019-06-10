<?php

namespace App\Services;

use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register(RegisterRequest $request): User
    {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => User::ROLE_USER
        ]);
    }

    public function update(User $user, UserUpdateRequest $request): void
    {
        $data = $request->only('name', 'email');

        if (! empty($request->input('password'))) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->comments()->delete();
            $user->images()->delete();
            $user->posts()->delete();
            $user->delete();
        });
    }

    public function setAdmin(User $user): void
    {
        $user->setAdmin();
    }

    public function setUser(User $user): void
    {
        $user->setUser();
    }

}