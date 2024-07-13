<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\Select;
use Filament\Support\Components\Component;
use Shopper\Core\Models\Country;

final class CountrySelect
{
    public function make(string $name): Component
    {
        return Select::make($name)
            ->options(
                Country::query()
                    ->select('name', 'id', 'region', 'cca2')
                    ->orderBy('name')
                    ->get()
                    ->mapWithKeys(
                        fn (Country $country): array => [
                            $country->id => "<div class='flex items-center gap-2'>
                                                <img
                                                    class='h-6 w-6 rounded-full shrink-0 object-cover'
                                                    src='{$country->svg_flag}'
                                                    alt='{$country->name} flag'
                                                />
                                                {$country->name}
                                            </div>",
                        ]
                    )
                    ->toArray()
            )
            ->allowHtml();
    }
}
