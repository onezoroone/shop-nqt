<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'name');
        CRUD::set('reorder.max_level', 1);
    }

    protected function setupListOperation()
    {
        CRUD::column('name')->type('text');
        CRUD::column('slug')->type('text');
        CRUD::column('type')->type('enum');
        CRUD::column('icon')->type('text');
        CRUD::column('sort_order')->type('number');

        CRUD::orderBy('type')->orderBy('sort_order');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(CategoryRequest::class);

        CRUD::field('name')->type('text')->size(6);
        CRUD::field('slug')->type('text')->size(6)
            ->hint('Leave empty to auto-generate from name');
        CRUD::field('type')->type('enum')->size(6);
        CRUD::field('icon')->type('text')->size(6)
            ->hint('LineAwesome icon class (e.g. la-globe)');
        CRUD::field('sort_order')->type('number')->default(0)->size(6);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
