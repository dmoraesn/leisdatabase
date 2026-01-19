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
        Schema::table('leis', function (Blueprint $table) {
            // Adicionando após a coluna 'abrangencia' para manter organização lógica
            $table->char('estado', 2)->nullable()->after('abrangencia')->comment('Sigla da UF (IBGE)');
            $table->string('cidade')->nullable()->after('estado')->comment('Nome da Cidade (IBGE)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leis', function (Blueprint $table) {
            $table->dropColumn(['estado', 'cidade']);
        });
    }
};