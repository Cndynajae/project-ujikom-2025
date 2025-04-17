<div>
    {{-- content --}}
    <div class="container px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 border-b-2 border-blue-500 pb-2 inline-block">
            Dashboard
        </h2>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Kolom untuk Jam Digital dan Sambutan -->
            <div class="w-full md:w-1/2">
                <!-- Jam Digital Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-6 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-200 dark:bg-blue-900 rounded-full -mr-16 -mt-16 opacity-50"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-4">
                            <i class="bx bx-time text-blue-500 text-2xl mr-2"></i>
                            <h3 class="text-blue-500 dark:text-blue-400 text-lg font-medium">Current Time</h3>
                        </div>
                        <p class="text-5xl font-black text-gray-800 dark:text-white tracking-tight" wire:poll.1s="updateClock">
                            {{ now()->format('H:i:s') }}
                        </p>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mt-2 font-medium">
                            {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                    </div>
                </div>

                <!-- Card Sambutan untuk User -->
                <div class="mt-8 bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-6 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-green-200 dark:bg-green-900 rounded-full -mr-16 -mt-16 opacity-50"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-4">
                            <i class="bx bx-user text-green-500 text-2xl mr-2"></i>
                            <h3 class="text-green-500 dark:text-green-400 text-lg font-medium">Welcome</h3>
                        </div>
                        <p class="text-2xl text-gray-800 dark:text-gray-200 font-bold">
                            Hello, <span class="text-green-500 dark:text-green-400">{{ Auth::user()->name }}</span>!
                        </p>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            We're glad to see you back. Your dashboard is ready.
                        </p>
                    </div>
                </div>

                @if ($totalUsers)
                    <div class="mt-8 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-6 overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-200 dark:bg-purple-900 rounded-full -mr-16 -mt-16 opacity-50"></div>
                        <div class="relative z-10">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-14 h-14 bg-purple-500 bg-opacity-20 dark:bg-opacity-30 rounded-full">
                                    <i class='bx bxs-user text-2xl text-purple-500 dark:text-purple-400'></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-purple-500 dark:text-purple-400">Total Users</p>
                                    <div class="flex items-baseline">
                                        <p class="text-3xl font-extrabold text-gray-800 dark:text-white">{{ $totalUsers }}</p>
                                        <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">registered accounts</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Kalender Card -->
            <div class="w-full md:w-1/2 mt-8 md:mt-0">
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-6 h-full overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-200 dark:bg-amber-900 rounded-full -mr-16 -mt-16 opacity-50"></div>
                    <div class="relative z-10">
                        <div class="flex items-center mb-4">
                            <i class="bx bx-calendar text-amber-500 text-2xl mr-2"></i>
                            <h3 class="text-amber-500 dark:text-amber-400 text-lg font-medium">Calendar</h3>
                        </div>
                        <div class="bg-white bg-opacity-70 dark:bg-gray-800 dark:bg-opacity-50 rounded-xl p-4">
                            @livewire('google-calendar')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            {{-- Original Card commented out --}}
        </div>
    </div>
</div>
