<?php

use \Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\Schema;
use \Illuminate\Database\Schema\Blueprint;

class CreateWebaliticTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webalitic', function(Blueprint $table){
            $table->integer("id")->autoIncrement();
            $table->string("time", 200)->default(NULL);
            $table->string("ip", 20)->default(NULL);
            $table->string("country", 100)->default(NULL);
            $table->text("page")->default(NULL);
            $table->text("referer")->default(NULL);
            $table->string("browser", 250)->default(NULL);
            $table->string("version", 10)->default(NULL);
            $table->string("os", 50)->default(NULL);
            $table->integer("is_bot")->default(0);
            $table->tinyText("u_agent")->default(NULL)->nullable();
            $table->string("m", 10)->default(NULL);
            $table->longText("geoip")->default(NULL);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webalitic');
    }
}