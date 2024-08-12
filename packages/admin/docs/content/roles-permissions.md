# Roles & Permissions
Manage access and permissions of your users and members in your store.

To connect to the dashboard you need to have the role `administrator` this role can be found in the configuration file `config/shopper/core.php`.

```php
/*
|--------------------------------------------------------------------------
| Configurations for the user
|--------------------------------------------------------------------------
|
| User configuration to manage user access using spatie/laravel-permission.
|
*/

'users' => [
  'admin_role' => 'administrator', // [tl! highlight]
  'default_role' => 'user',
],
```

After [creating an super admin](/installing#create-an-admin-user) we get the following result

```bash
php artisan shopper:admin

Create Admin User for Shopper administration panel

 Email Address [admin@admin.com]:
 > arthur@shopperlabs.io

 First Name [Shopper]:
 > 

 Last Name [Admin]:
 > 

 Password:
 > 

 Confirm Password:
 > 

Creating admin account...
User created successfully.
```

After logged as an admin, you can add members with permissions on your staff to log in to your store and complete tasks like **adding products** or managing **orders** and use roles to control what sections of your store they can access.

Permissions help you manage what your store’s staff can do in your admin. Roles let you delegate, and assign the level of access that your staff needs to do their jobs effectively.

Permissions are associated with roles. Depending on the role that a member has, you can assign different types of permissions to it to limit or increase the actions they can do.

All this management of roles and permissions is done using the [Laravel Permission](https://github.com/spatie/laravel-permission) package from [Spatie](https://spatie.be).

At installation Laravel Shopper comes with 3 roles: **Administrator**, **Manager** and **User**, the user role cannot be modified from the administration interface because it is the role that will be assigned to any customer who will create his account on your shop.

## RBAC ACL
RBAC (Role Based Access Control) or ACL (Access Control Layer) is an approach to restricting system access for users using roles system, Laravel Shopper allow to define the level of access for each user. With roles a user can access menus, pages. It is important to know that one Administrator can have multiple roles assigned.

To view the roles and permissions management page, you must go to the `Settings > Staff & Permissions`

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/settings-staff.png" alt="Setting Staff & permissions">
  <div class="caption">Settings > Staff & permissions</div>
</div>

### Fields
The model used for the **Role** is `Shopper\Core\Models\Role` this model extend from the Spatie Role model.

| Name        | Type      | Required |  Notes   |
|-------------|-----------|----------|------------|
| `id`  | autoinc   |         |   auto     |
| `name` | string    |   yes   | Role name in lowercase such as an slug (Eg.: author)|
| `guard_name`| string |   no  | This field is automatically filled in by Spatie |
| `display_name`| string  |  no  | Nullable, the readable name for the role (Eg.: Blog Author)|
| `description`| text | no | Nullable, the role description |
| `can_be_removed` | boolean | no | Default `true`, defines if a role can be deleted. Initially no role that comes with Shopper can be deleted from the interface. But the roles that will be added afterwards can be deleted.|

And the **Permission** model is `Shopper\Core\Models\Permission`

| Name        | Type      | Required |  Notes   |
|-------------|-----------|----------|------------|
| `id` | autoinc   |         |   auto     |
| `name` | string    |   yes   | Permission name in lowercase such as an slug (Eg.: create_post)|
| `guard_name`| string |   no  | This field is automatically filled in by Spatie |
| `group_name`| string |   no  | Permissions can be grouped into groups to better organize them. The basic permissions that come with Shopper are mostly grouped together. |
| `display_name`| string  |  no  | Nullable, the readable name for the permission (Eg.: Create Post)|
| `description`| text | no | Nullable, the permission description |
| `can_be_removed` | boolean | no | Default `true`, defines if a permission can be deleted. Initially no permission that comes with Shopper can be deleted from the interface. But the permissions that will be added afterwards can be deleted.|

The **Permission** model has some groups as shown here

```php
namespace Shopper\Framework\Models\User;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
  /**
   * Get a lists of permissions groups.
   */
  public static function groups(): array
  {
    return [
      'system' => __('System'),
      'brands' => __('Brands'),
      'categories' => __('Categories'),
      'collections' => __('Collections'),
      'products' => __('Products'),
      'customers' => __('Customers'),
      'orders' => __('Orders'),
      'discounts' => __('Discounts'),
    ];
  }
}
```

### Components
The components used to manage locations are found in the component configuration file `config/shopper/components.php`.

```php
use Shopper\Framework\Http\Livewire;
use Shopper\Framework\Http\Livewire\Components;

return [

  'livewire' => [

    'modals.delete-role' => Livewire\Modals\DeleteRole::class,
    'modals.create-permission' => Livewire\Modals\CreatePermission::class,
    'modals.create-role' => Livewire\Modals\CreateRole::class,

    'settings.management.create-admin-user' => Components\Settings\Management\CreateAdminUser::class,
    'settings.management.management' => Components\Settings\Management\Management::class,
    'settings.management.permissions' => Components\Settings\Management\Permissions::class,
    'settings.management.role' => Components\Settings\Management\Role::class,
    'settings.management.users-role' => Components\Settings\Management\UsersRole::class,

  ];

];
```

## Manage Roles
A Role is a set of permissions to perform certain operations within the system, which is assigned to a chosen Administrator. As said previously Laravel Shopper at installation comes with 3 roles but 2 are accessible in the administration panel.

The user role does not appear, modifying it could lead to bugs on your store so it is not listed here.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/roles-admins.png" alt="roles and admins users">
  <div class="caption">Roles & Admins</div>
</div>

:::warning
It's **strongly** advised to not change the name of roles when they are already assigned to users. If the role verification is done manually you will be forced to change this name in all **middleware**, **helpers**, **blade directives** etc.
:::

### Add role
To add a new role, you must click on `Add a new role` button. Required fields are marked with asterisks

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/add-role.png" alt="addd new role">
  <div class="caption">Add new role</div>
</div>

The added roles can be used later in your code to assign functionality or access to resources.

### Update role
To modify a role you must click on the role you want to modify to access the edit form.

And as already mentioned, all Shopper features are livewire components. So you can change everything at any time to fit your store.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/update-role.png" alt="update role">
  <div class="caption">Update role</div>
</div>

### Create admin
In addition to creating an administrator from the command line you can also do it from the Shopper interface, you just need to click on **Add Administrator** button.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/add-admin.png" alt="add new admin">
  <div class="caption">Add admin</div>
</div>

Then you fill in the information of your administrator with the role chosen for him

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/admin-user-form.png" alt="add new admin form">
  <div class="caption">Add admin form</div>
</div>

The actual role registration function looks like the code below. And the whole implementation class is `Shopper\Http\Livewire\Components\Settings\Management\CreateAdminUser` 

```php
public function store()
{
  $this->validate($this->rules(), $this->messages());

  $user = (new UserRepository())->create([
    'email' => $this->email,
    'first_name' => $this->first_name,
    'last_name' => $this->last_name,
    'password' => Hash::make($this->password),
    'phone_number' => $this->phone_number,
    'gender' => $this->gender,
    'email_verified_at' => now()->toDateTimeString(),
  ]);

  $role = Role::findById((int) $this->role_id);

  $user->assignRole([$role->name]);

  if ($this->send_mail) {
    $user->notify(new AdminSendCredentials($this->password));
  }

  session()->flash('success', __('Admin :user added successfully.', ['user' => $user->full_name]));

  $this->redirectRoute('shopper.settings.users');
}
```

### Create permission
Let’s assume that you would like to add a new permission to ACL. You will need to choose the role because every single permissions are linked to a role.

In the way that when a admin are granted of a specific role, he take all role's permissions.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/permissions-browse.png" alt="Role's permissions example">
  <div class="caption">Role's permissions example</div>
</div>

As you may have noticed all permissions are grouped by type, and the available types are mentioned above in this [section](/roles-permissions#fields).

To add a new permission you just need to click on the **Create permission** button.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/add-permission.png" alt="Add permission">
  <div class="caption">Add permission</div>
</div>

After adding your permission it will be automatically associated with the role and therefore all administrators with this role will have this permission.

If the permission has no group it will be in a `Custom permissions` section.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/custom-permissions.png" alt="new permission">
  <div class="caption">New permission</div>
</div>
