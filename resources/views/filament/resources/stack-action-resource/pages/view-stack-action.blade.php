<x-filament-panels::page>
    @livewire('queueable-stack-actions.stack-action-notification', [
        'stackActionId' => $this->record->getKey(),
        'isViewStackActionPage' => true
    ])

    {{ $this->table }}
</x-filament-panels::page>
