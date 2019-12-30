function updateFeatureSlug(location)
{
    var itemTitle = $( "#"+location+" #name" ).val();
    slug = convertToSlug(itemTitle);
    $( "#"+location+" #slug" ).val(slug);

}

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'');
}

$('#feature-create #name').keyup(function() {
    updateFeatureSlug('feature-create');
});

$('#feature-update #name').keyup(function() {
    updateFeatureSlug('feature-update');
});