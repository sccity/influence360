<?php 

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\BillFiles\ActivityController as BillFileActivityController;
use Webkul\Admin\Http\Controllers\BillFiles\BillFileController;

Route::group(['prefix' => 'bill-files'], function () {
    Route::controller(BillFileController::class)->group(function () {
        Route::get('/', 'index')->name('admin.bill-files.index');
        Route::get('create', 'create')->name('admin.bill-files.create');
        Route::post('create', 'store')->name('admin.bill-files.store');
        Route::get('view/{id}', 'view')->name('admin.bill-files.view');
        Route::get('edit/{id}', 'edit')->name('admin.bill-files.edit');
        Route::put('edit/{id}', 'update')->name('admin.bill-files.update');
        Route::delete('{id}', 'destroy')->name('admin.bill-files.delete');
        Route::post('mass-destroy', 'massDestroy')->name('admin.bill-files.mass_delete');
        
        // Route for toggling is_tracked status
        Route::post('{id}/toggle-tracked', 'toggleTracked')->name('admin.bill-files.toggle-tracked');
    });

    Route::controller(BillFileActivityController::class)->group(function () {
        // Existing route for activities index
        Route::get('{id}/activities', 'index')->name('admin.bill-files.activities.index');

        // New route for storing bill file mail activity
        Route::post('mail-activity', 'storeBillFileMailActivity')->name('admin.bill-files.mail-activity.store');
    });
});
