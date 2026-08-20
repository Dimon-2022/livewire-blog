<?php

use App\Models\Tag;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;

new class extends Component {
    #[Validate('required|string|min:2|max:255|unique:tags,name')]
    public string $name = '';

    public function save()
    {
        $this->validate();

        Tag::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);

        session()->flash('success', 'Tag created successfully!');

        $this->redirect(route('tags.index'), navigate: true);
    }
};
?>

<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New Tag</h1>
        <p class="mt-1 text-sm text-gray-600">Add a new tag for your blog posts</p>
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
                    placeholder="Tag name"
                    autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('tags.index') }}" wire:navigate class="text-sm font-medium text-gray-700 hover:text-gray-900">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Create Tag
                </button>
            </div>
        </form>
    </div>
</div>
