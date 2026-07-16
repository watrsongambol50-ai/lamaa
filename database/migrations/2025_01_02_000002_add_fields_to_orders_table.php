<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('service_mode')->default('wash_iron')->after('service_id');
            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
            $table->string('building_name')->nullable()->after('district');
            $table->string('building_number')->nullable()->after('building_name');
            $table->text('address_notes')->nullable()->after('building_number');
            $table->string('payment_method')->default('cash')->after('total_price');
            $table->string('coupon_code')->nullable()->after('payment_method');
            $table->float('discount_amount')->default(0)->after('coupon_code');
            $table->float('subtotal_price')->default(0)->after('discount_amount');
            $table->boolean('free_delivery')->default(false)->after('express_delivery');
            $table->boolean('is_pos_order')->default(false)->after('free_delivery');
            $table->integer('free_pieces_count')->default(0)->after('is_pos_order');
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'service_mode','city','district','building_name','building_number',
                'address_notes','payment_method','coupon_code','discount_amount',
                'subtotal_price','free_delivery','is_pos_order','free_pieces_count'
            ]);
        });
    }
};
