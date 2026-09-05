<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->after('order_number');
            $table->string('shipping_zone')->default('inside_dhaka')->after('shipping_cost');
            $table->decimal('grand_total', 10, 2)->default(0)->after('points_discount');
            $table->string('shipping_name')->nullable()->after('updated_at');
            $table->string('shipping_phone', 15)->nullable()->after('shipping_name');
            $table->text('shipping_address')->nullable()->after('shipping_phone');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_zip', 20)->nullable()->after('shipping_city');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'address']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('warranty')->nullable()->after('short_description');
            $table->string('stock_status')->default('in_stock')->after('stock');
            $table->json('images')->nullable()->after('sku');
            $table->decimal('shipping_inside_dhaka', 10, 2)->default(60.00)->after('images');
            $table->decimal('shipping_outside_dhaka', 10, 2)->default(120.00)->after('shipping_inside_dhaka');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 15)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('address')->nullable();
            $table->dropColumn(['customer_id', 'shipping_zone', 'grand_total', 'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_zip']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['warranty', 'stock_status', 'images', 'shipping_inside_dhaka', 'shipping_outside_dhaka']);
        });
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
