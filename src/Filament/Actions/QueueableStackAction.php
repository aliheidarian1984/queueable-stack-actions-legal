<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Filament\Actions;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StackActions\TypeEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Jobs\StackActionSetupJob;
use Aliheidarian1984\QueueableStackActionsLegal\Models\LegalStackAction;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use Closure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QueueableStackAction extends \Filament\Tables\Actions\StackAction
{
    private Closure | string | null $job = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this
        ->successNotificationTitle('عملیات گروهی با موفقیت به صف اضافه شد!لطفا تا پایان عملیات منتظر بمانید!')
        ->action(function (Collection $records, QueueableStackAction $action, array $data, Component $livewire) {
            if (! $action->getJob()) {
                throw new Exception(QueueableStackAction::class . 'نیاز به تنظیم یک جاب می باشد!لطفا با پشتیبانی تماس حاصل نمایید.');
            }

                $stackAction = $this->createStackAction(
                    identifier: $livewire::class,
                    totalRecords: $records->count(),
                    data: $data,
                );
                StackActionSetupJob::dispatch($stackAction, $records);
                $livewire->dispatch('refreshStackActionNotifications');
                $action->success();
            });
    }

    private function createStackAction(string $identifier, int $totalRecords, array $data): LegalStackAction
    {
        $team = filament()->getTenant();
        return Config::stackActionModel()::query()->create([
            'name' => $this->getLabel(),
            'type' => TypeEnum::TABLE,
            'identifier' => $identifier,
            'job' => $this->getJob(),
            'team_id' => $team['id'],
            'total_records' => $totalRecords,
            'data' => $data,
        ]);
    }

    public function job(Closure | string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->evaluate($this->job);
    }
}
