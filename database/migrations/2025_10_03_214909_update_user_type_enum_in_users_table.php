<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            ALTER TABLE users 
            MODIFY COLUMN user_type ENUM('admin','hr-representative','recruiter','candidate') 
            NOT NULL DEFAULT 'hr-representative'
        ");
        
        DB::table('users')
            ->where('user_type', 'hr-representative')
            ->update(['user_type' => 'recruiter']);
            
        DB::statement("
            ALTER TABLE users 
            MODIFY COLUMN user_type ENUM('admin','recruiter','candidate') 
            NOT NULL DEFAULT 'recruiter'
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
            ALTER TABLE users 
            MODIFY COLUMN user_type ENUM('admin','hr-representative') 
            NOT NULL DEFAULT 'hr-representative'
        ");

        DB::table('users')
        ->where('user_type', 'recruiter')
        ->update(['user_type' => 'hr-representative']);
    }
};
