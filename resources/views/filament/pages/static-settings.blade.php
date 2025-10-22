<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex justify-end gap-x-3">
            <x-filament::button
                type="button"
                color="gray"
                tag="a"
                href="/"
                target="_blank"
                icon="heroicon-o-eye"
            >
                Preview Site
            </x-filament::button>

            <x-filament::button
                type="submit"
                icon="heroicon-o-check"
            >
                Save Changes
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
