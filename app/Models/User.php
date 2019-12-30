<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : User.php
 **/

namespace App\Models;


class User extends \Quantum\base\Models\User
{
    public function dealerFavourite()
    {
        return $this->belongsToMany('App\Models\Dealers', 'dealer_user', 'user_id', 'dealers_id');
    }
}