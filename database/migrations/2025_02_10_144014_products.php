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
        Schema::create('products', function (Blueprint $table) {
            $table->id("ID_product");
            $table->string('Label', length:50);
            $table->integer('Box_quantity');
            $table->string('Image', length:255);
            $table->boolean('Packing');
            $table->char('Barcode', length:13);
            $table->foreignId('ID_category')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
