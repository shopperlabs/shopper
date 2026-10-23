<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\ImageDownloader;
use Shopper\Core\Jobs\DownloadProductImageJob;
use Shopper\Core\Models\ProductImport;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;

uses(Tests\Core\TestCase::class);

function onePixelPng(): string
{
    return (string) base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
}

/**
 * @param  array<int, array{handle: string, message: string}>  $errors
 */
function processingImport(array $errors = []): ProductImport
{
    return ProductImport::query()->create([
        'source' => 'csv',
        'disk' => 'local',
        'file_path' => 'imports/products.csv',
        'status' => ImportStatus::Processing,
        'batch_id' => 'batch-1',
        'errors' => $errors,
    ]);
}

describe(ImageDownloader::class, function (): void {
    it('refuses a url that does not point to a public http host', function (string $url): void {
        Http::fake();

        expect(fn (): string => (new ImageDownloader)->download($url))
            ->toThrow(ProductImportException::class);

        Http::assertNothingSent();
    })->with([
        'loopback' => 'http://127.0.0.1/a.png',
        'localhost' => 'http://localhost/a.png',
        'cloud metadata' => 'http://169.254.169.254/latest/meta-data/',
        'private network' => 'http://10.0.0.5/a.png',
        'shared address space' => 'http://100.100.100.200/a.png',
        'ipv6 loopback' => 'http://[::1]/a.png',
        'userinfo' => 'http://93.184.216.34@127.0.0.1/a.png',
        'file scheme' => 'file:///etc/passwd',
    ]);

    it('refuses a redirect to a private address', function (): void {
        Http::fake([
            '93.184.216.34/*' => Http::response('', 302, ['Location' => 'http://127.0.0.1/secret']),
        ]);

        expect(fn (): string => (new ImageDownloader)->download('https://93.184.216.34/a.png'))
            ->toThrow(ProductImportException::class);

        Http::assertSentCount(1);
    });

    it('stops after three redirects', function (): void {
        Http::fake(['93.184.216.34/*' => Http::response('', 302, ['Location' => '/loop.png'])]);

        expect(fn (): string => (new ImageDownloader)->download('https://93.184.216.34/loop.png'))
            ->toThrow(ProductImportException::class);

        Http::assertSentCount(4);
    });

    it('removes the temporary file when the server answers with an error', function (): void {
        Http::fake(function (Request $request, array $options) use (&$sink) {
            $sink = $options['sink'];

            return Http::response('', 404);
        });

        expect(fn (): string => (new ImageDownloader)->download('https://93.184.216.34/a.png'))
            ->toThrow(RequestException::class)
            ->and($sink)->not->toBeFile();
    });

    it('aborts a download larger than the media size limit', function (): void {
        config(['media-library.max_file_size' => 100]);

        Http::fake(function (Request $request, array $options) use (&$sink) {
            $sink = $options['sink'];

            expect($options['decode_content'])->toBeFalse();

            $options['progress'](0, 101);

            return Http::response(onePixelPng());
        });

        expect(fn (): string => (new ImageDownloader)->download('https://93.184.216.34/a.png'))
            ->toThrow(ProductImportException::class)
            ->and($sink)->not->toBeFile();
    });

    it('pins the connection to the checked address', function (): void {
        Http::fake(function (Request $request, array $options) {
            expect($options['curl'][CURLOPT_RESOLVE])->toBe(['93.184.216.34:443:93.184.216.34']);

            return Http::response(onePixelPng());
        });

        $path = (new ImageDownloader)->download('https://93.184.216.34/a.png');

        expect(file_get_contents($path))->toBe(onePixelPng());

        unlink($path);
    });
});

describe(DownloadProductImageJob::class, function (): void {
    it('stores the image with an extension guessed from its content', function (): void {
        Http::fake(['93.184.216.34/*' => Http::response(onePixelPng())]);

        $product = Product::factory()->create();

        (new DownloadProductImageJob($product, 'https://93.184.216.34/images/blue-shirt', 'Blue shirt', 3))
            ->handle(new ImageDownloader);

        $media = Media::query()->sole();

        expect($media->collection_name)->toBe(config('shopper.media.storage.collection_name'))
            ->and($media->file_name)->toBe('blue-shirt.png')
            ->and($media->name)->toBe('Blue shirt')
            ->and($media->order_column)->toBe(3);
    });

    it('skips an image already imported from the same url', function (): void {
        Http::fake(fn () => Http::response(onePixelPng()));

        $product = Product::factory()->create();

        foreach (['a/front', 'b/front', 'a/front'] as $path) {
            (new DownloadProductImageJob($product, "https://93.184.216.34/{$path}"))->handle(new ImageDownloader);
        }

        expect(Media::query()->count())->toBe(2);

        Http::assertSentCount(2);
    });

    it('records an image from a private host without retrying it', function (): void {
        $import = processingImport();

        (new DownloadProductImageJob(Product::factory()->create(), 'http://10.0.0.5/a.png'))
            ->withBatchId('batch-1')
            ->handle(new ImageDownloader);

        expect($import->refresh()->errors)->toHaveCount(1);
    });

    it('retries a download that failed for a temporary reason', function (int $status): void {
        Http::fake(['93.184.216.34/*' => Http::response('', $status)]);

        $import = processingImport();

        $job = (new DownloadProductImageJob(Product::factory()->create(), 'https://93.184.216.34/a.png'))
            ->withBatchId('batch-1');

        expect(fn () => $job->handle(new ImageDownloader))->toThrow(RequestException::class)
            ->and($import->refresh()->errors)->toBeEmpty();
    })->with([
        'server error' => 503,
        'rate limited' => 429,
    ]);

    it('downloads the image once when two jobs for the same url overlap', function (): void {
        $product = Product::factory()->create();
        $url = 'https://93.184.216.34/a.png';
        $run = fn (DownloadProductImageJob $job) => (new Pipeline(app()))
            ->send($job)
            ->through($job->middleware())
            ->then(fn (DownloadProductImageJob $job) => $job->handle(new ImageDownloader));
        $overlapped = false;

        Http::fake(function () use (&$overlapped, $run, $product, $url) {
            if (! $overlapped) {
                $overlapped = true;
                $run(new DownloadProductImageJob($product, $url));
            }

            return Http::response(onePixelPng());
        });

        $run(new DownloadProductImageJob($product, $url));

        expect(Media::query()->count())->toBe(1);

        Http::assertSentCount(1);
    });

    it('downloads the image again on retry when storing it failed', function (): void {
        Storage::fake('public');
        Http::fake(fn () => Http::response(onePixelPng()));

        $failing = true;

        Media::created(function () use (&$failing): void {
            if ($failing) {
                $failing = false;

                throw new RuntimeException('Disk unavailable.');
            }
        });

        $job = new DownloadProductImageJob(Product::factory()->create(), 'https://93.184.216.34/a.png');

        expect(fn () => $job->handle(new ImageDownloader))->toThrow(RuntimeException::class);

        $job->handle(new ImageDownloader);

        expect(Media::query()->sole()->getPath())->toBeFile();
    });

    it('lets the batch finish when the variant was deleted before its image downloaded', function (): void {
        config(['queue.default' => 'database']);

        $variant = ProductVariant::factory()->create();

        $batch = Bus::batch([new DownloadProductImageJob($variant, 'https://93.184.216.34/a.png')])->dispatch();

        $variant->delete();

        Artisan::call('queue:work', ['connection' => 'database', '--stop-when-empty' => true]);

        expect($batch->fresh()->finished())->toBeTrue();
    });

    it('records the failure on the import that queued the image', function (Model $model): void {
        Http::fake(['93.184.216.34/*' => Http::response('', 404)]);

        $import = processingImport([['handle' => 'mug', 'message' => 'Name is required.']]);

        (new DownloadProductImageJob($model, 'https://93.184.216.34/a.png'))
            ->withBatchId('batch-1')
            ->handle(new ImageDownloader);

        expect($import->refresh()->errors)->toBe([
            ['handle' => 'mug', 'message' => 'Name is required.'],
            ['handle' => 'tee', 'message' => 'Image [https://93.184.216.34/a.png] could not be imported: HTTP request returned status code 404'],
        ]);
    })->with([
        'product' => fn () => Product::factory()->create(['slug' => 'tee']),
        'variant' => fn () => ProductVariant::factory()->for(Product::factory()->state(['slug' => 'tee']), 'product')->create(),
    ]);

    it('throws a failure that no import can record', function (): void {
        Http::fake(['93.184.216.34/*' => Http::response('', 404)]);

        processingImport();

        $product = Product::factory()->create();

        expect(fn () => (new DownloadProductImageJob($product, 'https://93.184.216.34/a.png'))->handle(new ImageDownloader))
            ->toThrow(RequestException::class)
            ->and(fn () => (new DownloadProductImageJob($product, 'https://93.184.216.34/a.png'))->withBatchId('batch-2')->handle(new ImageDownloader))
            ->toThrow(RequestException::class);
    });

    it('records a temporary failure when the last attempt fails', function (): void {
        Http::fake(['93.184.216.34/*' => Http::response('', 503)]);

        $import = processingImport();

        $job = (new DownloadProductImageJob(Product::factory()->create(), 'https://93.184.216.34/a.png'))
            ->withFakeQueueInteractions()
            ->withBatchId('batch-1');
        $job->job->attempts = $job->tries;

        $job->handle(new ImageDownloader);

        expect($import->refresh()->errors)->toHaveCount(1);
    });

    it('stops recording image failures once the import holds 100 errors', function (): void {
        Http::fake(['93.184.216.34/*' => Http::response('', 404)]);

        $import = processingImport(array_fill(0, 100, ['handle' => 'mug', 'message' => 'Name is required.']));

        (new DownloadProductImageJob(Product::factory()->create(), 'https://93.184.216.34/a.png'))
            ->withBatchId('batch-1')
            ->handle(new ImageDownloader);

        expect($import->refresh()->errors)->toHaveCount(100);
    });

    it('removes the downloaded file when the media collection refuses it', function (): void {
        config(['shopper.media.accepts_mime_types' => ['image/png']]);

        Http::fake(function (Request $request, array $options) use (&$sink) {
            $sink = $options['sink'];

            return Http::response('<html></html>');
        });

        $import = processingImport();

        (new DownloadProductImageJob(Product::factory()->create(), 'https://93.184.216.34/page.html'))
            ->withBatchId('batch-1')
            ->handle(new ImageDownloader);

        expect($import->refresh()->errors)->toHaveCount(1)
            ->and($sink)->not->toBeFile();
    });
});
