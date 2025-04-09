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
        Schema::create('products__lists', function (Blueprint $table) {
            $table->foreignId('ID_product')->index();
            $table->foreignId('ID_list')->index();
            $table->integer('Quantity');
            $table->primary(['ID_product', 'ID_list']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products__lists');
    }
};
