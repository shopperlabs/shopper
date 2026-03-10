<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PDO;
use PDOException;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use WithWorkbench;

    protected function defineEnvironment($app): void
    {
        $app['config']->set('auth.providers.users.model', Core\Stubs\User::class);
        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__.'/../packages/admin/resources/views',
        ]);

        // Paratest sets TEST_TOKEN for each worker (0, 1, 2, etc.)
        $testToken = env('TEST_TOKEN', '');
        $dbSuffix = $testToken !== '' ? "_{$testToken}" : '';
        $dbName = env('DB_DATABASE', 'testing').$dbSuffix;

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', ':memory:'),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('MYSQL_PORT', env('DB_PORT', '3306')),
            'database' => $dbName,
            'username' => env('MYSQL_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('MYSQL_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'timezone' => '+00:00',
            'options' => [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ]);

        $app['config']->set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('PGSQL_PORT', env('DB_PORT', '5432')),
            'database' => $dbName,
            'username' => env('PGSQL_USERNAME', env('DB_USERNAME', 'postgres')),
            'password' => env('PGSQL_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
            'options' => [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ]);

        $connection = env('DB_CONNECTION', 'testing');

        // Auto-create test databases for MySQL/PostgreSQL parallel workers
        if ($dbSuffix !== '' && in_array($connection, ['mysql', 'pgsql'])) {
            $this->ensureDatabaseExists($connection, $dbName, $app['config']->get("database.connections.{$connection}"));
        }

        $app['config']->set('database.default', $connection);
        $app['config']->set('database.connections.testing', $app['config']->get('database.connections.sqlite'));

        $this->replaceModelsForTesting();
    }

    protected function replaceModelsForTesting(): void
    {
        config([
            'shopper.models.brand' => Core\Stubs\Brand::class,
            'shopper.models.category' => Core\Stubs\Category::class,
            'shopper.models.collection' => Core\Stubs\Collection::class,
            'shopper.models.product' => Core\Stubs\Product::class,
            'shopper.models.variant' => Core\Stubs\ProductVariant::class,
        ]);
    }

    protected function ensureDatabaseExists(string $driver, string $dbName, array $config): void
    {
        try {
            if ($driver === 'mysql') {
                $pdo = new PDO(
                    "mysql:host={$config['host']};port={$config['port']}",
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
                $pdo->exec("create database if not exists `{$dbName}` character set {$config['charset']} collate {$config['collation']}");
            } elseif ($driver === 'pgsql') {
                $pdo = new PDO(
                    "pgsql:host={$config['host']};port={$config['port']};dbname=postgres",
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
                $result = $pdo->query("select 1 from pg_database where datname = '{$dbName}'");

                if ($result->fetchColumn() === false) {
                    $pdo->exec("create database \"{$dbName}\"");
                }
            }
        } catch (PDOException) {
            // Database might already exist or connection failed - let it fail later with a clearer error
        }
    }
}
