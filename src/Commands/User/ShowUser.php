<?php

namespace KFoobar\Restful\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use KFoobar\Restful\Actions\User\FindUserByEmailAction;

class ShowUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:show {email} {--T|tokens}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show details about given user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = $this->findUser();

        $this->showUserDetails(
            user: $user
        );

        if ($this->option('tokens')) {
            $this->showUserTokens(user: $user);
        }

        return Command::SUCCESS;
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
     * Shows the user details.
     *
     * @param \App\Models\User $user
     */
    protected function showUserDetails(User $user): void
    {
        $this->components->info(
            'Details for ['.$user->email.']:'
        );

        $this->info('  • Name: '.$user->name);
        $this->info('  • E-mail: '.$user->email);
        $this->info('  • Created: '.$user->created_at->toDateString());
        $this->line('');
    }

    /**
     * Shows the user tokens.
     *
     * @param \App\Models\User $user
     */
    protected function showUserTokens(User $user): void
    {
        $this->components->info(
            'Access tokens for ['.$user->email.']:'
        );

        $user->tokens->each(function ($token) {
            if ($token->isExpired()) {
                $this->error(
                    sprintf('  • [ID:%s] %s (%s)', $token->id, $token->name, 'is expired')
                );
            } elseif (!$token->hasExpirationDate()) {
                $this->warn(
                    sprintf('  • [ID:%s] %s (%s)', $token->id, $token->name, 'has no expiration date')
                );
            } else {
                $this->info(
                    sprintf('  • [ID:%s] %s (%s)', $token->id, $token->name, $token->getExpirationDate())
                );
            }
        });

        $this->line('');
    }
}
