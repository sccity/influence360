<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\LegislativeCalendar\LegislativeActivityController as LegislativeCalendarActivityController;
use Webkul\Admin\Http\Controllers\LegislativeCalendar\LegislativeCalendarController;

Route::group(['prefix' => '/legislative-calendar'], function () {
    Route::controller(LegislativeCalendarController::class)->group(function () {
        Route::get('/', 'index')->name('admin.legislative-calendar.index');
        Route::get('view/{id}', 'view')->name('admin.legislative-calendar.view');
    });
});
