<?php

namespace KFoobar\Restful\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use KFoobar\Restful\Actions\User\DeleteUserAction;
use KFoobar\Restful\Actions\User\FindUserByEmailAction;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete the user';

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

        $this->deleteUser(
            user: $this->findUser()
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
     * Deletes the given user.
     *
     * @param \App\Models\User $user
     */
    protected function deleteUser(User $user): void
    {
        app(DeleteUserAction::class)->execute(
            user: $user
        );

        $this->components->info(
            'User ['.$user->email.'] deleted successfully.'
        );
    }
}
