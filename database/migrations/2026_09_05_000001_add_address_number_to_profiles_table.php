<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('profiles', 'address_number')) {
            return;
        }

        Schema::table('profiles', function (Blueprint $table) {
            $table->string('address_number', 20)->nullable()->after('address');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('profiles', 'address_number')) {
            return;
        }

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('address_number');
        });
    }
};
