<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    /**
     * Show the user's penalty history
     */
    public function index()
    {
        $user = auth()->user();

        $penalties = Penalty::where('borrower_id', $user->id)
            ->with(['borrowRequest.item', 'reportedBy'])
            ->latest()
            ->get();

        $totalPoints = $penalties->sum('penalty_points');
        $activePenalties = $penalties->where('status', 'active');
        
        $typeCounts = [
            'late_return' => $penalties->where('type', 'late_return')->count(),
            'damaged'     => $penalties->where('type', 'damaged')->count(),
            'missing'     => $penalties->where('type', 'missing')->count(),
        ];

        return view('penalties.index', compact(
            'penalties', 'totalPoints', 'activePenalties', 'typeCounts'
        ));
    }
}
