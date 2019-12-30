<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DealerCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('dealer_categories', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug')->index();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->boolean('system')->default(0);
            $table->string('area')->nullable()->index();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('dealers_categories', function(Blueprint $table) {
            $table->integer('dealers_id')->unsigned()->index();
            $table->foreign('dealers_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->integer('dealer_categories_id')->unsigned()->index();
            $table->foreign('dealer_categories_id')->references('id')->on('dealer_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->drop('dealers_categories');
        Schema::connection('mysql')->drop('dealer_categories');
    }
}
