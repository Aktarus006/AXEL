<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("collections", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
            $table->text("image")->nullable();
            $table->text("cover")->nullable();
            $table->string("online");
            $table->date("creation_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("collections");
    }
};
