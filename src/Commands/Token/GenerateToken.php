<?php

namespace KFoobar\Restful\Commands\Token;

use App\Models\User;
use Illuminate\Console\Command;
use KFoobar\Restful\Actions\Token\CreateTokenAction;
use KFoobar\Restful\Actions\Token\PurgeTokensAction;
use KFoobar\Restful\Actions\User\FindUserByEmailAction;
use Laravel\Sanctum\NewAccessToken;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate {email} {name=Webservice Token} {--U|unique} {--D|days=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an access token for given user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('unique') && !$this->confirmAction()) {
            $this->components->info('Aborting...');

            return Command::SUCCESS;
        }

        $user = $this->findUser();

        if ($this->option('unique')) {
            $this->purgeTokens(user: $user);
        }

        $this->createToken(user: $user);

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
     * Finds an user by email.
     *
     * @return \App\Models\User
     */
    protected function findUser(): User
    {
        return app(FindUserByEmailAction::class)->execute(
            email: $this->argument('email')
        );
    }

    /**
     * Purge all access tokens for given user.
     *
     * @param \App\Models\User $user
     */
    protected function purgeTokens(User $user): void
    {
        app(PurgeTokensAction::class)->execute(
            user: $user
        );
    }

    /**
     * Creates a token.
     *
     * @param \App\Models\User $user
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    protected function createToken(User $user): NewAccessToken
    {
        $token = app(CreateTokenAction::class)->execute(
            user: $user,
            name: $this->argument('name'),
            days: $this->option('days'),
        );

        $this->components->info(
            'Access token ['.$token->plainTextToken.'] created successfully.'
        );

        return $token;
    }
}
