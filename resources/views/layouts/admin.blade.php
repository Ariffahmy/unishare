<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'UniShare') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .sidebar-link {
            @apply flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-200;
        }
        .sidebar-link.active {
            @apply bg-purple-600 text-white;
        }
        .stat-card {
            @apply bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow;
        }

        /* Sidebar transition */
        .admin-sidebar {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 16rem; /* w-64 */
        }
        .admin-sidebar.collapsed {
            width: 4.5rem; /* icon-only */
        }
        .admin-main {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 16rem;
        }
        .admin-main.collapsed {
            margin-left: 4.5rem;
        }

        /* Hide text labels when collapsed */
        .admin-sidebar.collapsed .sidebar-text {
            display: none;
        }
        .admin-sidebar.collapsed .sidebar-logo-text {
            display: none;
        }
        .admin-sidebar.collapsed .sidebar-user-info {
            display: none;
        }
        .admin-sidebar.collapsed .sidebar-divider {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        /* Center icons when collapsed */
        .admin-sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        .admin-sidebar.collapsed .sidebar-link svg {
            margin-right: 0;
        }
        .admin-sidebar.collapsed .sidebar-logo-link {
            justify-content: center;
        }
        .admin-sidebar.collapsed .sidebar-user-wrapper {
            justify-content: center;
        }

        /* Tooltip on hover when collapsed */
        .sidebar-link {
            position: relative;
        }
        .sidebar-link .sidebar-tooltip {
            display: none;
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 0.75rem;
            padding: 0.375rem 0.75rem;
            background: #1f2937;
            color: white;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 0.375rem;
            white-space: nowrap;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            pointer-events: none;
        }
        .sidebar-link .sidebar-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1f2937;
        }
        .admin-sidebar.collapsed .sidebar-link:hover .sidebar-tooltip {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside id="admin-sidebar" class="admin-sidebar bg-gray-900 text-white flex flex-col fixed h-full z-20">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-800">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-link flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <div class="sidebar-logo-text">
                        <h1 class="text-xl font-bold">UniShare</h1>
                        <p class="text-xs text-gray-400">Admin Panel</p>
                    </div>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                    <span class="sidebar-tooltip">Dashboard</span>
                </a>

                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <span class="sidebar-text">Users</span>
                    <span class="sidebar-tooltip">Users</span>
                </a>

                <a href="{{ route('admin.items') }}" class="sidebar-link {{ request()->routeIs('admin.items*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="sidebar-text">Items</span>
                    <span class="sidebar-tooltip">Items</span>
                </a>

                <a href="{{ route('admin.borrow-requests') }}" class="sidebar-link {{ request()->routeIs('admin.borrow-requests*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="sidebar-text">Borrow Requests</span>
                    <span class="sidebar-tooltip">Borrow Requests</span>
                </a>

                <a href="{{ route('admin.penalties') }}" class="sidebar-link {{ request()->routeIs('admin.penalties*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span class="sidebar-text">Penalties</span>
                    <span class="sidebar-tooltip">Penalties</span>
                </a>

                <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="sidebar-text">Reports</span>
                    <span class="sidebar-tooltip">Reports</span>
                </a>

                <div class="border-t border-gray-800 my-4 sidebar-divider"></div>

                <a href="{{ route('dashboard') }}" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    <span class="sidebar-text">Back to Site</span>
                    <span class="sidebar-tooltip">Back to Site</span>
                </a>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-gray-800">
                <div class="sidebar-user-wrapper flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="admin-main" class="admin-main flex-1">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Sidebar Toggle Button -->
                        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-lg transition-colors duration-200" title="Toggle Sidebar">
                            <svg id="toggle-icon-menu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const main = document.getElementById('admin-main');
            const toggleBtn = document.getElementById('sidebar-toggle');

            // Restore state from localStorage
            const isCollapsed = localStorage.getItem('admin-sidebar-collapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                main.classList.add('collapsed');
            }

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('collapsed');

                // Save state to localStorage
                const collapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('admin-sidebar-collapsed', collapsed);
            });
        });
    </script>
</body>
</html>

