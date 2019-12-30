<?php

namespace App\Module;

use Illuminate\Support\Facades\Artisan;

/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Module.php
 **/
class Module
{
    public function install()
    {
        //dd('app install');
    }

    public function installMultiSite()
    {
        //dd('app install');
    }

    public function update()
    {
        Artisan::call('db:seed', array('--class' => 'GaukSeeder', '--force' => true));
        Artisan::call('db:seed', array('--class' => 'GaukShortcodes', '--force' => true));

    }
}