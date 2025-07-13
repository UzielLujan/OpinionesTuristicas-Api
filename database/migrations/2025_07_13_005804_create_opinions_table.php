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
        Schema::create('opinions', function (Blueprint $table) {
            $table->id(); // Campo _id estándar de MongoDB
            $table->string('title');
            $table->text('review');
            $table->integer('polarity');
            $table->string('town');
            $table->string('region');
            $table->string('type'); // "Hotel", "Restaurant", "Attractive"
            $table->string('usuario');
            $table->boolean('etiquetado_manual')->default(true);
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opinions');
    }
};
