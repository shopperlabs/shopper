<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use BladeUI\Icons\Factory as IconFactory;
use Closure;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Renderless;

use function Filament\Support\generate_icon_html;

class IconPicker extends Field
{
    use HasPlaceholder;

    protected string $view = 'shopper::filament.form.icon-picker';

    protected array|Closure|null $sets = null;

    protected int|Closure $searchResultsLimit = 48;

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder(__('shopper::forms.placeholder.icon_placeholder'));
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

    #[ExposedLivewireMethod]
    #[Renderless]
    public function getSearchResultsJs(?string $search = null): array
    {
        $search = mb_strtolower(mb_trim((string) $search));

        return collect($this->getIconNames())
            ->when($search !== '', fn ($icons) => $icons->filter(
                fn (string $icon): bool => str_contains($icon, $search)
            ))
            ->take($this->getSearchResultsLimit())
            ->map(fn (string $icon): array => [
                'name' => $icon,
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

    /**
     * @return array<int, string>
     */
    private function getIconNames(): array
    {
        $sets = $this->getSets();

        return Cache::remember(
            'shopper.icon-picker.'.md5(serialize($sets)),
            now()->addDay(),
            function () use ($sets): array {
                $iconSets = collect(App::make(IconFactory::class)->all())
                    ->when($sets, fn ($allSets) => $allSets->filter(
                        fn (array $set, string $name): bool => in_array($name, $sets, true)
                    ));

                $icons = [];

                foreach ($iconSets as $set) {
                    foreach ($set['paths'] as $path) {
                        foreach (File::files($path) as $file) {
                            $icons[] = $set['prefix'].'-'.$file->getFilenameWithoutExtension();
                        }
                    }
                }

                return $icons;
            }
        );
    }
}
