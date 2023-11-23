<?php

use \Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\Schema;
use \Illuminate\Database\Schema\Blueprint;

/**
 * CreateWebaliticUserTable - main class
 *
 * CreateWebaliticUserTable
 * distributed under the MIT License
 *
 * @author  Dominic Moeketsi developer@osit.co.za
 * @company OmniSol Information Technology (PTY) LTD
 * @version 1.00
 */
class CreateWebaliticUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("webalitic_user", function(Blueprint $table){
            $table->integer("id")->autoIncrement();
            $table->string("user_name")->default("User 123");
            $table->string("website_name")->default("Website 123");
            $table->integer("successful_transactions")->default(0);
            $table->integer("failed_transactions")->default(0);
            $table->tinyInteger("credentials_faulty")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webalitic_user');
    }
}