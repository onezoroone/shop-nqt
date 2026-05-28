<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

class OrderCrudController extends CrudController
{
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup()
    {
        CRUD::setModel(Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/order');
        CRUD::setEntityNameStrings('đơn hàng', 'các đơn hàng');
    }

    protected function setupListOperation()
    {
        CRUD::addClause('with', ['user.orders', 'items.product']);

        CRUD::column('id')->label('ID');
        CRUD::addColumn([
            'name' => 'customer_info',
            'label' => 'Khách hàng',
            'type' => 'closure',
            'function' => function (Order $entry): string {
                $name = $entry->user?->name ?? 'N/A';
                $email = $entry->user?->email;

                if (! $email) {
                    return $name;
                }

                return sprintf('%s (%s)', $name, $email);
            },
        ]);
        CRUD::addColumn([
            'name' => 'customer_orders_count',
            'label' => 'Số đơn của user',
            'type' => 'closure',
            'function' => function (Order $entry): string {
                return (string) ($entry->user?->orders?->count() ?? 0);
            },
        ]);
        CRUD::addColumn([
            'name' => 'products_summary',
            'label' => 'Sản phẩm đã mua',
            'type' => 'closure',
            'function' => function (Order $entry): string {
                $products = $entry->items
                    ->map(function ($item): string {
                        $title = $item->product?->title ?? 'Sản phẩm đã xóa';

                        if ($item->variant_name) {
                            $title .= ' - '.$item->variant_name;
                        }

                        return $title.' x'.$item->quantity;
                    })
                    ->take(3)
                    ->implode(', ');

                if ($products === '') {
                    return '-';
                }

                $remaining = $entry->items->count() - 3;

                if ($remaining > 0) {
                    return $products.' (+'.$remaining.' sản phẩm)';
                }

                return $products;
            },
        ]);
        CRUD::column('total_amount')->type('number')->prefix('$')->label('Tổng tiền');
        CRUD::column('status')->type('enum')->options([
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ])->label('Trạng thái');
        CRUD::column('payment_method')->label('PT Thanh toán');
        CRUD::column('created_at')->type('datetime')->label('Ngày tạo');

        CRUD::addButtonFromView('line', 'approve_order', 'approve_order', 'beginning');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'status' => 'required|in:pending,paid,completed,cancelled',
        ]);

        CRUD::field('status')->type('enum')->options([
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ])->label('Trạng thái đơn hàng');

        CRUD::field('notes')->type('textarea')->label('Ghi chú của admin');
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        CRUD::column('notes')->label('Ghi chú');
        CRUD::addColumn([
            'name' => 'products_detail',
            'label' => 'Chi tiết sản phẩm',
            'type' => 'closure',
            'escaped' => false,
            'function' => function (Order $entry): string {
                if ($entry->items->isEmpty()) {
                    return 'Không có sản phẩm.';
                }

                return $entry->items
                    ->map(function ($item): string {
                        $title = e($item->product?->title ?? 'Sản phẩm đã xóa');
                        $variant = $item->variant_name ? ' - '.e($item->variant_name) : '';

                        return sprintf(
                            '<div>%s%s x%d - $%s</div>',
                            $title,
                            $variant,
                            (int) $item->quantity,
                            number_format((float) $item->subtotal, 2)
                        );
                    })
                    ->implode('');
            },
        ]);
    }

    public function approve($id)
    {
        DB::transaction(function () use ($id): void {
            $order = Order::with('items')->lockForUpdate()->findOrFail($id);

            if ($order->status === 'paid') {
                return;
            }

            $order->status = 'paid';
            $order->save();

            foreach ($order->items as $item) {
                if (! $item->product_id || $item->quantity <= 0) {
                    continue;
                }

                DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('download_count', (int) $item->quantity);
            }
        });

        return 1;
    }
}
