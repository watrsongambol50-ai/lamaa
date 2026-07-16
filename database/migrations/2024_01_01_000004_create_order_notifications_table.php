<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->string('title');
            $table->string('title_ar');
            $table->text('body');
            $table->text('body_ar');
            $table->boolean('is_read')->default(false);
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->string('order_number')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_notifications'); }
};
