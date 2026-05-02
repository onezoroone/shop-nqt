<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
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
    use CreateOperation { store as traitStore; }
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        CRUD::setModel(Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    protected function setupListOperation()
    {
        $this->crud->query = $this->crud->query->withCount('variants');

        CRUD::column('title')->type('text')->limit(50);
        CRUD::column('categories')->type('select_multiple')
            ->entity('categories')->attribute('name')->model(Category::class)->label('Categories');
        CRUD::column('price')->type('number')->prefix('$')->decimals(2);
        CRUD::column('sale_price')->type('number')->prefix('$')->decimals(2);
        CRUD::column('variants_count')->label('Variants')->type('number')->suffix(' biến thể');
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
        CRUD::field('description')->type('ckeditor')->tab('Thông tin cơ bản');
        CRUD::field('changelog')->type('ckeditor')->label('Changelog (Lịch sử cập nhật)')->tab('Changelog');
        CRUD::field('thumbnail')->type('browse')->label('Ảnh đại diện (Thumbnail)');
        CRUD::field('gallery')->type('gallery_preview')->label('Gallery (chọn nhiều ảnh)');
        CRUD::field('price')->type('number')
            ->prefix('$')->attributes(['step' => '0.01'])->size(4);
        CRUD::field('sale_price')->type('number')
            ->prefix('$')->attributes(['step' => '0.01'])->size(4)
            ->hint('Leave empty for no sale');
        CRUD::field('download_count')->type('number')->default(0)->size(4)->label('Downloads');
        CRUD::field('tech_stack')->type('textarea')
            ->label('Technologies (comma separated)')->hint('Example: PHP, Laravel, Tailwind');
        CRUD::field('features')->type('textarea')
            ->label('Features (comma separated)')->hint('Example: Secure login, Dark mode, API');
        CRUD::field('demo_url')->type('url')->size(6)->label('Demo URL');
        CRUD::field('source_url')->type('url')->size(6)->label('Link Source Code (Google Drive, vv.)')->hint('Dùng cho sản phẩm không có biến thể. Nếu có biến thể, điền link ở từng biến thể.');

        // ===== Variants =====
        CRUD::field('variants')->type('variants_editor')->label('Biến thể')->tab('Biến thể');

        // SEO Fields
        CRUD::field('meta_title')->type('text')->tab('SEO')->label('Meta Title')->hint('Tiêu đề hiển thị trên kết quả tìm kiếm (Để trống sẽ tự lấy tiêu đề sản phẩm).');
        CRUD::field('meta_description')->type('textarea')->tab('SEO')->label('Meta Description')->hint('Mô tả ngắn gọn gọn (tối đa 160 ký tự).');
        CRUD::field('meta_keywords')->type('text')->tab('SEO')->label('Meta Keywords')->hint('Từ khóa, cách nhau bằng dấu phẩy.');
        CRUD::field('published_at')->type('datetime')->size(6);
    }

    protected function setupShowOperation()
    {
        CRUD::column('title')->type('text');
        CRUD::column('slug')->type('text');
        CRUD::column('categories')->type('select_multiple')
            ->entity('categories')->attribute('name')->model(Category::class);
        CRUD::column('status')->type('enum');
        CRUD::column('is_featured')->type('boolean')->label('Featured');
        CRUD::column('excerpt')->type('text')->limit(200);
        CRUD::column('description')->type('markdown')->label('Description');
        CRUD::column('changelog')->type('markdown')->label('Changelog');
        CRUD::column('thumbnail')->type('image')->label('Thumbnail');
        CRUD::column('gallery')->type('text')->value(function ($entry) {
            $gallery = is_array($entry->gallery) ? $entry->gallery : json_decode($entry->gallery ?? '[]', true);

            return implode(', ', $gallery ?: []);
        })->label('Gallery');
        CRUD::column('price')->type('number')->prefix('$')->decimals(2);
        CRUD::column('sale_price')->type('number')->prefix('$')->decimals(2);
        CRUD::column('variants_list')->label('Biến thể')->type('custom_html')->value(function ($entry) {
            $entry->loadMissing('variants');
            $variants = $entry->variants;
            if ($variants->isEmpty()) {
                return '<em class="text-muted">Không có biến thể</em>';
            }
            $html = '<table class="table table-sm table-bordered"><thead><tr><th>Tên</th><th>Giá</th><th>Sale</th><th>SKU</th><th>Mặc định</th></tr></thead><tbody>';
            foreach ($variants as $v) {
                $html .= '<tr>';
                $html .= '<td>'.$v->name.'</td>';
                $html .= '<td>$'.number_format($v->price, 2).'</td>';
                $html .= '<td>'.($v->sale_price ? '$'.number_format($v->sale_price, 2) : '—').'</td>';
                $html .= '<td>'.($v->sku ?: '—').'</td>';
                $html .= '<td>'.($v->is_default ? '✅' : '').'</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';

            return $html;
        });
        CRUD::column('download_count')->type('number')->label('Downloads');
        CRUD::column('demo_url')->type('url')->label('Demo URL');
        CRUD::column('source_url')->type('url')->label('Source URL');
        CRUD::column('meta_title')->type('text')->label('Meta Title');
        CRUD::column('meta_description')->type('text')->label('Meta Description');
        CRUD::column('meta_keywords')->type('text')->label('Meta Keywords');
        CRUD::column('published_at')->type('datetime');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $entry = $this->crud->getCurrentEntry();
        CRUD::field('tech_stack')->value(implode(', ', $entry->tech_stack ?? []));
        CRUD::field('features')->value(implode(', ', $entry->features ?? []));

        // Pre-fill variants editor
        $entry->loadMissing('variants');
        $variantsData = $entry->variants->map(fn (ProductVariant $v) => [
            'name' => $v->name,
            'price' => $v->price,
            'sale_price' => $v->sale_price,
            'source_url' => $v->source_url,
            'demo_url' => $v->demo_url,
            'sku' => $v->sku,
            'sort_order' => $v->sort_order,
            'is_default' => $v->is_default,
        ])->toArray();

        CRUD::field('variants')->value(json_encode($variantsData));
    }

    /**
     * Convert comma-separated tech_stack and features strings to arrays before saving.
     * Also strips variants from the request to prevent Backpack from processing it.
     */
    private function convertCsvFieldsToArrays(): void
    {
        $this->crud->setRequest($this->crud->validateRequest());

        // Capture variants JSON before stripping
        $this->variantsJson = $this->crud->getRequest()->input('variants', '[]');

        $request = $this->crud->getRequest();
        $request->request->remove('variants');

        $this->crud->setRequest($request->merge([
            'tech_stack' => array_values(array_filter(array_map('trim', explode(',', $request->input('tech_stack', ''))))),
            'features' => array_values(array_filter(array_map('trim', explode(',', $request->input('features', ''))))),
        ]));
        $this->crud->unsetValidation();
    }

    /** @var string Temporary storage for variants JSON between request processing and save */
    private string $variantsJson = '[]';

    /**
     * Sync product variants from the editor field data.
     */
    private function syncVariants(Product $product): void
    {
        $variantsInput = json_decode($this->variantsJson, true);

        // Remove old variants and recreate
        $product->variants()->delete();

        if (! is_array($variantsInput)) {
            return;
        }

        foreach ($variantsInput as $variantData) {
            $name = trim($variantData['name'] ?? '');
            if ($name === '') {
                continue;
            }

            $product->variants()->create([
                'name' => $name,
                'price' => (float) ($variantData['price'] ?? 0),
                'sale_price' => ! empty($variantData['sale_price']) ? (float) $variantData['sale_price'] : null,
                'source_url' => $variantData['source_url'] ?? null,
                'demo_url' => $variantData['demo_url'] ?? null,
                'sku' => $variantData['sku'] ?? null,
                'sort_order' => (int) ($variantData['sort_order'] ?? 0),
                'is_default' => ! empty($variantData['is_default']),
            ]);
        }
    }

    public function store()
    {
        $this->convertCsvFieldsToArrays();

        $response = $this->traitStore();

        $this->syncVariants($this->crud->getCurrentEntry());

        return $response;
    }

    public function update()
    {
        $this->convertCsvFieldsToArrays();

        $response = $this->traitUpdate();

        $this->syncVariants($this->crud->getCurrentEntry());

        return $response;
    }
}
