<?php

use Illuminate\Support\Facades\Route;

Route::get('release-notes/download', [\Sideso\ReleaseNotes\Http\Controllers\ReleaseNotesController::class, 'download'])
    ->middleware(['auth', 'signed'])
    ->name('release-notes.download');
