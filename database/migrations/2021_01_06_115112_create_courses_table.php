<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->integer('year')->default(1);
            $table->integer('semester')->default(1);
            $table->integer('credits')->default(5);
            $table->timestamps();
        });

        DB::statement('
        ALTER TABLE courses
        ADD CONSTRAINT year_max CHECK (year > 0 AND year <= 5)
        ');
        DB::statement('
        ALTER TABLE courses
        ADD CONSTRAINT semester_constr CHECK (semester > 0 AND semester <= 2)
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
