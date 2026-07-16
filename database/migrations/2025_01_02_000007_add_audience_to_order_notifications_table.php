<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('order_notifications', function (Blueprint $table) {
            $table->string('audience')->default('customer')->after('customer_id');
            $table->unsignedBigInteger('customer_id')->nullable()->change();
        });
    }
    public function down(): void {
        Schema::table('order_notifications', function (Blueprint $table) {
            $table->dropColumn('audience');
        });
    }
};
