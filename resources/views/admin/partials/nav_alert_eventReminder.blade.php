<?php

?>
@if($eventReminderDueCount)
<li>
    <a href="/admin/dealers/eventReminders/due" class="" data-toggle="tooltip" title="Reminders">
        <i class="far fa-clock"></i>
        <span class="visible-xs-inline-block position-right">Alerts</span>
        <span class="badge bg-warning-400">{{$eventReminderDueCount}}</span>
    </a>
</li>
@endif
