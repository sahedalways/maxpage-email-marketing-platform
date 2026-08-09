<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('template_categories')->onDelete('cascade');
            $table->boolean('is_guest')->default(false);
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
        Schema::dropIfExists('templates_categories');
    }
}
