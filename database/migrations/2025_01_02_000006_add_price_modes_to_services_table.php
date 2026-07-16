<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('services', function (Blueprint $table) {
            $table->float('price_wash_only')->nullable()->after('base_price');
            $table->float('price_iron_only')->nullable()->after('price_wash_only');
        });
    }
    public function down(): void {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['price_wash_only', 'price_iron_only']);
        });
    }
};
