<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        DB::statement("
            CREATE INDEX idx_jobs_history_gin
            ON jobs
            USING GIN (history)
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            DROP INDEX IF EXISTS idx_jobs_history_gin
        ");
    }
};
