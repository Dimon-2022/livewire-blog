<?php

use App\Livewire\Blog\Subscribe;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\User;
use App\Notifications\NewPostPublished;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('a user can subscribe to the blog', function () {
    $email = 'subscriber-' . uniqid() . '@example.com';

    Livewire::test(Subscribe::class)
        ->set('email', $email)
        ->call('subscribe');

    $this->assertDatabaseHas('subscribers', [
        'email' => $email,
    ]);
});

test('subscribers receive a notification when a new post is published', function () {
    Notification::fake();

    $email = 'subscriber-' . uniqid() . '@example.com';
    Subscriber::create([
        'email' => $email,
        'token' => 'test-token',
        'is_verified' => true
    ]);

    $author = User::factory()->create();
    $post = Post::create([
        'user_id' => $author->id,
        'title' => 'New Post',
        'slug' => 'new-post-' . uniqid(),
        'content' => 'Post body content',
        'status' => 'draft',
        'published_at' => now(),
    ]);

    $post->status = 'published';
    $post->save();

    Notification::assertSentTo(
        Subscriber::where('email', $email)->get(),
        NewPostPublished::class
    );
});

test('a user can unsubscribe', function () {
    $email = 'unsubscriber-' . uniqid() . '@example.com';

    $subscriber = Subscriber::create([
        'email' => $email,
    ]);

    $token = $subscriber->token;

    $this->assertDatabaseHas('subscribers', [
        'email' => $email,
        'token' => $token
    ]);

    $response = $this->get('/unsubscribe/' . $token);

    $response->assertStatus(200);
    $response->assertViewIs('unsubscribe');
    $this->assertDatabaseMissing('subscribers', [
        'email' => $email,
    ]);
});
