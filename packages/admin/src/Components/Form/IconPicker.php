<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use BladeUI\Icons\Factory as IconFactory;
use Closure;
use DateInterval;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Attributes\Renderless;

use function Filament\Support\generate_icon_html;

class IconPicker extends Field
{
    use HasPlaceholder;

    protected string $view = 'shopper::filament.form.icon-picker';

    protected array|Closure|null $sets = null;

    protected int|Closure $searchResultsLimit = 48;

    protected Closure|string $searchResultsView = 'shopper::filament.form.icon-picker.grid';

    protected Closure|array|null $searchResultsViewData = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder(__('shopper::forms.placeholder.icon_placeholder'));
    }

    public function gridSearchResults(): static
    {
        return $this->searchResultsView('shopper::filament.form.icon-picker.grid');
    }

    public function listSearchResults(): static
    {
        return $this->searchResultsView('shopper::filament.form.icon-picker.list');
    }

    public function iconsSearchResults(bool $withTooltips = true): static
    {
        return $this->searchResultsView('shopper::filament.form.icon-picker.icons', [
            'withTooltips' => $withTooltips,
        ]);
    }

    public function searchResultsView(Closure|string $view, Closure|array|null $viewData = null): static
    {
        $this->searchResultsView = $view;

        if ($viewData) {
            $this->searchResultsViewData = $viewData;
        }

        return $this;
    }

    public function getSearchResultsViewComponent(): \Illuminate\Contracts\View\View
    {
        return view(
            $this->evaluate($this->searchResultsView),
            [
                ...$this->evaluate($this->searchResultsViewData) ?? [],
                'field' => $this,
            ]
        );
    }

    public function sets(array|string|Closure|null $sets): static
    {
        $this->sets = is_string($sets) ? [$sets] : $sets;

        return $this;
    }

    public function getSets(): ?array
    {
        return $this->evaluate($this->sets);
    }

    public function searchResultsLimit(int|Closure $limit): static
    {
        $this->searchResultsLimit = $limit;

        return $this;
    }

    public function getSearchResultsLimit(): int
    {
        return $this->evaluate($this->searchResultsLimit);
    }

    /**
     * @return array<string, array{label: string, count: int}>
     */
    public function getSetOptions(): array
    {
        $allowedSets = $this->getSets();

        return collect(App::make(IconFactory::class)->all())
            ->when($allowedSets, fn ($sets) => $sets->filter(
                fn (array $set, string $name): bool => in_array($name, $allowedSets, true)
            ))
            ->mapWithKeys(fn (array $set, string $name): array => [$name => [
                'label' => Str::title($name),
                'count' => count($this->getIconNames($name)),
            ]])
            ->all();
    }

    #[ExposedLivewireMethod]
    #[Renderless]
    public function getSearchResultsJs(?string $search = null, ?string $set = null): array
    {
        $search = mb_strtolower(mb_trim((string) $search));

        return collect($this->getIconNames($set))
            ->when($search !== '', fn ($icons) => $icons->filter(
                fn (string $icon): bool => str_contains($icon, $search)
            ))
            ->take($this->getSearchResultsLimit())
            ->map(fn (string $icon): array => [
                'name' => $icon,
                'label' => $this->getIconLabel($icon),
                'html' => generate_icon_html($icon)?->toHtml(),
            ])
            ->values()
            ->all();
    }

    public function getSelectedIconHtml(): ?string
    {
        $state = $this->getState();

        return $state ? generate_icon_html($state)?->toHtml() : null;
    }

    public function getSelectedIconLabel(): ?string
    {
        $state = $this->getState();

        return is_string($state) && filled($state) ? $this->getIconLabel($state) : null;
    }

    private function getIconLabel(string $icon): string
    {
        foreach ($this->getIconPrefixes() as $prefix) {
            if (str_starts_with($icon, $prefix.'-')) {
                $icon = mb_substr($icon, mb_strlen($prefix) + 1);

                break;
            }
        }

        return str_replace('-', ' ', $icon);
    }

    /**
     * @return array<int, string>
     */
    private function getIconPrefixes(): array
    {
        return collect(App::make(IconFactory::class)->all())
            ->pluck('prefix')
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function getIconNames(?string $set = null): array
    {
        $sets = $this->getSets();

        $callback = function () use ($sets, $set): array {
            $iconSets = collect(App::make(IconFactory::class)->all())
                ->when($sets, fn ($allSets) => $allSets->filter(
                    fn (array $iconSet, string $name): bool => in_array($name, $sets, true)
                ))
                ->when($set, fn ($allSets) => $allSets->filter(
                    fn (array $iconSet, string $name): bool => $name === $set
                ));

            $icons = [];

            foreach ($iconSets as $iconSet) {
                foreach ($iconSet['paths'] as $path) {
                    foreach (File::files($path) as $file) {
                        $icons[] = $iconSet['prefix'].'-'.$file->getFilenameWithoutExtension();
                    }
                }
            }

            return $icons;
        };

        if (! config('shopper.admin.icon-picker.cache', true)) {
            return $callback();
        }

        return Cache::remember(
            'shopper.icon-picker.'.md5(serialize([$sets, $set])),
            now()->add(DateInterval::createFromDateString(
                config('shopper.admin.icon-picker.duration', '7 days')
            )),
            $callback
        );
    }
}
