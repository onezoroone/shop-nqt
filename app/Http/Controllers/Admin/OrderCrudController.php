<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
        CRUD::column('id')->label('ID');
        CRUD::column('user_id')->type('select')->entity('user')->attribute('name')->label('Khách hàng');
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
    }

    public function approve($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'paid';
        $order->save();

        return 1;
    }
}
