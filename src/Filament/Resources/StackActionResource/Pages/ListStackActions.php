<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources\StackActionResource\Pages;

use Aliheidarian1984\QueueableStackActionsLegal\Filament\Resources\StackActionResource;
use Filament\Resources\Pages\ListRecords;

class ListStackActions extends ListRecords
{
    protected static string $resource = StackActionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
