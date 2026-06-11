<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use RuntimeException;
use Shopper\Api\Actions\RegisterCustomerAction;
use Shopper\Api\Http\Requests\Auth\LoginRequest;
use Shopper\Api\Http\Requests\Auth\RegisterRequest;
use Shopper\Api\Http\Resources\CustomerResource;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticationController
{
    public function register(RegisterRequest $request, RegisterCustomerAction $action): JsonResponse
    {
        $customer = $action->execute($request->validated());

        /** @var JsonResponse $response */
        $response = CustomerResource::make($customer)
            ->additional(['meta' => ['token' => $this->issueToken($customer)]])
            ->toResponse($request);

        return $response->setStatusCode(Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        /** @var class-string<Model&Authenticatable> $model */
        $model = (string) config('auth.providers.users.model');

        /** @var (Model&Authenticatable)|null $customer */
        $customer = $model::query()->where('email', $request->string('email')->toString())->first();

        if ($customer === null || ! Hash::check($request->string('password')->toString(), $customer->getAuthPassword())) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        /** @var JsonResponse $response */
        $response = CustomerResource::make($customer)
            ->additional(['meta' => ['token' => $this->issueToken($customer)]])
            ->toResponse($request);

        return $response;
    }

    public function logout(Request $request): Response
    {
        $bearer = $request->bearerToken();

        if ($bearer !== null) {
            PersonalAccessToken::findToken($bearer)?->delete();
        }

        return response()->noContent();
    }

    private function issueToken(Model&Authenticatable $customer): string
    {
        if (! method_exists($customer, 'createToken')) {
            throw new RuntimeException(sprintf(
                'The [%s] model must use the [Laravel\Sanctum\HasApiTokens] trait to authenticate against the store API.',
                $customer::class,
            ));
        }

        /** @var NewAccessToken $token */
        $token = $customer->createToken('store', ['store']);

        return $token->plainTextToken;
    }
}
