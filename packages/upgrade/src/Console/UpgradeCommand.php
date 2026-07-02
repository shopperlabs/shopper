<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

#[AsCommand('shopper:upgrade')]
final class UpgradeCommand extends Command
{
    protected $signature = 'shopper:upgrade
        {--path=app : Comma-separated paths for Rector to process}
        {--force : Run every step without confirmation}';

    protected $description = 'Run the full 2.x to 3.x upgrade: migrations, code renames and data migrations';

    public function handle(): int
    {
        warning('This will run database migrations, rewrite application code with Rector, and migrate permission and money data.');
        note('Make sure your work is committed to git and your database is backed up before continuing.');

        if (! $this->option('force')) {
            if ($this->workingTreeIsDirty() && ! confirm('Your git working tree has uncommitted changes. Continue anyway?', default: false)) {
                warning('Upgrade cancelled. Commit or stash your changes first.');

                return self::SUCCESS;
            }

            if (! confirm('Run the 2.x to 3.x upgrade now?', default: false)) {
                warning('Upgrade cancelled.');

                return self::SUCCESS;
            }
        }

        info('Step 1/5 — Running database migrations');
        $this->call('migrate', ['--force' => true]);

        info('Step 2/5 — Renaming classes with Rector');
        $this->runRector();

        info('Step 3/5 — Migrating permissions to dot notation');
        $this->call('shopper:upgrade:permissions', ['--force' => true]);

        info('Step 4/5 — Fixing zero-decimal currency amounts');
        $this->call('shopper:upgrade:fix-zero-decimal-currencies', ['--force' => true]);

        info('Step 5/5 — Reconciling the stock snapshot against the inventory ledger');
        $this->call('shopper:stock:reconcile', ['--fix' => true]);

        $this->printNextSteps();

        return self::SUCCESS;
    }

    private function runRector(): void
    {
        $path = (string) $this->option('path');

        $hasTarget = collect(explode(',', $path))
            ->map(fn (string $segment): string => mb_trim($segment))
            ->filter()
            ->contains(fn (string $segment): bool => is_dir(base_path($segment)));

        if (! $hasTarget) {
            warning("None of the paths [{$path}] exist — skipping Rector. Run `shopper:upgrade:rector --path=...` manually.");

            return;
        }

        $this->call('shopper:upgrade:rector', ['--path' => $path]);
    }

    private function workingTreeIsDirty(): bool
    {
        $process = new Process(['git', 'status', '--porcelain'], base_path());
        $process->run();

        if (! $process->isSuccessful()) {
            return false;
        }

        return mb_trim($process->getOutput()) !== '';
    }

    private function printNextSteps(): void
    {
        info('Automated upgrade steps complete.');

        note(
            "Next steps:\n".
            "  1. Review the changes Rector made to your code.\n".
            "  2. Run `php artisan boost:update --discover` to pull the Shopper upgrade skills for the changes that need manual judgment (settings, contracts, product edit, config).\n".
            '  3. Run your test suite and boot the app to confirm everything works.'
        );
    }
}
