<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 *
 * @property-read CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup()
    {
        CRUD::setModel(Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    protected function setupListOperation()
    {
        CRUD::column('title')->type('text')->limit(50);
        CRUD::column('categories')->type('select_multiple')
            ->entity('categories')->attribute('name')->model(Category::class)->label('Categories');
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
        CRUD::field('categories')->type('select2_multiple')
            ->entity('categories')->attribute('name')
            ->model(Category::class)
            ->pivot(true)
            ->options(function ($query) {
                return $query->where('type', 'product')->orderBy('sort_order')->get();
            })->label('Thể loại (chọn nhiều)');
        CRUD::field('status')->type('enum')->size(3);
        CRUD::field('is_featured')->type('boolean')->label('Featured')->size(3);
        CRUD::field('excerpt')->type('textarea')->attributes(['rows' => 3]);
        CRUD::field('description')->type('summernote')->tab('Thông tin cơ bản');
        CRUD::field('changelog')->type('summernote')->label('Changelog (Lịch sử cập nhật)')->tab('Changelog');
        CRUD::field('thumbnail')->type('browse')->label('Ảnh đại diện (Thumbnail)');
        CRUD::field('gallery')->type('gallery_preview')->label('Gallery (chọn nhiều ảnh)');
        CRUD::field('price')->type('number')
            ->prefix('$')->attributes(['step' => '0.01'])->size(4);
        CRUD::field('sale_price')->type('number')
            ->prefix('$')->attributes(['step' => '0.01'])->size(4)
            ->hint('Leave empty for no sale');
        CRUD::field('download_count')->type('number')->default(0)->size(4)->label('Downloads');
        CRUD::field('tech_stack_csv')->type('textarea')
            ->label('Technologies (comma separated)')->hint('Example: PHP, Laravel, Tailwind');
        CRUD::field('features_csv')->type('textarea')
            ->label('Features (comma separated)')->hint('Example: Secure login, Dark mode, API');
        CRUD::field('demo_url')->type('url')->size(6)->label('Demo URL');
        CRUD::field('source_url')->type('url')->size(6)->label('Link Source Code (Google Drive, vv.)')->hint('Sẽ hiển thị cho khách sau khi thanh toán xong.');

        // SEO Fields
        CRUD::field('meta_title')->type('text')->tab('SEO')->label('Meta Title')->hint('Tiêu đề hiển thị trên kết quả tìm kiếm (Để trống sẽ tự lấy tiêu đề sản phẩm).');
        CRUD::field('meta_description')->type('textarea')->tab('SEO')->label('Meta Description')->hint('Mô tả ngắn gọn gọn (tối đa 160 ký tự).');
        CRUD::field('meta_keywords')->type('text')->tab('SEO')->label('Meta Keywords')->hint('Từ khóa, cách nhau bằng dấu phẩy.');
        CRUD::field('published_at')->type('datetime')->size(6);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
