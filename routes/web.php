<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('comments')
    ->name('comments.')
    ->controller(\Lara\Comment\Http\Controllers\CommentController::class)
    ->group(function () {
        Route::post('/{comment}/comments',  'store')
            ->name('comments.store');

        Route::put('/{comment}',  'update')
            ->name('update');

        Route::delete('/{comment}',  'destroy')
            ->name('destroy');
    });
