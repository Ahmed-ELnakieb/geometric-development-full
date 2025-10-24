<x-filament-panels::page>
    @if ($error)
        <div class="mb-4 rounded-lg border border-danger-600 bg-danger-50 p-4 text-danger-900 dark:border-danger-400 dark:bg-danger-950 dark:text-danger-100">
            <div class="flex items-start">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium">Error Loading Inbox</h3>
                    <div class="mt-2 text-sm">{{ $error }}</div>
                    <div class="mt-4">
                        <a href="/admin/mail-settings" class="text-sm font-semibold underline hover:no-underline">
                            Configure IMAP Settings →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        {{-- Stats --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Emails</div>
                <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
            </div>
            <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Unread</div>
                <div class="mt-1 text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $stats['unread'] }}</div>
            </div>
            <div class="flex items-center justify-end">
                <x-filament::button
                    wire:click="refresh"
                    icon="heroicon-o-arrow-path"
                    color="gray"
                >
                    Refresh Inbox
                </x-filament::button>
            </div>
        </div>

        {{-- Email List or Viewer --}}
        @if ($selectedEmail)
            {{-- Email Viewer --}}
            <div class="rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 p-4 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedEmail['subject'] }}</h2>
                        <x-filament::button
                            wire:click="closeEmail"
                            icon="heroicon-o-x-mark"
                            color="gray"
                            size="sm"
                        >
                            Close
                        </x-filament::button>
                    </div>
                    <div class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                        <div><strong>From:</strong> {{ $selectedEmail['from_name'] }} &lt;{{ $selectedEmail['from'] }}&gt;</div>
                        <div><strong>To:</strong> {{ $selectedEmail['to'] }}</div>
                        <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($selectedEmail['date'])->format('F j, Y \a\t g:i A') }}</div>
                        @if ($selectedEmail['attachments'] > 0)
                            <div><strong>Attachments:</strong> {{ $selectedEmail['attachments'] }}</div>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    @if ($selectedEmail['body_html'])
                        <iframe 
                            src="data:text/html;charset=utf-8;base64,{{ base64_encode($selectedEmail['body_html']) }}" 
                            class="w-full rounded border dark:border-gray-700"
                            style="min-height: 400px; height: 600px;"
                            sandbox="allow-same-origin"
                        ></iframe>
                    @else
                        <div class="whitespace-pre-wrap rounded border p-4 text-sm dark:border-gray-700">
                            {{ $selectedEmail['body_text'] }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Email List --}}
            <div class="rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="border-b border-gray-200 p-4 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Inbox</h2>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($emails as $email)
                        <div 
                            wire:click="viewEmail('{{ $email['uid'] }}')"
                            class="cursor-pointer p-4 transition hover:bg-gray-50 dark:hover:bg-gray-700 {{ !$email['is_read'] ? 'bg-primary-50 dark:bg-primary-900/10' : '' }}"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        @if (!$email['is_read'])
                                            <span class="h-2 w-2 rounded-full bg-primary-600"></span>
                                        @endif
                                        <span class="font-semibold text-gray-900 dark:text-white {{ !$email['is_read'] ? 'font-bold' : '' }}">
                                            {{ $email['from_name'] }}
                                        </span>
                                    </div>
                                    <div class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-200 {{ !$email['is_read'] ? 'font-semibold' : '' }}">
                                        {{ $email['subject'] }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $email['body_preview'] }}...
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0 text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($email['date'])->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            @if ($loading)
                                <div>Loading emails...</div>
                            @else
                                <div>No emails found</div>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
