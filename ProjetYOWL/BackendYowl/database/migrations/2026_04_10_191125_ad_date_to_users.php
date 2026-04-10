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
        Schema::table('users', function (Blueprint $table) {
            $table->date('Birth_Year')->after('password');
            $table->string('Phone')->nullable()->after('Birth_Year');
            $table->string('Photo_profil')->nullable()->after('Phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('Birth_Year');
            $table->dropColumn('Phone');
            $table->dropColumn('Photo_profil');
        });
    }
};
