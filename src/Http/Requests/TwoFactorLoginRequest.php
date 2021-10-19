<?php

namespace Shopper\Framework\Http\Requests;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Shopper\Framework\Contracts\TwoFactorAuthenticationProvider;
use Shopper\Framework\Http\Responses\FailedTwoFactorLoginResponse;

class TwoFactorLoginRequest extends FormRequest
{
    /**
     * The user attempting the two factor challenge.
     */
    protected $challengedUser;

    /**
     * Indicates if the user wished to be remembered after login.
     *
     * @var bool
     */
    protected $remember;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => 'nullable|string',
            'recovery_code' => 'nullable|string',
        ];
    }

    /**
     * Determine if the request has a valid two factor code.
     *
     * @throws ValidationException
     */
    public function hasValidCode(): bool
    {
        return $this->code && app(TwoFactorAuthenticationProvider::class)->verify(
            decrypt($this->challengedUser()->two_factor_secret),
            $this->code
        );
    }

    /**
     * Get the valid recovery code if one exists on the request.
     *
     * @throws ValidationException
     */
    public function validRecoveryCode(): ?string
    {
        if (! $this->recovery_code) {
            return null;
        }

        return collect($this->challengedUser()->recoveryCodes())->first(fn ($code) => hash_equals($this->recovery_code, $code) ? $code : null);
    }

    /**
     * Determine if there is a challenged user in the current session.
     */
    public function hasChallengedUser(): bool
    {
        $model = app(StatefulGuard::class)->getProvider()->getModel();

        return $this->session()->has('login.id') &&
            $model::find($this->session()->get('login.id'));
    }

    /**
     * Get the user that is attempting the two factor challenge.
     *
     * @throws ValidationException
     */
    public function challengedUser()
    {
        if ($this->challengedUser) {
            return $this->challengedUser;
        }

        $model = app(StatefulGuard::class)->getProvider()->getModel();

        if (! $this->session()->has('login.id') ||
            ! $user = $model::find($this->session()->pull('login.id'))) {
            throw new HttpResponseException(app(FailedTwoFactorLoginResponse::class)->toResponse($this));
        }

        return $this->challengedUser = $user;
    }

    /**
     * Determine if the user wanted to be remembered after login.
     */
    public function remember(): bool
    {
        if (! $this->remember) {
            $this->remember = $this->session()->pull('login.remember', false);
        }

        return $this->remember;
    }
}
