$(document).ready(function(){
    $(document).on('click', '.gavelBoxFavourite', function(e){
        var urlTo = $(this).attr('href');
        var favourite = this;
        e.preventDefault();
        $.ajax(
            {
                url : urlTo,
                type: "POST",
                data : { _token: csrf_token}
            }).done(function(data) {
            if(data.type == 'add') {
                $(favourite).find('i').removeClass('icon-heart-o');
                $(favourite).find('i').addClass('icon-heart');
            }
            if(data.type == 'remove') {
                $(favourite).find('i').removeClass('icon-heart');
                $(favourite).find('i').addClass('icon-heart-o');
            }
            $.notify({
                // options
                icon: 'fa  fa-check-circle',
                message: '&nbsp;'+data.message
        },{
                // settings
                type: 'success',
                    animate: {
                    enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                }
            });
        });


    });
});