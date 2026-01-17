<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
use Shopper\Core\Models\PaymentMethod as PaymentMethodModel;

#[Layout('shopper::components.layouts.setting')]
class PaymentMethod extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    /** @var array<string>|null */
    public ?array $tabs = [];

    public function mount(): void
    {
        $this->tabs = collect(['general'])->toArray();
    }

    public function createPaymentAction(): Action
    {
        return Action::make('createPayment')
            ->label(__('shopper::pages/settings/payments.add_payment'))
            ->modalWidth(Width::TwoExtraLarge)
            ->modalHeading(__('shopper::pages/settings/payments.add_payment'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.save'))
            ->schema($this->getPaymentFormSchema())
            ->action(function (array $data): void {
                PaymentMethodModel::query()->create($data);

                Notification::make()
                    ->title(__('shopper::notifications.payment.add'))
                    ->success()
                    ->send();
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PaymentMethodModel::query()->latest())
            ->columns([
                ImageColumn::make('logo')
                    ->label(__('shopper::forms.label.logo'))
                    ->circular()
                    ->disk(config('shopper.media.storage.disk_name'))
                    ->defaultImageUrl(shopper_fallback_url()),
                TextColumn::make('title')
                    ->label(__('shopper::forms.label.title'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('link_url')
                    ->copyable()
                    ->label(__('shopper::forms.label.website')),
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
                    ->successNotificationTitle(__('shopper::notifications.payment.add')),
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
        return view('shopper::livewire.pages.settings.payment-method');
    }

    /**
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    protected function getPaymentFormSchema(): array
    {
        return [
            FileUpload::make('logo')
                ->label(__('shopper::forms.label.provider_logo'))
                ->avatar()
                ->image()
                ->maxSize(1024)
                ->disk(config('shopper.media.storage.disk_name'))
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
                    TextInput::make('link_url')
                        ->label(__('shopper::forms.label.payment_doc'))
                        ->placeholder('https://notchpay.co')
                        ->url(),
                ]),
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
}
