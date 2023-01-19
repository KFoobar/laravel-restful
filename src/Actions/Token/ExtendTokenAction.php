<?php

namespace KFoobar\Restful\Actions\Token;

use App\Models\Sanctum\PersonalAccessToken;

class ExtendTokenAction
{
    /**
     * Executes the action.
     *
     * @param \App\Models\Sanctum\PersonalAccessToken $token
     * @param int                                  $days
     *
     * @return bool
     */
    public function execute(PersonalAccessToken $token, int $days): bool
    {
        return $token->update([
            'expires_at' => now()->addDays($days),
        ]);
    }
}
