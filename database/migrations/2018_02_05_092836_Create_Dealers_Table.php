<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('dealers', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique()->index();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->integer('country_id')->unsigned()->nullable()->index();
            $table->string('postcode')->nullable();
            $table->string('town')->nullable()->index();
            $table->string('county')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('auction_url')->nullable();
            $table->string('online_bidding_url')->nullable();
            $table->text('details')->nullable();
            $table->string('buyers_premium')->nullable();
            $table->text('directions')->nullable();
            $table->text('rail_station')->nullable();
            $table->text('notes')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->boolean('has_streetview')->default(0);
            $table->enum('type', ['auctioneer', 'dealer', 'classified'])->default('auctioneer')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->boolean('online_only')->nullable()->index();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('dealer_media', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dealer_id')->unsigned()->index();
            $table->text('name')->nullable();
            $table->enum('type', ['image', 'video'])->default('image')->index();
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
        Schema::connection('mysql')->drop('dealers');
        Schema::connection('mysql')->drop('dealer_media');
    }
}
