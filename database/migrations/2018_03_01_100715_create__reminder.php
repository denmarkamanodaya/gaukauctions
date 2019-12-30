<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReminder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('remind_on')->nullable()->index();
            $table->text('about')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('remindable_id')->default(0);
            $table->string('remindable_type')->nullable();
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
        Schema::connection('mysql')->drop('reminders');
    }
}
