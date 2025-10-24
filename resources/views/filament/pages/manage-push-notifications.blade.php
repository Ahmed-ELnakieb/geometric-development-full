<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $stats = $this->getStats();
            @endphp
            
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Subscriptions</p>
                        <p class="text-2xl font-bold">{{ $stats['total_subscriptions'] }}</p>
                    </div>
                    <x-heroicon-o-users class="w-8 h-8 text-primary-500" />
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Subscriptions</p>
                        <p class="text-2xl font-bold text-success-600">{{ $stats['active_subscriptions'] }}</p>
                    </div>
                    <x-heroicon-o-check-circle class="w-8 h-8 text-success-500" />
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">This Week</p>
                        <p class="text-2xl font-bold text-info-600">{{ $stats['recent_subscriptions'] }}</p>
                    </div>
                    <x-heroicon-o-calendar class="w-8 h-8 text-info-500" />
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Expired</p>
                        <p class="text-2xl font-bold text-warning-600">{{ $stats['expired_subscriptions'] }}</p>
                    </div>
                    <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-warning-500" />
                </div>
            </x-filament::card>
        </div>

        <!-- Send Notification Form -->
        <x-filament::card>
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-medium">Send Push Notification</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Send a notification to all active subscribers
                    </p>
                </div>

                <form wire:submit="sendNotification">
                    {{ $this->form }}
                    
                    <div class="mt-6">
                        <x-filament::button type="submit" size="lg">
                            <x-heroicon-o-paper-airplane class="w-5 h-5 mr-2" />
                            Send to All Subscribers
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </x-filament::card>

        <!-- Quick Actions -->
        <x-filament::card>
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Quick Actions</h3>
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <a href="{{ route('filament.admin.resources.push-subscriptions.index') }}" 
                       class="flex items-center justify-center px-4 py-3 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                        <x-heroicon-o-list-bullet class="w-5 h-5 mr-2" />
                        View All Subscriptions
                    </a>
                    
                    <a href="{{ route('pwa.test') }}" 
                       target="_blank"
                       class="flex items-center justify-center px-4 py-3 text-sm font-medium text-white bg-info-600 rounded-lg hover:bg-info-700">
                        <x-heroicon-o-beaker class="w-5 h-5 mr-2" />
                        Test PWA Features
                    </a>
                    
                    <a href="{{ route('notifications.preferences') }}" 
                       target="_blank"
                       class="flex items-center justify-center px-4 py-3 text-sm font-medium text-white bg-success-600 rounded-lg hover:bg-success-700">
                        <x-heroicon-o-cog-6-tooth class="w-5 h-5 mr-2" />
                        User Preferences
                    </a>
                </div>
            </div>
        </x-filament::card>

        <!-- Information -->
        <x-filament::card>
            <div class="space-y-2">
                <h3 class="text-lg font-medium">About Push Notifications</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <p>
                        <strong>Push notifications</strong> allow you to send real-time updates to users even when they're not actively browsing your website.
                    </p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li>Notifications are sent to all active subscribers</li>
                        <li>Users must grant permission to receive notifications</li>
                        <li>Notifications work on desktop and mobile browsers</li>
                        <li>Expired subscriptions (30+ days inactive) are automatically cleaned up</li>
                    </ul>
                </div>
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>