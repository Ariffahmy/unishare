@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('items.my-items') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-purple-600 mb-4 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to My Items
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Item</h1>
        <p class="mt-2 text-sm text-gray-600">Update your item details and photos.</p>
    </div>

    <div class="glass-card rounded-xl p-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were issues with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Existing Photos Section -->
        @if($item->photos->count() > 0)
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-3">Current Photos</label>
                <div class="grid grid-cols-5 gap-4">
                    @foreach($item->photos as $photo)
                        <div class="relative group aspect-square">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                 class="w-full h-full object-cover rounded-lg border border-gray-200">
                            @if($photo->is_primary)
                                <span class="absolute top-1 left-1 px-2 py-0.5 text-xs font-medium bg-purple-600 text-white rounded">Primary</span>
                            @endif
                            <form action="{{ route('items.delete-photo', [$item, $photo]) }}" method="POST" 
                                  class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Delete this photo?')"
                                        class="p-1 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-gray-500">Click the X to remove a photo. The first photo is the primary display image.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Item Title</label>
                <div class="mt-2">
                    <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-3 px-4" 
                        placeholder="e.g. Scientific Calculator, Lab Coat, Textbook">
                </div>
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <div class="mt-2">
                    <select name="category" id="category" 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-3 px-4">
                        <option value="">Select a category</option>
                        <option value="Electronics" {{ old('category', $item->category) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                        <option value="Books & Textbooks" {{ old('category', $item->category) == 'Books & Textbooks' ? 'selected' : '' }}>Books & Textbooks</option>
                        <option value="Clothing & Accessories" {{ old('category', $item->category) == 'Clothing & Accessories' ? 'selected' : '' }}>Clothing & Accessories</option>
                        <option value="Sports Equipment" {{ old('category', $item->category) == 'Sports Equipment' ? 'selected' : '' }}>Sports Equipment</option>
                        <option value="Lab Equipment" {{ old('category', $item->category) == 'Lab Equipment' ? 'selected' : '' }}>Lab Equipment</option>
                        <option value="Furniture" {{ old('category', $item->category) == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="Kitchen & Dining" {{ old('category', $item->category) == 'Kitchen & Dining' ? 'selected' : '' }}>Kitchen & Dining</option>
                        <option value="Other" {{ old('category', $item->category) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <div class="mt-2">
                    <textarea id="description" name="description" rows="4" 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-3 px-4" 
                        placeholder="Describe the item's condition, features, and any other important details...">{{ old('description', $item->description) }}</textarea>
                </div>
            </div>

            <!-- Two Column Grid for Condition & Points -->
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700">Condition</label>
                    <div class="mt-2">
                        <select id="condition" name="condition" 
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-3 px-4">
                            <option value="">Select condition</option>
                            <option value="New" {{ old('condition', $item->condition) == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Good" {{ old('condition', $item->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('condition', $item->condition) == 'Fair' ? 'selected' : '' }}>Fair</option>
                            <option value="Poor" {{ old('condition', $item->condition) == 'Poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="points_per_day" class="block text-sm font-medium text-gray-700">Points per Day</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <input type="number" name="points_per_day" id="points_per_day" value="{{ old('points_per_day', $item->points_per_day) }}" min="0" 
                            class="block w-full rounded-lg border-gray-300 pl-4 pr-12 py-3 focus:border-purple-500 focus:ring-purple-500 sm:text-sm" placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm font-medium">pts</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Max Days -->
            <div>
                <label for="max_days" class="block text-sm font-medium text-gray-700">Maximum Borrow Duration (Days)</label>
                <div class="mt-2">
                    <input type="number" name="max_days" id="max_days" value="{{ old('max_days', $item->max_days) }}" min="1" max="365" 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-3 px-4">
                    <p class="mt-1 text-xs text-gray-500">The longest period you're willing to lend this item for.</p>
                </div>
            </div>

            <!-- Add More Photos -->
            @php
                $remainingSlots = 5 - $item->photos->count();
            @endphp
            @if($remainingSlots > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Add More Photos ({{ $remainingSlots }} slots available)</label>
                    <div class="mt-2">
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photos" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                                        <span>Upload photos</span>
                                        <input id="photos" name="photos[]" type="file" class="sr-only" multiple accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB each</p>
                            </div>
                        </div>
                        <div id="photo-preview" class="mt-4 grid grid-cols-5 gap-4"></div>
                    </div>
                    @error('photos.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Actions -->
            <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-100 mt-6">
                <a href="{{ route('items.my-items') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-sm transition-all duration-200 transform hover:scale-[1.02]">
                    Update Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('photos')?.addEventListener('change', function(e) {
    const preview = document.getElementById('photo-preview');
    preview.innerHTML = '';
    
    const maxFiles = {{ $remainingSlots }};
    const files = Array.from(e.target.files).slice(0, maxFiles);
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative aspect-square';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg border border-gray-200">
                <span class="absolute top-1 left-1 px-2 py-0.5 text-xs font-medium bg-green-600 text-white rounded">New</span>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection

