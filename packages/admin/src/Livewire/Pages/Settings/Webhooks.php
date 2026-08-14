<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\WebhookDeliveryStatus;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\Traits\HasPublicId;
use Shopper\Core\Models\WebhookDelivery;
use Shopper\Core\Models\WebhookSubscription;
use Shopper\Core\Webhooks\WebhookRegistry;
use Shopper\Core\Webhooks\WebhookUrl;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Webhooks extends Component implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    use WithSettingsBreadcrumbs;

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/settings/webhooks.title')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('system.settings');
    }

    public function createWebhookAction(): Action
    {
        return Action::make('createWebhook')
            ->label(__('shopper::pages/settings/webhooks.add_webhook'))
            ->authorize('system.settings')
            ->modalWidth(Width::ExtraLarge)
            ->modalHeading(__('shopper::pages/settings/webhooks.add_webhook'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.save'))
            ->schema($this->getWebhookFormSchema())
            ->disabled(fn (): bool => $this->hasReachedSubscriptionCap())
            ->action(function (array $data): void {
                if ($this->hasReachedSubscriptionCap()) {
                    Notification::make()
                        ->title(__('shopper::pages/settings/webhooks.cap_reached'))
                        ->danger()
                        ->send();

                    return;
                }

                $secret = Str::random(40);

                WebhookSubscription::query()->create(array_merge($data, [
                    'secret' => $secret,
                ]));

                Notification::make()
                    ->title(__('shopper::pages/settings/webhooks.created'))
                    ->body(__('shopper::pages/settings/webhooks.secret_reveal', ['secret' => $secret]))
                    ->persistent()
                    ->success()
                    ->send();
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(WebhookSubscription::query()->latest())
            ->columns([
                TextColumn::make('url')
                    ->label(__('shopper::forms.label.url'))
                    ->searchable()
                    ->limit(50),
                TextColumn::make('events')
                    ->label(__('shopper::pages/settings/webhooks.events'))
                    ->badge(),
                ToggleColumn::make('is_active')
                    ->label(__('shopper::forms.label.status'))
                    ->beforeStateUpdated(fn (): mixed => $this->authorize('system.settings')),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date(),
            ])
            ->recordActions([
                Action::make('deliveries')
                    ->label(__('shopper::pages/settings/webhooks.deliveries'))
                    ->icon(Untitledui::List)
                    ->color('gray')
                    ->iconButton()
                    ->authorize('system.settings')
                    ->modalHeading(__('shopper::pages/settings/webhooks.deliveries'))
                    ->modalWidth(Width::TwoExtraLarge)
                    ->modalContent(fn (WebhookSubscription $record): View => view(
                        'shopper::livewire.pages.settings.partials.webhook-deliveries',
                        ['deliveries' => $record->deliveries()->with('event')->latest('id')->limit(25)->get()],
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('shopper::forms.actions.close')),
                Action::make('redeliver')
                    ->label(__('shopper::pages/settings/webhooks.redeliver'))
                    ->icon(Untitledui::RefreshCcw)
                    ->color('gray')
                    ->iconButton()
                    ->authorize('system.settings')
                    ->action(fn (WebhookSubscription $record) => $this->redeliver($record)),
                Action::make('regenerateSecret')
                    ->label(__('shopper::pages/settings/webhooks.regenerate_secret'))
                    ->icon(Untitledui::Key)
                    ->color('success')
                    ->iconButton()
                    ->authorize('system.settings')
                    ->requiresConfirmation()
                    ->modalDescription(__('shopper::pages/settings/webhooks.regenerate_secret_warning'))
                    ->action(function (WebhookSubscription $record): void {
                        $secret = Str::random(40);

                        $record->update(['secret' => $secret]);

                        Notification::make()
                            ->title(__('shopper::pages/settings/webhooks.secret_regenerated'))
                            ->body(__('shopper::pages/settings/webhooks.secret_reveal', ['secret' => $secret]))
                            ->persistent()
                            ->success()
                            ->send();
                    }),
                EditAction::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->authorize('system.settings')
                    ->modalWidth(Width::ExtraLarge)
                    ->schema($this->getWebhookFormSchema())
                    ->successNotificationTitle(__('shopper::pages/settings/webhooks.updated')),
                DeleteAction::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->authorize('system.settings')
                    ->iconButton(),
            ])
            ->emptyState(view('shopper::livewire.tables.empty-states.webhooks'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.webhooks')
            ->title(__('shopper::pages/settings/webhooks.title'));
    }

    protected function hasReachedSubscriptionCap(): bool
    {
        $cap = (int) config('shopper.webhooks.max_subscriptions', 50);

        return $cap > 0 && WebhookSubscription::query()->count() >= $cap;
    }

    /**
     * Queue a new delivery for the subscription's most recent event.
     *
     * Refused when the event's resource no longer exists: replaying a
     * stored snapshot after the subject was deleted would re-emit data the
     * erasure was meant to remove (GDPR right to erasure).
     */
    protected function redeliver(WebhookSubscription $subscription): void
    {
        $lastDelivery = $subscription->deliveries()
            ->with('event')
            ->latest('id')
            ->first();

        if ($lastDelivery === null) {
            return;
        }

        $event = $lastDelivery->event;

        if ($event->resource_type !== null && ! $this->resourceStillExists($event->resource_type, (string) $event->resource_id)) {
            Notification::make()
                ->title(__('shopper::pages/settings/webhooks.redeliver_refused'))
                ->danger()
                ->send();

            return;
        }

        $delivery = WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $subscription->id,
            'status' => WebhookDeliveryStatus::Pending,
        ]);

        DeliverWebhookJob::dispatch($delivery->id)
            ->onQueue((string) config('shopper.webhooks.queue', 'webhooks'));

        Notification::make()
            ->title(__('shopper::pages/settings/webhooks.redelivered'))
            ->success()
            ->send();
    }

    protected function resourceStillExists(string $morphClass, string $resourceId): bool
    {
        $model = Relation::getMorphedModel($morphClass) ?? $morphClass;

        if (! class_exists($model)) {
            return false;
        }

        return in_array(HasPublicId::class, class_uses_recursive($model), true)
            ? $model::query()->where('public_id', $resourceId)->exists()
            : $model::query()->whereKey($resourceId)->exists();
    }

    /**
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    protected function getWebhookFormSchema(): array
    {
        return [
            TextInput::make('url')
                ->label(__('shopper::forms.label.url'))
                ->placeholder('https://example.com/webhooks/shopper')
                ->url()
                ->required()
                ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                    if (! WebhookUrl::isSafe((string) $value)) {
                        $fail(__('shopper::pages/settings/webhooks.unsafe_url'));
                    }
                }),
            CheckboxList::make('events')
                ->label(__('shopper::pages/settings/webhooks.events'))
                ->options(collect(resolve(WebhookRegistry::class)->events())
                    ->values()
                    ->mapWithKeys(fn (string $name): array => [$name => $name])
                    ->all())
                ->columns()
                ->required(),
            Textarea::make('description')
                ->label(__('shopper::forms.label.description'))
                ->rows(2),
        ];
    }
}
