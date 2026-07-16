<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('barcode')->nullable();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('service_id')->constrained('services');
            $table->string('service_name');
            $table->string('service_name_ar');
            $table->integer('quantity')->default(1);
            $table->float('total_price');
            $table->boolean('is_free_order')->default(false);
            $table->string('status')->default('new');
            $table->text('notes')->nullable();
            $table->text('damage_note_photo_url')->nullable();
            $table->date('pickup_date');
            $table->date('delivery_date')->nullable();
            $table->string('pickup_time_slot')->nullable();
            $table->string('delivery_time_slot')->nullable();
            $table->string('address')->nullable();
            $table->integer('rating')->nullable();
            $table->text('rating_comment')->nullable();
            $table->boolean('express_delivery')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
