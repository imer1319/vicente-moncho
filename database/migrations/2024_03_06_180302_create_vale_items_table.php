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
        Schema::create('vale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('valet_id')->constrained('valets');
            $table->text('descripcion');
            $table->bigInteger('cantidad');
            $table->string('importe')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_vales');
    }
};
