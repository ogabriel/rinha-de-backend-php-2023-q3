<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE INDEX pessoas_busca_gist_trgm_ops_index ON pessoas USING gist (busca gist_trgm_ops (siglen = '1024'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS pessoas_busca_gist_trgm_ops_index;');
    }
};
