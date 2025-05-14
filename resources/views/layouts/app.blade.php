<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'ClubSync')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Additional CSS for voting system -->
        @stack('styles')
        
        <!-- Notification Styles -->
        <style>
            .notification-dropdown {
                position: relative;
                display: inline-block;
            }
            .notification-list {
                display: none;
                position: absolute;
                right: 0;
                width: 350px;
                max-height: 500px;
                overflow-y: auto;
                background: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                z-index: 1000;
                border-radius: 0.5rem;
            }
            .notification-item.unread {
                background-color: #f8f9fa;
            }
            .notification-bell:hover + .notification-list,
            .notification-list:hover {
                display: block;
            }
            .notification-badge {
                position: absolute;
                top: -5px;
                right: -5px;
            }
            .notification-item {
                transition: background-color 0.2s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Notification Bell -->
            @auth
            <div class="fixed top-4 right-4 z-50">
                <div class="notification-dropdown">
                    <button class="notification-bell p-2 bg-white rounded-full shadow-md relative hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="notification-badge bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </button>
                    
                    <div class="notification-list">
    @forelse(auth()->user()->notifications->take(5) as $notification)
        <div class="notification-item {{ $notification->unread() ? 'unread' : '' }} border-b border-gray-100"
             data-notification-id="{{ $notification->id }}">
            <a href="{{ $notification->data['action_url'] ?? '#' }}" 
               class="block p-3 hover:bg-gray-50 transition-colors">
                <h6 class="font-medium text-gray-900">
                    {{ $notification->data['title'] ?? $notification->data['message'] }}
                </h6>
                <small class="text-gray-500 text-sm">
                    {{ $notification->created_at->diffForHumans() }}
                </small>
            </a>
        </div>
    @empty
        <div class="p-4 text-center text-gray-500">No notifications</div>
    @endforelse
    
    <!-- MARK ALL AS READ BUTTON - ADD THIS SECTION -->
    @if(auth()->user()->unreadNotifications->count() > 0)
    <div class="p-2 border-t border-gray-100">
        <button id="mark-all-read" class="w-full text-left px-3 py-1 text-sm text-indigo-600 hover:text-indigo-800">
            Mark all as read
        </button>
    </div>
    @endif
    
    @if(auth()->user()->notifications->count() > 0)
        <div class="text-center p-2 border-t border-gray-100">
            <a href="{{ route('notifications.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                View All Notifications
            </a>
        </div>
    @endif
</div>è
                </div>
            </div>
            @endauth

            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="container mx-auto py-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Dynamic Content -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Additional JS for voting system -->
        @stack('scripts')
        
        <!-- Notification Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mark single notification as read
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const notificationId = this.dataset.notificationId;
                        if (notificationId) {
                            fetch(`/notifications/${notificationId}/mark-as-read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json'
                                }
                            }).then(response => {
                                if (response.ok) {
                                    this.classList.remove('unread');
                                    updateUnreadCount();
                                }
                            });
                        }
                    });
                });

                // Mark all as read
                const markAllReadBtn = document.getElementById('mark-all-read');
                if (markAllReadBtn) {
                    markAllReadBtn.addEventListener('click', function() {
                        fetch('/notifications/mark-all-read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        }).then(response => {
                            if (response.ok) {
                                document.querySelectorAll('.notification-item').forEach(item => {
                                    item.classList.remove('unread');
                                });
                                updateUnreadCount();
                            }
                        });
                    });
                }

                function updateUnreadCount() {
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        const unreadItems = document.querySelectorAll('.notification-item.unread').length;
                        if (unreadItems > 0) {
                            badge.textContent = unreadItems;
                        } else {
                            badge.remove();
                        }
                    }
                }
            });
        </script>
        
        <!-- Inline scripts for dynamic vote options -->
        @hasSection('inline-scripts')
            <script>
                @yield('inline-scripts')
            </script>
        @endif
    </body>
</html>