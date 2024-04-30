<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Reviews;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Shopper\Core\Models\Review;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::query()->with(['author', 'reviewrateable'])
            )
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('author.picture')
                        ->circular()
                        ->grow(false),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\TextColumn::make('author.full_name')
                                ->weight(FontWeight::Bold)
                                ->searchable()
                                ->grow(false),
                            Tables\Columns\TextColumn::make('approved')
                                ->badge()
                                ->formatStateUsing(
                                    fn (bool $state): string => $state
                                        ? __('shopper::pages/products.reviews.published')
                                        : __('shopper::pages/products.reviews.pending')
                                )
                                ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                        ]),
                        Tables\Columns\TextColumn::make('created_at')
                            ->date()
                            ->color('gray')
                            ->sortable(),
                        Tables\Columns\ViewColumn::make('rating')
                            ->view('shopper::livewire.tables.cells.reviews.rating'),
                        Tables\Columns\TextColumn::make('content')
                            ->lineClamp(2)
                            ->color('gray'),
                        Tables\Columns\ViewColumn::make('reviewrateable.name')
                            ->view('shopper::livewire.tables.cells.reviews.product'),
                    ]),
                ])->extraAttributes([
                    'class' => '!items-start',
                ]),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('delete')
                    ->label(__('shopper::forms.actions.delete')),
                Tables\Actions\Action::make('view')
                    ->label(__('shopper::forms.actions.view'))
                    ->icon('untitledui-eye')
                    ->action(
                        fn (Review $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.review-detail',
                            arguments: ['review' => $record]
                        )
                    ),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('approved')
                    ->label(__('shopper::pages/products.reviews.approved_status')),
                Tables\Filters\TernaryFilter::make('is_recommended')
                    ->label(__('shopper::pages/products.reviews.is_recommended')),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ]);
    }

    #[On('reviewUpdated')]
    public function render(): View
    {
        return view('shopper::livewire.pages.reviews.index', [
            'total' => Review::query()->count(),
        ])
            ->title(__('shopper::pages/reviews.menu'));
    }
}
