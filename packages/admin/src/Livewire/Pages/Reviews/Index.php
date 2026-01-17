<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Reviews;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Review;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::query()->with(['author', 'reviewrateable'])
            )
            ->columns([
                Split::make([
                    ImageColumn::make('author.picture')
                        ->circular()
                        ->grow(false),
                    Stack::make([
                        Split::make([
                            TextColumn::make('author.full_name')
                                ->weight(FontWeight::Bold)
                                ->searchable()
                                ->grow(false),
                            TextColumn::make('approved')
                                ->badge()
                                ->formatStateUsing(
                                    fn (bool $state): string => $state
                                        ? __('shopper::pages/products.reviews.published')
                                        : __('shopper::pages/products.reviews.pending')
                                )
                                ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                        ]),
                        TextColumn::make('created_at')
                            ->date()
                            ->color('gray')
                            ->sortable(),
                        ViewColumn::make('rating')
                            ->view('shopper::livewire.tables.cells.reviews.rating'),
                        TextColumn::make('content')
                            ->lineClamp(2)
                            ->color('gray'),
                        ViewColumn::make('reviewrateable.name')
                            ->view('shopper::livewire.tables.cells.reviews.product'),
                    ]),
                ])->extraAttributes([
                    'class' => '!items-start',
                ]),
            ])
            ->recordActions([
                DeleteAction::make('delete')
                    ->label(__('shopper::forms.actions.delete')),
                Action::make('view')
                    ->label(__('shopper::forms.actions.view'))
                    ->icon(Untitledui::Eye)
                    ->action(
                        fn (Review $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.review-detail',
                            arguments: ['review' => $record]
                        )
                    ),
            ])
            ->filters([
                TernaryFilter::make('approved')
                    ->label(__('shopper::pages/products.reviews.approved_status')),
                TernaryFilter::make('is_recommended')
                    ->label(__('shopper::pages/products.reviews.is_recommended')),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.reviews.index')
            ->title(__('shopper::pages/reviews.menu'));
    }
}
