<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\ImageDownloader;
use Shopper\Core\Jobs\DownloadProductImageJob;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\Core\Stubs\Product;

uses(Tests\Core\TestCase::class);

function onePixelPng(): string
{
    return (string) base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
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

    it('removes the downloaded file when the media collection refuses it', function (): void {
        config(['shopper.media.accepts_mime_types' => ['image/png']]);

        Http::fake(function (Request $request, array $options) use (&$sink) {
            $sink = $options['sink'];

            return Http::response('<html></html>');
        });

        expect(fn () => (new DownloadProductImageJob(Product::factory()->create(), 'https://93.184.216.34/page.html'))
            ->handle(new ImageDownloader))
            ->toThrow(FileUnacceptableForCollection::class)
            ->and($sink)->not->toBeFile();
    });
});
