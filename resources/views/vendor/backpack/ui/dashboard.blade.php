@extends(backpack_view('blank'))

@php
    $totalRevenue = \App\Models\Order::whereIn('status', ['paid', 'completed'])->sum('total_amount');
    $totalOrders = \App\Models\Order::count();
    $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
    $totalUsers = \App\Models\User::count();
    $totalProducts = \App\Models\Product::count();

    $recentOrders = \App\Models\Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
    $recentContacts = \App\Models\Contact::orderBy('created_at', 'desc')->take(5)->get();
@endphp

@section('header')
    <div class="container-xl">
        <div class="page-header d-print-none text-white">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Tổng quan hệ thống</div>
                    <h2 class="page-title">
                        Bảng điều khiển quản trị
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            {{-- Thống kê nhanh --}}
            <div class="row row-deck row-cards mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Tổng Doanh Thu</div>
                            </div>
                            <div class="h1 mb-3 text-success">${{ number_format($totalRevenue, 2) }}</div>
                            <div class="d-flex mb-2">
                                <div>Từ các đơn đã thanh toán</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Tổng Đơn Hàng</div>
                            </div>
                            <div class="h1 mb-3 text-primary">{{ $totalOrders }}</div>
                            <div class="d-flex mb-2">
                                <div><span class="text-warning font-weight-bold">{{ $pendingOrders }}</span> đơn đang chờ duyệt</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Tổng Sản Phẩm</div>
                            </div>
                            <div class="h1 mb-3 text-info">{{ $totalProducts }}</div>
                            <div class="d-flex mb-2">
                                <div>Sản phẩm trên cửa hàng</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Tổng Khách Hàng</div>
                            </div>
                            <div class="h1 mb-3 text-purple">{{ $totalUsers }}</div>
                            <div class="d-flex mb-2">
                                <div>Tài khoản đã đăng ký</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards">
                {{-- Đơn hàng mới nhất --}}
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Đơn hàng mới nhất</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Mã Đơn</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                        <tr>
                                            <td><span class="text-muted">#{{ $order->id }}</span></td>
                                            <td>{{ $order->user ? $order->user->name : 'N/A' }}</td>
                                            <td class="font-weight-bold">${{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                @if($order->status == 'paid' || $order->status == 'completed')
                                                    <span class="badge bg-success me-1"></span> Đã thanh toán
                                                @elseif($order->status == 'pending')
                                                    <span class="badge bg-warning me-1"></span> Chờ duyệt
                                                @else
                                                    <span class="badge bg-danger me-1"></span> Đã huỷ
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Chưa có đơn hàng nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <a href="{{ backpack_url('order') }}" class="btn btn-primary btn-sm ml-auto">Xem tất cả đơn hàng</a>
                        </div>
                    </div>
                </div>

                {{-- Liên hệ mới nhất --}}
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liên hệ gần đây</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentContacts as $contact)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::limit($contact->name, 15) }}</td>
                                            <td>
                                                @if($contact->is_read)
                                                    <span class="text-muted"><i class="la la-check"></i> Đã đọc</span>
                                                @else
                                                    <span class="text-danger font-weight-bold"><i class="la la-envelope"></i> Mới</span>
                                                @endif
                                            </td>
                                            <td>{{ $contact->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">Không có liên hệ mới</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <a href="{{ backpack_url('contact') }}" class="btn btn-outline-primary btn-sm ml-auto">Quản lý liên hệ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
