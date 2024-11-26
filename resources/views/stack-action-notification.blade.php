<div @class([
        'mb-4',
        'hidden' => $stackAction->dismissed_at && !$isViewStackActionPage
])
        {{ \Aliheidarian1984\QueueableStackActionsLegal\Support\Config::pollingInterval() ? 'wire:poll.' . \Aliheidarian1984\QueueableStackActionsLegal\Support\Config::pollingInterval(): '' }}
>
    @php
        $color = \Aliheidarian1984\QueueableStackActionsLegal\Support\Config::color($stackAction->status);
        $colorStyles = \Illuminate\Support\Arr::toCssStyles([
              \Filament\Support\get_color_css_variables(
              $color,
              shades: [200, 700],
          ),
        ]);
    @endphp
    <div style="{{ $colorStyles }}"
         class="p-6 w-full shadow rounded flex bg-custom-200 dark:bg-custom-700">
        <div class="w-2/3 flex-initial">
            <span style="{{ $colorStyles }}"
                  class="text-md font-semibold block text-custom-700 dark:text-white"
            >
                @lang('queueable-stack-actions::notification.stack_action_status', ['name' => $stackAction->name, 'status' => $stackAction->status->getLabel()->lower()])
            </span>
            <div class="py-2">
                <span class="text-2xl font-semibold">{{ $processedPercentage }}%</span>
                <span class="text-gray-500 dark:text-white text-sm pl-2">@lang('queueable-stack-actions::notification.complete')</span>
            </div>
            <div class="flex w-full h-3 bg-white rounded-full overflow-hidden">
                @foreach($groupedRecords as $status => $count)
                    @php
                        $groupColor = \Aliheidarian1984\QueueableStackActionsLegal\Support\Config::color($status);
                        $groupColorStyles = \Illuminate\Support\Arr::toCssStyles([
                          \Filament\Support\get_color_css_variables(
                          $groupColor,
                          shades: [500, 600, 700],
                          ),
                        ]);
                        $status = \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::from($status);
                        $tooltip = $count . ' ' . $status->getLabel()->lower();
                        $percentage = round($count / $stackAction->total_records * 100);
                    @endphp

                    <div x-tooltip="'{{ $tooltip }}'"
                         @style([
                            "width: " . $percentage . "%;",
                            $groupColorStyles
                         ])
                         class="flex flex-col justify-center overflow-hidden bg-custom-600 text-xs text-white text-center whitespace-nowrap dark:bg-custom-500"
                         role="progressbar"
                         aria-valuenow="{{ $percentage }}"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                @endforeach
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-white text-xs pt-1">
                    {{ $stackAction->status->getLabel() }}
                    {{
                        match ($stackAction->status) {
                            \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::IN_PROGRESS => $stackAction->started_at->diffForHumans(),
                            \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::FINISHED => $stackAction->finished_at->diffForHumans(),
                            \Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum::FAILED => $stackAction->failed_at->diffForHumans(),
                            default => $stackAction->created_at->diffForHumans()
                        }
                    }}
                </span>
             @if(!$isViewStackActionPage && \Aliheidarian1984\QueueableStackActionsLegal\Support\Config::resource())
                    @php
                    $team = filament()->getTenant();
                    $tenant = $team->slug;
                    @endphp
                       <a href="{{ \Aliheidarian1984\QueueableStackActionsLegal\Support\Config::resource()::getUrl('view', ['tenant' => $tenant, 'record' => $stackAction->id]) }}"
                         target="_blank"
                        class="text-xs text-primary pt-1">@lang('queueable-stack-actions::notification.view_details')</a>

                @endif
            </div>
        </div>
        <div class="w-1/3 flex-initial align-middle flex justify-end items-center">
            @if(!$isViewStackActionPage)
                <x-heroicon-o-x-mark x-tooltip="'@lang('queueable-stack-actions::notification.dismiss')'"
                                     wire:click="dismiss"
                                     styles="{{ $colorStyles }}"
                                     class="h-8 cursor-pointer text-custom-700 dark:text-white"
                />
            @endif
        </div>
    </div>
</div>
