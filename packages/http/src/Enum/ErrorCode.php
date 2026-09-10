<?php

declare(strict_types=1);

namespace Shopper\Http\Enum;

use Symfony\Component\HttpFoundation\Response;

enum ErrorCode: string
{
    case BadRequest = 'bad_request';
    case Unauthenticated = 'unauthenticated';
    case Forbidden = 'forbidden';
    case NotFound = 'not_found';
    case MethodNotAllowed = 'method_not_allowed';
    case Conflict = 'conflict';
    case Invalid = 'invalid';
    case RateLimited = 'rate_limited';
    case ServerError = 'server_error';
    case HttpError = 'http_error';

    case CredentialsInvalid = 'credentials_invalid';
    case ResetTokenInvalid = 'reset_token_invalid';

    case IncludeNotAllowed = 'include_not_allowed';
    case FilterTooWide = 'filter_too_wide';
    case CurrencyRequired = 'currency_required';
    case CurrencyUnknown = 'currency_unknown';

    case PurchasableUnavailable = 'purchasable_unavailable';
    case VariantRequired = 'variant_required';
    case PriceMissing = 'price_missing';
    case PriceChanged = 'price_changed';
    case StockInsufficient = 'stock_insufficient';
    case CartEmpty = 'cart_empty';
    case CartCompleted = 'cart_completed';
    case CartNothingToCollect = 'cart_nothing_to_collect';
    case EmailRequired = 'email_required';
    case PromotionNotApplicable = 'promotion_not_applicable';
    case PromotionBudgetReached = 'promotion_budget_reached';
    case PromotionLimitReached = 'promotion_limit_reached';

    case ShippingMethodRequired = 'shipping_method_required';
    case ShippingOptionUnavailable = 'shipping_option_unavailable';
    case ShippingPriceChanged = 'shipping_price_changed';

    case PaymentMethodRequired = 'payment_method_required';
    case PaymentMethodUnavailable = 'payment_method_unavailable';
    case PaymentMethodNotConfigured = 'payment_method_not_configured';
    case PaymentProviderUnavailable = 'payment_provider_unavailable';
    case PaymentSessionMismatch = 'payment_session_mismatch';
    case PaymentSessionInProgress = 'payment_session_in_progress';
    case PaymentSessionRequired = 'payment_session_required';

    case AvatarInvalid = 'avatar_invalid';

    public static function fromStatus(int $status): self
    {
        return match (true) {
            $status === Response::HTTP_BAD_REQUEST => self::BadRequest,
            $status === Response::HTTP_UNAUTHORIZED => self::Unauthenticated,
            $status === Response::HTTP_FORBIDDEN => self::Forbidden,
            $status === Response::HTTP_NOT_FOUND => self::NotFound,
            $status === Response::HTTP_METHOD_NOT_ALLOWED => self::MethodNotAllowed,
            $status === Response::HTTP_CONFLICT => self::Conflict,
            $status === Response::HTTP_TOO_MANY_REQUESTS => self::RateLimited,
            $status >= Response::HTTP_INTERNAL_SERVER_ERROR => self::ServerError,
            default => self::HttpError,
        };
    }
}
