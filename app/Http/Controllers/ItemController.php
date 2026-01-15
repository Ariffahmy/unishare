<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::latest()->get();
        return view('items.index', compact('items'));
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
        ]);

        // temporary until you implement login
        $validated['owner_id'] = 1;
        $validated['is_active'] = true;

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'points_per_day' => 'required|integer|min:0',
            'max_days' => 'required|integer|min:1|max:365',
        ]);

    $item->update($validated);

    return redirect()->route('items.index')->with('success', 'Item updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted!');
    }
}
