@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-12">
    
    <!-- Incoming Requests (Lender) -->
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900 border-l-4 border-purple-500 pl-4">Incoming Requests <span class="text-sm font-normal text-gray-500 ml-2">(People who want your items)</span></h2>
        
        @if($receivedRequests->isEmpty())
            <div class="bg-white rounded-xl p-8 text-center text-gray-500 shadow-sm border border-gray-100">
                You haven't received any borrow requests yet.
            </div>
        @else
            <div class="grid gap-6">
                @foreach($receivedRequests as $request)
                    <div class="glass-card rounded-xl p-6 flex flex-col md:flex-row items-start justify-between gap-6 transition-all hover:shadow-md
                        {{ $request->status === 'overdue' ? 'border-l-4 border-orange-500' : '' }}
                        {{ $request->status === 'missing' ? 'border-l-4 border-red-600' : '' }}
                    ">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold 
                                    {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $request->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $request->status === 'returned' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $request->status === 'cancelled' ? 'bg-gray-200 text-gray-600' : '' }}
                                    {{ $request->status === 'overdue' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $request->status === 'missing' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst($request->status) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $request->created_at->diffForHumans() }}</span>

                                {{-- Overdue warning badge --}}
                                @if($request->isOverdue() && $request->status !== 'missing')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-500 text-white animate-pulse">
                                        ⚠ {{ $request->overdue_days_count }} day(s) overdue
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">
                                <span class="text-purple-600">{{ $request->borrower->name }}</span> wants to borrow 
                                <a href="{{ route('items.show', $request->item) }}" class="underline hover:text-purple-600">{{ $request->item->title }}</a>
                            </h3>
                            <div class="text-sm text-gray-600 mt-1 flex flex-wrap gap-x-4">
                                <span><span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($request->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</span>
                                <span><span class="font-medium">Total:</span> {{ $request->total_points ?? ($request->points_per_day * (\Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1)) }} pts</span>
                            </div>

                            {{-- Show penalties if any --}}
                            @if($request->penalty_points > 0)
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center gap-2 text-red-700 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Penalty: {{ $request->penalty_points }} pts deducted from borrower
                                    </div>
                                    @if($request->damage_description)
                                        <p class="text-xs text-red-600 mt-1">Damage: {{ $request->damage_description }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col items-end gap-3">
                            <!-- Actions for Lender -->
                            @if($request->status === 'pending')
                                <div class="flex items-center gap-3">
                                    <form action="{{ route('borrow-requests.approve', $request) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('borrow-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition">Reject</button>
                                    </form>
                                </div>
                            @elseif($request->status === 'approved')
                                <form action="{{ route('borrow-requests.borrowed', $request) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition flex items-center">
                                        Mark as Handed Over
                                    </button>
                                </form>
                            @elseif(in_array($request->status, ['borrowed', 'overdue']))
                                <div class="flex flex-col gap-2">
                                    <form action="{{ route('borrow-requests.returned', $request) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Mark as Returned
                                        </button>
                                    </form>
                                    <button onclick="document.getElementById('missing-modal-{{ $request->id }}').classList.remove('hidden')" 
                                            class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        Mark as Missing
                                    </button>
                                </div>
                            @elseif($request->status === 'returned')
                                <div class="flex flex-col gap-2">
                                    @if($request->canBeRatedBy(auth()->id()))
                                        <a href="{{ route('ratings.create', $request) }}" 
                                           class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-sm font-medium rounded-lg hover:from-yellow-500 hover:to-orange-600 transition flex items-center shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Rate Borrower
                                        </a>
                                    @else
                                        <span class="px-3 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Rated
                                        </span>
                                    @endif

                                    {{-- Report damage button (only if no damage reported yet) --}}
                                    @if(!$request->damage_description)
                                        <button onclick="document.getElementById('damage-modal-{{ $request->id }}').classList.remove('hidden')" 
                                                class="px-4 py-2 bg-orange-100 text-orange-700 text-sm font-medium rounded-lg hover:bg-orange-200 transition flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            Report Damage
                                        </button>
                                    @endif
                                </div>
                            @elseif($request->status === 'missing')
                                <span class="px-3 py-2 bg-red-100 text-red-700 text-sm rounded-lg flex items-center font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Item Missing
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Damage Report Modal --}}
                    @if(in_array($request->status, ['returned', 'borrowed', 'overdue']) && !$request->damage_description)
                        <div id="damage-modal-{{ $request->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Report Damage</h3>
                                <p class="text-sm text-gray-500 mb-4">Describe the damage to <strong>{{ $request->item->title }}</strong>. A penalty of <strong>{{ \App\Models\Penalty::DAMAGE_PENALTY }} points</strong> will be submitted for admin review.</p>
                                
                                <form action="{{ route('borrow-requests.report-damage', $request) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <textarea name="damage_description" rows="3" required
                                        class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition mb-3"
                                        placeholder="Describe the damage (e.g., scratched screen, torn pages, missing parts...)"></textarea>
                                    
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">📷 Upload Evidence Photo <span class="text-red-500">*</span></label>
                                        <input type="file" name="evidence_photo" accept="image/jpeg,image/png,image/jpg,image/webp" required
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition">
                                        <p class="text-xs text-gray-400 mt-1">JPEG, PNG, or WebP. Max 5MB.</p>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" onclick="this.closest('[id^=damage-modal]').classList.add('hidden')"
                                                class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                            Cancel
                                        </button>
                                        <button type="submit" 
                                                class="flex-1 px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition">
                                            Submit Report
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Missing Report Modal --}}
                    @if(in_array($request->status, ['borrowed', 'overdue']))
                        <div id="missing-modal-{{ $request->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Report Missing Item</h3>
                                <p class="text-sm text-gray-500 mb-4">Mark <strong>{{ $request->item->title }}</strong> as missing. Upload proof (e.g., a screenshot of messages showing the borrower is unresponsive). The report will be reviewed by an admin.</p>
                                
                                <form action="{{ route('borrow-requests.missing', $request) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">📷 Upload Evidence Photo <span class="text-red-500">*</span></label>
                                        <input type="file" name="evidence_photo" accept="image/jpeg,image/png,image/jpg,image/webp" required
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition">
                                        <p class="text-xs text-gray-400 mt-1">JPEG, PNG, or WebP. Max 5MB.</p>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" onclick="this.closest('[id^=missing-modal]').classList.add('hidden')"
                                                class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                            Cancel
                                        </button>
                                        <button type="submit" 
                                                class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                                            Confirm Missing
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- Outgoing Requests (Borrower) -->
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900 border-l-4 border-purple-500 pl-4">Outgoing Requests <span class="text-sm font-normal text-gray-500 ml-2">(Items you want)</span></h2>
        
        @if($sentRequests->isEmpty())
            <div class="bg-white rounded-xl p-8 text-center text-gray-500 shadow-sm border border-gray-100">
                You haven't requested any items yet. <a href="{{ route('dashboard') }}" class="text-purple-600 font-medium hover:underline">Browse items</a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($sentRequests as $request)
                    <div class="glass-card rounded-xl p-6 flex flex-col md:flex-row items-start justify-between gap-6 transition-all hover:bg-white hover:shadow-md
                        {{ $request->status === 'overdue' ? 'border-l-4 border-orange-500' : '' }}
                        {{ $request->status === 'missing' ? 'border-l-4 border-red-600' : '' }}
                    ">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold 
                                    {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $request->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $request->status === 'returned' ? 'text-gray-800 bg-gray-100' : '' }}
                                    {{ $request->status === 'cancelled' ? 'bg-gray-200 text-gray-600' : '' }}
                                    {{ $request->status === 'overdue' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $request->status === 'missing' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst($request->status) }}
                                </span>

                                {{-- Overdue warning for borrower --}}
                                @if($request->isOverdue() && $request->status !== 'missing')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-500 text-white animate-pulse">
                                        ⚠ {{ $request->overdue_days_count }} day(s) overdue — return ASAP!
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">
                                Request for <a href="{{ route('items.show', $request->item) }}" class="underline hover:text-purple-600">{{ $request->item->title }}</a>
                            </h3>
                             <div class="text-sm text-gray-600 mt-1 flex flex-wrap gap-x-4">
                                <span><span class="font-medium">Owner:</span> {{ $request->lender->name }}</span>
                                <span><span class="font-medium">Dates:</span> {{ \Carbon\Carbon::parse($request->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d') }}</span>
                            </div>

                            {{-- Show penalties the borrower received --}}
                            @if($request->penalty_points > 0)
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center gap-2 text-red-700 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Penalty applied: {{ $request->penalty_points }} pts deducted
                                    </div>
                                    @if($request->damage_description)
                                        <p class="text-xs text-red-600 mt-1">Damage reported: {{ $request->damage_description }}</p>
                                    @endif
                                    @if($request->status === 'missing')
                                        <p class="text-xs text-red-600 mt-1 font-medium">⚠ This item has been marked as missing by the owner.</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                             <!-- Actions for Borrower -->
                            @if(in_array($request->status, ['pending', 'approved']))
                                <form action="{{ route('borrow-requests.cancel', $request) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium underline">Cancel Request</button>
                                </form>
                            @elseif($request->status === 'returned')
                                @if($request->canBeRatedBy(auth()->id()))
                                    <a href="{{ route('ratings.create', $request) }}" 
                                       class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-sm font-medium rounded-lg hover:from-yellow-500 hover:to-orange-600 transition flex items-center shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Leave Rating
                                    </a>
                                @else
                                    <span class="px-3 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Rated
                                    </span>
                                @endif
                            @elseif($request->status === 'missing')
                                <span class="px-3 py-2 bg-red-100 text-red-700 text-sm rounded-lg flex items-center font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Missing — Contact owner
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

