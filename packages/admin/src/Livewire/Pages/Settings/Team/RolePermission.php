<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Team;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Models\Permission;
use Shopper\Models\Role;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property Schema $form
 */
#[Layout('shopper::components.layouts.setting')]
class RolePermission extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Role $role;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('view_users');

        $this->form->fill($this->role->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('shopper::modals.roles.labels.name'))
                    ->placeholder('manager')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('display_name', Str::title($state)))
                    ->required(),
                TextInput::make('display_name')
                    ->label(__('shopper::forms.label.display_name'))
                    ->placeholder('Manager')
                    ->required(),
                Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->rows(4)
                    ->columnSpan('full'),
            ])
            ->columns()
            ->statePath('data')
            ->model($this->role);
    }

    public function deleteAction(): Action
    {
        return DeleteAction::make('delete')
            ->label(__('shopper::forms.actions.delete'))
            ->icon(Untitledui::Trash03)
            ->visible($this->role->can_be_removed)
            ->record($this->role)
            ->successNotificationTitle(__('shopper::notifications.users_roles.role_deleted'))
            ->after(fn () => $this->redirectRoute(name: 'shopper.settings.users', navigate: true));
    }

    public function generatePermissionsAction(): Action
    {
        return Action::make('generatePermissions')
            ->label(__('shopper::pages/settings/staff.generate_permissions'))
            ->icon(Untitledui::ShieldZap)
            ->color('gray')
            ->modalWidth(Width::Medium)
            ->modalHeading(__('shopper::pages/settings/staff.generate_permissions'))
            ->modalDescription(__('shopper::pages/settings/staff.generate_permissions_description'))
            ->modalSubmitActionLabel(__('shopper::pages/settings/staff.generate_permissions'))
            ->schema([
                TextInput::make('resource')
                    ->label(__('shopper::pages/settings/staff.resource_name'))
                    ->placeholder('tags, shipments, reviews…')
                    ->live(debounce: 300)
                    ->required(),
                Select::make('group_name')
                    ->label(__('shopper::forms.label.group_name'))
                    ->options(Permission::groups())
                    ->native(false)
                    ->columnSpan('full'),
                TextEntry::make('preview')
                    ->label(__('shopper::pages/settings/staff.permissions_preview'))
                    ->hidden(fn (Get $get): bool => blank($get('resource')))
                    ->state(function (Get $get): HtmlString {
                        $resource = $get('resource');

                        $badges = collect(['browse', 'read', 'edit', 'add', 'delete'])
                            ->map(fn (string $prefix): string => Blade::render(
                                '<x-filament::badge color="gray">{{ $name }}</x-filament::badge>',
                                ['name' => "{$prefix}_{$resource}"],
                            ))
                            ->implode('');

                        return new HtmlString('<div class="mt-2 flex flex-wrap gap-1.5">'.$badges.'</div>');
                    })
                    ->columnSpan('full'),
            ])
            ->action(function (array $data): void {
                $this->authorize('view_users');

                $resource = $data['resource'];
                $group = $data['group_name'] ?? null;

                Permission::generate($resource, $group);

                $prefixes = ['browse', 'read', 'edit', 'add', 'delete'];

                foreach ($prefixes as $prefix) {
                    $this->role->givePermissionTo("{$prefix}_{$resource}");
                }

                $this->dispatch('permissionAdded');

                Notification::make()
                    ->title(__('shopper::notifications.users_roles.permission_add'))
                    ->success()
                    ->send();
            });
    }

    public function createPermissionAction(): Action
    {
        return Action::make('createPermission')
            ->label(__('shopper::pages/settings/staff.create_permission'))
            ->icon(Untitledui::Lock04)
            ->modalWidth(Width::ExtraLarge)
            ->modalHeading(__('shopper::modals.permissions.new'))
            ->modalDescription(__('shopper::modals.permissions.new_description'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.save'))
            ->schema([
                Select::make('group_name')
                    ->label(__('shopper::forms.label.group_name'))
                    ->options(Permission::groups())
                    ->native(false)
                    ->columnSpan('full'),
                TextInput::make('name')
                    ->label(__('shopper::modals.permissions.labels.name'))
                    ->placeholder('create_post, manage_articles, etc')
                    ->unique(table: Permission::class, column: 'name')
                    ->maxLength(30)
                    ->required(),
                TextInput::make('display_name')
                    ->label(__('shopper::forms.label.display_name'))
                    ->placeholder('Create Blog posts')
                    ->maxLength(75)
                    ->required(),
                Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->rows(3)
                    ->columnSpan('full'),
            ])
            ->action(function (array $data): void {
                $this->authorize('view_users');

                /** @var Permission $permission */
                $permission = Permission::query()->create($data);

                $this->role->givePermissionTo($permission->name);

                $this->dispatch('permissionAdded');

                Notification::make()
                    ->title(__('shopper::notifications.users_roles.permission_add'))
                    ->success()
                    ->send();
            });
    }

    public function save(): void
    {
        $this->authorize('view_users');

        $this->role->update($this->form->getState());

        Notification::make()
            ->body(__('shopper::notifications.update', ['item' => __('shopper::pages/settings/staff.role')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.team.role')
            ->title(__('shopper::pages/settings/staff.roles').' ~ '.$this->role->display_name);
    }
}
