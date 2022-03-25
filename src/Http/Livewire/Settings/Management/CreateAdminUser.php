<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Notifications\AdminSendCredentials;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Rules\Phone;
use Shopper\Framework\Rules\RealEmailValidator;

class CreateAdminUser extends AbstractBaseComponent
{
    public bool $send_mail = false;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $first_name = null;
    public ?string $last_name = null;
    public string $gender = 'male';
    public ?string $phone_number = null;
    public bool $is_admin = false;
    public $role_id;

    public function generate()
    {
        $this->password = mb_substr(mb_strtoupper(uniqid(str_random(10))), 0, 10);

        $this->resetErrorBag(['password']);
    }

    public function updated(string $field)
    {
        $this->validateOnly($field, $this->rules(), $this->messages());
    }

    public function updatedRoleId(int $id)
    {
        $chooseRole = Role::findById($id);

        $this->is_admin = $chooseRole->name === config('shopper.system.users.admin_role');
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique(shopper_table('users'), 'email'),
                new RealEmailValidator(),
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => Password::min(8)->letters()->mixedCase(),
            'role_id' => 'required',
            'phone_number' => [
                'nullable',
                new Phone(),
            ],
        ];
    }

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

    public function messages(): array
    {
        return [
            'email.required' => __('Email is required'),
            'email.email' => __('This Email is not a valid email address'),
            'email.unique' => __('This email adresse is already used'),
            'first_name.required' => __('Admin first name is required'),
            'last_name.required' => __('Admin last name is required'),
            'password.required' => __('Password is required'),
            'role_id.required' => __('The admin role is required'),
            'phone_number.*' => __('This phone number is not a valid number'),
        ];
    }

    public function render()
    {
        return view('shopper::livewire.settings.management.create', [
            'roles' => Role::query()
                ->select(['id', 'display_name', 'name'])
                ->where('name', '<>', config('shopper.system.users.default_role'))
                ->get(),
        ]);
    }
}
