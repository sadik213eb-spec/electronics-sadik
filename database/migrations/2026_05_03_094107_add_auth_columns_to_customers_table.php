<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->date('birthday')->nullable()->after('password');
            $table->string('profile_photo')->nullable()->after('birthday');
            $table->string('remember_token', 100)->nullable()->after('profile_photo');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['password', 'birthday', 'profile_photo', 'remember_token']);
        });
    }
};
