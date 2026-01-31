<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->change();
            $table->string('site')->nullable()->change();
            $table->string('instagram')->nullable()->change();
            $table->string('facebook')->nullable()->change();
            $table->string('linkedin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->integer('profile_picture')->nullable()->change();
            $table->integer('site')->nullable()->change();
            $table->integer('instagram')->nullable()->change();
            $table->integer('facebook')->nullable()->change();
            $table->integer('linkedin')->nullable()->change();
        });
    }
};
