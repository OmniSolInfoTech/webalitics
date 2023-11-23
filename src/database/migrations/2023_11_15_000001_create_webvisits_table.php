<?php

use \Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\Schema;
use \Illuminate\Database\Schema\Blueprint;

/**
 * CreateWebvisitsTable - main class
 *
 * CreateWebvisitsTable
 * distributed under the MIT License
 *
 * @author  Dominic Moeketsi developer@osit.co.za
 * @company OmniSol Information Technology (PTY) LTD
 * @version 1.00
 */
class CreateWebvisitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webvisits', function(Blueprint $table){
            $table->integer("id")->autoIncrement();
            $table->date("date")->default(NULL);
            $table->integer("visits")->default(NULL);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webvisits');
    }
}