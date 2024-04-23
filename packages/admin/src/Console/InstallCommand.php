<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Shopper\Core\Console\Thanks;
use Shopper\ShopperServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;

use function Laravel\Prompts\info;

#[AsCommand(name: 'shopper:panel-install')]
final class InstallCommand extends Command
{
    protected ProgressBar $progressBar;

    protected $signature = 'shopper:panel-install';

    protected $description = 'Install Shopper e-commerce admin panel';

    public function __construct()
    {
        parent::__construct();

        if (file_exists(config_path('shopper/admin.php'))) {
            $this->setHidden();
        }
    }

    public function handle(): void
    {
        $this->newLine();
        $this->progressBar = $this->output->createProgressBar(3);
        sleep(1);

        info('Installation of Shopper Panel, publish assets and config files');

        if (! $this->progressBar->getProgress()) {
            $this->progressBar->start();
        }

        $this->call('vendor:publish', ['--provider' => ShopperServiceProvider::class]);
        $this->progressBar->advance();

        info('Publish filament-forms assets...');
        $this->call('filament:assets');

        info('Enable Shopper symlink for storage...');
        $this->call('shopper:link');
        $this->progressBar->advance();

        $this->completeSetup();

        if (! $this->option('no-interaction')) {
            (new Thanks($this->output))();
        }
    }

    protected function completeSetup(): void
    {
        $this->progressBar->finish();

        info("
                                      ,@@@@@@@,
                              ,,,.   ,@@@@@@/@@,  .oo8888o.
                           ,&%%&%&&%,@@@@@/@@@@@@,8888\\88/8o
                          ,%&\\%&&%&&%,@@@\\@@@/@@@88\\88888/88'
                          %&&%&%&/%&&%@@\\@@/ /@@@88888\\88888'
                          %&&%/ %&%%&&@@\\ V /@@' `88\\8 `/88'
                          `&%\\ ` /%&'    |.|        \\ '|8'
                              |o|        | |         | |
                              |.|        | |         | |
       ======================== Installation Complete 🚀 ======================
        ");

        $this->comment("Before create an admin user you have to change the extend class of your User Model to The Shopper User Model 'Shopper\\Core\\Models\\User'");
        $this->comment("To create a user, run 'php artisan shopper:user'");
    }
}
