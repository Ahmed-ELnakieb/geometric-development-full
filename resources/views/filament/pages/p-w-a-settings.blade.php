<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Status Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Background Sync</p>
                        <p class="text-lg font-bold {{ config('pwa.background_sync.enabled') ? 'text-success-600' : 'text-gray-400' }}">
                            {{ config('pwa.background_sync.enabled') ? 'Enabled' : 'Disabled' }}
                        </p>
                    </div>
                    @if(config('pwa.background_sync.enabled'))
                        <x-heroicon-o-check-circle class="w-8 h-8 text-success-500" />
                    @else
                        <x-heroicon-o-x-circle class="w-8 h-8 text-gray-400" />
                    @endif
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Push Notifications</p>
                        <p class="text-lg font-bold {{ config('pwa.push_notifications.enabled') ? 'text-success-600' : 'text-gray-400' }}">
                            {{ config('pwa.push_notifications.enabled') ? 'Enabled' : 'Disabled' }}
                        </p>
                    </div>
                    @if(config('pwa.push_notifications.enabled'))
                        <x-heroicon-o-check-circle class="w-8 h-8 text-success-500" />
                    @else
                        <x-heroicon-o-x-circle class="w-8 h-8 text-gray-400" />
                    @endif
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">VAPID Keys</p>
                        <p class="text-lg font-bold {{ config('pwa.push_notifications.vapid.public_key') ? 'text-success-600' : 'text-warning-600' }}">
                            {{ config('pwa.push_notifications.vapid.public_key') ? 'Configured' : 'Not Set' }}
                        </p>
                    </div>
                    @if(config('pwa.push_notifications.vapid.public_key'))
                        <x-heroicon-o-key class="w-8 h-8 text-success-500" />
                    @else
                        <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-warning-500" />
                    @endif
                </div>
            </x-filament::card>
        </div>

        <!-- Configuration Form -->
        <form wire:submit="save">
            {{ $this->form }}
            
            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" size="lg">
                    Save Settings
                </x-filament::button>
            </div>
        </form>

        <!-- Help Section -->
        <x-filament::card>
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Configuration Help</h3>
                
                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Background Sync</h4>
                        <p>Allows forms and data to be queued when offline and automatically synchronized when the connection is restored.</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Push Notifications</h4>
                        <p>Enables sending real-time notifications to users even when they're not actively browsing your website.</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">VAPID Keys</h4>
                        <p>Voluntary Application Server Identification (VAPID) keys are required for secure push notifications. Click "Generate New Keys" to create a new pair.</p>
                        <p class="mt-1 text-warning-600 dark:text-warning-400">
                            <strong>Warning:</strong> Generating new keys will invalidate all existing push subscriptions. Users will need to re-subscribe.
                        </p>
                    </div>
                </div>
                
                <div class="mt-4 p-4 bg-info-50 dark:bg-info-900/20 rounded-lg">
                    <div class="flex items-start">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-info-600 dark:text-info-400 mr-2 mt-0.5" />
                        <div class="text-sm text-info-800 dark:text-info-200">
                            <p class="font-semibold">Testing Your Configuration</p>
                            <p class="mt-1">After saving settings, visit the <a href="{{ route('pwa.test') }}" target="_blank" class="underline">PWA Test Page</a> to verify all features are working correctly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>