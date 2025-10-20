<div class="rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-900 p-4">
    @php
        $record = $getRecord();
        $url = $record ? $record->getUrl() : null;
        $mimeType = $record ? $record->mime_type : null;
    @endphp

    @if($url && $mimeType)
        @if(str_starts_with($mimeType, 'image/'))
            <div class="flex justify-center">
                <img src="{{ $url }}" 
                     alt="{{ $record->name }}" 
                     class="max-w-full h-auto max-h-96 rounded-lg shadow-md"
                     style="object-fit: contain;">
            </div>
        @elseif(str_starts_with($mimeType, 'video/'))
            <div class="flex justify-center">
                <video controls class="max-w-full h-auto max-h-96 rounded-lg shadow-md">
                    <source src="{{ $url }}" type="{{ $mimeType }}">
                    Your browser does not support the video tag.
                </video>
            </div>
        @elseif($mimeType === 'application/pdf')
            <div class="flex flex-col items-center gap-4">
                <div class="text-center">
                    <svg class="w-24 h-24 mx-auto text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">PDF Document</p>
                </div>
                <a href="{{ $url }}" 
                   target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Open PDF
                </a>
            </div>
        @else
            <div class="flex flex-col items-center gap-2 py-8">
                <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $mimeType }}</p>
                <a href="{{ $url }}" 
                   target="_blank" 
                   class="text-primary-600 hover:text-primary-700 font-medium">
                    Download File
                </a>
            </div>
        @endif
        
        <div class="mt-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">File URL:</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 break-all mt-1">{{ $url }}</p>
        </div>
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            No preview available
        </div>
    @endif
</div>
