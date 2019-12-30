<?php

use Illuminate\Database\Seeder;
use Quantum\base\Models\Shortcodes;

class GaukShortcodes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'popularAuctions',
            'callback' => 'App\Shortcodes\Auctioneers::popularAuctions',
            'title' => 'Auctioneers - Popular',
            'description' => 'Show the popular auctioneers [popularAuctions].<br>Options available is amount: [popularAuctions amount=4]<br>Max returned amount is 20.',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'happeningAuctions',
            'callback' => 'App\Shortcodes\Auctioneers::happeningAuctions',
            'title' => 'Auctioneers - Happening',
            'description' => 'Show the happening auctioneers [happeningAuctions].',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'categorySearchBox',
            'callback' => 'App\Shortcodes\Auctioneers::categorySearchBox',
            'title' => 'Auctioneers - Category Search Box',
            'description' => 'Show the auctioneer category search box [categorySearchBox].',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'fullwidthlast',
            'callback' => 'App\Shortcodes\GaukTheme::fullWidthLast',
            'title' => 'Full Width Last',
            'description' => 'Use this to display content the full width of the page [fullWidthLast]YOUR CONTENT[/fullWidthLast].',
        ]);

        Shortcodes::firstOrCreate([
            'type' => 'Modules',
            'name' => 'latestPosts',
            'callback' => 'App\Shortcodes\Blog::latestPosts',
            'title' => 'Latest Posts',
            'description' => 'Display the latest blog posts [latestPosts amount=3].',
        ]);

    }
}
