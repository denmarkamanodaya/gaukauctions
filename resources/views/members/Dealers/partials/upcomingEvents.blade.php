@if(is_array($events) && count($events) > 0)
    <div class="widget widget-text mt-20">
    <h6>Upcoming Events</h6>

    @foreach($events as $eventDate => $daysEvent)
    <h6>{!! $eventDate !!}</h6>
        @foreach($daysEvent as $event)

            <div class="dealerFeatures mb-20"><ul>
                    <a href="/members/auctioneer/{{$dealer->slug}}/event/{{$event->slug}}/{{$daysEvent->eventdate->toDateString()}}">
            <li>{{$event->title}}</li>
            @if($event->start_time == '00:00' && $event->end_time == '23:59')
                <li><i class="far fa-clock"></i> All Day Event</li>
            @else
                <li><i class="far fa-clock"></i> {{$event->start_time}} till {{$event->end_time}}</li>
            @endif
                    </a>
            </ul>

            </div>
        @endforeach

    @endforeach
    </div>
@endif