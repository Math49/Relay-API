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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('ID_stock');
            $table->foreignId('ID_store')->index();
            $table->foreignId('ID_product')->index();
            $table->integer('Nmb_boxes');
            $table->integer('Quantity');
            $table->integer('Nmb_on_shelves');
            $table->boolean('Is_empty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
