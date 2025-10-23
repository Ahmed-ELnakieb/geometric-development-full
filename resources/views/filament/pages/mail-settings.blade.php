<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex justify-end gap-x-3">
            <x-filament::button
                type="button"
                color="info"
                wire:click="sendTestEmail"
                icon="heroicon-o-paper-airplane"
            >
                Send Test Email
            </x-filament::button>

            <x-filament::button
                type="submit"
                icon="heroicon-o-check"
            >
                Save Configuration
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
