<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SkillRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SkillCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SkillCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Skill::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/skill');
        CRUD::setEntityNameStrings('skill', 'skills');
    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'name');
        CRUD::set('reorder.max_level', 1);
    }

    protected function setupListOperation()
    {
        CRUD::column('name')->type('text');
        CRUD::column('category')->type('text');
        CRUD::column('proficiency')->type('number')->suffix('%');
        CRUD::column('icon')->type('text');
        CRUD::column('sort_order')->type('number');

        CRUD::orderBy('category')->orderBy('sort_order');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(SkillRequest::class);

        CRUD::field('name')->type('text')->size(6);
        CRUD::field('category')->type('select_from_array')->size(6)
            ->options([
                'Backend' => 'Backend',
                'Frontend' => 'Frontend',
                'DevOps' => 'DevOps',
                'Tools' => 'Tools',
            ]);
        CRUD::field('proficiency')->type('number')->size(4)
            ->attributes(['min' => 0, 'max' => 100])
            ->suffix('%')->hint('0-100');
        CRUD::field('icon')->type('text')->size(4)
            ->hint('CSS class or SVG name');
        CRUD::field('sort_order')->type('number')->default(0)->size(4);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
