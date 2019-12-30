<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DealerFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('dealers_features', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->index();
            $table->boolean('system')->default(0);
            $table->integer('position')->default(0);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('dealer_features', function(Blueprint $table) {
            $table->integer('dealers_id')->unsigned()->index();
            $table->foreign('dealers_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->integer('dealers_features_id')->unsigned()->index();
            $table->foreign('dealers_features_id')->references('id')->on('dealers_features')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->drop('dealer_features');
        Schema::connection('mysql')->drop('dealers_features');
    }
}
