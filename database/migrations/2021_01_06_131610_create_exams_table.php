<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses');
            $table->text('type')->default('examen');
            $table->dateTime('date')->useCurrent();
            $table->integer('hours')->default(0);
            $table->integer('minutes')->default(0);
            $table->integer('number_of_exercises')->default(0);
            $table->text('exercises_type')->default('');
            $table->integer('total_points')->default(0);
            $table->integer('minimum_points')->default(0);
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
        Schema::dropIfExists('exams');
    }
}
