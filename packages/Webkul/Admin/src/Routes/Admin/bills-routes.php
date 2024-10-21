<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Bills\ActivityController as BillActivityController;
use Webkul\Admin\Http\Controllers\Bills\BillController;

Route::group(['prefix' => 'legislation'], function () {
    // Tracked Bills route
    Route::get('tracked-bills', [BillController::class, 'trackedBills'])->name('admin.bills.tracked');

    Route::group(['prefix' => 'bills'], function () {
        Route::controller(BillController::class)->group(function () {
            Route::get('/', 'index')->name('admin.bills.index');
            Route::get('create', 'create')->name('admin.bills.create');
            Route::post('create', 'store')->name('admin.bills.store');
            Route::get('view/{id}', 'view')->name('admin.bills.view');
            Route::get('edit/{id}', 'edit')->name('admin.bills.edit');
            Route::put('edit/{id}', 'update')->name('admin.bills.update');
            Route::delete('{id}', 'destroy')->name('admin.bills.delete');
            Route::post('mass-destroy', 'massDestroy')->name('admin.bills.mass_delete');
            Route::post('{id}/toggle-tracked', 'toggleTracked')->name('admin.bills.toggle-tracked');
        });

        Route::controller(BillActivityController::class)->group(function () {
            Route::get('{id}/activities', 'index')->name('admin.bills.activities.index');
        });
    });
});
