<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Notifications\AdminSendCredentials;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Rules\Phone;

class CreateAdminUser extends Component
{
    public bool $send_mail = false;
    public string $email = '';
    public string $password = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $gender = 'male';
    public $phone_number;
    public $role_id;
    public $is_admin = false;

    public function generate()
    {
        $this->password = substr(strtoupper(uniqid(str_random(10))), 0, 10);
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules(), $this->messages());
    }

    public function updatedRoleId($id)
    {
        $chooseRole = Role::findById($id);

        $this->is_admin = $chooseRole->name === config('shopper.system.users.admin_role');
    }

    public function save()
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

        session()->flash('success', "Admin $user->full_name added successfully.");
        $this->redirectRoute('shopper.settings.users');
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:'. shopper_table('users'),
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

    public function render()
    {
        $roles = Role::query()
            ->select(['id', 'display_name', 'name'])
            ->where('name', '<>', config('shopper.system.users.default_role'))
            ->get();

        return view('shopper::livewire.settings.management.create', compact('roles'));
    }
}
