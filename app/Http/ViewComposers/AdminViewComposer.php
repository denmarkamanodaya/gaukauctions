<?php
namespace App\Http\ViewComposers;

use App\Models\Problems;
use App\Models\Reminders;
use Illuminate\Contracts\View\View;

class AdminViewComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $reminders = \Cache::remember('eventReminderDueCount', 10, function () {
            return Reminders::with('remindable')->where('remindable_type', 'App\Models\Dealers')->where('remind_on', '<', now())->orderBy('remind_on', 'ASC')->count();
        });

        $problems = \Cache::remember('problemCount', 10, function () {
            return Problems::where('problemable_type', 'App\Models\Dealers')->count();
        });

        $view->with(['eventReminderDueCount' => $reminders, 'problemCount' => $problems]);
    }
}