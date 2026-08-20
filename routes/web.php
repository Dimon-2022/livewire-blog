<?php

use App\Livewire\PostList;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', 'pages::dashboard')->name('dashboard');
});

Route::get('/blog', PostList::class)->name('blog.index');
Route::livewire('/blog/{slug}', 'pages::posts.show')->name('blog.show');

Route::get('/unsubscribe/{token}', function ($token) {
    $subscriber = Subscriber::where('token', $token)->first();
    if($subscriber){
        $subscriber->delete();
        return view('unsubscribe');
    }
    abort(404);
})->name('unsubscribe');

Route::middleware('auth')->group(function () {
    Route::livewire('/posts', 'pages::posts.index')->name('posts.index')->middleware('can:create posts');

    Route::livewire('/posts/create', 'pages::posts.create')->middleware('can:create posts')->name('posts.create');

    Route::livewire('/posts/{post}/edit', 'pages::posts.edit')->name('posts.edit');

    Route::livewire('/users', 'pages::users.index')->name('users.index')->middleware('can:manage users');

    Route::livewire('/users/create', 'pages::users.create')->middleware('can:manage users')->name('users.create');

    Route::livewire('/users/{user}/edit', 'pages::users.edit')->middleware('can:manage users')->name('users.edit');

    // Categories routes
    Route::livewire('/categories', 'pages::categories.index')
        ->middleware('can:manage roles')
        ->name('categories.index');

    Route::livewire('/categories/create', 'pages::categories.create')
        ->middleware('can:manage roles')
        ->name('categories.create');

    Route::livewire('/categories/{category}/edit', 'pages::categories.edit')
        ->middleware('can:manage roles')
        ->name('categories.edit');

    // Tags routes
    Route::livewire('/tags', 'pages::tags.index')
        ->middleware('can:manage roles')
        ->name('tags.index');

    Route::livewire('/tags/create', 'pages::tags.create')
        ->middleware('can:manage roles')
        ->name('tags.create');

    Route::livewire('/tags/{tag}/edit', 'pages::tags.edit')
        ->middleware('can:manage roles')
        ->name('tags.edit');

    Route::livewire('/comments', 'pages::comments.index')
        ->middleware('can:create posts')
        ->name('comments.index');

    require __DIR__.'/settings.php';
});

