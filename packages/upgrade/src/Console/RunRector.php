<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Process;

#[AsCommand('shopper:upgrade:rector')]
final class RunRector extends Command
{
    protected $signature = 'shopper:upgrade:rector
        {--path=app : Comma-separated paths to process}
        {--dry-run : Preview changes without modifying any file}';

    protected $description = 'Apply the 2.x to 3.x class rename rules to your application code with Rector';

    public function handle(): int
    {
        $binary = base_path('vendor/bin/rector');

        if (! file_exists($binary)) {
            $this->error('Rector is not installed. Run: composer require rector/rector --dev');

            return self::FAILURE;
        }

        $arguments = [
            $binary,
            'process',
            ...array_map('trim', explode(',', (string) $this->option('path'))),
            '--config',
            (string) realpath(__DIR__.'/../../rector.php'),
            '--clear-cache',
        ];

        if ($this->option('dry-run')) {
            $arguments[] = '--dry-run';
        }

        $process = new Process($arguments, base_path(), timeout: null);

        if (Process::isTtySupported()) {
            $process->setTty(true);
        }

        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        return $process->getExitCode() ?? self::FAILURE;
    }
}
