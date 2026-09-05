<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('subscription_plans')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('subscription_plans', 'product_id')) {
                $table->foreignId('product_id')
                    ->nullable()
                    ->after('producer_id')
                    ->constrained('products');
            }

            if (! Schema::hasColumn('subscription_plans', 'image_url')) {
                $table->text('image_url')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('subscription_plans')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });
    }
};
