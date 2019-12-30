<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DealerAuctionAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('dealers_addresses', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dealers_id')->unsigned()->index();
            $table->string('name');
            $table->text('address')->nullable();
            $table->integer('country_id')->unsigned()->nullable()->index();
            $table->string('postcode')->nullable();
            $table->string('town')->nullable()->index();
            $table->string('county')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('auction_url')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
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
        Schema::connection('mysql')->drop('dealers_addresses');
    }
}
