<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Actions\CreateShipmentAction;
use Shopper\Core\Exceptions\CannotCreateEmptyShipmentException;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Shipping\Services\CarrierRateService;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Schema $form
 * @property-read Collection<int, OrderItem> $unfulfilledItems
 */
class CreateShippingLabel extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithSlideOverForm;

    #[Locked]
    public Order $order;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public string $action = 'save';

    public ?string $title = null;

    public ?string $description = null;

    public static function panelMaxWidth(): string
    {
        return '5xl';
    }

    public function mount(): void
    {
        $this->authorize('orders.edit');

        $this->title = __('shopper::pages/orders.create_shipping_label');

        $this->form->fill([
            'carrier_id' => $this->order->shippingOption?->carrier_id,
            'items' => $this->unfulfilledItems->map(fn ($item): array => [
                'item_id' => $item->id,
            ])->values()->all(),
        ]);
    }

    /** @return Collection<int, OrderItem> */
    #[Computed]
    public function unfulfilledItems(): Collection
    {
        /** @var Collection<int, OrderItem> */
        return $this->order
            ->items()
            ->with('product', 'product.media')
            ->whereNull('order_shipping_id')
            ->where(
                fn ($query) => $query
                    ->whereNull('fulfillment_status')
                    ->orWhere('fulfillment_status', FulfillmentStatus::Pending)
            )
            ->get();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(5)
                    ->extraAttributes(['class' => '[&>div]:divide-x [&>div]:divide-sh-border'])
                    ->schema([
                        Group::make()
                            ->columnSpan(3)
                            ->extraAttributes(['class' => 'py-6 pr-6'])
                            ->schema([
                                Select::make('carrier_id')
                                    ->label(__('shopper::pages/orders.carrier_service'))
                                    ->options(fn (): array => resolve(CarrierRateService::class)->getCarrierSelectOptions())
                                    ->native(false)
                                    ->searchable()
                                    ->allowHtml()
                                    ->required(),
                                Group::make()
                                    ->columns()
                                    ->schema([
                                        TextInput::make('tracking_number')
                                            ->label(__('shopper::forms.label.tracking_number'))
                                            ->placeholder('e.g. 1Z999AA10123456784'),
                                        TextInput::make('tracking_url')
                                            ->label(__('shopper::forms.label.tracking_url'))
                                            ->placeholder('https://your-tracking-url.com')
                                            ->url(),
                                    ]),
                            ]),
                        Group::make()
                            ->columnSpan(2)
                            ->extraAttributes(['class' => 'pt-6'])
                            ->schema([
                                View::make('shopper::livewire.slide-overs.partials.shipping-label-summary'),
                                View::make('shopper::livewire.slide-overs.partials.shipping-label-address'),
                            ]),
                    ]),
                Repeater::make('items')
                    ->label(__('shopper::pages/orders.items_to_fulfill'))
                    ->table([
                        TableColumn::make('')->hiddenHeaderLabel()->width('60px'),
                        TableColumn::make(__('shopper::words.product')),
                        TableColumn::make(__('shopper::words.qty'))->width('100px'),
                        TableColumn::make(__('shopper::forms.label.price'))->width('120px'),
                    ])
                    ->compact()
                    ->reorderable(false)
                    ->minItems(1)
                    ->maxItems(fn (): int => $this->unfulfilledItems->count())
                    ->defaultItems(0)
                    ->schema([
                        TextEntry::make('image')
                            ->hiddenLabel()
                            ->state(function (Get $get): HtmlString {
                                $item = $this->unfulfilledItems->firstWhere('id', $get('item_id'));
                                // @phpstan-ignore-next-line
                                $url = $item?->product?->getThumbnailUrl();

                                return new HtmlString(
                                    $url
                                        ? '<img src="'.e($url).'" class="size-8 rounded-lg object-cover" />'
                                        : '<span class="size-8 rounded-lg bg-sh-skeleton"></span>'
                                );
                            }),
                        Select::make('item_id')
                            ->options(fn (): array => $this->unfulfilledItems->pluck('name', 'id')->all())
                            ->required()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->native(false)
                            ->searchable(),
                        TextEntry::make('quantity')
                            ->hiddenLabel()
                            ->state(function (Get $get): string {
                                $item = $this->unfulfilledItems->firstWhere('id', $get('item_id'));

                                return (string) ($item->quantity ?? '-');
                            }),
                        TextEntry::make('unit_price')
                            ->hiddenLabel()
                            ->state(function (Get $get): string {
                                $item = $this->unfulfilledItems->firstWhere('id', $get('item_id'));

                                return $item
                                    ? shopper_money_format($item->unit_price_amount, $this->order->currency_code)
                                    : '-';
                            }),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->authorize('orders.edit');

        $data = $this->form->getState();

        try {
            (new CreateShipmentAction)->execute(
                order: $this->order,
                carrierId: (int) $data['carrier_id'],
                itemIds: array_map('intval', collect($data['items'])->pluck('item_id')->all()),
                trackingNumber: $data['tracking_number'] ?? null,
                trackingUrl: $data['tracking_url'] ?? null,
                description: __('shopper::notifications.shipments.label_created'),
            );
        } catch (CannotCreateEmptyShipmentException) {
            Notification::make()
                ->title(__('shopper::pages/orders.notifications.shipment_empty'))
                ->warning()
                ->send();

            $this->closePanel();

            return;
        }

        Notification::make()
            ->title(__('shopper::pages/orders.notifications.shipment_created'))
            ->success()
            ->send();

        $this->dispatch('order.shipping.created');
        $this->closePanel();
    }
}
