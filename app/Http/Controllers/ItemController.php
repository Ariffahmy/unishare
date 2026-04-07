<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with base query for active items
        $query = Item::where('is_active', true);

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Apply category filter if provided
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Get filtered items, ordered by newest first
        $items = $query->latest()->get();

        return view('dashboard', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'nullable|string|max:50',
            'points_per_day' => 'required|integer|min:0',
            'max_days' => 'required|integer|min:1|max:365',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $validated['owner_id'] = auth()->id();
        $validated['is_active'] = true;

        // Remove photos from validated array before creating item
        $photos = $request->file('photos');
        unset($validated['photos']);

        $item = Item::create($validated);

        // Handle photo uploads
        if ($photos) {
            foreach ($photos as $index => $photo) {
                $path = $photo->store('item_photos', 'public');
                ItemPhoto::create([
                    'item_id' => $item->id,
                    'photo_path' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Item posted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        // Ensure user owns the item
        if ($item->owner_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'nullable|string|max:50',
            'points_per_day' => 'required|integer|min:0',
            'max_days' => 'required|integer|min:1|max:365',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Remove photos from validated array
        $photos = $request->file('photos');
        unset($validated['photos']);

        $item->update($validated);

        // Handle new photo uploads
        if ($photos) {
            $existingCount = $item->photos()->count();
            foreach ($photos as $index => $photo) {
                if ($existingCount + $index >= 5) break; // Max 5 photos
                
                $path = $photo->store('item_photos', 'public');
                ItemPhoto::create([
                    'item_id' => $item->id,
                    'photo_path' => $path,
                    'is_primary' => $existingCount === 0 && $index === 0,
                    'order' => $existingCount + $index,
                ]);
            }
        }

        return redirect()->route('items.my-items')->with('success', 'Item updated!');
    }

    /**
     * Delete a specific photo from an item
     */
    public function deletePhoto(Item $item, ItemPhoto $photo)
    {
        // Ensure user owns the item
        if ($item->owner_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Ensure photo belongs to item
        if ($photo->item_id !== $item->id) {
            return back()->with('error', 'Photo not found.');
        }

        // Delete file from storage
        if (Storage::exists('public/' . $photo->photo_path)) {
            Storage::delete('public/' . $photo->photo_path);
        }

        // If this was primary, make another photo primary
        $wasPrimary = $photo->is_primary;
        $photo->delete();

        if ($wasPrimary) {
            $newPrimary = $item->photos()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Photo deleted!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted!');
    }

    /**
     * Display the user's own items
     */
    public function myItems()
    {
        $items = Item::where('owner_id', auth()->id())
            ->latest()
            ->get();

        return view('items.my-items', compact('items'));
    }

    /**
     * Toggle item active status
     */
    public function toggleStatus(Item $item)
    {
        // Ensure user owns the item
        if ($item->owner_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $item->update(['is_active' => !$item->is_active]);

        $status = $item->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Item {$status} successfully!");
    }
}
