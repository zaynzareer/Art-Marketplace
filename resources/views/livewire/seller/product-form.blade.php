<div class="container mx-auto px-6 py-10">
    <div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-8">

        <h1 class="text-2xl font-bold mb-6">
            {{ $isEdit ? 'Update Product' : 'Add New Product' }}
        </h1>

        <form wire:submit.prevent="save" class="space-y-6">

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium">Product Name</label>
                <input type="text" wire:model.defer="name"
                    class="mt-1 w-full rounded-md border px-4 py-2">
                @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea rows="4" wire:model.defer="description"
                    class="mt-1 w-full rounded-md border px-4 py-2"></textarea>
            </div>

            {{-- Price --}}
            <div>
                <label class="block text-sm font-medium">Price (USD)</label>
                <input type="number" step="0.01" wire:model.defer="price"
                    class="mt-1 w-full rounded-md border px-4 py-2">
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium">Category</label>
                <select wire:model.defer="category"
                    class="mt-1 w-full rounded-md border px-4 py-2">
                    <option value="">Select category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Image --}}
            <div
                x-data="{ uploading: false, progress: 0 }"
                x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false"
                x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
            >
                <label class="block text-sm font-medium">Product Image</label>

                <input type="file" wire:model.live="image"
                    class="mt-2 block w-full text-sm">

                {{-- Progress Bar --}}
                <div x-show="uploading" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                        :style="{ width: progress + '%' }"></div>
                </div>
                <div x-show="uploading" x-text="progress + '%'" class="text-sm text-gray-500 mt-1"></div>


                {{-- Image preview --}}
                <div class="mt-4">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}"
                             class="h-40 rounded-md object-cover">
                    @elseif ($existingImage)
                        <img src="{{ asset('storage/' . $existingImage) }}"
                             class="h-40 rounded-md object-cover">
                    @endif
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col items-end">
                <button type="submit"
                    class="bg-black text-white px-6 py-2 rounded-md hover:opacity-90">
                    {{ $isEdit ? 'Update Product' : 'Add Product' }}
                </button>
                <div wire:loading wire:target="save" class="text-sm text-gray-500 mt-2">
                    Saving product, please wait...
                </div>
            </div>

        </form>
    </div>
</div>
