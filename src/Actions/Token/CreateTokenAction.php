<?php

namespace KFoobar\Restful\Actions\Token;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

class CreateTokenAction
{
    /**
     * Executes the action.
     *
     * @param \App\Models\User $user
     * @param string           $name
     * @param int|null         $days
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function execute(User $user, string $name, ?int $days): NewAccessToken
    {
        $expirationDate = is_int($days)
            ? now()->addDays($days)
            : null;

        return $user->createToken($name, ['*'], $expirationDate);
    }
}
