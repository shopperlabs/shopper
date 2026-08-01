<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Store;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Shopper\Api\Http\Resources\SettingResource;
use Shopper\Core\Enum\SocialPlatform;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Http\Support\Vary;

final class SettingController
{
    /**
     * The storefront reads this on nearly every page render, so the response
     * is cacheable by the browser and any shared cache. It varies by language:
     * the negotiated locale drives the translated country name.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $response = SettingResource::make($this->attributes())->toResponse($request);

        $response->setPublic();
        $response->setMaxAge((int) config('shopper.api.settings.max_age', 300));
        $response->setSharedMaxAge((int) config('shopper.api.settings.max_age', 300));
        $response->setEtag(hash('xxh128', (string) $response->getContent()));

        Vary::add($response, 'Accept-Language');

        $response->isNotModified($request);

        return $response;
    }

    /**
     * Read as a single keyed query rather than one cached lookup per key: a
     * setting that was never filled in holds null, and Cache::remember() treats
     * a cached null as a miss, so those keys would hit the database on every
     * request forever.
     *
     * @return array<string, mixed>
     */
    private function attributes(): array
    {
        $keys = array_map(strval(...), (array) config('shopper.api.settings.expose', []));

        $values = Setting::query()->whereIn('key', $keys)->pluck('value', 'key');

        $attributes = [];

        foreach ($keys as $key) {
            $attributes += $this->attribute($key, $values->get($key));
        }

        return $attributes + [
            'default_locale' => app()->getLocale(),
            'supported_locales' => $this->supportedLocales(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function attribute(string $key, mixed $value): array
    {
        return match ($key) {
            'country_id' => ['country' => $this->country($value)],
            'default_currency_id' => ['default_currency' => shopper_currency()],
            'currencies' => ['currencies' => $this->currencies((array) $value)],
            'logo', 'cover' => [$key => $this->media($value)],
            'social_links' => ['social_links' => $this->socialLinks((array) $value)],
            default => [$key => $value],
        };
    }

    /**
     * @return array{code: string, name: string}|null
     */
    private function country(mixed $id): ?array
    {
        if (blank($id)) {
            return null;
        }

        $country = Country::query()->find($id);

        return $country instanceof Country
            ? ['code' => $country->cca2, 'name' => $country->translated_name]
            : null;
    }

    /**
     * @param  array<int|string, mixed>  $ids
     * @return array<int, string>
     */
    private function currencies(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        return Currency::query()
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->pluck('code')
            ->all();
    }

    /**
     * The store logo and cover are plain paths on the media disk, uploaded from
     * the settings form rather than attached through the media library. A disk
     * configured without a domain yields a root relative url, unusable by a
     * storefront served from another origin, so it is resolved against the app.
     *
     * @return array{url: string}|null
     */
    private function media(mixed $path): ?array
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        $url = Storage::disk((string) config('shopper.media.storage.disk_name'))->url($path);

        return ['url' => str_starts_with($url, 'http') ? $url : url($url)];
    }

    /**
     * @param  array<int|string, mixed>  $links
     * @return array<int, array{platform: string, url: string, label: string}>
     */
    private function socialLinks(array $links): array
    {
        return collect($links)
            ->map(function (mixed $link): ?array {
                $platform = SocialPlatform::tryFrom((string) (is_array($link) ? ($link['platform'] ?? '') : ''));
                $url = is_array($link) ? (string) ($link['url'] ?? '') : '';

                if (! $platform instanceof SocialPlatform || $url === '') {
                    return null;
                }

                return [
                    'platform' => $platform->value,
                    'url' => $url,
                    'label' => $platform->getLabel(),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{code: string, label: string}>
     */
    private function supportedLocales(): array
    {
        return collect((array) config('shopper.admin.locales', []))
            ->map(fn (mixed $locale, string $code): array => [
                'code' => $code,
                'label' => (string) (is_array($locale) ? ($locale['label'] ?? $code) : $code),
            ])
            ->values()
            ->all();
    }
}
