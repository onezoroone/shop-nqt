@if ($entry->status == 'pending')
    <a href="javascript:void(0)" onclick="approveEntry(this)" data-route="{{ route('order.approve', $entry->id) }}" class="btn btn-sm btn-link text-success" data-button-type="approve">
        <i class="la la-check"></i> Duyệt đơn
    </a>
@endif

@if ($crud->hasAccess('update') && !isset($approveEntryScriptLoaded))
    @php
        $approveEntryScriptLoaded = true;
    @endphp

    @push('after_scripts') @if (request()->ajax()) @endpush @endif
    @bassetBlock('backpack/crud/buttons/approve-button.js')
    <script>
        if (typeof approveEntry != 'function') {
            $('[data-button-type=approve]').unbind('click');

            function approveEntry(button) {
                // ask for confirmation before approving an item
                // e.g. Drop down menu with a confirm button
                var route = $(button).attr('data-route');

                swal({
                    title: "Duyệt đơn hàng này?",
                    text: "Trạng thái đơn hàng sẽ được chuyển thành Đã thanh toán.",
                    icon: "info",
                    buttons: ["Hủy bỏ", "Đồng ý duyệt"],
                    dangerMode: false,
                }).then((value) => {
                    if (value) {
                        $.ajax({
                            url: route,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(result) {
                                if (result == 1) {
                                    // Show an alert with the result
                                    new Noty({
                                        type: "success",
                                        text: "Đã duyệt đơn hàng thành công.",
                                    }).show();

                                    // Hide the modal, if any
                                    $('.modal').modal('hide');

                                    // Refresh the table
                                    if (typeof window.crud !== 'undefined' && window.crud.table) {
                                        window.crud.table.ajax.reload();
                                    } else {
                                        location.reload();
                                    }
                                } else {
                                    // if the result is an array, it means 
                                    // we have notification bubbles to show
                                    if (result instanceof Object) {
                                        // trigger one or more bubble notifications 
                                        Object.entries(result).forEach(function(entry) {
                                            var type = entry[0];
                                            entry[1].forEach(function(message, i) {
                                                new Noty({
                                                    type: type,
                                                    text: message
                                                }).show();
                                            });
                                        });
                                    } else {
                                        // Show an error alert
                                        new Noty({
                                            type: "error",
                                            text: "Đã xảy ra lỗi khi duyệt đơn hàng."
                                        }).show();
                                    }
                                }
                            },
                            error: function(result) {
                                // Show an alert with the result
                                new Noty({
                                    type: "warning",
                                    text: "Đã xảy ra lỗi hệ thống."
                                }).show();
                            }
                        });
                    }
                });
            }
        }
    </script>
    @endBassetBlock
    @if (!request()->ajax()) @endpush @endif
@endif
