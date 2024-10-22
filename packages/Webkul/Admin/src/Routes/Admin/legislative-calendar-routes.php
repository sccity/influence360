<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\LegislativeCalendar\LegislativeCalendarController;

Route::group(['prefix' => '/legislative-calendar'], function () {
    Route::controller(LegislativeCalendarController::class)->group(function () {
        Route::get('/', 'index')->name('admin.legislative-calendar.index');
        Route::get('view/{id}', 'view')->name('admin.legislative-calendar.view');
        Route::get('event/{id}', 'viewEvent')->name('admin.legislative-calendar.event.view');
        Route::get('get-events', 'getEvents')->name('admin.legislative-calendar.get-events');
    });
});
