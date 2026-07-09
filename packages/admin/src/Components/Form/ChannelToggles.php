<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Collection;
use Shopper\Core\Models\Contracts\Channel;

class ChannelToggles extends CheckboxList
{
    protected string $view = 'shopper::filament.form.channel-toggles';

    /** @var Collection<int, Channel>|null */
    protected ?Collection $channels = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableOptionWhen(
            fn (string $value): bool => ! $this->getChannels()->firstWhere('id', (int) $value)?->is_enabled
        );
    }

    /**
     * @return Collection<int, Channel>
     */
    public function getChannels(): Collection
    {
        return $this->channels ??= resolve(Channel::class)::query()
            ->orderByDesc('is_enabled')
            ->orderBy('name')
            ->get();
    }

    /**
     * Disabled channels stay valid so existing attachments survive a save.
     *
     * @return array<int, string>
     */
    public function getInValidationRuleValues(): ?array
    {
        return $this->getChannels()
            ->pluck('id')
            ->map(fn ($id): string => (string) $id)
            ->all();
    }
}
