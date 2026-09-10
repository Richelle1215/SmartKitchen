<!-- Dynamic SmartKitchen Navigation Bar -->
<nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo / Brand -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center text-sm">SK</div>
                    <span class="font-bold text-gray-900 hidden sm:inline">SmartKitchen</span>
                </a>
            </div>

            <!-- Desktop Navigation Menu -->
            <div class="hidden md:flex items-center gap-1">
                @auth
                    <!-- Authenticated User Navigation -->
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Home
                    </a>
                    <a href="{{ route('recipes.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Recipes
                    </a>
                    <a href="{{ route('search.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Categories
                    </a>
                    <a href="{{ route('video-shorts.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Shorts
                    </a>
                    <a href="{{ route('ai.assistant') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        AI Coach
                    </a>
                    <a href="{{ route('meal-plans.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Meal Planner
                    </a>
                    <a href="{{ route('pantry.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Pantry
                    </a>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-red-700 hover:text-red-900 hover:bg-red-50 border border-red-200">
                            ⚙️ Admin
                        </a>
                    @endif

                @else
                    <!-- Guest Navigation -->
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Home
                    </a>
                    <a href="{{ route('recipes.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Recipes
                    </a>
                    <a href="{{ route('search.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Categories
                    </a>
                    <a href="{{ route('video-shorts.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Shorts
                    </a>
                    <a href="{{ route('ai.assistant') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        AI
                    </a>
                @endauth
            </div>

            <!-- Right Side: Notifications, Profile, Auth Buttons -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <!-- Notifications -->
                    <a href="{{ route('messages.index') }}" class="relative px-2 py-1 text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 15.071V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v4.071a2.032 2.032 0 01-.595 1.524L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="sr-only">Notifications</span>
                    </a>

                    <!-- User Menu Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-gray-100">
                            <div class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-700" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50" style="display: none;">
                            <a href="{{ route('profile.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                👤 My Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                ⚙️ Edit Profile
                            </a>
                            <a href="{{ route('recipes.my-recipes') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                📖 My Recipes
                            </a>
                            <a href="{{ route('favorites.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                ❤️ Favorites
                            </a>
                            <a href="{{ route('achievements.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                🏆 Achievements
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 rounded-b-lg">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <!-- Guest Auth Buttons -->
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600">
                        Sign Up
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="md:hidden flex items-center gap-4">
                @auth
                    <!-- Mobile Notifications -->
                    <a href="{{ route('messages.index') }}" class="text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 15.071V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v4.071a2.032 2.032 0 01-.595 1.524L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </a>
                @endauth
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 pb-4">
            @auth
                <!-- Mobile Authenticated Menu -->
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Home
                </a>
                <a href="{{ route('recipes.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Recipes
                </a>
                <a href="{{ route('search.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Categories
                </a>
                <a href="{{ route('video-shorts.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Shorts
                </a>
                <a href="{{ route('ai.assistant') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    AI Coach
                </a>
                <a href="{{ route('meal-plans.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Meal Planner
                </a>
                <a href="{{ route('pantry.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Pantry
                </a>

                <hr class="my-2">

                <a href="{{ route('profile.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    👤 My Profile
                </a>
                <a href="{{ route('recipes.my-recipes') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    📖 My Recipes
                </a>
                <a href="{{ route('favorites.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    ❤️ Favorites
                </a>
                <a href="{{ route('achievements.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    🏆 Achievements
                </a>

                @if(Auth::user()->role === 'admin')
                    <hr class="my-2">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-red-700 hover:text-red-900 hover:bg-red-50 border border-red-200">
                        ⚙️ Admin Dashboard
                    </a>
                @endif

                <hr class="my-2">

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-700 hover:text-red-900 hover:bg-red-50">
                        🚪 Logout
                    </button>
                </form>
            @else
                <!-- Mobile Guest Menu -->
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Home
                </a>
                <a href="{{ route('recipes.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Recipes
                </a>
                <a href="{{ route('search.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Categories
                </a>
                <a href="{{ route('video-shorts.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Shorts
                </a>
                <a href="{{ route('ai.assistant') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    AI
                </a>

                <hr class="my-2">

                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-orange-500 text-white hover:bg-orange-600 mx-3 mt-2">
                    Sign Up
                </a>
            @endauth
        </div>
    </div>
</nav>
