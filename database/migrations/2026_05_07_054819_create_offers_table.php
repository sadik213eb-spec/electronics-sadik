<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();       // offer thumbnail
            $table->string('banner')->nullable();      // offer banner
            $table->json('product_ids')->nullable();   // selected products
            $table->boolean('show_timer')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
