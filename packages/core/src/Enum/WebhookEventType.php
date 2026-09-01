<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

enum WebhookEventType: string implements HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case OrderCreated = 'order.created';

    case OrderPaid = 'order.paid';

    case OrderCancelled = 'order.cancelled';

    case OrderShipped = 'order.shipped';

    case OrderCompleted = 'order.completed';

    case ShipmentCreated = 'shipment.created';

    case ShipmentDelivered = 'shipment.delivered';

    case ShipmentDeliveryFailed = 'shipment.delivery_failed';

    case ShipmentReturned = 'shipment.returned';

    case ProductCreated = 'product.created';

    case ProductUpdated = 'product.updated';

    case ProductDeleted = 'product.deleted';

    case CustomerRegistered = 'customer.registered';

    case CartCompleted = 'cart.completed';

    public function getLabel(): string
    {
        return $this->value;
    }
}
