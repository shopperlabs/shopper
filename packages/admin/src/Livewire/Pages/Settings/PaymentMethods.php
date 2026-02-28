<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Services\PaymentProcessingService;

#[Layout('shopper::components.layouts.setting')]
class PaymentMethods extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function createPaymentAction(): Action
    {
        return Action::make('createPayment')
            ->label(__('shopper::pages/settings/payments.add_payment'))
            ->modalWidth(Width::TwoExtraLarge)
            ->modalHeading(__('shopper::pages/settings/payments.add_payment'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.save'))
            ->schema($this->getPaymentFormSchema())
            ->action(function (array $data): void {
                PaymentMethod::query()->create($data);

                Notification::make()
                    ->title(__('shopper::notifications.payment.add'))
                    ->success()
                    ->send();
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PaymentMethod::query()->latest())
            ->columns([
                ImageColumn::make('logo')
                    ->label(__('shopper::forms.label.logo'))
                    ->circular()
                    ->getStateUsing(fn (PaymentMethod $record): ?string => resolve(PaymentProcessingService::class)->getLogoUrl($record))
                    ->defaultImageUrl(shopper_fallback_url()),
                TextColumn::make('title')
                    ->label(__('shopper::forms.label.title'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('driver')
                    ->label(__('shopper::forms.label.driver'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? Payment::driver($state)->name() : __('shopper::words.manual'))
                    ->color(fn (?string $state): string => match (true) {
                        $state === null || $state === 'manual' => 'gray',
                        Payment::isConfigured($state) => 'success',
                        default => 'warning',
                    }),
                ToggleColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.status')),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date(),
            ])
            ->recordActions([
                EditAction::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->modalWidth(Width::TwoExtraLarge)
                    ->schema($this->getPaymentFormSchema())
                    ->successNotificationTitle(__('shopper::notifications.payment.update')),
                DeleteAction::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton(),
            ])
            ->emptyStateIcon(Untitledui::CreditCard02)
            ->emptyStateDescription(__('shopper::pages/settings/payments.no_method'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.payment-methods');
    }

    /**
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    protected function getPaymentFormSchema(): array
    {
        return [
            SpatieMediaLibraryFileUpload::make('logo')
                ->label(__('shopper::forms.label.provider_logo'))
                ->avatar()
                ->image()
                ->maxSize(config('shopper.media.max_size.thumbnail'))
                ->collection(config('shopper.media.storage.thumbnail_collection'))
                ->columnSpan('full'),
            Grid::make()
                ->schema([
                    TextInput::make('title')
                        ->label(__('shopper::forms.label.payment_method'))
                        ->placeholder('NotchPay')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', $state)),
                    Hidden::make('slug'),
                    Select::make('driver')
                        ->label(__('shopper::forms.label.driver'))
                        ->options($this->getDriverOptions())
                        ->default('manual')
                        ->required()
                        ->helperText(__('shopper::pages/settings/payments.driver_help'))
                        ->native(false)
                        ->allowHtml(),
                ]),
            TextInput::make('link_url')
                ->label(__('shopper::forms.label.payment_doc'))
                ->placeholder('https://laravelshopper.dev')
                ->url()
                ->columnSpanFull(),
            Textarea::make('description')
                ->label(__('shopper::forms.label.additional_details'))
                ->helperText(__('shopper::pages/settings/payments.help_text'))
                ->rows(2)
                ->columnSpan('full'),
            Textarea::make('instructions')
                ->label(__('shopper::forms.label.payment_instruction'))
                ->helperText(__('shopper::pages/settings/payments.instruction'))
                ->rows(2)
                ->columnSpan('full'),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getDriverOptions(): array
    {
        $options = [
            'manual' => '<div class="flex items-center gap-2">'
                .'<svg class="size-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>'
                .'<span>'.__('shopper::words.manual').'</span>'
                .'</div>',
        ];

        foreach (Payment::availableDrivers() as $driver) {
            if ($driver === 'manual') {
                continue;
            }

            $driverInstance = Payment::driver($driver);
            $isConfigured = $driverInstance->isConfigured();

            $statusBadge = $isConfigured
                ? ''
                : '<span class="inline-flex items-center rounded-md px-1 py-0.5 text-[10px]/3 bg-warning-50 text-warning-600 ring-1 ring-inset inset-ring-warning-600/20 dark:bg-warning-400/10 dark:text-warning-500 dark:inset-ring-warning-400/20">'.__('shopper::words.not_configured').'</span>';

            $logo = $driverInstance->logo();
            $logoHtml = $logo
                ? '<img src="'.e($logo).'" alt="'.e($driverInstance->name()).'" class="size-5 object-contain" />'
                : '<span class="size-5 rounded bg-gray-200 dark:bg-white/30"></span>';

            $options[$driver] = '<div class="flex items-center gap-2">'
                .$logoHtml
                .'<span>'.e($driverInstance->name()).'</span>'
                .$statusBadge
                .'</div>';
        }

        return $options;
    }
}
