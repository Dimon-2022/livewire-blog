<?php

use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Создаем разрешения, если их нет
    if (Permission::where('name', 'manage roles')->count() === 0) {
        Permission::create(['name' => 'manage roles']);
    }

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('manage roles');
});

test('categories index page is displayed', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('categories.index'));

    $response->assertOk();
});

test('categories can be created', function () {
    $this->actingAs($this->user);

    $name = 'New Category ' . uniqid();

    Livewire::test('pages::categories.create')
        ->set('name', $name)
        ->set('description', 'Category Description')
        ->set('color', '#123456')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => $name,
        'description' => 'Category Description',
        'color' => '#123456',
    ]);
});

test('categories can be updated', function () {
    $category = Category::create([
        'name' => 'Old Category ' . uniqid(),
        'slug' => 'old-category-' . uniqid(),
        'color' => '#ffffff'
    ]);

    $this->actingAs($this->user);

    $newName = 'Updated Category ' . uniqid();

    Livewire::test('pages::categories.edit', ['category' => $category])
        ->set('name', $newName)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => $newName,
    ]);
});

test('categories can be deleted', function () {
    $category = Category::create([
        'name' => 'To Be Deleted',
        'slug' => 'to-be-deleted',
        'color' => '#000000'
    ]);

    $this->actingAs($this->user);

    Livewire::test('pages::categories.index')
        ->call('deleteCategory', $category->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});
