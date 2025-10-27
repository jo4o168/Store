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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email')->nullable();
            $table->string('document')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('type');
            $table->integer('profile_picture')->nullable();
            $table->integer('site')->nullable();
            $table->integer('instagram')->nullable();
            $table->integer('facebook')->nullable();
            $table->integer('linkedin')->nullable();
            $table->date('birthdate')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
            $table->boolean('active')->default(true);
            $table->softDeletes();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('type');
            $table->integer('stock');
            $table->decimal('price')->default(0);
            $table->boolean('active')->default(true);
            $table->foreignId('contact_id')->constrained('contacts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('contacts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('active');
            $table->dropSoftDeletes();
        });
    }
};
