<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\PersonalAccessToken;
use Shopper\Api\Http\Requests\Auth\ForgotPasswordRequest;
use Shopper\Api\Http\Requests\Auth\ResetPasswordRequest;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;
use Symfony\Component\HttpFoundation\Response;

final class PasswordResetController
{
    /**
     * Always answers 202 whether the email exists or not, so the endpoint
     * cannot be used to enumerate accounts.
     */
    public function send(ForgotPasswordRequest $request): Response
    {
        Password::sendResetLink($request->only('email'));

        return response()->noContent(Response::HTTP_ACCEPTED);
    }

    public function reset(ResetPasswordRequest $request): Response
    {
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function (Model&Authenticatable $customer, string $password): void {
                $customer->forceFill(['password' => Hash::make($password)])->save();

                PersonalAccessToken::query()
                    ->where('tokenable_type', $customer->getMorphClass())
                    ->where('tokenable_id', $customer->getKey())
                    ->delete();

                event(new PasswordReset($customer));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ApiValidationException::withCode(ErrorCode::ResetTokenInvalid, ['email' => __(Password::INVALID_TOKEN)]);
        }

        return response()->noContent();
    }
}
