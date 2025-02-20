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
        Schema::create('categories_enable', function (Blueprint $table) {
            $table->id('ID_category_enable');
            $table->foreignId('ID_store')->index();
            $table->foreignId('ID_category')->index();
            $table->smallInteger('Category_position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_enable');
    }
};
