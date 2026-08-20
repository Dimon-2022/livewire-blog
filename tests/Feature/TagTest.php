<?php

use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    if (Permission::where('name', 'manage roles')->count() === 0) {
        Permission::create(['name' => 'manage roles']);
    }

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('manage roles');
});

test('tags index page is displayed', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('tags.index'));

    $response->assertOk();
});

test('tags can be created', function () {
    $this->actingAs($this->user);

    $name = 'New Tag ' . uniqid();

    Livewire::test('pages::tags.create')
        ->set('name', $name)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseHas('tags', [
        'name' => $name,
    ]);
});

test('tags can be updated', function () {
    $tag = Tag::create([
        'name' => 'Old Tag ' . uniqid(),
        'slug' => 'old-tag-' . uniqid(),
    ]);

    $this->actingAs($this->user);

    $newName = 'Updated Tag ' . uniqid();

    Livewire::test('pages::tags.edit', ['tag' => $tag])
        ->set('name', $newName)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => $newName,
    ]);
});

test('tags can be deleted', function () {
    $tag = Tag::create([
        'name' => 'To Be Deleted',
        'slug' => 'to-be-deleted',
    ]);

    $this->actingAs($this->user);

    Livewire::test('pages::tags.index')
        ->call('deleteTag', $tag->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('tags', [
        'id' => $tag->id,
    ]);
});
