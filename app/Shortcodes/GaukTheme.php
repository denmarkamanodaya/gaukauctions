<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : GaukTheme.php
 **/

namespace App\Shortcodes;

use Illuminate\Support\Facades\View;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class GaukTheme
{
    public static function fullWidth(ShortcodeInterface $s)
    {
        //dd($s);
        $content = $s->getContent();
        
        $before = '</div></div>';
        $after = '<div class="page-section" style="padding-top:70px; padding-bottom:50px;">
<div class="container">';
        return $before.$content.$after;
    }

    public static function fullWidthLast(ShortcodeInterface $s)
    {
        //dd($s);
        $content = $s->getContent();

        $before = '</div></div>';
        $after = '<div class="page-section" style="padding-top:0px; padding-bottom:0px; margin-bottom: -50px;">
<div class="container">';
        return $before.$content.$after;
    }
}