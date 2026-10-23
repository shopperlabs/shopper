<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Support\Facades\Http;
use Shopper\Core\Exceptions\ProductImportException;
use Throwable;

final class ImageDownloader
{
    private const int MAX_REDIRECTS = 3;

    private const int TIMEOUT = 30;

    public function download(string $url): string
    {
        $path = (string) tempnam(sys_get_temp_dir(), 'shopper-import');
        $maxSize = (int) config('media-library.max_file_size', 1024 * 1024 * 10);
        $deadline = microtime(true) + self::TIMEOUT;

        try {
            for ($redirects = 0; $redirects <= self::MAX_REDIRECTS; $redirects++) {
                $response = Http::withoutRedirecting()
                    ->sink($path)
                    ->connectTimeout(10)
                    ->timeout(max($deadline - microtime(true), 1))
                    ->withOptions([
                        'verify' => (bool) config('media-library.media_downloader_ssl', true),
                        'decode_content' => false,
                        'curl' => $this->pinPublicAddress($url),
                        'progress' => function (int $total, int $downloaded) use ($url, $maxSize): void {
                            if ($downloaded > $maxSize) {
                                throw new ProductImportException("Image [{$url}] is larger than {$maxSize} bytes.");
                            }
                        },
                    ])
                    ->get($url);

                if (! $response->redirect()) {
                    $response->throw();

                    return $path;
                }

                $url = (string) UriResolver::resolve(new Uri($url), new Uri($response->header('Location')));
            }

            throw new ProductImportException("Image [{$url}] redirects more than ".self::MAX_REDIRECTS.' times.');
        } catch (Throwable $exception) {
            unlink($path);

            throw $exception;
        }
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function pinPublicAddress(string $url): array
    {
        $scheme = mb_strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = mb_strtolower((string) parse_url($url, PHP_URL_HOST));

        if (! in_array($scheme, ['http', 'https'], true) || $host === '') {
            throw new ProductImportException("Image [{$url}] is not an http or https URL.");
        }

        if (app()->isLocal()) {
            return [];
        }

        $addresses = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : gethostbynamel($host);

        if (! $addresses) {
            throw new ProductImportException("Image host [{$host}] could not be resolved.");
        }

        foreach ($addresses as $address) {
            if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_GLOBAL_RANGE) !== $address) {
                throw new ProductImportException("Image host [{$host}] is not a public address.");
            }
        }

        $port = parse_url($url, PHP_URL_PORT) ?: ($scheme === 'https' ? 443 : 80);

        return [CURLOPT_RESOLVE => ["{$host}:{$port}:{$addresses[0]}"]];
    }
}
