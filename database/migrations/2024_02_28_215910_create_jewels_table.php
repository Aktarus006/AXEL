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
        Schema::create("jewels", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->float("price")->nullable();
            $table->text("cover")->nullable();
            $table->string("description");
            $table->text("image")->nullable();
            $table->string("type");
            $table->string("material");
            $table->boolean("online");
            $table->date("creation_date");
            $table->foreignId("collection_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("jewels");
    }
};
