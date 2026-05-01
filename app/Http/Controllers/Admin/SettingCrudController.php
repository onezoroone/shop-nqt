<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SettingCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SettingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Setting::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/setting');
        CRUD::setEntityNameStrings('setting', 'settings');

        // Settings should only be edited, not created/deleted
        CRUD::denyAccess(['create', 'delete', 'show']);
    }

    protected function setupListOperation()
    {
        CRUD::column('key')->type('text')->label('Setting Key');
        CRUD::column('value')->type('text')->limit(80)->label('Value');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(SettingRequest::class);

        CRUD::field('key')->type('text')->attributes(['readonly' => 'readonly'])
            ->hint('Setting key (read-only)');
        CRUD::field('value')->type('textarea')->attributes(['rows' => 4])
            ->label('Value');
    }
}
