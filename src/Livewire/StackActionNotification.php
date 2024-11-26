<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Livewire;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Models\LegalStackAction;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Livewire\Component;

class StackActionNotification extends Component
{
    public int $stackActionId;

    public LegalStackAction $stackAction;

    public Collection $groupedRecords;

    public float $processedPercentage = 0;

    public bool $isViewStackActionPage = false;

    public function boot(): void
    {
        $this->stackAction = Config::stackActionModel()::query()->findOrFail($this->stackActionId);

        $records = $this->stackAction->records->groupBy('status');

        $this->processedPercentage = 100;
        if ($this->stackAction->total_records) {
            $this->processedPercentage = round((($records->get(StatusEnum::FINISHED->value)?->count() ?? 0) + ($records->get(StatusEnum::FAILED->value)?->count() ?? 0)) / $this->stackAction->total_records * 100, 1);
        }
        $this->groupedRecords = $records->map(fn(Collection $records) => $records->count());
    }

    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('queueable-stack-actions::stack-action-notification');
    }

    public function dismiss(): void
    {
        $this->stackAction->dismissed_at = now();
        $this->stackAction->save();
    }
}
