<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('subscription_plans') || Schema::hasColumn('subscription_plans', 'product_id')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('producer_id')->constrained('products');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('subscription_plans', 'product_id')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }
};
