<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Customer pickup location, captured via browser geolocation on the order form.
            // Lets admins/delivery staff see the customer's pin on a map.
            $table->decimal('lat', 10, 7)->nullable()->after('address_notes');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');

            // Customer-facing cancellation.
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->text('cancel_reason')->nullable()->after('cancelled_at');

            // Online payment (Moyasar) tracking.
            $table->string('payment_status')->default('unpaid')->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'cancelled_at', 'cancel_reason', 'payment_status', 'payment_reference']);
        });
    }
};
