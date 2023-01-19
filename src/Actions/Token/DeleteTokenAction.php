<?php

namespace KFoobar\Restful\Actions\Token;

use App\Models\Sanctum\PersonalAccessToken;

class DeleteTokenAction
{
    /**
     * Executes the action.
     *
     * @param \App\Models\Sanctum\PersonalAccessToken $token
     *
     * @return mixed
     */
    public function execute(PersonalAccessToken $token): mixed
    {
        return $token->delete();
    }
}
