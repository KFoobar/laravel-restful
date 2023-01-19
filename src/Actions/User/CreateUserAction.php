<?php

namespace KFoobar\Restful\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserAction
{
    /**
     * Executes the action.
     *
     * @param  string     $email
     * @param  string     $name
     * @throws \Exception
     *
     * @return \App\Models\User
     */
    public function execute(string $email, string $name): User
    {
        if (!$this->isEmailUnique(email: $email)) {
            throw new \Exception('Email is already registered');
        }

        if (!$user = $this->createUser(email: $email, name: $name)) {
            throw new \Exception('Failed to create user');
        }

        return $user;
    }

    /**
     * Determines whether the specified email is unique.
     *
     * @param string $email
     *
     * @return bool
     */
    protected function isEmailUnique(string $email): bool
    {
        return !User::query()
            ->where('email', $email)
            ->exists();
    }

    /**
     * Creates an user.
     *
     * @param string $email
     * @param string $name
     *
     * @return \App\Models\User
     */
    protected function createUser(string $email, string $name): User
    {
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make(
                Str::random(32)
            ),
        ]);
    }
}
