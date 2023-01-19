<?php

namespace KFoobar\Restful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restful:install {--P|purge : Remove all frontend assets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs Laravel Restful';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->components->info('Installing Laravel Restful...');

        $this->directories();
        $this->stubs();

        if ($this->option('purge')) {
            $this->purge();
        }

        $this->composer([
            "kfoobar/laravel-uuid:^1.0",
        ]);

        $this->components->info('Laravel Restful installed successfully!');
    }

    /**
     * Installs directories.
     */
    private function directories(): void
    {
        $this->components->info('Installing directories...');

        (new Filesystem)->ensureDirectoryExists(app_path('Models/Request'));
        (new Filesystem)->ensureDirectoryExists(app_path('Models/Sanctum'));
    }

    /**
     * Installs stubs.
     */
    private function stubs(): void
    {
        $this->components->info('Installing stubs...');

        copy(__DIR__.'/../../stubs/app/Models/Request/RequestLog.php', app_path('Models/Request/RequestLog.php'));
        copy(__DIR__.'/../../stubs/app/Models/Sanctum/PersonalAccessToken.php', app_path('Models/Sanctum/PersonalAccessToken.php'));
        copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));

        copy(__DIR__.'/../../stubs/app/Providers/AppServiceProvider.php', app_path('Providers/AppServiceProvider.php'));

        copy(__DIR__.'/../../stubs/public/robots.txt', base_path('public/robots.txt'));

        copy(__DIR__.'/../../stubs/routes/api.php', base_path('routes/api.php'));
        copy(__DIR__.'/../../stubs/routes/channels.php', base_path('routes/channels.php'));
        copy(__DIR__.'/../../stubs/routes/console.php', base_path('routes/console.php'));
        copy(__DIR__.'/../../stubs/routes/web.php', base_path('routes/web.php'));
    }

    /**
     * Purges frontend assets.
     */
    private function purge(): void
    {
        $this->components->info('Deleting frontend assets...');

        (new Filesystem)->cleanDirectory(base_path('resources'));

        (new Filesystem)->delete(base_path('package.json'));
        (new Filesystem)->delete(base_path('vite.config.js'));
    }

    /**
     * Install Composer packages.
     *
     * @param array $packages
     */
    private function composer(array $packages): void
    {
        $this->components->info('Installing Composer packages...');

        $command = array_merge(['composer', 'require'], $packages);

        $this->runProcess(
            array_merge(['composer', 'require'], $packages),
            ['COMPOSER_MEMORY_LIMIT' => '-1']
        );
    }

    /**
     * Runs a process of given command.
     *
     * @param array $commands
     * @param array $options
     */
    private function runProcess(array $commands, array $options = []): void
    {
        (new Process($commands, base_path(), $options))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }
}
