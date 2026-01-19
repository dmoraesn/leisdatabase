<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('artigos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lei_id')
                ->constrained('leis')
                ->onDelete('cascade');

            $table->string('numero');
            $table->longText('texto');

            $table->integer('ordem')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artigos');
    }
};
