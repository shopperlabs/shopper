<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Notifications\AdminSendCredentials;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Rules\Phone;
use Shopper\Framework\Rules\RealEmailValidator;

class CreateAdminUser extends AbstractBaseComponent
{
    /**
     * Indicate if user will receive mail with credentials.
     *
     * @var bool
     */
    public bool $send_mail = false;

    /**
     * Admin email address.
     *
     * @var string
     */
    public $email;

    /**
     * Admin password.
     *
     * @var string
     */
    public $password;

    /**
     * Admin first name.
     *
     * @var string
     */
    public $first_name;

    /**
     * Admin last name.
     *
     * @var string
     */
    public $last_name;

    /**
     * Admin default gender.
     *
     * @var string
     */
    public $gender = 'male';

    /**
     * Phone number attribute.
     *
     * @var string
     */
    public $phone_number;

    /**
     * Admin define role id.
     *
     * @var integer
     */
    public $role_id;

    /**
     * Define if the choose role is an admin role.
     *
     * @var bool
     */
    public $is_admin = false;

    /**
     * Generate a random 10 characters password.
     *
     * @return void
     */
    public function generate()
    {
        $this->password = substr(strtoupper(uniqid(str_random(10))), 0, 10);

        $this->resetErrorBag(['password']);
    }

    /**
     * Real-time component validation.
     *
     * @param  string  $field
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules(), $this->messages());
    }

    /**
     * Update roleId to determine if the choose role is an admin role.
     *
     * @param  string  $id
     * @return void
     */
    public function updatedRoleId($id)
    {
        $chooseRole = Role::findById($id);

        $this->is_admin = $chooseRole->name === config('shopper.system.users.admin_role');
    }

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules(), $this->messages());

        $user = (new UserRepository())->create([
            'email'        => $this->email,
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'password'     => Hash::make($this->password),
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

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique(shopper_table('users'), 'email'),
                new RealEmailValidator()
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|min:8',
            'role_id' => 'required',
            'phone_number' => [
                'nullable',
                new Phone()
            ]
        ];
    }

    /**
     * Custom error messages.
     *
     * @return string[]
     */
    public function messages()
    {
        return [
          'email.required' => __("Email is required"),
          'email.email' => __("This Email is not a valid email address"),
          'email.unique' => __("This email adresse is already used"),
          'first_name.required' => __("Admin first name is required"),
          'last_name.required' => __("Admin last name is required"),
          'password.required' => __("Password is required"),
          'role_id.required' => __("The admin role is required"),
          'phone_number.*' => __("This phone number is not a valid number"),
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $roles = Role::query()
            ->select(['id', 'display_name', 'name'])
            ->where('name', '<>', config('shopper.system.users.default_role'))
            ->get();

        return view('shopper::livewire.settings.management.create', compact('roles'));
    }
}
