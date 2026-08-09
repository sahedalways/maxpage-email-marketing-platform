<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('mail_gateway_name')->nullable();
            $table->string('mail_gateway_email')->nullable();
            $table->string('mail_gateway_type')->default('smtp');
            $table->string('mail_host')->nullable();
            $table->string('mail_driver')->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_api_key')->nullable();



            $table->string('whatsapp_business_name')->nullable();
            $table->string('whatsapp_access_token')->nullable();
            $table->string('whatsapp_no_id')->nullable();
            $table->string('whatsapp_account_id')->nullable();

            $table->string('twilio_phone_number')->nullable();
            $table->string('twilio_auth_token')->nullable();
            $table->string('twilio_account_sid')->nullable();


            $table->string('sms_key')->nullable();
            $table->string('key_ident')->nullable();
            $table->string('sms_sender_name')->nullable();
            $table->string('sms_type')->nullable();
            $table->string('brevo_sms_api_key')->nullable();

            $table->enum('is_gateway_type', ['whatsapp_business', 'twilio', 'brevo', 'other']);
            $table->enum('type', ['whatsapp', 'sms', 'email']);
            $table->enum('status', ['default', 'inactive'])->default('inactive');
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
        Schema::dropIfExists('message_gateways');
    }
}
