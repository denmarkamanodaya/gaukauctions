<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarTables2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->date('start_day')->nullable()->index();
            $table->time('start_time')->nullable()->index();
            $table->date('end_day')->nullable()->index();
            $table->time('end_time')->nullable();
            $table->string('repeat_type')->nullable()->index();
            $table->string('repeat_year')->nullable()->index();
            $table->string('repeat_month')->nullable()->index();
            $table->string('repeat_day')->nullable()->index();
            $table->string('repeat_week')->nullable()->index();
            $table->string('repeat_weekday')->nullable()->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->string('cal_eventable_id')->nullable();
            $table->string('cal_eventable_type')->nullable();
            $table->integer('repeat_amount')->unsigned()->nullable()->index();
            $table->integer('repeated')->unsigned()->nullable()->index();
            $table->date('repeat_finished')->nullable()->index();
            $table->string('tenant')->nullable()->index();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('calendar_events_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_events_id')->unsigned()->index();
            $table->mediumText('description');
            $table->text('address')->nullable();
            $table->string('county')->nullable();
            $table->integer('country_id')->unsigned()->nullable()->default(826)->index();
            $table->string('postcode')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('event_image')->nullable();
            $table->string('event_url')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('calendar_subables', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('calendar_subable_id')->unsigned()->index();
            $table->string('calendar_subable_type')->index();
        });

        Schema::connection('mysql')->create('calendar_dealer_categories', function(Blueprint $table) {
            $table->integer('calendar_id')->unsigned()->index();
            $table->foreign('calendar_id')->references('id')->on('calendar_events')->onDelete('cascade');
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
        Schema::connection('mysql')->drop('calendar_categories');
        Schema::connection('mysql')->drop('calendar_subables');
        Schema::connection('mysql')->drop('calendar_events_meta');
        Schema::connection('mysql')->drop('calendar_events');
    }
}
