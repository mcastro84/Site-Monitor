<?php

// post
Route::post('add-url', ['uses' => 'WebsiteMonitorController@addUrl' ,'as' => 'website-monitor-add-url']);
Route::post('remove-url', ['uses' => 'WebsiteMonitorController@removeUrl' ,'as' => 'website-monitor-remove-url']);

// get
Route::get('check-urls', ['uses' => 'WebsiteMonitorController@checkUrls' ,'as' => 'website-monitor-check-urls']);