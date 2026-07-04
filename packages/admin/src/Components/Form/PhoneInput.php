<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Cache;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Shopper\Core\Models\Country;
use Shopper\Core\Rules\Phone;

class PhoneInput extends TextInput
{
    protected string $view = 'shopper::filament.form.phone-input';

    protected string|Closure|null $defaultCountry = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('shopper::forms.label.phone_number'))
            ->tel()
            ->rule(fn (): Phone => new Phone($this->getDefaultCountry()))
            ->afterStateHydrated(function (self $component, $state): void {
                if (blank($state) || ! is_string($state)) {
                    return;
                }

                $component->state($component->formatNumber($state, PhoneNumberFormat::E164) ?? $state);
            })
            ->dehydrateStateUsing(function (?string $state): ?string {
                if (blank($state)) {
                    return null;
                }

                return $this->formatNumber($state, PhoneNumberFormat::E164) ?? $state;
            });
    }

    public function defaultCountry(string|Closure|null $country): static
    {
        $this->defaultCountry = $country;

        return $this;
    }

    public function getDefaultCountry(): ?string
    {
        return $this->evaluate($this->defaultCountry) ?? Phone::defaultRegion();
    }

    /**
     * @return array<int, array{code: string, name: string, dial: string, flag: string}>
     */
    public function getPhoneCountries(): array
    {
        return Cache::remember(
            'shopper.phone-countries.'.app()->getLocale(),
            now()->addDay(),
            fn (): array => Country::query()
                ->get(['id', 'name', 'cca2', 'phone_calling_code'])
                ->map(function (Country $country): ?array {
                    $callingCode = $country->phone_calling_code ?? [];
                    $root = $callingCode['root'] ?? null;
                    $suffixes = $callingCode['suffixes'] ?? [];

                    if (blank($root)) {
                        return null;
                    }

                    return [
                        'code' => $country->cca2,
                        'name' => $country->translated_name,
                        'dial' => count($suffixes) === 1 ? $root.$suffixes[0] : $root,
                        'flag' => $country->svg_flag,
                    ];
                })
                ->filter()
                ->sortBy(fn (array $country): string => $country['name'], SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
                ->all()
        );
    }

    public function getSelectedCountryCode(): ?string
    {
        $phoneNumber = $this->parseState();

        if ($phoneNumber) {
            $region = PhoneNumberUtil::getInstance()->getRegionCodeForNumber($phoneNumber);

            if ($region) {
                return $region;
            }
        }

        return $this->getDefaultCountry();
    }

    public function getNationalNumber(): ?string
    {
        $phoneNumber = $this->parseState();

        if ($phoneNumber) {
            return PhoneNumberUtil::getInstance()->getNationalSignificantNumber($phoneNumber);
        }

        $state = $this->getState();

        return is_string($state) ? $state : null;
    }

    private function parseState(): ?PhoneNumber
    {
        $state = $this->getState();

        if (blank($state) || ! is_string($state)) {
            return null;
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $phoneNumberUtil->parse($state, $this->getDefaultCountry());
        } catch (NumberParseException) {
            return null;
        }

        return $phoneNumberUtil->isValidNumber($phoneNumber) ? $phoneNumber : null;
    }

    private function formatNumber(string $value, PhoneNumberFormat $format): ?string
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $phoneNumberUtil->parse($value, $this->getDefaultCountry());
        } catch (NumberParseException) {
            return null;
        }

        if (! $phoneNumberUtil->isValidNumber($phoneNumber)) {
            return null;
        }

        return $phoneNumberUtil->format($phoneNumber, $format);
    }
}
