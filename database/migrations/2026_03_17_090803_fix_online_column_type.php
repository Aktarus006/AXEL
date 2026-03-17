<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, normalize data in collections
        DB::table('collections')->where('online', 'Offline')->orWhere('online', 'offline')->update(['online' => '0']);
        DB::table('collections')->where('online', 'Online')->orWhere('online', 'online')->update(['online' => '1']);
        
        // Normalize data in creators
        DB::table('creators')->where('online', 'Offline')->orWhere('online', 'offline')->update(['online' => '0']);
        DB::table('creators')->where('online', 'Online')->orWhere('online', 'online')->update(['online' => '1']);

        // Normalize data in news
        DB::table('news')->where('online', 'Offline')->orWhere('online', 'offline')->update(['online' => '0']);
        DB::table('news')->where('online', 'Online')->orWhere('online', 'online')->update(['online' => '1']);

        // Then change column types
        Schema::table('collections', function (Blueprint $table) {
            $table->integer('online')->default(0)->change();
        });

        Schema::table('creators', function (Blueprint $table) {
            $table->integer('online')->default(0)->change();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->integer('online')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->string('online')->change();
        });

        Schema::table('creators', function (Blueprint $table) {
            $table->string('online')->change();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('online')->change();
        });
    }
};
