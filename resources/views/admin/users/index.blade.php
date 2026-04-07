@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<!-- Search & Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Search by name or email..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        </div>
        <div class="sm:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                <option value="">All Users</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="admin" {{ request('status') === 'admin' ? 'selected' : '' }}>Admins</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
            Filter
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.users') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Clear
            </a>
        @endif
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requests</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 {{ $user->is_suspended ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 flex items-center">
                                        {{ $user->name }}
                                        @if($user->is_admin)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Admin</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->points_balance ?? 0 }} pts</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->items_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->borrow_requests_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->ratings_received_count > 0)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-900">{{ $user->average_rating }}</span>
                                    <span class="ml-1 text-xs text-gray-500">({{ $user->ratings_received_count }})</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">No ratings</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_suspended)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Suspended
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-purple-600 hover:text-purple-900">View</a>
                                
                                @if($user->id !== auth()->id())
                                    <!-- Toggle Admin -->
                                    <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('{{ $user->is_admin ? 'Remove admin privileges?' : 'Grant admin privileges?' }}')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                            {{ $user->is_admin ? 'Demote' : 'Promote' }}
                                        </button>
                                    </form>

                                    <!-- Suspend/Unsuspend -->
                                    @if($user->is_suspended)
                                        <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">Unsuspend</button>
                                        </form>
                                    @elseif(!$user->is_admin)
                                        <button onclick="openSuspendModal({{ $user->id }}, '{{ $user->name }}')" class="text-red-600 hover:text-red-900">
                                            Suspend
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Suspend Modal -->
<div id="suspendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Suspend User</h3>
        <p class="text-gray-600 mb-4">Are you sure you want to suspend <strong id="suspendUserName"></strong>?</p>
        <form id="suspendForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason (optional)</label>
                <textarea name="reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="Enter reason for suspension..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeSuspendModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Suspend User</button>
            </div>
        </form>
    </div>
</div>

<script>
function openSuspendModal(userId, userName) {
    document.getElementById('suspendUserName').textContent = userName;
    document.getElementById('suspendForm').action = `/admin/users/${userId}/suspend`;
    document.getElementById('suspendModal').classList.remove('hidden');
    document.getElementById('suspendModal').classList.add('flex');
}

function closeSuspendModal() {
    document.getElementById('suspendModal').classList.add('hidden');
    document.getElementById('suspendModal').classList.remove('flex');
}
</script>
@endsection

