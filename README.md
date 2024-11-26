# This is my package queueable-stack-actions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/aliheidarian1984/queueable-stack-actions-legal.svg?style=flat-square)](https://packagist.org/packages/aliheidarian1984/queueable-stack-actions-legal)
[![Total Downloads](https://img.shields.io/packagist/dt/aliheidarian1984/queueable-stack-actions-legal.svg?style=flat-square)](https://packagist.org/packages/aliheidarian1984/queueable-stack-actions-legal)


This Filament plugin simplifies managing stack operations asynchronously in a queue. It provides tracking and status updates for tasks, while supporting both action calls and job dispatches. Excellent for stack data updates and tasks with Filament & Livewire support for real-time notifications.

## Installation

You can install the package via composer:

```bash
composer require aliheidarian1984/queueable-stack-actions-legal
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="queueable-stack-actions-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="queueable-stack-actions-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="queueable-stack-actions-views"
```


## Usage

First you will need to register this plugin on your Filament panel

```php
use \Aliheidarian1984\QueueableStackActionsLegal\QueueableStackActionsPlugin;
use Filament\View\PanelsRenderHook;
\Aliheidarian1984\QueueableStackActionsLegal\Enums\StatusEnum;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            QueueableStackActionsPlugin::make()
                ->stackActionModel(YourStackActionModel::class) // (optional) - Allows you to register your own model which extends \Aliheidarian1984\QueueableStackActionsLegal\Models\StackAction
                ->stackActionRecordModel(YourStackActionRecordModel::class) // (optional) - Allows you to register your own model for records which extends \Aliheidarian1984\QueueableStackActionsLegal\Models\StackActionRecord
                ->renderHook(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE) // (optional) - Allows you to change where notification is rendered, multiple render hooks can be passed as array [Default: PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE]
                ->pollingInterval('5s') // (optional) - Allows you to change or disable polling interval, set to null to disable. [Default: 5s]
                ->queue('redis', 'default')  // (optional) - Allows you to change which connection and queue should be used [Default: env('QUEUE_CONNECTION'), default]
                ->resource(YourStackActionResource::class) // (optional) - Allows you to change which resource should be used to display historical stack actions
                ->colors([
                    StatusEnum::QUEUED->value => 'slate',
                    StatusEnum::IN_PROGRESS->value => 'info',
                    StatusEnum::FINISHED->value => 'success',
                    StatusEnum::FAILED->value => 'danger',
                ]), // (optional) - Allows you to change notification and badge colors used for statuses. Uses filament colors defined in panel provider. [Default: as show in method]
        ]);
}
```

To start leveraging the benefits of this package, you'll initially create a job tailored to manage your unique stack action records. This specialized job should inherit from the `Aliheidarian1984\QueueableStackActionsLegal\Jobs\StackActionJob` class, enabling it to seamlessly employ the features of the package.

```php
<?php

namespace App\Jobs;

use Aliheidarian1984\QueueableStackActionsLegal\Filament\Actions\ActionResponse;
use Aliheidarian1984\QueueableStackActionsLegal\Jobs\StackActionJob;

class DeleteUserStackActionJob extends StackActionJob
{
    protected function action($record, ?array $data): ActionResponse
    {
        if($record->isAdmin()) {
            return  ActionResponse::make()
                             ->failure()
                             ->message('Admin users cannot be deleted');
        }
    
        return ActionResponse::make()
                             ->success();
    }
}
```

Following that, create a `QueueableStackAction`  and link it to the job you've just created. This process directly assigns the job to the action.
```php
...
->stackActions([
    QueueableStackAction::make('delete_user')
                        ->label('Delete selected')
                        ->job(DeleteUserStackActionJob::class)
])
```

Once set up, this will generate notifications to keep users apprised of your stack action progress on the current page. The information remains visible until manually dismissed, providing an unintrusive user experience.

![Stack Action Notification](https://raw.githubusercontent.com/aliheidarian1984/queueable-stack-actions-legal/main/resources/images/notification.png)

The notification is contextually aware and will only appear on the page where the action was initiated by the user. This tailored approach keeps things neat and relevant. It comes with an easy dismissal feature; a simple click on 'X' will close the notification.

Even after the task execution, all stack action records are preserved for reference. They can readily be accessed via the `StackActionResource`, ensuring continuity and availability of information when needed.

![Stack Action Notification](https://raw.githubusercontent.com/aliheidarian1984/queueable-stack-actions-legal/main/resources/images/resource.png)
![Stack Action Notification](https://raw.githubusercontent.com/aliheidarian1984/queueable-stack-actions-legal/main/resources/images/view-action.png)

## Changelog

Please see [CHANGELOG](https://github.com/aliheidarian1984/queueable-stack-actions-legal//blob/HEAD/CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/aliheidarian1984/queueable-stack-actions-legal//blob/HEAD/.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](https://github.com/aliheidarian1984/queueable-stack-actions-legal/security/policy) on how to report security vulnerabilities.

## Credits

- [Eddie Rusinskas](https://github.com/bytexr)
- [All Contributors](https://github.com/aliheidarian1984/queueable-stack-actions-legal/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/aliheidarian1984/queueable-stack-actions-legal/blob/HEAD/LICENSE.md) for more information.
# queueable-bulk-actions-legal
# queueable-stack-actions-legal
# queueable-stack-actions-legal
# queueable-stack-actions-legal
