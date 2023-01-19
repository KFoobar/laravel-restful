<?php

namespace KFoobar\Restful\Actions\User;

use App\Models\User;

class FindUserByEmailAction
{
    /**
     * Executes the action.
     *
     * @param  string     $email
     * @throws \Exception
     *
     * @return \App\Models\User
     */
    public function execute(string $email): User
    {
        $user = User::firstWhere('email', $email);

        if (!$user instanceof User) {
            throw new \Exception('User not found');
        }

        return $user;
    }
}
