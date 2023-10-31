<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_code')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branch');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('master_department');
            $table->unsignedBigInteger('designation_id');
            $table->foreign('designation_id')->references('id')->on('master_designation');
            $table->string('name', '500');
            $table->date('birth_date')->nullable();
            $table->string('gender', '50');
            $table->text('address')->nullable();
            $table->unsignedBigInteger('city');
            $table->foreign('city')->references('id')->on('master_city');
            $table->unsignedBigInteger('state');
            $table->foreign('state')->references('id')->on('master_state');
            $table->unsignedBigInteger('country');
            $table->foreign('country')->references('id')->on('master_country');
            $table->integer('pincode')->nullable();
            $table->string('mobile', '100')->nullable();
            $table->string('email', '100');
            $table->date('joining_date')->nullable();
            $table->integer('salary');
            $table->integer('status')->default(1);
            $table->string('profile_photo', '100')->default('profile.png');
            $table->string('resume', '100')->nullable();
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
        Schema::dropIfExists('employee');
    }
}
