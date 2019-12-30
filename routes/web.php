<?php
//Member Routes
Route::group(['namespace' => 'Members', 'middleware' => ['auth']], function()
{
    Route::get('members/auctioneers/{clearFilters?}', ['as' => 'members_auctioneers', 'uses' => 'Dealers@index']);
    Route::post('members/auctioneers', ['as' => 'members_auctioneers', 'uses' => 'Dealers@search']);
    Route::get('members/auctioneer/{id}', ['as' => 'members_auctioneer_show', 'uses' => 'Dealers@show']);
    Route::get('members/auctioneer/{id}/report-a-problem', ['as' => 'members_auctioneer_show', 'uses' => 'Dealers@problem']);
    Route::post('members/auctioneer/{id}/report-a-problem', ['as' => 'members_auctioneer_show', 'uses' => 'Dealers@problemStore']);

    Route::get('members/auctioneer/{id}/event/{eventId}/{eventDate}', ['as' => 'members_auctioneer_show', 'uses' => 'Dealers@showEvent']);

    Route::post('members/ajax/getDealers', ['as' => 'members_ajax_getDealers', 'uses' => 'Ajax@getDealers']);

    Route::get('members/gavelbox', ['as' => 'members_gavelbox', 'uses' => 'GavelBox@index']);

    Route::group(['middleware' => ['premium']], function()
    {
        //dealer favourites
        Route::get('members/gavelbox/auctioneers', ['as' => 'members_gavelbox_shortlist_show', 'uses' => 'GavelBox@favouriteAuctioneers']);
        Route::get('members/auctioneer/{id}/favourite', ['as' => 'members_auctioneer_favourite', 'uses' => 'Dealers@favourite']);
        Route::post('members/auctioneer/{id}/favourite', ['as' => 'members_auctioneer_shortlist', 'uses' => 'Dealers@favourite']);

        //calendar
        Route::get('members/gavelbox/calendar', ['as' => 'members_mygarage_calendar', 'uses' => 'Calendar@calendar']);
        Route::post('members/gavelbox/calendar/getDaysEvents', ['as' => 'members_calendar_dayEvents', 'uses' => 'Calendar@getDay']);
        Route::post('members/gavelbox/calendar/getDaysEvents/monthly', ['as' => 'members_calendar_monthlyEvents', 'uses' => 'Calendar@getMonth']);

    });

});

//Admin Routes
Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function()
{
    //Dashboard
    Route::get('admin/dashboard', ['as' => 'admin_dashboard', 'uses' => 'Dashboard@index']);
    Route::get('admin/dashboard/ajax/users', ['as' => 'admin_dashboard_ajax_users', 'uses' => 'Dashboard@userWidget']);
    Route::get('admin/dashboard/ajax/tickets', ['as' => 'admin_dashboard_ajax_tickets', 'uses' => 'Dashboard@ticketWidget']);
    Route::get('admin/dashboard/ajax/newsletterCount', ['as' => 'admin_dashboard_ajax_newsletterCount', 'uses' => 'Dashboard@newsletterWidget']);

    Route::get('admin/dealers/auctioneers', ['as' => 'admin_auctioneers', 'uses' => 'Auctioneers@index']);
    Route::get('admin/dealers/auctioneers/data', ['as' => 'admin_auctioneers_data', 'uses' => 'Auctioneers@data']);
    Route::get('admin/dealers/auctioneer/create', ['as' => 'admin_auctioneer_create', 'uses' => 'Auctioneers@create']);
    Route::post('admin/dealers/auctioneer/create', ['as' => 'admin_auctioneer_create', 'uses' => 'Auctioneers@store']);
    Route::get('admin/dealers/auctioneer/{id}', ['as' => 'admin_auctioneer_show', 'uses' => 'Auctioneers@show']);
    Route::post('admin/dealers/auctioneer/{id}/delete', ['as' => 'admin_auctioneer_delete', 'uses' => 'Auctioneers@delete']);
    Route::get('admin/dealers/auctioneer/{id}/edit', ['as' => 'admin_auctioneer_edit', 'uses' => 'Auctioneers@edit']);
    Route::post('admin/dealers/auctioneer/{id}/edit', ['as' => 'admin_auctioneer_update', 'uses' => 'Auctioneers@update']);
    Route::post('admin/dealers/auctioneer/{id}/gallery/upload', ['as' => 'admin_auctioneer_gallery_upload', 'uses' => 'Auctioneers@galleryUpload']);
    Route::post('admin/dealers/auctioneer/{id}/gallery/delete', ['as' => 'admin_auctioneer_gallery_delete', 'uses' => 'Auctioneers@galleryDelete']);


    Route::get('admin/dealers/auctioneer/{id}/events', ['as' => 'admin_auctioneer_events', 'uses' => 'AuctioneerEvents@index']);
    Route::get('admin/dealers/auctioneer/{id}/event/create', ['as' => 'admin_auctioneer_event_create', 'uses' => 'AuctioneerEvents@create']);
    Route::post('admin/dealers/auctioneer/{id}/event/create', ['as' => 'admin_auctioneer_event_store', 'uses' => 'AuctioneerEvents@store']);
    Route::get('admin/dealers/auctioneer/{id}/event/{event}/edit', ['as' => 'admin_auctioneer_event_edit', 'uses' => 'AuctioneerEvents@edit']);
    Route::post('admin/dealers/auctioneer/{id}/event/{event}/edit', ['as' => 'admin_auctioneer_event_edit', 'uses' => 'AuctioneerEvents@update']);
    Route::post('admin/dealers/auctioneer/{id}/event/{event}/delete', ['as' => 'admin_auctioneer_event_edit', 'uses' => 'AuctioneerEvents@delete']);
    Route::get('admin/dealers/auctioneer/{id}/event/{event}/delete', ['as' => 'admin_auctioneer_event_edit', 'uses' => 'AuctioneerEvents@delete']);
    Route::get('admin/dealers/auctioneer/{id}/event/{event}/clone', ['as' => 'admin_auctioneer_event_clone', 'uses' => 'AuctioneerEvents@cloneEvent']);
    Route::post('admin/dealers/auctioneer/{id}/event/deleteSelected', ['as' => 'admin_auctioneer_event_deleteSelected', 'uses' => 'AuctioneerEvents@deleteSelectedEvents']);



    //Gauk Settings
    Route::get('admin/gauk-settings', ['as' => 'admin_gauksettings', 'uses' => 'GaukSettings@index']);
    Route::post('admin/gauk-settings', ['as' => 'admin_gauksettings_update', 'uses' => 'GaukSettings@update']);

    //FileManager
    Route::get('admin/filemanager', ['uses' => '\Unisharp\Laravelfilemanager\controllers\LfmController@show', 'as' => 'show']);
    Route::any('admin/filemanager/upload', ['uses' => '\Unisharp\Laravelfilemanager\controllers\UploadController@upload', 'as' => 'upload']);
    Route::get('admin/filemanager/jsonitems', ['uses' => '\Unisharp\Laravelfilemanager\controllers\ItemsController@getItems', 'as' => 'getItems']);
    Route::get('admin/filemanager/newfolder', ['uses' => '\Unisharp\Laravelfilemanager\controllers\FolderController@getAddfolder', 'as' => 'getAddfolder']);
    Route::get('admin/filemanager/deletefolder', ['uses' => '\Unisharp\Laravelfilemanager\controllers\FolderController@getDeletefolder', 'as' => 'getDeletefolder']);
    Route::get('admin/filemanager/folders', ['uses' => '\Unisharp\Laravelfilemanager\controllers\FolderController@getFolders', 'as' => 'getFolders']);
    Route::get('admin/filemanager/crop', ['uses' => '\Unisharp\Laravelfilemanager\controllers\CropController@getCrop', 'as' => 'getCrop']);
    Route::get('admin/filemanager/cropimage', ['uses' => '\Unisharp\Laravelfilemanager\controllers\CropController@getCropimage', 'as' => 'getCropimage']);
    Route::get('admin/filemanager/rename', ['uses' => '\Unisharp\Laravelfilemanager\controllers\RenameController@getRename', 'as' => 'getRename']);
    Route::get('admin/filemanager/resize', ['uses' => '\Unisharp\Laravelfilemanager\controllers\ResizeController@getResize', 'as' => 'getResize']);
    Route::get('admin/filemanager/doresize', ['uses' => '\Unisharp\Laravelfilemanager\controllers\ResizeController@performResize', 'as' => 'performResize']);
    Route::get('admin/filemanager/download', ['uses' => '\Unisharp\Laravelfilemanager\controllers\DownloadController@getDownload', 'as' => 'getDownload']);
    Route::get('admin/filemanager/delete', ['uses' => '\Unisharp\Laravelfilemanager\controllers\DeleteController@getDelete', 'as' => 'getDelete']);

    Route::get('admin/cache/settings', ['as' => 'admin_cache_setting', 'uses' => 'CacheSettings@index']);
    Route::get('admin/cache/settings/clear', ['as' => 'admin_cache_setting/clear', 'uses' => 'CacheSettings@clearAll']);

    //Filemanager
    Route::get('admin/filemanager', ['as' => 'admin_filemanager', 'uses' => 'Filemanager@index']);
    Route::get('admin/filemanager/jsonitems', ['as' => 'admin_filemanager_jsonitems', 'uses' => 'Filemanager@jsonitems']);
    Route::get('admin/filemanager/errors', ['as' => 'admin_filemanager_getErrors', 'uses' => 'Filemanager@getErrors']);
    Route::post('admin/filemanager/upload', ['as' => 'admin_filemanager_upload', 'uses' => 'Filemanager@upload']);

    //Calendar
    Route::get('admin/calendar/import', ['as' => 'admin_calendar_import', 'uses' => 'CalendarImport@index']);
    Route::post('admin/calendar/import', ['as' => 'admin_calendar_import', 'uses' => 'CalendarImport@create']);
    Route::get('admin/calendar/importFromFile', ['as' => 'admin_calendar_importFromFile', 'uses' => 'CalendarImport@importFromFile']);
    Route::post('admin/calendar/import/markComplete', ['as' => 'admin_calendar_markComplete', 'uses' => 'CalendarImport@markComplete']);

    //Auctioneer Import
    Route::get('admin/auctioneerimport/importFromFile', ['as' => 'admin_auctioneerimport_importFromFile', 'uses' => 'AuctioneerImport@importFromFile']);
    Route::get('admin/auctioneerimport/updateFromFile', ['as' => 'admin_auctioneerimport_updateFromFile', 'uses' => 'AuctioneerImport@updateFromFile']);

    //Dealer Features
    Route::get('admin/dealers/features', ['as' => 'admin_dealers_features', 'uses' => 'DealerFeatures@index']);
    Route::post('admin/dealers/features', ['as' => 'admin_dealers_features_create', 'uses' => 'DealerFeatures@store']);
    Route::get('admin/dealers/features/{id}', ['as' => 'admin_dealers_features_show', 'uses' => 'DealerFeatures@show']);
    Route::post('admin/dealers/features/{id}', ['as' => 'admin_dealers_features_update', 'uses' => 'DealerFeatures@update']);
    Route::post('admin/dealers/features/{id}/delete', ['as' => 'admin_dealers_features_delete', 'uses' => 'DealerFeatures@delete']);

    //Dealer Categories
    Route::get('admin/dealers/categories', ['as' => 'admin_categories', 'uses' => 'DealerCategories@index']);
    Route::post('admin/dealers/categories/create', ['as' => 'admin_categories', 'uses' => 'DealerCategories@store']);
    Route::get('admin/dealers/category/{id}', ['as' => 'admin_category_show', 'uses' => 'DealerCategories@show']);
    Route::get('admin/dealers/category/{id}/test', ['as' => 'admin_category_show', 'uses' => 'DealerCategories@showtest']);
    Route::post('admin/dealers/category/{id}/delete', ['as' => 'admin_category_delete', 'uses' => 'DealerCategories@delete']);
    Route::post('admin/dealers/category/{id}/update', ['as' => 'admin_category_update', 'uses' => 'DealerCategories@update']);
    Route::post('admin/dealers/category/{id}/create-child', ['as' => 'admin_category_create_child', 'uses' => 'DealerCategories@storeChild']);
    Route::get('admin/dealers/category/{id}/child/{child}', ['as' => 'admin_category_child_edit', 'uses' => 'DealerCategories@editChild']);
    Route::post('admin/dealers/category/{id}/child/{child}/update', ['as' => 'admin_category_child_edit', 'uses' => 'DealerCategories@updateChild']);
    Route::post('admin/dealers/category/{id}/child/{child}/delete', ['as' => 'admin_category_child_edit', 'uses' => 'DealerCategories@deleteChild']);

    //Dealer Auction Addresses
    Route::get('admin/dealers/auctioneer/{id}/address/create', ['as' => 'admin_auctioneer_address_create', 'uses' => 'Auctioneers@addressCreate']);
    Route::post('admin/dealers/auctioneer/{id}/address/create', ['as' => 'admin_auctioneer_address_create', 'uses' => 'Auctioneers@addressStore']);
    Route::get('admin/dealers/auctioneer/{id}/address/{address}/edit', ['as' => 'admin_auctioneer_address_edit', 'uses' => 'Auctioneers@addressEdit']);
    Route::post('admin/dealers/auctioneer/{id}/address/{address}/edit', ['as' => 'admin_auctioneer_address_update', 'uses' => 'Auctioneers@addressUpdate']);
    Route::post('admin/dealers/auctioneer/{id}/address/{address}/delete', ['as' => 'admin_auctioneer_address_delete', 'uses' => 'Auctioneers@addressDelete']);
    Route::post('admin/dealers/auctioneer/{id}/getAddress', ['as' => 'admin_auctioneer_getAddress', 'uses' => 'Auctioneers@getAddress']);

    //Event Reminders
    Route::get('admin/dealers/eventReminders', ['as' => 'admin_eventReminders', 'uses' => 'EventReminders@index']);
    Route::get('admin/dealers/eventReminders/due', ['as' => 'admin_eventReminders_due', 'uses' => 'EventReminders@due']);
    Route::get('admin/dealers/eventReminders/{id}/delete', ['as' => 'admin_eventReminders_delete', 'uses' => 'EventReminders@delete']);
    Route::get('admin/dealers/eventReminders/due/{id}/delete', ['as' => 'admin_eventReminders_delete_due', 'uses' => 'EventReminders@deleteDue']);
    Route::post('admin/dealers/eventReminders/due/deleteSelected', ['as' => 'admin_eventReminders_deleteSelectedDue', 'uses' => 'EventReminders@deleteSelectedDue']);
    Route::post('admin/dealers/eventReminders/deleteSelected', ['as' => 'admin_eventReminders_deleteSelected', 'uses' => 'EventReminders@deleteSelected']);

    //Dealer Problems
    Route::get('admin/dealers/problems', ['as' => 'admin_dealers_problems', 'uses' => 'DealerProblems@index']);
    Route::get('admin/dealers/problem/{id}', ['as' => 'admin_dealers_problem_show', 'uses' => 'DealerProblems@show']);
    Route::get('admin/dealers/problem/{id}/delete', ['as' => 'admin_dealers_problem_show', 'uses' => 'DealerProblems@delete']);

    //user import
    //Route::get('admin/importUsers', ['as' => 'admin_dealers_problems', 'uses' => 'UserImport@index']);

});

//public routes

Route::group(['namespace' => 'Frontend'], function()
{
    Route::get('auctioneers/{clearFilters?}', ['as' => 'public_auctioneers', 'uses' => 'Dealers@index']);
    Route::post('auctioneers', ['as' => 'public_auctioneers', 'uses' => 'Dealers@search']);
    Route::get('auctioneers', ['as' => 'public_auctioneers', 'uses' => 'Dealers@index']);
    Route::get('auctioneer/{id}', ['as' => 'public_auctioneer_show', 'uses' => 'Dealers@show']);


    //temp export
    Route::get('tempexport/categories/{apikey}', ['as' => 'temp_export_categories', 'uses' => 'Export@categories']);
    Route::get('tempexport/features/{apikey}', ['as' => 'temp_export_features', 'uses' => 'Export@features']);
    Route::get('tempexport/dealers/{apikey}', ['as' => 'temp_export_dealers', 'uses' => 'Export@dealers']);
});
