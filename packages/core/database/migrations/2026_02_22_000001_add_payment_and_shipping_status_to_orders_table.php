<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $table = $this->getTableName('orders');

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->string('payment_status', 32)
                ->default(PaymentStatus::Pending->value)
                ->after('status');
            $blueprint->string('shipping_status', 32)
                ->default(ShippingStatus::Unfulfilled->value)
                ->after('payment_status');
        });

        DB::table($table)->where('status', 'pending')->update([
            'status' => OrderStatus::New->value,
        ]);

        DB::table($table)->where('status', 'registered')->update([
            'status' => OrderStatus::Processing->value,
        ]);

        DB::table($table)->where('status', 'paid')->update([
            'status' => OrderStatus::Processing->value,
            'payment_status' => PaymentStatus::Paid->value,
        ]);

        DB::table($table)->where('status', 'shipped')->update([
            'status' => OrderStatus::Processing->value,
            'payment_status' => PaymentStatus::Paid->value,
            'shipping_status' => ShippingStatus::Shipped->value,
        ]);

        DB::table($table)->where('status', 'delivered')->update([
            'status' => OrderStatus::Processing->value,
            'payment_status' => PaymentStatus::Paid->value,
            'shipping_status' => ShippingStatus::Delivered->value,
        ]);

        DB::table($table)->where('status', OrderStatus::Completed->value)->update([
            'payment_status' => PaymentStatus::Paid->value,
        ]);
    }

    public function down(): void
    {
        Schema::table($this->getTableName('orders'), function (Blueprint $blueprint): void {
            $blueprint->dropColumn(['payment_status', 'shipping_status']);
        });
    }
};
