<?php

namespace KFoobar\Restful\Commands\Token;

use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Console\Command;
use KFoobar\Restful\Actions\Token\FindTokenByIdAction;

class ShowToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:show {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show details about given access token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->showTokenDetails(
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
     * Shows the access token details.
     *
     * @param \App\Models\Sanctum\PersonalAccessToken $token
     */
    protected function showTokenDetails(PersonalAccessToken $token): void
    {
        $this->components->info(
            'Details for [ID: '.$token->id.']:'
        );

        $this->info('  • Name: '.$token->name);
        $this->info('  • Created: '.$token->created_at->toDateString());

        if ($token->isExpired()) {
            $this->error('  • Expires: Expired at '.$token->created_at->toDateString());
        } elseif (!$token->hasExpirationDate()) {
            $this->warn('  • Expires: Has no expiration date');
        } else {
            $this->info('  • Expires: '.$token->getExpirationDate());
        }

        if (!empty($token->last_used_at)) {
            $this->info('  • Last used: '.$token->last_used_at->toDateString());
        } else {
            $this->info('  • Last used: Never');
        }

        $this->line('');
    }
}
