<?php

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;

new class extends Component {
    public Category $category;

    public string $name = '';
    public string $description = '';
    public string $color = '#4F46E5';

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->color = $category->color ?? '#4F46E5';
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:categories,name,' . $this->category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'color' => $this->color,
        ]);

        session()->flash('success', 'Category updated successfully!');

        $this->redirect(route('categories.index'), navigate: true);
    }
};
?>

<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Category: {{ $category->name }}</h1>
        <p class="mt-1 text-sm text-gray-600">Update category details</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    type="text"
                    id="name"
                    wire:model.live.debounce="name"
                    placeholder="Category name"
                    autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                    id="description"
                    wire:model="description"
                    rows="3"
                    placeholder="Short description of the category"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color -->
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                <div class="mt-1 flex items-center gap-3">
                    <input
                        type="color"
                        id="color"
                        wire:model="color"
                        class="h-8 w-8 rounded border-gray-300"
                    />
                    <input
                        type="text"
                        wire:model.live="color"
                        placeholder="#4F46E5"
                        class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>
                @error('color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}" wire:navigate class="text-sm font-medium text-gray-700 hover:text-gray-900">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
