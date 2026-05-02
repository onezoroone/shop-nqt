{{-- Custom Variants Editor field for Backpack CRUD --}}
@php
    $variants = old('variants') ?? ($field['value'] ?? '[]');
    if (is_array($variants)) {
        $variants = json_encode($variants);
    }
@endphp

<div class="form-group col-md-12 mb-3">
    <label class="form-label fw-bold">🔀 Biến thể sản phẩm</label>
    <p class="text-muted small mb-3">Nếu sản phẩm có nhiều phiên bản (VD: Laravel, WordPress) với giá khác nhau, thêm biến thể ở đây.</p>

    <input type="hidden" name="{{ $field['name'] }}" id="variants-json" value="{{ $variants }}">

    <div id="variants-container">
        <table class="table table-sm table-bordered align-middle" id="variants-table">
            <thead class="table-light">
                <tr>
                    <th>Tên biến thể</th>
                    <th style="width: 11%">Giá ($)</th>
                    <th style="width: 11%">Sale ($)</th>
                    <th>Link Source</th>
                    <th>Link Demo</th>
                    <th style="width: 8%">SKU</th>
                    <th style="width: 6%">TT</th>
                    <th style="width: 3%">MĐ</th>
                    <th style="width: 3%"></th>
                </tr>
            </thead>
            <tbody id="variants-tbody"></tbody>
        </table>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-variant-btn">
            <i class="la la-plus"></i> Thêm biến thể
        </button>
    </div>
</div>

@push('after_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('variants-tbody');
    const hiddenInput = document.getElementById('variants-json');
    const addBtn = document.getElementById('add-variant-btn');

    let variantsData = [];
    try {
        variantsData = JSON.parse(hiddenInput.value || '[]');
        if (!Array.isArray(variantsData)) variantsData = [];
    } catch(e) {
        variantsData = [];
    }

    function createRow(data = {}) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" class="form-control form-control-sm v-name" value="${escHtml(data.name || '')}" placeholder="VD: Laravel"></td>
            <td><input type="number" class="form-control form-control-sm v-price" value="${data.price || ''}" step="0.01" min="0"></td>
            <td><input type="number" class="form-control form-control-sm v-sale-price" value="${data.sale_price || ''}" step="0.01" min="0"></td>
            <td><input type="url" class="form-control form-control-sm v-source-url" value="${escHtml(data.source_url || '')}" placeholder="https://..."></td>
            <td><input type="url" class="form-control form-control-sm v-demo-url" value="${escHtml(data.demo_url || '')}" placeholder="https://..."></td>
            <td><input type="text" class="form-control form-control-sm v-sku" value="${escHtml(data.sku || '')}"></td>
            <td><input type="number" class="form-control form-control-sm v-sort" value="${data.sort_order || 0}" min="0" style="width:50px"></td>
            <td class="text-center"><input type="checkbox" class="form-check-input v-default" ${data.is_default ? 'checked' : ''}></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-variant-btn" title="Xóa"><i class="la la-trash"></i></button></td>
        `;
        tbody.appendChild(tr);

        tr.querySelectorAll('input').forEach(input => input.addEventListener('change', syncToHidden));
        tr.querySelector('.remove-variant-btn').addEventListener('click', () => { tr.remove(); syncToHidden(); });
        tr.querySelector('.v-default').addEventListener('change', function() {
            if (this.checked) {
                tbody.querySelectorAll('.v-default').forEach(cb => { if (cb !== this) cb.checked = false; });
            }
            syncToHidden();
        });
    }

    function syncToHidden() {
        const rows = tbody.querySelectorAll('tr');
        const result = [];
        rows.forEach(tr => {
            const name = tr.querySelector('.v-name').value.trim();
            if (!name) return;
            result.push({
                name: name,
                price: parseFloat(tr.querySelector('.v-price').value) || 0,
                sale_price: tr.querySelector('.v-sale-price').value ? parseFloat(tr.querySelector('.v-sale-price').value) : null,
                source_url: tr.querySelector('.v-source-url').value.trim() || null,
                demo_url: tr.querySelector('.v-demo-url').value.trim() || null,
                sku: tr.querySelector('.v-sku').value.trim() || null,
                sort_order: parseInt(tr.querySelector('.v-sort').value) || 0,
                is_default: tr.querySelector('.v-default').checked,
            });
        });
        hiddenInput.value = JSON.stringify(result);
    }

    function escHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    variantsData.forEach(v => createRow(v));

    addBtn.addEventListener('click', () => {
        createRow();
        syncToHidden();
    });
});
</script>
@endpush
