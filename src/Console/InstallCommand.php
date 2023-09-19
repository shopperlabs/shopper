<?php

declare(strict_types=1);

namespace Shopper\Framework\Console;

use Illuminate\Console\Command;
use Shopper\Framework\Providers\ShopperServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Symfony\Component\Console\Helper\ProgressBar;

class InstallCommand extends Command
{
    protected ProgressBar $progressBar;

    protected $signature = 'shopper:install';

    protected $description = 'Install Shopper e-commerce administration';

    public function __construct()
    {
        parent::__construct();

        if (file_exists(config_path('shopper/system.php'))) {
            $this->setHidden();
        }
    }

    public function handle(): void
    {
        $this->progressBar = $this->output->createProgressBar(3);
        $this->introMessage();
        sleep(1);

        $this->info('Installation of Shopper package, setup database, publish assets and config files');

        if (! $this->progressBar->getProgress()) {
            $this->progressBar->start();
        }

        $this->call('vendor:publish', ['--provider' => ShopperServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => MediaLibraryServiceProvider::class, '--tag' => 'migrations']);
        $this->progressBar->advance();

        $this->setupDatabaseConfig();
        $this->addEnvVariable();
        $this->call('shopper:link');
        $this->progressBar->advance();

        $this->completeSetup();

        if (! $this->option('no-interaction')) {
            (new Thanks($this->output))();
        }
    }

    protected function setupDatabaseConfig(): void
    {
        $this->info('Migrating the database tables into your application');
        $this->call('migrate');
        $this->progressBar->advance();

        $this->info('Flush data into the database');
        $this->call('db:seed', ['--class' => 'ShopperSeeder']);
        $this->progressBar->advance();

        // Visually slow down the installation process so that the user can read what's happening
        usleep(350000);
    }

    protected function addEnvVariable(): void
    {
        $env = [
            'SHOPPER_DASHBOARD_PREFIX' => config('shopper.routes.prefix'),
        ];

        $this->progressBar->advance();
        setEnvironmentValue($env);
        $this->info('Add SHOPPER_DASHBOARD_PREFIX to .env file');
    }

    protected function completeSetup(): void
    {
        $this->progressBar->finish();

        // Outro message
        $this->info("
                                      ,@@@@@@@,
                              ,,,.   ,@@@@@@/@@,  .oo8888o.
                           ,&%%&%&&%,@@@@@/@@@@@@,8888\\88/8o
                          ,%&\\%&&%&&%,@@@\\@@@/@@@88\\88888/88'
                          %&&%&%&/%&&%@@\\@@/ /@@@88888\\88888'
                          %&&%/ %&%%&&@@\\ V /@@' `88\\8 `/88'
                          `&%\\ ` /%&'    |.|        \\ '|8'
                              |o|        | |         | |
                              |.|        | |         | |
       ======================== Installation Complete ======================
        ");

        $this->comment("Before create an admin user you have to change the extend class of your User Model to The Shopper User Model 'Shopper\\Framework\\Models\\User\\User'");
        $this->comment("To create a user, run 'php artisan shopper:admin'");
    }

    protected function introMessage(): void
    {
        // Intro message
        $this->info('
                _____ __
              / ___// /_  ____  ____  ____  ___  _____
              \__ \/ __ \/ __ \/ __ \/ __ \/ _ \/ ___/
             ___/ / / / / /_/ / /_/ / /_/ /  __/ /
            /____/_/ /_/\____/ .___/ .___/\___/_/
                            /_/   /_/

            Installation started. Please wait...
        ');
    }
}
