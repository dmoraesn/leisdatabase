<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lei_importacoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lei_id')
                ->constrained('leis')
                ->onDelete('cascade');

            $table->string('arquivo_pdf');
            $table->longText('texto_extraido')->nullable();

            $table->enum('status', [
                'pendente',
                'processando',
                'concluida',
                'erro'
            ])->default('pendente');

            $table->text('erro')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lei_importacoes');
    }
};
