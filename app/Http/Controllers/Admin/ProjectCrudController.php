<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProjectRequest;
use App\Models\Category;
use App\Models\Project;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProjectCrudController
 *
 * @property-read CrudPanel $crud
 */
class ProjectCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup()
    {
        CRUD::setModel(Project::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/project');
        CRUD::setEntityNameStrings('project', 'projects');
    }

    protected function setupListOperation()
    {
        CRUD::column('title')->type('text')->limit(50);
        CRUD::column('category_id')->type('select')
            ->entity('category')->attribute('name')->model(Category::class);
        CRUD::column('status')->type('enum');
        CRUD::column('is_featured')->type('boolean')->label('Featured');
        CRUD::column('published_at')->type('datetime');
        CRUD::column('sort_order')->type('number');

        CRUD::orderBy('published_at', 'desc');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProjectRequest::class);

        CRUD::field('title')->type('text')->size(8);
        CRUD::field('slug')->type('text')->size(4)
            ->hint('Leave empty to auto-generate');
        CRUD::field('category_id')->type('select2')
            ->entity('category')->attribute('name')
            ->model(Category::class)
            ->options(function ($query) {
                return $query->where('type', 'project')->orderBy('sort_order')->get();
            })->size(6);
        CRUD::field('status')->type('enum')->size(3);
        CRUD::field('is_featured')->type('boolean')->label('Featured')->size(3);
        CRUD::field('excerpt')->type('textarea')->attributes(['rows' => 3]);
        CRUD::field('description')->type('ckeditor');
        CRUD::field('thumbnail')->type('browse')->label('Ảnh đại diện (Thumbnail)');
        CRUD::field('tech_stack_csv')->type('textarea')
            ->label('Technologies (comma separated)')->hint('Example: PHP, Laravel, Tailwind');
        CRUD::field('demo_url')->type('url')->size(6)->label('Demo URL');
        CRUD::field('source_url')->type('url')->size(6)->label('Source Code URL');

        // SEO Fields
        CRUD::field('meta_title')->type('text')->tab('SEO')->label('Meta Title')->hint('Tiêu đề hiển thị trên kết quả tìm kiếm (Để trống sẽ tự lấy tiêu đề dự án).');
        CRUD::field('meta_description')->type('textarea')->tab('SEO')->label('Meta Description')->hint('Mô tả ngắn gọn gọn (tối đa 160 ký tự).');
        CRUD::field('meta_keywords')->type('text')->tab('SEO')->label('Meta Keywords')->hint('Từ khóa, cách nhau bằng dấu phẩy.');
        CRUD::field('sort_order')->type('number')->default(0)->size(6);
        CRUD::field('published_at')->type('datetime')->size(6);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
