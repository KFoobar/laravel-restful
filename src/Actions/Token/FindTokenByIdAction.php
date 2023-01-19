<?php

namespace KFoobar\Restful\Actions\Token;

use App\Models\Sanctum\PersonalAccessToken;

class FindTokenByIdAction
{
    /**
     * Executes the action.
     *
     * @param  int        $id
     * @throws \Exception
     *
     * @return \App\Models\Sanctum\PersonalAccessToken
     */
    public function execute(int $id): PersonalAccessToken
    {
        $token = PersonalAccessToken::find($id);

        if (!$token instanceof PersonalAccessToken) {
            throw new \Exception('Access token not found');
        }

        return $token;
    }
}
