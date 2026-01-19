<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->enum('origem', ['auto', 'manual'])
                ->default('auto')
                ->after('ordem');

            $table->enum('confidence', ['high', 'medium', 'low'])
                ->nullable()
                ->after('origem');
        });
    }

    public function down(): void
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->dropColumn(['origem', 'confidence']);
        });
    }
};
