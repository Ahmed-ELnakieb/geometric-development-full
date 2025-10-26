<x-filament-panels::page>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
    
    <div class="space-y-6">
        <!-- Filters -->
        <x-filament::section>
            <x-slot name="heading">
                Filters
            </x-slot>
            
            {{ $this->form }}
        </x-filament::section>

        <!-- Stats Overview -->
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
                    {{ number_format($stats['avg_daily_visits'], 1) }}
                </div>
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Avg Daily Visits</div>
            </div>
        </div>

        <!-- Visit Trends Chart -->
        <x-filament::section>
            <x-slot name="heading">
                Visit Trends
            </x-slot>
            
            @php
                $trends = $this->getVisitTrends();
            @endphp
            
            @if(count($trends) > 0)
                <div class="h-64">
                    <canvas id="visitTrendsChart"></canvas>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('visitTrendsChart').getContext('2d');
                        const trends = @json($trends);
                        
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: trends.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                                datasets: [{
                                    label: 'Visits',
                                    data: trends.map(item => item.visits),
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.1,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No trend data available</p>
            @endif
        </x-filament::section>

        <!-- Charts and Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Pages -->
            <x-filament::section>
                <x-slot name="heading">
                    Top Pages
                </x-slot>
                
                @php
                    $topPages = $this->getTopPages();
                @endphp
                
                @if(count($topPages) > 0)
                    <div class="space-y-3">
                        @foreach($topPages as $page)
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-950 dark:text-white truncate">
                                        {{ $page['url'] }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <x-filament::badge color="primary">
                                        {{ number_format($page['visits']) }} visits
                                    </x-filament::badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No data available</p>
                @endif
            </x-filament::section>

            <!-- Top Referrers -->
            <x-filament::section>
                <x-slot name="heading">
                    Top Referrers
                </x-slot>
                
                @php
                    $topReferrers = $this->getTopReferrers();
                @endphp
                
                @if(count($topReferrers) > 0)
                    <div class="space-y-3">
                        @foreach($topReferrers as $referrer)
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-950 dark:text-white truncate">
                                        {{ $referrer['referer'] }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <x-filament::badge color="success">
                                        {{ number_format($referrer['visits']) }} visits
                                    </x-filament::badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No referrer data available</p>
                @endif
            </x-filament::section>
        </div>

        <!-- Model-specific visits -->
        @if($this->modelType !== 'all' || count($this->getModelVisits()) > 0)
            <x-filament::section>
                <x-slot name="heading">
                    @if($this->modelType === 'all')
                        Top Content
                    @else
                        {{ class_basename($this->modelType) }} Visits
                    @endif
                </x-slot>
                
                @php
                    $modelVisits = $this->getModelVisits();
                @endphp
                
                @if(count($modelVisits) > 0)
                    <div class="space-y-3">
                        @foreach($modelVisits as $visit)
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-950 dark:text-white">
                                        @if($visit['visitable'])
                                            {{ $visit['visitable']['title'] ?? $visit['visitable']['name'] ?? 'Untitled' }}
                                        @else
                                            {{ class_basename($visit['visitable_type']) }} #{{ $visit['visitable_id'] }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ class_basename($visit['visitable_type']) }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <x-filament::badge color="info">
                                        {{ number_format($visit['visits']) }} visits
                                    </x-filament::badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No content visits available</p>
                @endif
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>