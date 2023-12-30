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
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

        Schema::create('pessoas', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('apelido', 32)->unique();
            $table->string('nome', 100);
            $table->string('nascimento', 10);
            $table->text('busca');
        });

        DB::statement('ALTER TABLE pessoas ADD COLUMN stack VARCHAR(32)[]');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP EXTENSION IF EXISTS "uuid-ossp";');
        Schema::dropIfExists('pessoas');
    }
};
