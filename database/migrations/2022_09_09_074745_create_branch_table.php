<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('name', '500');
            $table->text('address')->nullable();
            $table->unsignedBigInteger('city');
            $table->foreign('city')->references('id')->on('master_city');
            $table->unsignedBigInteger('state');
            $table->foreign('state')->references('id')->on('master_state');
            $table->unsignedBigInteger('country');
            $table->foreign('country')->references('id')->on('master_country');
            $table->integer('pincode')->nullable();
            $table->string('mobile', '100');
            $table->string('phone', '100')->nullable();
            $table->string('email', '100');
            $table->string('website', '100')->nullable();
            $table->string('logo', '100')->default('default.png');
            $table->integer('status')->default(1);
            $table->string('contact_person_name', '500');
            $table->string('contact_person_mobile', '100');
            $table->string('contact_person_email', '100')->nullable();
            $table->text('contact_person_address')->nullable();
            $table->integer('contact_person_city');
            $table->integer('contact_person_state');
            $table->integer('contact_person_country');
            $table->integer('contact_person_pincode')->nullable();
            $table->string('contact_person_photo', '100')->default('default.png');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('branch');
    }
}
