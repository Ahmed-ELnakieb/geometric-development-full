<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="flex gap-4">
            <!-- Status Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            @if(settings('preloader_enabled', true))
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Preloader Status</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ settings('preloader_enabled', true) ? 'Enabled' : 'Disabled' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Logo Type Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Logo Type</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ settings('preloader_use_logo', true) ? 'Website Logo' : 'Geometric SVG' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Background Type Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm3 2h6v4H7V4zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Background</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ ucfirst(settings('preloader_background_type', 'color')) }}
                            @if(settings('preloader_background_type', 'color') === 'color')
                                ({{ settings('preloader_background_color', '#060606') }})
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Text Preview Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Text Preview</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ strtoupper(settings('preloader_main_text', 'GEOMETRIC')) }} / {{ strtoupper(settings('preloader_sub_text', 'DEVELOPMENT')) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Form -->
        <form wire:submit="save">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                {{ $this->form }}
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end mt-8">
                <x-filament::button
                    type="submit"
                    color="primary"
                    icon="heroicon-o-check"
                    class="min-w-[140px] font-semibold"
                >
                    Save Settings
                </x-filament::button>
            </div>
        </form>

        <!-- Help Section -->
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-purple-800 dark:text-purple-200">Preloader Tips</h3>
                    <div class="mt-2 text-sm text-purple-700 dark:text-purple-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Enable/Disable:</strong> Turn the preloader on or off completely</li>
                            <li><strong>Logo Options:</strong> Use your website logo or a geometric SVG design</li>
                            <li><strong>Custom Text:</strong> Personalize the main and subtitle text (automatically converted to uppercase)</li>
                            <li><strong>Background:</strong> Choose between solid colors or upload custom background images</li>
                            <li><strong>Live Preview:</strong> See changes in real-time in the preview section</li>
                            <li><strong>Performance:</strong> The preloader automatically hides after 4 seconds or when page loads</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>