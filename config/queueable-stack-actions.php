<?php

return [
    /**
     * Table names used to created database tables needed for the package
     */
    'tables' => [
        'legal_stack_actions' => 'legal_stack_actions',
        'legal_stack_action_records' => 'legal_stack_action_records',
    ],

    /**
     * Models used in the package, they can be overridden with your own models, just make sure to extend the ones below
     */
    'models' => [

        'stack_action' => Aliheidarian1984\QueueableStackActionsLegal\Models\LegalStackAction::class,
        'stack_action_record' => Aliheidarian1984\QueueableStackActionsLegal\Models\LegalStackActionRecord::class,
    ],

    /**
     * Where to render notification components.
     *
     * More information: https://filamentphp.com/docs/3.x/support/render-hooks
     */
    'render_hook' => Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE,

    /**
     * How often notification component will be polled, set to null if don't want to poll
     */
    'polling_interval' => '5s',

    /**
     * Which queue connection and queue name should be used
     */
    'queue' => [
        'connection' => env('QUEUE_CONNECTION', 'sync'),
        'queue' => 'default',
    ],

    /**
     * Resource used to display historical stack actions, set to null if you would not like to have this functionality
     */
    'resource' => \Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources\StackActionResource::class,

    /**
     * Default colors used to display notifications and statuses. Uses filament colors.
     *
     * More information: https://filamentphp.com/docs/3.x/support/colors
     */
    'colors' => [
        \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::QUEUED->value => 'gray',
        \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::IN_PROGRESS->value => 'info',
        \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::FINISHED->value => 'success',
        \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::FAILED->value => 'danger',
    ],
];
