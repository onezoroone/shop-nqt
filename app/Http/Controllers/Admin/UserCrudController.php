<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class UserCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;

    public function setup()
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/user');
        CRUD::setEntityNameStrings('người dùng', 'người dùng');

        CRUD::denyAccess(['create', 'update', 'delete']);
    }

    protected function setupListOperation()
    {
        CRUD::addClause('with', ['orders']);

        CRUD::column('id')->label('ID');
        CRUD::column('name')->label('Tên');
        CRUD::column('email')->label('Email');
        CRUD::addColumn([
            'name' => 'orders_count',
            'label' => 'Số lượng đơn',
            'type' => 'closure',
            'function' => function (User $entry): string {
                return (string) $entry->orders->count();
            },
        ]);
        CRUD::addColumn([
            'name' => 'total_spent',
            'label' => 'Tổng đã mua',
            'type' => 'closure',
            'function' => function (User $entry): string {
                $total = $entry->orders
                    ->whereIn('status', ['paid', 'completed'])
                    ->sum('total_amount');

                return '$'.number_format((float) $total, 2);
            },
        ]);
        CRUD::column('created_at')->type('datetime')->label('Ngày tạo');
    }

    protected function setupShowOperation()
    {
        CRUD::addClause('with', ['orders.items.product']);

        CRUD::column('id')->label('ID');
        CRUD::column('name')->label('Tên');
        CRUD::column('email')->label('Email');
        CRUD::column('created_at')->type('datetime')->label('Ngày tạo');

        CRUD::addColumn([
            'name' => 'orders_count',
            'label' => 'Số lượng đơn',
            'type' => 'closure',
            'function' => function (User $entry): string {
                return (string) $entry->orders->count();
            },
        ]);
        CRUD::addColumn([
            'name' => 'orders_summary',
            'label' => 'Đơn hàng đã mua',
            'type' => 'closure',
            'escaped' => false,
            'function' => function (User $entry): string {
                if ($entry->orders->isEmpty()) {
                    return 'Chưa có đơn hàng.';
                }

                return $entry->orders
                    ->sortByDesc('id')
                    ->map(function ($order): string {
                        $products = $order->items
                            ->map(fn ($item): string => ($item->product?->title ?? 'Sản phẩm đã xóa').' x'.$item->quantity)
                            ->implode(', ');

                        return sprintf(
                            '<div>#%d - %s - $%s (%s)</div><div style="font-size: 12px; opacity: .8; margin-bottom: 8px;">%s</div>',
                            $order->id,
                            e($order->status),
                            number_format((float) $order->total_amount, 2),
                            e($order->created_at?->format('d/m/Y H:i') ?? ''),
                            e($products !== '' ? $products : 'Không có sản phẩm')
                        );
                    })
                    ->implode('');
            },
        ]);
    }
}
