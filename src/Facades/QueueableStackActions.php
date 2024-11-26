<?php

namespace Aliheidarian1984\QueueableStackActionsLegal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Aliheidarian1984\QueueableStackActionsLegal\QueueableStackActions
 */
class QueueableStackActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aliheidarian1984\QueueableStackActionsLegal\QueueableStackActions::class;
    }
}
