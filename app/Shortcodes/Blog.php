<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : Blog.php
 **/

namespace App\Shortcodes;


use Illuminate\Support\Facades\View;
use Quantum\blog\Services\BlogService;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Blog
{

    public static function latestPosts(ShortcodeInterface $s)
    {
        $total = $s->getParameter('amount') ? $s->getParameter('amount') : 3;

        $blogService = new BlogService();

        $area = ['public'];
        if(\Auth::check()) $area = ['members', 'public'];

        $latestPosts = $blogService->latest_posts($area);
        $latestPosts = $latestPosts->where('meta.featured_image', '!=', '')->take($total);

        //dd($latestPosts);

        $view = View::make('Shortcodes.latestPosts', compact('latestPosts'));
        $widget = $view->render();
        //echo $widget; exit;
        return $widget;

    }

}