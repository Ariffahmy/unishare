@extends('layouts.admin')

@section('title', 'User Details: ' . $user->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-purple-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Users
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- User Info Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold mx-auto">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
                
                <div class="mt-4 flex justify-center space-x-2">
                    @if($user->is_admin)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">Admin</span>
                    @endif
                    @if($user->is_suspended)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Suspended</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Active</span>
                    @endif
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-500">Phone</span>
                    <span class="font-medium text-gray-900">{{ $user->phone_number ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-500">Location</span>
                    <span class="font-medium text-gray-900">{{ $user->location ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-500">Points Balance</span>
                    <span class="font-medium text-gray-900">{{ $user->points_balance ?? 0 }} pts</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100">
                    <span class="text-gray-500">Joined</span>
                    <span class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                @if($user->average_rating > 0)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-500">Rating</span>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $user->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="ml-1 text-sm">({{ $user->ratings_received_count }})</span>
                        </div>
                    </div>
                @endif
            </div>

            @if($user->bio)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Bio</h4>
                    <p class="text-gray-700">{{ $user->bio }}</p>
                </div>
            @endif

            @if($user->is_suspended)
                <div class="mt-6 p-4 bg-red-50 rounded-lg">
                    <h4 class="text-sm font-medium text-red-800 mb-1">Suspension Details</h4>
                    <p class="text-sm text-red-600">Suspended: {{ $user->suspended_at ? $user->suspended_at->format('M d, Y H:i') : 'Unknown' }}</p>
                    <p class="text-sm text-red-600">Reason: {{ $user->suspension_reason ?? 'No reason provided' }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 space-y-3">
                <!-- Adjust Points -->
                <form action="{{ route('admin.users.adjust-points', $user) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="points" placeholder="Points (+/-)" required
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700">Adjust</button>
                </form>

                @if($user->id !== auth()->id())
                    <div class="flex space-x-2">
                        <!-- Toggle Admin -->
                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('{{ $user->is_admin ? 'Remove admin privileges?' : 'Grant admin privileges?' }}')"
                                class="w-full px-4 py-2 border border-yellow-300 text-yellow-700 rounded-lg hover:bg-yellow-50 text-sm">
                                {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                            </button>
                        </form>

                        <!-- Suspend/Unsuspend -->
                        @if($user->is_suspended)
                            <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                    Unsuspend
                                </button>
                            </form>
                        @elseif(!$user->is_admin)
                            <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Suspend this user?')"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                    Suspend
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
            <h3 class="font-semibold text-gray-900 mb-4">Statistics</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-purple-600">{{ $user->items_count }}</p>
                    <p class="text-sm text-gray-500">Items Posted</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-purple-600">{{ $user->borrow_requests_count }}</p>
                    <p class="text-sm text-gray-500">Borrow Requests</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ $user->ratings_received_count }}</p>
                    <p class="text-sm text-gray-500">Ratings Received</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-600">{{ $user->ratings_given_count }}</p>
                    <p class="text-sm text-gray-500">Ratings Given</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Recent Items -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Recent Items ({{ $user->items_count }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($user->items as $item)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->title }}</p>
                            <p class="text-sm text-gray-500">{{ $item->category ?? 'No category' }} • {{ $item->points_per_day }} pts/day</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">No items posted</div>
                @endforelse
            </div>
        </div>

        <!-- Borrow History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Borrow History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Other Party</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($borrowHistory as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $request->item->title ?? 'Deleted' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($request->borrower_id === $user->id)
                                        <span class="text-blue-600">Borrower</span>
                                    @else
                                        <span class="text-green-600">Lender</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $request->borrower_id === $user->id ? $request->lender->name : $request->borrower->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $request->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $request->status === 'returned' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $request->status === 'cancelled' ? 'bg-gray-100 text-gray-600' : '' }}
                                    ">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $request->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No borrow history</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

