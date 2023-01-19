<?php

namespace KFoobar\Restful\Commands\Token;

use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Console\Command;
use KFoobar\Restful\Actions\Token\DeleteTokenAction;
use KFoobar\Restful\Actions\Token\FindTokenByIdAction;

class RevokeToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:revoke {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently revoke the access token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->confirmAction()) {
            $this->components->info('Aborting...');

            return Command::SUCCESS;
        }

        $this->deleteToken(
            token: $this->findToken()
        );

        return Command::SUCCESS;
    }

    /**
     * Prompts the user for confirmation.
     *
     * @return bool
     */
    protected function confirmAction(): bool
    {
        return $this->confirm(
            'This action is irreversible. Do you want to continue?'
        );
    }

    /**
     * Finds a token by id.
     *
     * @return \App\Models\Sanctum\PersonalAccessToken
     */
    protected function findToken(): PersonalAccessToken
    {
        return app(FindTokenByIdAction::class)->execute(
            id: $this->argument('id')
        );
    }

    /**
     * Deletes the given access token.
     *
     * @param \App\Models\Sanctum\PersonalAccessToken $token
     */
    protected function deleteToken(PersonalAccessToken $token): void
    {
        app(DeleteTokenAction::class)->execute(
            token: $token
        );

        $this->components->info(
            'Access token ['.$token->id.'] revoked successfully.'
        );
    }
}
