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
        Schema::table('creators', function (Blueprint $table) {
            if (!Schema::hasColumn('creators', 'avatar_hovered')) {
                $table->text('avatar_hovered')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            if (Schema::hasColumn('creators', 'avatar_hovered')) {
                $table->dropColumn('avatar_hovered');
            }
        });
    }
};
