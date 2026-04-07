<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\BorrowRequest;
use App\Models\Penalty;
use App\Models\Rating;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin Dashboard with statistics
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_items' => Item::count(),
            'active_items' => Item::where('is_active', true)->count(),
            'total_requests' => BorrowRequest::count(),
            'pending_requests' => BorrowRequest::where('status', 'pending')->count(),
            'completed_transactions' => BorrowRequest::where('status', 'returned')->count(),
            'overdue_items' => BorrowRequest::whereIn('status', ['borrowed', 'overdue'])->whereDate('end_date', '<', now())->count(),
            'missing_items' => BorrowRequest::where('status', 'missing')->count(),
            'total_penalties' => Penalty::where('status', 'active')->count(),
            'total_ratings' => Rating::count(),
            'total_messages' => Message::count(),
        ];

        // Recent activity
        $recentUsers = User::latest()->take(5)->get();
        $recentItems = Item::with('owner')->latest()->take(5)->get();
        $recentRequests = BorrowRequest::with(['borrower', 'lender', 'item'])
            ->latest()
            ->take(5)
            ->get();

        // Chart data - registrations per day (last 7 days)
        $registrationData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentItems', 'recentRequests', 'registrationData'));
    }

    /**
     * User Management - List all users
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->input('status') === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->input('status') === 'suspended') {
                $query->where('is_suspended', true);
            } elseif ($request->input('status') === 'active') {
                $query->where('is_suspended', false);
            }
        }

        $users = $query->withCount(['items', 'borrowRequests', 'ratingsReceived'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * View user details
     */
    public function showUser(User $user)
    {
        $user->loadCount(['items', 'borrowRequests', 'ratingsReceived', 'ratingsGiven']);
        $user->load(['items' => fn($q) => $q->latest()->take(5)]);
        
        $borrowHistory = BorrowRequest::where('borrower_id', $user->id)
            ->orWhere('lender_id', $user->id)
            ->with(['item', 'borrower', 'lender'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.users.show', compact('user', 'borrowHistory'));
    }

    /**
     * Toggle admin status
     */
    public function toggleAdmin(User $user)
    {
        // Prevent removing own admin status
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot modify your own admin status.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'granted admin privileges' : 'removed from admin';
        return back()->with('success', "{$user->name} has been {$status}.");
    }

    /**
     * Suspend a user
     */
    public function suspendUser(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend yourself.');
        }

        if ($user->is_admin) {
            return back()->with('error', 'Cannot suspend an admin user. Remove admin status first.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user->update([
            'is_suspended' => true,
            'suspended_at' => now(),
            'suspension_reason' => $request->input('reason', 'No reason provided'),
        ]);

        return back()->with('success', "{$user->name} has been suspended.");
    }

    /**
     * Unsuspend a user
     */
    public function unsuspendUser(User $user)
    {
        $user->update([
            'is_suspended' => false,
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        return back()->with('success', "{$user->name} has been unsuspended.");
    }

    /**
     * Adjust user points
     */
    public function adjustPoints(Request $request, User $user)
    {
        $request->validate([
            'points' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]);

        $user->update([
            'points_balance' => $user->points_balance + $request->input('points'),
        ]);

        $action = $request->input('points') > 0 ? 'added' : 'deducted';
        $amount = abs($request->input('points'));

        return back()->with('success', "{$amount} points {$action} for {$user->name}.");
    }

    /**
     * Item Management - List all items
     */
    public function items(Request $request)
    {
        $query = Item::with('owner');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $items = $query->latest()->paginate(15);

        return view('admin.items.index', compact('items'));
    }

    /**
     * Toggle item active status
     */
    public function toggleItemStatus(Item $item)
    {
        $item->update(['is_active' => !$item->is_active]);

        $status = $item->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Item '{$item->title}' has been {$status}.");
    }

    /**
     * Delete an item
     */
    public function deleteItem(Item $item)
    {
        $title = $item->title;
        $item->delete();

        return redirect()->route('admin.items')->with('success', "Item '{$title}' has been deleted.");
    }

    /**
     * Borrow Request Management
     */
    public function borrowRequests(Request $request)
    {
        $query = BorrowRequest::with(['borrower', 'lender', 'item.photos']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->latest()->paginate(15);

        $statusCounts = [
            'all' => BorrowRequest::count(),
            'pending' => BorrowRequest::where('status', 'pending')->count(),
            'approved' => BorrowRequest::where('status', 'approved')->count(),
            'borrowed' => BorrowRequest::where('status', 'borrowed')->count(),
            'returned' => BorrowRequest::where('status', 'returned')->count(),
            'overdue' => BorrowRequest::where('status', 'overdue')->count(),
            'missing' => BorrowRequest::where('status', 'missing')->count(),
            'rejected' => BorrowRequest::where('status', 'rejected')->count(),
            'cancelled' => BorrowRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.borrow-requests.index', compact('requests', 'statusCounts'));
    }

    /**
     * Reports & Analytics
     */
    public function reports()
    {
        // Top lenders (most items)
        $topLenders = User::withCount('items')
            ->orderByDesc('items_count')
            ->take(10)
            ->get();

        // Top borrowers (most requests)
        $topBorrowers = User::withCount('borrowRequests')
            ->orderByDesc('borrow_requests_count')
            ->take(10)
            ->get();

        // Most popular items (most borrow requests)
        $popularItems = Item::with(['photos', 'owner'])
            ->withCount('borrowRequests')
            ->orderByDesc('borrow_requests_count')
            ->take(10)
            ->get();

        // Category distribution
        $categoryDistribution = Item::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        // Monthly transactions
        $monthlyTransactions = BorrowRequest::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports', compact(
            'topLenders', 
            'topBorrowers', 
            'popularItems', 
            'categoryDistribution',
            'monthlyTransactions'
        ));
    }

    /**
     * Penalties Management
     */
    public function penalties(Request $request)
    {
        $query = Penalty::with(['borrowRequest.item', 'borrower', 'reportedBy']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $penalties = $query->latest()->paginate(15);

        $typeCounts = [
            'all' => Penalty::count(),
            'late_return' => Penalty::where('type', 'late_return')->count(),
            'damaged' => Penalty::where('type', 'damaged')->count(),
            'missing' => Penalty::where('type', 'missing')->count(),
        ];

        $pendingCount = Penalty::where('status', 'pending')->count();
        $totalPenaltyPoints = Penalty::where('status', 'active')->sum('penalty_points');

        return view('admin.penalties.index', compact('penalties', 'typeCounts', 'totalPenaltyPoints', 'pendingCount'));
    }

    /**
     * Approve a pending penalty — deduct points and compensate lender
     */
    public function approvePenalty(Penalty $penalty)
    {
        if ($penalty->status !== Penalty::STATUS_PENDING) {
            return back()->with('error', 'This penalty is not pending review.');
        }

        $pointService = app(\App\Services\PointService::class);

        DB::transaction(function () use ($penalty, $pointService) {
            // Deduct penalty points from borrower
            try {
                $pointService->spend(
                    $penalty->borrower_id,
                    $penalty->penalty_points,
                    'penalty_' . $penalty->type,
                    "{$penalty->type_label} penalty for borrow request #{$penalty->borrow_request_id}",
                    $penalty->borrow_request_id
                );
            } catch (\RuntimeException $e) {
                // If insufficient points, deduct whatever is available
                $borrower = User::find($penalty->borrower_id);
                $actualDeduct = $borrower->points_balance;
                if ($actualDeduct > 0) {
                    $pointService->spend(
                        $penalty->borrower_id,
                        $actualDeduct,
                        'penalty_' . $penalty->type,
                        "{$penalty->type_label} penalty (partial) for borrow request #{$penalty->borrow_request_id}",
                        $penalty->borrow_request_id
                    );
                }
            }

            // Compensate lender for damage/missing
            if (in_array($penalty->type, [Penalty::TYPE_DAMAGED, Penalty::TYPE_MISSING])) {
                $borrowRequest = $penalty->borrowRequest;
                $pointService->add(
                    $borrowRequest->lender_id,
                    $penalty->penalty_points,
                    $penalty->type === Penalty::TYPE_DAMAGED ? 'damage_compensation' : 'missing_compensation',
                    "{$penalty->type_label} compensation for borrow request #{$penalty->borrow_request_id}",
                    $penalty->borrow_request_id
                );
            }

            // Update borrow request penalty points
            $borrowRequest = $penalty->borrowRequest;
            $borrowRequest->update([
                'penalty_points' => ($borrowRequest->penalty_points ?? 0) + $penalty->penalty_points,
            ]);

            // Mark penalty as active
            $penalty->update(['status' => Penalty::STATUS_ACTIVE]);
        });

        return back()->with('success', "Penalty approved! {$penalty->penalty_points} points deducted from {$penalty->borrower->name}.");
    }

    /**
     * Reject a pending penalty — no points deducted
     */
    public function rejectPenalty(Penalty $penalty)
    {
        if ($penalty->status !== Penalty::STATUS_PENDING) {
            return back()->with('error', 'This penalty is not pending review.');
        }

        $penalty->update(['status' => Penalty::STATUS_REJECTED]);

        // If it was a missing item report, revert the status back to borrowed/overdue
        if ($penalty->type === Penalty::TYPE_MISSING) {
            $borrowRequest = $penalty->borrowRequest;
            if ($borrowRequest->status === BorrowRequest::STATUS_MISSING) {
                $newStatus = $borrowRequest->isOverdue() ? BorrowRequest::STATUS_OVERDUE : BorrowRequest::STATUS_BORROWED;
                $borrowRequest->update(['status' => $newStatus]);
            }
        }

        return back()->with('success', 'Penalty report rejected. No points were deducted.');
    }
}

