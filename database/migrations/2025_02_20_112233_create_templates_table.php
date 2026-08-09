<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('name');
            $table->longtext('content')->nullable();
            $table->tinyInteger('builder')->default(0);
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_private')->default(0);
            $table->string('theme')->nullable();
            $table->string('type');
            $table->boolean('is_guest')->default(false);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
