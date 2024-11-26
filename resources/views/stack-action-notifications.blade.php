<div>
    @foreach($stackActions as $stackAction)
        @livewire('queueable-stack-actions.stack-action-notification', [
            'stackActionId' => $stackAction->getKey()
        ])
    @endforeach
</div>
