<?php

namespace Shopper\Framework\Console;

use Illuminate\Console\Command;
use Spatie\Analytics\AnalyticsServiceProvider;
use Symfony\Component\Console\Helper\ProgressBar;
use Shopper\Framework\Providers\ShopperServiceProvider;

class InstallCommand extends Command
{
    /**
     * @var ProgressBar
     */
    protected $progressBar;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopper:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Shopper e-commerce administration';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        if (file_exists(config_path('shopper/system.php'))) {
            $this->setHidden(true);
        }
    }

    /**
     * Execute the console command.
     */
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
        $this->call('vendor:publish', ['--provider' => AnalyticsServiceProvider::class]);
        $this->progressBar->advance();

        $this->setupDatabaseConfig();
        $this->addEnvVariable();
        $this->call('shopper:link');
        $this->progressBar->advance();

        $this->complete();

        if (! (bool) $this->option('no-interaction')) {
            (new Thanks($this->output))();
        }
    }

    /**
     * Setup database configuration and seeder.
     */
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

    /**
     * Set env variables.
     */
    protected function addEnvVariable(): void
    {
        $env = [
            'DASHBOARD_PREFIX' => config('shopper.routes.prefix'),
        ];

        $this->progressBar->advance();
        setEnvironmentValue($env);
        $this->info('Add DASHBOARD_PREFIX to .env file');
    }

    protected function complete(): void
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

        $this->comment("To create a user, run 'php artisan shopper:admin'");
    }

    protected function introMessage()
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
