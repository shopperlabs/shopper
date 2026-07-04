<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Closure;
use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Country;

class CountryField extends Select
{
    protected array|Closure|null $only = null;

    protected array|Closure|null $except = null;

    protected bool|Closure $groupedByRegion = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('shopper::forms.label.country'))
            ->searchable()
            ->native(false)
            ->allowHtml()
            ->options(fn (): array => $this->getCountryOptions())
            ->getSearchResultsUsing(function (string $search): array {
                $search = mb_strtolower($search);

                return $this->getCountries()
                    ->filter(fn (Country $country): bool => str_contains(mb_strtolower($country->translated_name), $search))
                    ->take(50)
                    ->mapWithKeys(fn (Country $country): array => [$country->id => $this->getOptionHtml($country)])
                    ->all();
            });
    }

    public function only(array|Closure|null $codes): static
    {
        $this->only = $codes;

        return $this;
    }

    public function except(array|Closure|null $codes): static
    {
        $this->except = $codes;

        return $this;
    }

    public function groupedByRegion(bool|Closure $condition = true): static
    {
        $this->groupedByRegion = $condition;

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    private function getCountries(): Collection
    {
        /** @var Collection<int, Country> $countries */
        $countries = Cache::remember(
            'shopper.countries.options.'.app()->getLocale(),
            now()->addDay(),
            fn (): Collection => Country::query()
                ->get(['id', 'name', 'cca2', 'region'])
                ->sortBy(fn (Country $country): string => $country->translated_name, SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
        );

        $only = $this->evaluate($this->only);
        $except = $this->evaluate($this->except);

        return $countries
            ->when($only, fn (Collection $countries): Collection => $countries->filter(
                fn (Country $country): bool => in_array($country->cca2, $only, true)
            ))
            ->when($except, fn (Collection $countries): Collection => $countries->reject(
                fn (Country $country): bool => in_array($country->cca2, $except, true)
            ))
            ->values();
    }

    /**
     * @return array<int|string, string|array<int, string>>
     */
    private function getCountryOptions(): array
    {
        $countries = $this->getCountries();

        if (! $this->evaluate($this->groupedByRegion)) {
            return $countries
                ->mapWithKeys(fn (Country $country): array => [$country->id => $this->getOptionHtml($country)])
                ->all();
        }

        return $countries
            ->sortBy('region')
            ->groupBy('region')
            ->map(
                fn (Collection $group): array => $group
                    ->mapWithKeys(fn (Country $country): array => [$country->id => $this->getOptionHtml($country)])
                    ->all()
            )
            ->all();
    }

    private function getOptionHtml(Country $country): string
    {
        return '<span class="flex items-center gap-2"><img src="'.$country->svg_flag.'" class="size-4 shrink-0 rounded-full object-cover" alt="" /><span>'.e($country->translated_name).'</span></span>';
    }
}
