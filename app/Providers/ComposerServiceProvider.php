<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ComposerServiceProvider.php
 **/

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer(
            'members.GavelBox.*', 'App\Http\ViewComposers\GavelBoxViewComposer'
        );
        view()->composer(
            'members.Dealers.indexFullMap', 'App\Http\ViewComposers\AuctioneerListViewComposer'
        );

        view()->composer(
            'members.Dealers.searchFullMap', 'App\Http\ViewComposers\AuctioneerListViewComposer'
        );
        view()->composer(
            'frontend.Dealers.indexFullMap', 'App\Http\ViewComposers\AuctioneerListViewComposer'
        );

        view()->composer(
            'frontend.Dealers.searchFullMap', 'App\Http\ViewComposers\AuctioneerListViewComposer'
        );

        view()->composer(
            'base::admin.Navigation', 'App\Http\ViewComposers\AdminViewComposer'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}