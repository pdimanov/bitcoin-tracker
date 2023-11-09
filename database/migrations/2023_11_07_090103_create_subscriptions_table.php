<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->unsignedInteger('price')->nullable();
            $table->string('currency');
            $table->unsignedFloat('percentage')->nullable();
            $table->integer('interval')->nullable();
            $table->timestamp('last_notified')->nullable();
            $table->timestamps();
            $table->index(['currency', 'price', 'percentage', 'last_notified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
