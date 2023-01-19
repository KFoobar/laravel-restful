<?php

namespace KFoobar\Restful\Actions\Token;

use App\Models\User;

class PurgeTokensAction
{
    /**
     * Executes the action.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function execute(User $user): mixed
    {
        return $user->tokens()->delete();
    }
}
