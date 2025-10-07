<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Aggiungi una nuova colonna temporanea 'user_type_new' con il tipo desiderato
        DB::statement("
            ALTER TABLE users
            ADD COLUMN user_type_new VARCHAR(50) DEFAULT 'hr-representative' NOT NULL
        ");

        // Copia i valori dalla colonna esistente nella nuova colonna
        DB::table('users')->update([
            'user_type_new' => DB::raw("
                CASE
                    WHEN user_type = 'hr-representative' THEN 'recruiter'
                    ELSE user_type
                END
            ")
        ]);

        // Rimuovi la vecchia colonna
        DB::statement("ALTER TABLE users DROP COLUMN user_type");

        // Rinomina la nuova colonna in 'user_type'
        DB::statement("ALTER TABLE users RENAME COLUMN user_type_new TO user_type");

        // (Opzionale) Aggiungi un check constraint per limitare i valori
        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT user_type_check
            CHECK (user_type IN ('admin', 'recruiter', 'candidate'))
        ");
    }

    public function down()
    {
        // Rimuovi il constraint
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS user_type_check");

        // Crea una colonna temporanea per ripristinare il vecchio valore
        DB::statement("
            ALTER TABLE users
            ADD COLUMN user_type_old VARCHAR(50) DEFAULT 'hr-representative' NOT NULL
        ");

        // Ripristina i valori
        DB::table('users')->update([
            'user_type_old' => DB::raw("
                CASE
                    WHEN user_type = 'recruiter' THEN 'hr-representative'
                    ELSE user_type
                END
            ")
        ]);

        // Rimuovi la colonna modificata e rinomina quella vecchia
        DB::statement("ALTER TABLE users DROP COLUMN user_type");
        DB::statement("ALTER TABLE users RENAME COLUMN user_type_old TO user_type");
    }
};