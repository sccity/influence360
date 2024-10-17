<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Initiative\ActivityController;
use Webkul\Admin\Http\Controllers\Initiative\EmailController;
use Webkul\Admin\Http\Controllers\Initiative\InitiativeController;
use Webkul\Admin\Http\Controllers\Initiative\QuoteController;
use Webkul\Admin\Http\Controllers\Initiative\TagController;

Route::controller(InitiativeController::class)->prefix('initiatives')->group(function () {
    Route::get('', 'index')->name('admin.initiatives.index');

    Route::get('create', 'create')->name('admin.initiatives.create');

    Route::post('create', 'store')->name('admin.initiatives.store');

    Route::get('view/{id}', 'view')->name('admin.initiatives.view');

    Route::get('edit/{id}', 'edit')->name('admin.initiatives.edit');

    Route::put('edit/{id}', 'update')->name('admin.initiatives.update');

    Route::put('attributes/edit/{id}', 'updateAttributes')->name('admin.initiatives.attributes.update');

    Route::put('stage/edit/{id}', 'updateStage')->name('admin.initiatives.stage.update');

    Route::get('search', 'search')->name('admin.initiatives.search');

    Route::delete('{id}', 'destroy')->name('admin.initiatives.delete');

    Route::post('mass-update', 'massUpdate')->name('admin.initiatives.mass_update');

    Route::post('mass-destroy', 'massDestroy')->name('admin.initiatives.mass_delete');

    Route::get('get/{pipeline_id?}', 'get')->name('admin.initiatives.get');

    Route::delete('product/{initiative_id}', 'removeProduct')->name('admin.initiatives.product.remove');

    Route::put('product/{initiative_id}', 'addProduct')->name('admin.initiatives.product.add');

    Route::get('kanban/look-up', [InitiativeController::class, 'kanbanLookup'])->name('admin.initiatives.kanban.look_up');

    Route::controller(ActivityController::class)->prefix('{id}/activities')->group(function () {
        Route::get('', 'index')->name('admin.initiatives.activities.index');
    });

    Route::controller(TagController::class)->prefix('{id}/tags')->group(function () {
        Route::post('', 'attach')->name('admin.initiatives.tags.attach');

        Route::delete('', 'detach')->name('admin.initiatives.tags.detach');
    });

    Route::controller(EmailController::class)->prefix('{id}/emails')->group(function () {
        Route::post('', 'store')->name('admin.initiatives.emails.store');

        Route::delete('', 'detach')->name('admin.initiatives.emails.detach');
    });

    Route::controller(QuoteController::class)->prefix('{id}/quotes')->group(function () {
        Route::delete('{quote_id?}', 'delete')->name('admin.initiatives.quotes.delete');
    });
});
