<?php

namespace Shopper\Framework\Observers;

use Shopper\Framework\Models\Shop\Shop;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ShopObserver
{
    /**
     * Trigger Before Create a Shop
     *
     * @param  Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function creating(Shop $shop)
    {
        if (config('shopper.api_connection') === 'jwt') {
            try {
                $user = auth()->userOrFail();
                $shop->owner_id = $user->id;
            } catch (UserNotDefinedException $exception) {
                return response()->json(['error' => $exception->getMessage()], 401);
            }
        } else {
            $shop->owner_id = auth()->user()->id;
        }
    }
}
