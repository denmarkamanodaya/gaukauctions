<?php

use Illuminate\Database\Seeder;
use Quantum\base\Models\Menu;
use Quantum\base\Models\MenuItems;
use Quantum\base\Models\Settings;

class GaukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('title', 'Main Admin Menu')->tenant()->firstOrFail();

        if(!$dealerHeader = MenuItems::where('menu_id',$menu->id)->where('parent_id', 0)->where('title', 'Dealers')->first())
        {

            $dealerHeader =MenuItems::create( [
                'menu_id' => $menu->id,
                'icon' => 'fas fa-id-badge',
                'url' => '',
                'title' => 'Dealers',
                'permission' => 'view-admin-area',
                'type' => 'dropdown',
                'position' => 13
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $dealerHeader->id)->where('title', 'Auctioneers')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $dealerHeader->id,
                'icon' => 'fas fa-gavel',
                'url' => url('admin/dealers/auctioneers'),
                'title' => 'Auctioneers',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 1
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $dealerHeader->id)->where('title', 'Features')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $dealerHeader->id,
                'icon' => 'fas fa-badge-check',
                'url' => url('admin/dealers/features'),
                'title' => 'Features',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 30
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $dealerHeader->id)->where('title', 'Categories')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $dealerHeader->id,
                'icon' => 'far fa-sitemap',
                'url' => url('admin/dealers/categories'),
                'title' => 'Categories',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 40
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $dealerHeader->id)->where('title', 'Reminders')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $dealerHeader->id,
                'icon' => 'far fa-clock',
                'url' => url('admin/dealers/eventReminders'),
                'title' => 'Reminders',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 50
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $dealerHeader->id)->where('title', 'Problems')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $dealerHeader->id,
                'icon' => 'far fa-exclamation-triangle',
                'url' => url('admin/dealers/problems'),
                'title' => 'Problems',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 60
            ] );

            \Artisan::call('cache:clear');
        }

        $logDD = MenuItems::where('menu_id',$menu->id)->where('type', 'dropdown')->where('title', 'Settings')->first();
        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Gauk Settings')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'fas fa-cogs',
                'url' => url('admin/gauk-settings'),
                'title' => 'Gauk Settings',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 2
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$menuItem = MenuItems::where('menu_id',$menu->id)->where('parent_id', $logDD->id)->where('title', 'Cache Settings')->first())
        {

            MenuItems::create( [
                'menu_id' => $menu->id,
                'parent_id' => $logDD->id,
                'icon' => 'far fa-copy',
                'url' => url('admin/cache/settings'),
                'title' => 'Cache Settings',
                'permission' => 'view-admin-area',
                'type' => 'normal',
                'position' => 10
            ] );

            \Artisan::call('cache:clear');
        }

        if(!$import_setting = Settings::where('name', 'gauk_import_api_key')->tenant()->first())
        {
            Settings::create([
                'name' => 'gauk_import_api_key',
                'data' => ''
            ]);

            Settings::create([
                'name' => 'gauk_import_api_status',
                'data' => 'live'
            ]);
            \Cache::forget('site.settings');

        }

        if(!$map_setting = Settings::where('name', 'google_map_api_key')->tenant()->first())
        {
            Settings::create([
                'name' => 'google_map_api_key',
                'data' => ''
            ]);
            \Cache::forget('site.settings');

        }

        if(!$main_content_role = Settings::where('name', 'main_content_role')->tenant()->first())
        {
            Settings::create([
                'name' => 'main_content_role',
                'data' => ''
            ]);
            \Cache::forget('site.settings');

        }
        if(!$snippet = \Quantum\base\Models\News::where('title', 'Dealer Report Problem')->where('area', 'members')->tenant()->first()) {
            \Quantum\base\Models\News::firstOrCreate([
                'title'   => 'Dealer Report Problem',
                'content' => '',
                'area'    => 'members',
                'status'  => 'published',
                'type'    => 'snippet',
                'system'  => 1,
                'tenant'  => config('app.name'),
            ]);

            \Quantum\base\Models\News::firstOrCreate([
                'title'   => 'Dealer Report Problem Received',
                'content' => '',
                'area'    => 'members',
                'status'  => 'published',
                'type'    => 'snippet',
                'system'  => 1,
                'tenant'  => config('app.name'),
            ]);
        }


    }
}
