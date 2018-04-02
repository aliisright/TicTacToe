<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataIntoTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
            DB::table('tests')->delete();

            DB::table('tests')->insert(array(
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 1,
                  ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 2,
                ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 3,
                ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 4,
                ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 5,
                ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 6,
                ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 7,
                ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 8,
                ),
                array(
                  'selected' => false,
                  'sign' => '',
                  'num' => 9,
                ),
            ));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests', function (Blueprint $table) {
            //
        });
    }
}
