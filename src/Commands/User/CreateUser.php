<?php

namespace KFoobar\Restful\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use KFoobar\Restful\Actions\Token\CreateTokenAction;
use KFoobar\Restful\Actions\User\CreateUserAction;
use Laravel\Sanctum\NewAccessToken;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email} {name} {--T|token} {--D|days=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user with an optional access token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = $this->createUser();

        if ($this->option('token')) {
            $this->createToken(user: $user);
        }

        return Command::SUCCESS;
    }

    /**
     * Creates an user.
     *
     * @return \App\Models\User
     */
    protected function createUser(): User
    {
        $user = app(CreateUserAction::class)->execute(
            email: $this->argument('email'),
            name: $this->argument('name')
        );

        $this->components->info(
            'User ['.$user->email.'] created successfully.'
        );

        return $user;
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
            name: 'Webservice Access Token',
            days: $this->option('days'),
        );

        $this->components->info(
            'Access token ['.$token->plainTextToken.'] created successfully.'
        );

        return $token;
    }
}
