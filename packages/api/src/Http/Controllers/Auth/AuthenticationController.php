<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use RuntimeException;
use Shopper\Api\Actions\RegisterCustomerAction;
use Shopper\Api\Actions\TransferCartAction;
use Shopper\Api\Http\Requests\Auth\LoginRequest;
use Shopper\Api\Http\Requests\Auth\RegisterRequest;
use Shopper\Api\Http\Resources\CustomerResource;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class AuthenticationController
{
    public function __construct(
        private readonly DatabaseManager $database,
        private readonly TransferCartAction $transferCart,
    ) {}

    /**
     * Create a customer account and open a session.
     *
     * An optional `cart_id` attaches the guest cart to the new account.
     * `meta.cart_id` is the customer's open cart after the call, the guest
     * cart folded into it when one was sent, null when they have none. The
     * cart never makes the registration fail: an unknown, completed or
     * foreign cart is ignored.
     */
    public function register(RegisterRequest $request, RegisterCustomerAction $action): JsonResponse
    {
        $customer = $action->execute($request->validated());

        /** @var JsonResponse $response */
        $response = CustomerResource::make($customer)
            ->additional(['meta' => [
                'token' => $this->issueToken($customer),
                'cart_id' => $this->attachCart($request, $customer),
            ]])
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
            throw ApiValidationException::withCode(ErrorCode::CredentialsInvalid, ['email' => __('auth.failed')]);
        }

        /** @var JsonResponse $response */
        $response = CustomerResource::make($customer)
            ->additional(['meta' => [
                'token' => $this->issueToken($customer),
                'cart_id' => $this->attachCart($request, $customer),
            ]])
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

    /**
     * The customer's cart once the requested guest cart, if any, has been
     * attached: the cart the transfer answered, otherwise the open cart the
     * customer already owns. A retried call finds the guest cart already
     * merged and still answers the surviving cart, so the client can always
     * reconcile.
     */
    private function attachCart(Request $request, Model&Authenticatable $customer): ?string
    {
        $publicId = $request->string('cart_id')->toString();

        if ($publicId !== '') {
            try {
                $cart = $this->database->transaction(fn (): ?Cart => $this->transferRequestedCart($publicId, $customer));

                if ($cart !== null) {
                    return $cart->public_id;
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return resolve(CartContract::class)::query()
            ->where('customer_id', $customer->getAuthIdentifier())
            ->whereNull('completed_at')
            ->latest('id')
            ->value('public_id');
    }

    private function transferRequestedCart(string $publicId, Model&Authenticatable $customer): ?Cart
    {
        /** @var Cart|null $cart */
        $cart = resolve(CartContract::class)::query()
            ->wherePublicId($publicId)
            ->whereNull('completed_at')
            ->where(fn (Builder $query) => $query
                ->whereNull('customer_id')
                ->orWhere('customer_id', $customer->getAuthIdentifier()))
            ->lockForUpdate()
            ->first();

        return $cart === null ? null : $this->transferCart->execute($cart, (int) $customer->getAuthIdentifier());
    }

    private function issueToken(Model&Authenticatable $customer): string
    {
        if (! method_exists($customer, 'createToken')) {
            throw new RuntimeException(sprintf(
                'The [%s] model must use the [Laravel\Sanctum\HasApiTokens] trait to authenticate against the store API.',
                $customer::class,
            ));
        }

        $minutes = config('shopper.api.token_expiration');
        $minutes = is_numeric($minutes) ? (int) $minutes : null;

        /** @var NewAccessToken $token */
        $token = $customer->createToken('store', ['store'], $minutes !== null && $minutes > 0 ? now()->addMinutes($minutes) : null);

        return $token->plainTextToken;
    }
}
