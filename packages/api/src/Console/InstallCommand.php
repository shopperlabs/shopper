<?php

declare(strict_types=1);

namespace Shopper\Api\Console;

use Closure;
use Illuminate\Console\Command;
use Laravel\Sanctum\HasApiTokens;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

#[AsCommand(name: 'shopper:api:install')]
final class InstallCommand extends Command
{
    protected $signature = 'shopper:api:install';

    protected $description = 'Set up the Shopper Store API: Sanctum migrations, user model and configuration';

    public function handle(): int
    {
        intro('Setting up the Shopper Store API');

        $this->task('Publishing Sanctum migrations', function (): void {
            $this->callSilently('vendor:publish', ['--tag' => 'sanctum-migrations']);
        });

        $this->task('Publishing Shopper configuration', function (): void {
            $this->callSilently('vendor:publish', ['--tag' => 'shopper-config']);
        });

        $this->prepareUserModel();

        if (confirm('Run database migrations?')) {
            $this->task('Running database migrations', function (): void {
                $this->callSilently('migrate');
            });
        }

        $this->renderNextSteps();

        return self::SUCCESS;
    }

    private function task(string $title, Closure $callback): void
    {
        spin(callback: $callback, message: $title);

        $width = 52;
        $dots = str_repeat('.', max(1, $width - mb_strlen($title)));

        $this->output->writeln("  <fg=#94A3B8>{$title}</> <fg=#334155>{$dots}</> <fg=#22C55E>✓</>");
    }

    private function prepareUserModel(): void
    {
        /** @var class-string $model */
        $model = (string) config('auth.providers.users.model');

        if (! class_exists($model)) {
            warning("Unable to resolve the [{$model}] user model. Add the [Laravel\Sanctum\HasApiTokens] trait to it manually.");

            return;
        }

        if (in_array(HasApiTokens::class, class_uses_recursive($model), true)) {
            $this->task("The [{$model}] model already uses [HasApiTokens]", fn (): null => null);
        } else {
            $this->addApiTokensTrait($model);
        }

        if (! method_exists($model, 'addresses')) {
            warning("The [{$model}] model does not use the [Shopper\Traits\InteractsWithShopper] trait. Customer endpoints need it.");
        }
    }

    /**
     * @param  class-string  $model
     *
     * @throws ReflectionException
     */
    private function addApiTokensTrait(string $model): void
    {
        $path = (new ReflectionClass($model))->getFileName();

        if ($path === false || ! is_writable($path) || ! confirm("Add the [HasApiTokens] trait to [{$model}]?")) {
            $this->printTraitInstructions($model);

            return;
        }

        $contents = (string) file_get_contents($path);

        if (! str_contains($contents, 'use Laravel\Sanctum\HasApiTokens;')) {
            $contents = preg_match_all('/^use\s+[^;]+;$/m', $contents, $matches, PREG_OFFSET_CAPTURE) > 0
                ? $this->insertAfterLastImport($contents, $matches[0])
                : (string) preg_replace('/^(namespace\s+[^;]+;)/m', "$1\n\nuse Laravel\Sanctum\HasApiTokens;", $contents, 1);
        }

        $modified = (string) preg_replace('/(\bclass\s+\w+[^{]*\{)/', "$1\n    use HasApiTokens;\n", $contents, 1);

        if ($modified === $contents || ! str_contains($modified, 'use HasApiTokens;')) {
            $this->printTraitInstructions($model);

            return;
        }

        file_put_contents($path, $modified);

        $this->task("Adding the [HasApiTokens] trait to [{$model}]", fn (): null => null);
    }

    /**
     * @param  array<int, array{0: string, 1: int}>  $imports
     */
    private function insertAfterLastImport(string $contents, array $imports): string
    {
        [$statement, $offset] = end($imports);

        $position = $offset + mb_strlen($statement);

        return mb_substr($contents, 0, $position)."\nuse Laravel\Sanctum\HasApiTokens;".mb_substr($contents, $position);
    }

    /**
     * @param  class-string  $model
     */
    private function printTraitInstructions(string $model): void
    {
        warning("Add the [Laravel\Sanctum\HasApiTokens] trait to [{$model}] to enable the store authentication endpoints:");

        note(<<<'PHP'
            use Laravel\Sanctum\HasApiTokens;

            class User extends Authenticatable implements ShopperUser
            {
                use HasApiTokens;
            }
            PHP);
    }

    private function renderNextSteps(): void
    {
        $prefix = (string) config('shopper.http.prefix', 'store');

        note(implode("\n", [
            "→ Your storefront endpoints live under /{$prefix}",
            '→ Pin a pricing zone per request with the X-Shopper-Zone header',
            '→ Consume the API with: npm install @shopperlabs/shopper-sdk',
        ]));

        outro('The Shopper Store API is ready!');
    }
}
