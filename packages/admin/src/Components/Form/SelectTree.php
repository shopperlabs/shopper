<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Closure;
use Exception;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\CanBeDisabled;
use Filament\Forms\Components\Concerns\CanBeSearchable;
use Filament\Forms\Components\Concerns\HasActions;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasPivotData;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Contracts\HasAffixActions;
use Filament\Forms\Components\Field;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SelectTree extends Field implements HasAffixActions
{
    use CanBeDisabled;
    use CanBeSearchable;
    use HasActions;
    use HasAffixes;
    use HasPivotData;
    use HasPlaceholder;

    protected string $view = 'shopper::filament.form.select-tree';

    protected bool $withCount = false;

    protected bool $alwaysOpen = false;

    protected string $emptyLabel = '';

    protected bool $independent = true;

    protected ?string $customKey = null;

    protected string $titleAttribute;

    protected string $parentAttribute;

    protected null | int | string $parentNullValue = null;

    protected bool $clearable = true;

    protected bool $expandSelected = true;

    protected bool $enableBranchNode = false;

    protected bool $grouped = true;

    protected string | Closure $relationship;

    protected ?Closure $modifyQueryUsing;

    protected Closure | int $defaultOpenLevel = 0;

    protected string $direction = 'auto';

    protected Closure | array $disabledOptions = [];

    protected Closure | array $hiddenOptions = [];

    protected array | Closure | null $createOptionActionForm = null;

    protected string | Closure | null $createOptionModalHeading = null;

    protected ?Closure $modifyCreateOptionActionUsing = null;

    protected ?Closure $modifyManageOptionActionsUsing = null;

    protected ?Closure $createOptionUsing = null;

    protected Closure | bool | null $withTrashed = false;

    protected function setUp(): void
    {
        // Load the state from relationships using a callback function.
        $this->loadStateFromRelationshipsUsing(static function (self $component): void {
            // Get the current relationship associated with the component.
            $relationship = $component->getRelationship();

            // Check if the relationship is a BelongsToMany relationship.
            if ($relationship instanceof BelongsToMany) {
                // Retrieve related model instances and extract their IDs into an array.
                $state = $relationship->getResults()
                    ->pluck($relationship->getRelatedKeyName())
                    ->toArray();

                // Set the component's state with the extracted IDs.
                $component->state($state);
            }
        });

        // Save relationships using a callback function.
        $this->saveRelationshipsUsing(static function (self $component, $state): void {
            // Check if the component's relationship is a BelongsToMany relationship.
            if ($component->getRelationship() instanceof BelongsToMany) {
                // Wrap the state in a collection and convert it to an array if it's not set.
                $state = Arr::wrap($state ?? []);

                $pivotData = $component->getPivotData();

                // Sync the relationship with the provided state (IDs).
                if ($pivotData === []) {
                    $component->getRelationship()->sync($state ?? []);

                    return;
                }

                // Sync the relationship with the provided state (IDs) plus pivot data.
                $component->getRelationship()->syncWithPivotValues($state ?? [], $pivotData);
            }
        });

        $this->createOptionUsing(static function (SelectTree $component, array $data, Form $form) {
            $record = $component->getRelationship()->getRelated();
            $record->fill($data);
            $record->save();

            $form->model($record)->saveRelationships();

            return $component->getCustomKey($record);
        });

        $this->suffixActions([
            static fn (SelectTree $component): ?Action => $component->getCreateOptionAction(),
        ]);
    }

    private function buildTree(): Collection
    {
        // Start with two separate query builders
        $nullParentQuery = $this->getRelationship()->getRelated()->query()->where($this->getParentAttribute(), $this->getParentNullValue());
        $nonNullParentQuery = $this->getRelationship()->getRelated()->query()->whereNot($this->getParentAttribute(), $this->getParentNullValue());

        // If we're not at the root level and a modification callback is provided, apply it to null query
        if ($this->modifyQueryUsing) {
            $nullParentQuery = $this->evaluate($this->modifyQueryUsing, ['query' => $nullParentQuery]);
        }

        if ($this->withTrashed) {
            $nullParentQuery->withTrashed($this->withTrashed);
            $nonNullParentQuery->withTrashed($this->withTrashed); // @phpstan-ignore-line
        }

        $nullParentResults = $nullParentQuery->get();
        $nonNullParentResults = $nonNullParentQuery->get();

        // Combine the results from both queries
        $combinedResults = $nullParentResults->concat($nonNullParentResults);

        return $this->buildTreeFromResults($combinedResults);
    }

    private function buildTreeFromResults($results, $parent = null): Collection
    {
        // Assign the parent's null value to the $parent variable if it's not null
        if ($parent === null || $parent === $this->getParentNullValue()) {
            $parent = $this->getParentNullValue() ?? $parent;
        }

        // Create a collection to store the tree
        $tree = collect();

        // Create a mapping of results by their parent IDs for faster lookup
        $resultMap = [];

        // Group results by their parent IDs
        foreach ($results as $result) {
            $parentId = $result->{$this->getParentAttribute()};
            if (! isset($resultMap[$parentId])) {
                $resultMap[$parentId] = [];
            }
            $resultMap[$parentId][] = $result;
        }

        // Define disabled options
        $disabledOptions = $this->getDisabledOptions();

        // Define hidden options
        $hiddenOptions = $this->getHiddenOptions();

        // Recursively build the tree starting from the root (null parent)
        $rootResults = $resultMap[$parent] ?? [];
        foreach ($rootResults as $result) {
            // Build a node and add it to the tree
            $node = $this->buildNode($result, $resultMap, $disabledOptions, $hiddenOptions);
            $tree->push($node);
        }

        return $tree;
    }

    private function buildNode($result, $resultMap, $disabledOptions, $hiddenOptions): array
    {
        $key = $this->getCustomKey($result);

        // Create a node with 'name' and 'value' attributes
        $node = [
            'name' => $result->{$this->getTitleAttribute()},
            'value' => $key,
            'parent' => $result->{$this->getParentAttribute()},
            'disabled' => in_array($key, $disabledOptions),
            'hidden' => in_array($key, $hiddenOptions),
        ];

        // Check if the result has children
        if (isset($resultMap[$key])) {
            $children = collect();
            // Recursively build child nodes
            foreach ($resultMap[$key] as $child) {
                // don't add the hidden ones
                if (in_array($this->getCustomKey($child), $hiddenOptions)) {
                    continue;
                }
                $childNode = $this->buildNode($child, $resultMap, $disabledOptions, $hiddenOptions);
                $children->push($childNode);
            }
            // Add children to the node
            $node['children'] = $children->toArray();
        }

        return $node;
    }

    public function relationship(string $relationship, string $titleAttribute, string $parentAttribute, ?Closure $modifyQueryUsing = null): self
    {
        $this->relationship = $relationship;
        $this->titleAttribute = $titleAttribute;
        $this->parentAttribute = $parentAttribute;
        $this->modifyQueryUsing = $modifyQueryUsing;

        return $this;
    }

    public function withCount(bool $withCount = true): static
    {
        $this->withCount = $withCount;

        return $this;
    }

    public function withTrashed(bool $withTrashed = true): static
    {
        $this->withTrashed = $withTrashed;

        return $this;
    }

    public function direction(string $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function parentNullValue(int | string | null $parentNullValue = null): static
    {
        $this->parentNullValue = $parentNullValue;

        return $this;
    }

    public function getRelationship(): BelongsToMany | BelongsTo
    {
        return $this->getModelInstance()->{$this->evaluate($this->relationship)}();
    }

    public function getTitleAttribute(): string
    {
        return $this->evaluate($this->titleAttribute);
    }

    public function getParentAttribute(): string
    {
        return $this->evaluate($this->parentAttribute);
    }

    public function getParentNullValue(): null | int | string
    {
        return $this->evaluate($this->parentNullValue);
    }

    public function clearable(bool $clearable = true): static
    {
        $this->clearable = $clearable;

        return $this;
    }

    public function grouped(bool $grouped = true): static
    {
        $this->grouped = $grouped;

        return $this;
    }

    public function defaultOpenLevel(Closure | int $defaultOpenLevel = 0): static
    {
        $this->defaultOpenLevel = $defaultOpenLevel;

        return $this;
    }

    public function expandSelected(bool $expandSelected = true): static
    {
        $this->expandSelected = $expandSelected;

        return $this;
    }

    public function emptyLabel(string $emptyLabel): static
    {
        $this->emptyLabel = $emptyLabel;

        return $this;
    }

    public function independent(bool $independent = true): static
    {
        $this->independent = $independent;

        return $this;
    }

    public function withKey(string $customKey): static
    {
        $this->customKey = $customKey;

        return $this;
    }

    public function disabledOptions(Closure | array $disabledOptions): static
    {
        $this->disabledOptions = $disabledOptions;

        return $this;
    }

    public function hiddenOptions(Closure | array $hiddenOptions): static
    {
        $this->hiddenOptions = $hiddenOptions;

        return $this;
    }

    public function alwaysOpen(bool $alwaysOpen = true): static
    {
        $this->alwaysOpen = $alwaysOpen;

        return $this;
    }

    public function enableBranchNode(bool $enableBranchNode = true): static
    {
        $this->enableBranchNode = $enableBranchNode;

        return $this;
    }

    public function getTree(): Collection | array
    {
        return $this->evaluate($this->buildTree());
    }

    public function getExpandSelected(): bool
    {
        return $this->evaluate($this->expandSelected);
    }

    public function getGrouped(): bool
    {
        return $this->evaluate($this->grouped);
    }

    public function getWithTrashed(): bool
    {
        return $this->evaluate($this->withTrashed);
    }

    public function getIndependent(): bool
    {
        return $this->evaluate($this->independent);
    }

    public function getCustomKey($record)
    {
        return is_null($this->customKey) ? $record->getKey() : $record->{$this->customKey};
    }

    public function getWithCount(): bool
    {
        return $this->evaluate($this->withCount);
    }

    public function getMultiple(): bool
    {
        return $this->evaluate($this->getRelationship() instanceof BelongsToMany);
    }

    public function getClearable(): bool
    {
        return $this->evaluate($this->clearable);
    }

    public function getAlwaysOpen(): bool
    {
        return $this->evaluate($this->alwaysOpen);
    }

    public function getEnableBranchNode(): bool
    {
        return $this->evaluate($this->enableBranchNode);
    }

    public function getDefaultOpenLevel(): int
    {
        return $this->evaluate($this->defaultOpenLevel);
    }

    public function getEmptyLabel(): string
    {
        return $this->emptyLabel ? $this->evaluate($this->emptyLabel) : __('No results found');
    }

    public function getDirection(): string
    {
        return $this->evaluate($this->direction);
    }

    public function getDisabledOptions(): array
    {
        return $this->evaluate($this->disabledOptions);
    }

    public function getHiddenOptions(): array
    {
        return $this->evaluate($this->hiddenOptions);
    }

    public function getCreateOptionActionForm(Form $form): array | Form | null
    {
        return $this->evaluate($this->createOptionActionForm, ['form' => $form]);
    }

    public function hasCreateOptionActionFormSchema(): bool
    {
        return (bool) $this->createOptionActionForm;
    }

    public function getCreateOptionModalHeading(): ?string
    {
        return $this->evaluate($this->createOptionModalHeading);
    }

    public function createOptionForm(array | Closure | null $schema): static
    {
        $this->createOptionActionForm = $schema;

        return $this;
    }

    public function getCreateOptionActionName(): string
    {
        return 'createOption';
    }

    public function getCreateOptionUsing(): ?Closure
    {
        return $this->createOptionUsing;
    }

    public function createOptionUsing(Closure $callback): static
    {
        $this->createOptionUsing = $callback;

        return $this;
    }

    public function getCreateOptionAction(): ?Action
    {
        if ($this->isDisabled()) {
            return null;
        }

        if (! $this->hasCreateOptionActionFormSchema()) {
            return null;
        }

        $action = Action::make($this->getCreateOptionActionName())
            ->form(function (SelectTree $component, Form $form): array | Form | null {
                return $component->getCreateOptionActionForm($form->model(
                    $component->getRelationship() ? $component->getRelationship()->getModel()::class : null, // @phpstan-ignore-line
                ));
            })
            ->action(static function (Action $action, array $arguments, SelectTree $component, array $data, ComponentContainer $form): void {
                if (! $component->getCreateOptionUsing()) {
                    throw new Exception("Select field [{$component->getStatePath()}] must have a [createOptionUsing()] closure set.");
                }

                $createdOptionKey = $component->evaluate($component->getCreateOptionUsing(), [
                    'data' => $data,
                    'form' => $form,
                ]);

                $state = $component->getMultiple()
                    ? [
                        ...$component->getState() ?? [],
                        $createdOptionKey,
                    ]
                    : $createdOptionKey;

                $component->state($state);
                $component->callAfterStateUpdated();

                if (! ($arguments['another'] ?? false)) {
                    return;
                }

                $action->callAfter();

                $form->fill();

                $action->halt();
            })
            ->color('gray')
            ->icon(FilamentIcon::resolve('forms::components.select.actions.create-option') ?? 'heroicon-m-plus')
            ->iconButton()
            ->modalHeading($this->getCreateOptionModalHeading() ?? __('filament-forms::components.select.actions.create_option.modal.heading'))
            ->modalSubmitActionLabel(__('filament-forms::components.select.actions.create_option.modal.actions.create.label'))
            ->extraModalFooterActions(fn (Action $action, SelectTree $component): array => $component->getMultiple() ? [
                $action->makeModalSubmitAction('createAnother', arguments: ['another' => true])
                    ->label(__('filament-forms::components.select.actions.create_option.modal.actions.create_another.label')),
            ] : []);

        if ($this->modifyManageOptionActionsUsing) {
            $action = $this->evaluate($this->modifyManageOptionActionsUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        if ($this->modifyCreateOptionActionUsing) {
            $action = $this->evaluate($this->modifyCreateOptionActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }
}
