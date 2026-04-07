<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function show()
    {
        $user = Auth::user();
        
        // Load relationships
        $user->load(['likedItems', 'borrowRequests.item', 'items']);
        
        // Get borrow history (completed requests)
        $borrowHistory = $user->borrowRequests()
            ->with(['item', 'lender'])
            ->latest()
            ->get();

        // Get penalties received by this user
        $penalties = \App\Models\Penalty::where('borrower_id', $user->id)
            ->with(['borrowRequest.item', 'reportedBy'])
            ->latest()
            ->get();
        
        return view('profile.show', compact('user', 'borrowHistory', 'penalties'));
    }

    /**
     * Show the form for editing the user's profile
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Toggle like on an item
     */
    public function toggleLike($itemId)
    {
        $user = Auth::user();
        
        // Check if already liked
        if ($user->likedItems()->where('item_id', $itemId)->exists()) {
            // Unlike
            $user->likedItems()->detach($itemId);
            return back()->with('success', 'Item removed from favorites');
        } else {
            // Like
            $user->likedItems()->attach($itemId);
            return back()->with('success', 'Item added to favorites');
        }
    }
}
