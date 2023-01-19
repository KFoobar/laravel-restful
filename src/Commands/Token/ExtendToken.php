<?php

namespace KFoobar\Restful\Commands\Token;

use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Console\Command;
use KFoobar\Restful\Actions\Token\ExtendTokenAction;
use KFoobar\Restful\Actions\Token\FindTokenByIdAction;

class ExtendToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:extend {id} {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extend expiration date for given access token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->extendToken(
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
            'This will revoke any existing access tokens. Do you want to continue?'
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
     * Extends given access token.
     *
     * @param \App\Models\Sanctum\PersonalAccessToken $token
     */
    protected function extendToken(PersonalAccessToken $token): void
    {
        app(ExtendTokenAction::class)->execute(
            token: $token,
            days: $this->argument('days'),
        );

        $this->components->info(
            'Access token [ID: '.$token->id.'] extended successfully.'
        );
    }
}
