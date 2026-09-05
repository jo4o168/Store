<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'producer_id')) {
            return;
        }

        $orderIds = DB::table('orders')->whereNull('producer_id')->pluck('id');

        foreach ($orderIds as $orderId) {
            $producerId = DB::table('order_items')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('order_items.order_id', $orderId)
                ->whereNull('order_items.deleted_at')
                ->value('products.producer_id');

            if ($producerId) {
                DB::table('orders')->where('id', $orderId)->update(['producer_id' => $producerId]);
            }
        }
    }

    public function down(): void
    {
        // Data backfill only.
    }
};
