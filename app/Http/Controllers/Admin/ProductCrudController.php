<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    protected function setupListOperation()
    {
        CRUD::column('title')->type('text')->limit(50);
        CRUD::column('category_id')->type('select')
            ->entity('category')->attribute('name')->model(\App\Models\Category::class);
        CRUD::column('price')->type('number')->prefix('$')->decimals(2);
        CRUD::column('sale_price')->type('number')->prefix('$')->decimals(2);
        CRUD::column('download_count')->type('number')->label('Downloads');
        CRUD::column('status')->type('enum');
        CRUD::column('is_featured')->type('boolean')->label('Featured');
        CRUD::column('published_at')->type('datetime');

        CRUD::orderBy('published_at', 'desc');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::field('title')->type('text')->size(8);
        CRUD::field('slug')->type('text')->size(4)
            ->hint('Leave empty to auto-generate');
        CRUD::field('category_id')->type('select2')
            ->entity('category')->attribute('name')
            ->model(\App\Models\Category::class)
            ->options(function ($query) {
                return $query->where('type', 'product')->orderBy('sort_order')->get();
            })->size(6);
        CRUD::field('status')->type('enum')->size(3);
        CRUD::field('is_featured')->type('boolean')->label('Featured')->size(3);
        CRUD::field('excerpt')->type('textarea')->attributes(['rows' => 3]);
        CRUD::field('description')->type('wysiwyg');
        CRUD::field('thumbnail')->type('upload')
            ->withFiles(['disk' => 'public', 'path' => 'products']);
        CRUD::field('price')->type('number')
            ->prefix('$')->attributes(['step' => '0.01'])->size(4);
        CRUD::field('sale_price')->type('number')
            ->prefix('$')->attributes(['step' => '0.01'])->size(4)
            ->hint('Leave empty for no sale');
        CRUD::field('download_count')->type('number')->default(0)->size(4)->label('Downloads');
        CRUD::field('tech_stack')->type('repeatable')
            ->subfields([['name' => 'value', 'type' => 'text', 'label' => 'Technology']])
            ->hint('Technologies used');
        CRUD::field('features')->type('repeatable')
            ->subfields([['name' => 'value', 'type' => 'text', 'label' => 'Feature']])
            ->hint('Product features list');
        CRUD::field('demo_url')->type('url')->size(6)->label('Demo URL');
        CRUD::field('published_at')->type('datetime')->size(6);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
