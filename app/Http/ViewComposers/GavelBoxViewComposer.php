<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class GavelBoxViewComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('Template', 'FullWithSide');
    }
}