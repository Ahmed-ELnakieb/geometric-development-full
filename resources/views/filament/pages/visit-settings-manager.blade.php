<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="flex gap-4">
            @php
                $stats = $this->getVisitStats();
            @endphp
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center flex-1">
                <div class="text-2xl font-bold text-primary-600 dark:text-primary-400 mb-1">
                    {{ number_format($stats['total_visits']) }}
                </div>
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Total Visits</div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center flex-1">
                <div class="text-2xl font-bold text-success-600 dark:text-success-400 mb-1">
                    {{ number_format($stats['unique_visitors']) }}
                </div>
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Unique Visitors</div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center flex-1">
                <div class="text-2xl font-bold text-warning-600 dark:text-warning-400 mb-1">
                    {{ number_format($stats['today_visits']) }}
                </div>
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Today's Visits</div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center flex-1">
                <div class="text-2xl font-bold text-info-600 dark:text-info-400 mb-1">
                    {{ $stats['avg_daily_visits'] }}
                </div>
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Avg Daily Visits</div>
            </div>
        </div>

        <!-- Dynamic Settings Form -->
        <form wire:submit="save">
            {{ $this->form }}
        </form>

        <!-- Status Information -->
        <x-filament::section>
            <x-slot name="heading">
                Important Notes
            </x-slot>
            
            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-start space-x-2">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                    <p><strong>Admin Routes:</strong> All routes starting with <code>/admin</code> are automatically excluded from tracking to prevent admin activity from skewing analytics.</p>
                </div>
                
                <div class="flex items-start space-x-2">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                    <p><strong>Performance:</strong> Enable queue processing for high-traffic sites to avoid slowing down page loads.</p>
                </div>
                
                <div class="flex items-start space-x-2">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                    <p><strong>Privacy:</strong> Use IP exclusions to prevent tracking of internal team members or specific users.</p>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>