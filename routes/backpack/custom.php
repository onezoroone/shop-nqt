<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('category', 'CategoryCrudController');
    Route::crud('project', 'ProjectCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('skill', 'SkillCrudController');
    Route::crud('contact', 'ContactCrudController');
    Route::post('contact/{id}/mark-read', 'ContactCrudController@markAsRead')->name('contact.markAsRead');
    Route::crud('setting', 'SettingCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
