<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lei_localidades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lei_id')
                ->constrained('leis')
                ->onDelete('cascade');

            $table->string('pais')->default('Brasil');

            $table->string('estado')->nullable();
            $table->string('cidade')->nullable();

            $table->string('ibge_estado_id')->nullable();
            $table->string('ibge_cidade_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lei_localidades');
    }
};
