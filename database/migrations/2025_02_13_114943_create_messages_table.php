<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->unsignedBigInteger('gateway_id');
            $table->foreign('gateway_id')->references('id')->on('message_gateways')->onDelete('cascade');
            $table->string('receiver_email')->nullable();
            $table->integer('receiver_phone_no')->nullable();
            $table->text('content');
            $table->string('subject')->nullable();
            $table->timestamp('schedule_at')->nullable();
            $table->enum('type', ['whatsapp', 'phone', 'email']);
            $table->enum('status', ['sent', 'failed', 'pending', 'schedule'])->default('pending');
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
        Schema::dropIfExists('messages');
    }
}
