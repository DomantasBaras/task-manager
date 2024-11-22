<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Add a 'username' column
            $table->string('username')->default('Guest');  // You can use nullable if users may not be logged in
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Drop the 'username' column if rolling back the migration
            $table->dropColumn('username');
        });
    }
};

