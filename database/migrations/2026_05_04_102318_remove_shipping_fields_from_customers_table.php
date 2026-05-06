<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_mobile',
                'shipping_city',
                'shipping_address',
            ]);
        });
    }

    public function down(): void
    {
        // Rollback — adds columns back if needed
        Schema::table('customers', function (Blueprint $table) {
            $table->string('shipping_name')->nullable();
            $table->string('shipping_mobile')->nullable();
            $table->string('shipping_city')->nullable();
            $table->text('shipping_address')->nullable();
        });
    }
};
