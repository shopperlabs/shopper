<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\PaymentMethod;

#[Layout('shopper::components.layouts.setting')]
class Payment extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(PaymentMethod::query())
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('shopper::layout.forms.label.logo'))
                    ->circular()
                    ->disk(config('shopper.core.storage.disk_name'))
                    ->defaultImageUrl(config('shopper.media.fallback_url')),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('shopper::layout.forms.label.title'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('link_url')
                    ->copyable()
                    ->label(__('shopper::layout.forms.label.website')),

                Tables\Columns\ToggleColumn::make('is_enabled')
                    ->label(__('shopper::layout.forms.label.status')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shopper::layout.forms.label.updated_at'))
                    ->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::layout.forms.actions.edit'))
                    ->action(fn (PaymentMethod $record) => $this->dispatch(
                        'openModal',
                        component: 'shopper-modals.payment-method-form',
                        arguments: ['paymentId' => $record->id],
                    )),

                Tables\Actions\DeleteAction::make('delete')
                    ->label(__('shopper::layout.forms.actions.delete')),
            ])
            ->emptyStateIcon('untitledui-credit-card-02')
            ->emptyStateDescription(__('shopper::pages/settings.payment.no_method'));
    }

    #[On('onPaymentMethodAdded')]
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.payment');
    }
}
