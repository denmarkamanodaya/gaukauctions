<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DeleteEventReminderMultiRequest;
use App\Models\Reminders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventReminders extends Controller
{

    public function index()
    {
        $reminders = Reminders::with('remindable')->where('remindable_type', 'App\Models\Dealers')->orderBy('remind_on', 'ASC')->paginate(20);
        return view('admin.EventReminders.index', compact('reminders'));
    }

    public function due()
    {
        $reminders = Reminders::with('remindable')->where('remindable_type', 'App\Models\Dealers')->where('remind_on', '<', now())->orderBy('remind_on', 'ASC')->paginate(20);
        return view('admin.EventReminders.due', compact('reminders'));
    }

    public function delete($id)
    {
        Reminders::where('id', $id)->where('remindable_type', 'App\Models\Dealers')->delete();
        flash('Reminder Deleted')->success();
        \Cache::forget('eventReminderDueCount');
        return redirect()->back();
    }

    public function deleteDue($id)
    {
        Reminders::where('id', $id)->where('remindable_type', 'App\Models\Dealers')->delete();
        flash('Reminder Deleted')->success();
        \Cache::forget('eventReminderDueCount');
        return redirect()->back();
    }

    public function deleteSelected(DeleteEventReminderMultiRequest $request)
    {
        if($request->deleteSelected && count($request->deleteSelected) > 0)
        {
            Reminders::whereIn('id', $request->deleteSelected)->where('remindable_type', 'App\Models\Dealers')->delete();
            flash('Reminder Deleted')->success();
            \Cache::forget('eventReminderDueCount');
        }
        return redirect()->back();
    }

    public function deleteSelectedDue(DeleteEventReminderMultiRequest $request)
    {
        if($request->deleteSelected && count($request->deleteSelected) > 0)
        {
            Reminders::whereIn('id', $request->deleteSelected)->where('remindable_type', 'App\Models\Dealers')->delete();
            flash('Reminder Deleted')->success();
            \Cache::forget('eventReminderDueCount');
        }
        return redirect()->back();
    }

}
