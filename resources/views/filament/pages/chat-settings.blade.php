<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">WhatsApp Chat Settings</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Configure your WhatsApp Business API integration and chat widget settings</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="flex gap-4">
            <!-- API Status Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">API Status</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            @php
                                $appId = \App\Models\ChatSetting::get('whatsapp_app_id');
                                $phoneNumber = \App\Models\ChatSetting::get('whatsapp_phone_number');
                                $accessToken = \App\Models\ChatSetting::get('whatsapp_access_token');
                                
                                if (empty($appId) || empty($phoneNumber) || empty($accessToken)) {
                                    echo 'Not Configured';
                                } else {
                                    echo 'Configured';
                                }
                            @endphp
                        </p>
                    </div>
                </div>
            </div>

            <!-- Active Chats Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Active Chats</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @php
                                try {
                                    $activeCount = \App\Models\Conversation::where('status', 'active')->count();
                                } catch (\Exception $e) {
                                    $activeCount = 0;
                                }
                            @endphp
                            {{ $activeCount }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Waiting Chats Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Waiting</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @php
                                try {
                                    $waitingCount = \App\Models\Conversation::where('status', 'waiting')->count();
                                } catch (\Exception $e) {
                                    $waitingCount = 0;
                                }
                            @endphp
                            {{ $waitingCount }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Online Agents Card -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Agents</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @php
                                try {
                                    $onlineAgents = \App\Models\ChatAgent::where('status', 'online')->count();
                                } catch (\Exception $e) {
                                    $onlineAgents = 0;
                                }
                            @endphp
                            {{ $onlineAgents }} online
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
            <div class="flex flex-wrap justify-end gap-4 mt-8">
                <x-filament::button
                    type="button"
                    color="secondary"
                    wire:click="testConnection"
                    icon="heroicon-o-signal"
                    class="min-w-[140px]"
                >
                    Test Connection
                </x-filament::button>

                <x-filament::button
                    type="button"
                    color="warning"
                    wire:click="sendTestMessage"
                    icon="heroicon-o-paper-airplane"
                    wire:confirm="Are you sure you want to send a test message?"
                    class="min-w-[140px]"
                >
                    Send Test Message
                </x-filament::button>

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
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Setup Instructions</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Create a WhatsApp Business Account at <a href="https://business.whatsapp.com" target="_blank" class="underline">business.whatsapp.com</a></li>
                            <li>Set up your app in the <a href="https://developers.facebook.com" target="_blank" class="underline">Meta Developer Console</a></li>
                            <li>Get your App ID, Phone Number ID, and Access Token from the console</li>
                            <li>Configure the webhook URL in your WhatsApp app settings</li>
                            <li>Test the connection using the "Test Connection" button</li>
                            <li>Send a test message to verify everything is working</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <x-filament-actions::modals />
</x-filament-panels::page>