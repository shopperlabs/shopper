<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

uses(Tests\Api\TestCase::class);

afterEach(function (): void {
    File::delete(File::glob(database_path('migrations/*personal_access_tokens*')));
    File::delete(base_path('TmpApiUser.php'));
});

it('publishes the sanctum migrations and recognizes a ready user model', function (): void {
    $this->artisan('shopper:api:install')
        ->expectsConfirmation('Run database migrations?')
        ->assertSuccessful();

    expect(File::glob(database_path('migrations/*personal_access_tokens*')))->not->toBeEmpty();
});

it('adds the `HasApiTokens` trait to the configured user model', function (): void {
    $path = base_path('TmpApiUser.php');

    File::put($path, <<<'PHP'
        <?php

        declare(strict_types=1);

        namespace Tests\Tmp;

        use Illuminate\Foundation\Auth\User as Authenticatable;
        use Illuminate\Notifications\Notifiable;

        class TmpApiUser extends Authenticatable
        {
            use Notifiable;
        }
        PHP);

    require $path;

    config(['auth.providers.users.model' => Tests\Tmp\TmpApiUser::class]);

    $this->artisan('shopper:api:install')
        ->expectsConfirmation('Add the [HasApiTokens] trait to [Tests\Tmp\TmpApiUser]?', 'yes')
        ->expectsConfirmation('Run database migrations?')
        ->assertSuccessful();

    $contents = File::get($path);

    expect($contents)
        ->toContain('use Laravel\Sanctum\HasApiTokens;')
        ->toContain("\n    use HasApiTokens;\n");
});
