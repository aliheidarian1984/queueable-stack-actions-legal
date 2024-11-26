<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Livewire;

use Aliheidarian1984\QueueableStackActionsLegal\Enums\StackActions\TypeEnum;
use Aliheidarian1984\QueueableStackActionsLegal\Support\Config;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StackActionNotifications extends Component
{
    public Collection $stackActions;

    public string $identifier;

    protected $listeners = ['refreshStackActionNotifications' => '$refresh'];

    public function boot(): void
    {
        $team = filament()->getTenant();
        $this->stackActions = Config::stackActionModel()::query()
            ->where('type', TypeEnum::TABLE)
            ->where('team_id',$team['id'] )
            ->where('identifier', $this->identifier)
            ->whereNull('dismissed_at')
            ->get();
    }

    public function render(): Factory | Application | View | \Illuminate\Contracts\Foundation\Application
    {
        return view('queueable-stack-actions::stack-action-notifications');
    }
}
