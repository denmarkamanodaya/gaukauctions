function allDayEvent()
{
    var allDay = $( "#all_day_event" ).val();

    if(allDay == 'yes') {
        $('.event_time').hide();
    } else {
        $('.event_time').show();
    }
}

$('#all_day_event').change(function() {
    allDayEvent();
});

function repeatEvent()
{
    var repeat = $( "#repeat_event" ).val();

    if(repeat === 'yes') {
        $('.repeat_event').show();
        repeatEventType();
    } else {
        $('.repeat_event_months').hide();
        $('.repeat_event_weeks').hide();
        $('#repeat_event_days').hide();
        $('.repeat_event').hide();
    }
}

function repeatEventType() {
    var repeat_type = $( "#repeat_type" ).val();

    if(repeat_type === 'daily') {
        $('.repeat_event_months').hide();
        $('.repeat_event_weeks').hide();
        $('.repeat_event_days').hide();
    }

    if(repeat_type === 'weekly') {
        $('.repeat_event_months').hide();
        $('.repeat_event_weeks').hide();
        $('.repeat_event_days').show();
    }

    if(repeat_type === 'monthly') {
        $('.repeat_event_months').hide();
        $('.repeat_event_weeks').show();
        $('.repeat_event_days').show();
    }
    if(repeat_type === 'yearly') {
        $('.repeat_event_months').show();
        $('.repeat_event_weeks').show();
        $('.repeat_event_days').show();
    }
}

$('#repeat_event').change(function() {
    repeatEvent();
    repeatEventType();
});

$('#repeat_type').change(function() {
    repeatEventType();
});



CKEDITOR.replace( 'description', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    height: 500
} );

$("#start_date").flatpickr({
    altInput: true,
    dateFormat: "Y-m-d",
    altFormat: "F j, Y"
});

$("#end_date").flatpickr({
    altInput: true,
    dateFormat: "Y-m-d",
    altFormat: "F j, Y"
});

$("#start_time").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});

$("#end_time").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});

var last_repeat = $('#additional-start-wrap div.repeat:last').attr('id');
last_repeat = last_repeat.split("-");
last_repeat = parseInt(last_repeat[0]) + 1;

$('.repeatable-add').click(function() {
    // Clone row
    $('#0-extra_start_dates').clone(true).appendTo('#additional-start-wrap');
    $('#additional-start-wrap div.repeat:last').removeClass( "no-display");
    $('#additional-start-wrap div.repeat:last').attr("id", last_repeat+'-repeatable');
    $('#additional-start-wrap div.repeat:last span').attr("id", last_repeat+'-repeatable');
    $('#additional-start-wrap div.repeat:last input#0-extra_start_dates').attr("id", last_repeat+'-extra_start_dates');
    $("#"+last_repeat+"-extra_start_dates").flatpickr({
        altInput: true,
        dateFormat: "Y-m-d",
        altFormat: "F j, Y"
    });
    last_repeat = last_repeat +1;

});

$('.repeatable-del').on('click',function(event) {
    var trid=  $(this).attr( "id" );
    trid = trid.split("-");
    trid = trid[0];
    $("#additional-start-wrap div#"+trid+"-repeatable").remove();
});


function setEventImage()
{
    var eventImage = $('#event_image').val();
    if(eventImage !='')
    {
        $("#thumbnail_event_image").attr("src",eventImage);
        $('#event_image_preview_wrap').show();
    } else {
        $('#event_image_remove').hide();
    }
}

$('#event_image_remove').click(function(){
    $('#event_image').val('');
    $("#thumbnail_event_image").attr("src",'');
});
$('#lfm2').filemanager('image', {prefix: '/filemanager'});


allDayEvent();
repeatEventType();
repeatEvent();
setEventImage();