<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leis', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');
            $table->string('numero')->nullable();
            $table->integer('ano')->nullable();

            $table->enum('abrangencia', [
                'municipal',
                'estadual',
                'federal'
            ]);

            $table->enum('status', [
                'rascunho',
                'processando',
                'processada',
                'erro'
            ])->default('rascunho');

            $table->json('json_original')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leis');
    }
};
