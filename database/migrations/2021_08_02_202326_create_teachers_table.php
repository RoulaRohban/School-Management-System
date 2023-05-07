<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('birthdate');
            $table->string('origin_place');

            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')
                ->references('id')->on('schools')
                ->onDelete('cascade');

            $table->string('identificationPath');
            $table->string('familyBookPath');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('type',['manager','teacher','prompt'])->default('teacher');

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
        Schema::dropIfExists('teachers');
    }
}
