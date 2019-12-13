<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Http\Resources\User as UserResource;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return resources list of users
     *
     * @return UserResource
     */
    public function index()
    {
        $users = $this->repository->with('roles')->get();
        $usersCollections = collect();

        foreach ($users as $user) {
            if ($user->hasRole(config('shopper.users.admin_role'))) {
                $usersCollections->push($user);
            }
        }

        return new UserResource($usersCollections);
    }
}
