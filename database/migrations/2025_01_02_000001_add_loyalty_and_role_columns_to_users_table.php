<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_pieces_washed')->default(0)->after('total_orders');
            $table->integer('free_pieces_earned')->default(0)->after('total_pieces_washed');
            $table->string('country_code')->default('+966')->after('phone');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('role');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_pieces_washed', 'free_pieces_earned', 'country_code']);
            $table->dropConstrainedForeignId('created_by');
        });
    }
};
