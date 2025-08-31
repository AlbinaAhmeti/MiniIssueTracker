<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\IssueMemberController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueTagController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects'); 

Route::resource('projects', ProjectController::class);

Route::resource('projects.issues', IssueController::class)->shallow();

Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');

Route::resource('tags', TagController::class)->only(['index','store']);

Route::get('/issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
Route::post('/issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

Route::post('/issues/{issue}/tags', [IssueTagController::class, 'attach'])->name('issues.tags.attach');
Route::delete('/issues/{issue}/tags/{tag}', [IssueTagController::class, 'detach'])->name('issues.tags.detach');

Route::resource('projects', ProjectController::class)->only(['index','show']);
Route::resource('issues',   IssueController::class)->only(['index','show']);
Route::resource('tags',     TagController::class)->only(['index','show']);

Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class)->except(['index','show']);
    Route::resource('issues',   IssueController::class)->except(['index','show']);
    Route::resource('tags',     TagController::class)->except(['index','show']);
});


Route::post('/issues/{issue}/members', [IssueMemberController::class, 'attach'])
    ->name('issues.members.attach');

Route::delete('/issues/{issue}/members/{user}', [IssueMemberController::class, 'detach'])
    ->name('issues.members.detach');

Route::post('/issues/{issue}/tags', [IssueTagController::class, 'attach'])
    ->middleware('auth')->name('issues.tags.attach');

Route::delete('/issues/{issue}/tags/{tag}', [IssueTagController::class, 'detach'])
    ->middleware('auth')->name('issues.tags.detach');

Route::post('/issues/{issue}/members', [IssueMemberController::class, 'attach'])
    ->middleware('auth')->name('issues.members.attach');

Route::delete('/issues/{issue}/members/{user}', [IssueMemberController::class, 'detach'])
    ->middleware('auth')->name('issues.members.detach');

    Route::get('/issues/search', [IssueController::class, 'search'])->name('issues.search');

require __DIR__.'/auth.php';
