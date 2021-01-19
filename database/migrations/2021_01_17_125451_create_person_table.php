<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->string('name', 500)->nullable(false);
            $table->string('last_name', 500)->nullable(false);
            $table->string('title', 500)->nullable(true);
            $table->string('email', 500)->nullable(false);
            $table->string('phone_number', 15)->nullable(true);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::table('persons', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person');
    }
}
