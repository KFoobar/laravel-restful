<?php

namespace KFoobar\Restful\Actions\User;

use App\Models\User;

class DeleteUserAction
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
        return $user->delete();
    }
}
