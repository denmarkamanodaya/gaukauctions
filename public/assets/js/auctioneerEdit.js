CKEDITOR.replace( 'details', {
    filebrowserImageBrowseUrl: '/filemanager?type=Images',
    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/filemanager?type=Files',
    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
    uploadUrl: '/admin/filemanager/upload?_token='+csrf_token,
    height: 300
} );


function onlineOnlyValue()
{
    if($('#online_only').is(":checked")) {
        $('#not_online_only').hide();
    } else {
        $('#not_online_only').show();
    }
}


$('#online_only').on('change', function (e) {
    onlineOnlyValue();
});
onlineOnlyValue();