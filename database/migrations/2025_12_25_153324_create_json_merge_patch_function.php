<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<SQL
            CREATE OR REPLACE FUNCTION json_merge_patch("target" jsonb, "patch" jsonb) 
            RETURNS jsonb AS $$
            BEGIN
                RETURN COALESCE(
                    jsonb_object_agg(
                        COALESCE("tkey", "pkey"),
                        CASE
                            WHEN "tval" ISNULL THEN "pval"
                            WHEN "pval" ISNULL THEN "tval"
                            WHEN jsonb_typeof("tval") != 'object' OR jsonb_typeof("pval") != 'object' THEN "pval"
                            ELSE json_merge_patch("tval", "pval")
                        END
                    ), '{}'::jsonb
                )
                FROM jsonb_each("target") e1("tkey", "tval")
                FULL JOIN jsonb_each("patch") e2("pkey", "pval")
                    ON "tkey" = "pkey"
                WHERE jsonb_typeof("pval") != 'null'
                    OR "pval" ISNULL;
            END;
            $$ LANGUAGE plpgsql;
        SQL);
    }

    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS json_merge_patch(jsonb, jsonb);');
    }
};
