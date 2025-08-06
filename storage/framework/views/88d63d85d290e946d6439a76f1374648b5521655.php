<?php $__env->startSection('container'); ?>
<style>
    .umkm-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .umkm-card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .umkm-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .umkm-card-body {
        padding: 1.5rem; /* [RESPONSIVE] Mengurangi padding di layar kecil */
    }

    .btn-umkm {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center; /* [RESPONSIVE] Center content inside button */
        gap: 8px;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }

    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
    }

    .page-title {
        color: white;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .page-title h1 {
        font-size: 2rem; /* [RESPONSIVE] Menyesuaikan ukuran font */
        font-weight: 800;
        margin-bottom: 10px;
    }

    .page-title p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .table-umkm {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .table-umkm thead th {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 15px 12px;
        white-space: nowrap;
    }

    .table-umkm tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
    }

    .table-umkm tbody tr:hover {
        background-color: #f8f9fa;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    @media (min-width: 768px) {
        .page-title h1 {
            font-size: 2.5rem;
        }
        .umkm-card-body {
            padding: 25px;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>🖨️ CETAK BARCODE BARANG</h1>
        <p>Pilih barang yang ingin dicetak barcodenya dalam satu lembar A4 (maks. 12)</p>
    </div>

    <?php if(session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="umkm-card">
        <div class="umkm-card-header">
            <h3 class="umkm-card-title">
                <i class="bi bi-list-check"></i>
                Pilih Barang
            </h3>
        </div>
        <div class="umkm-card-body">
            <form action="<?php echo e(route('goods.cetakbarcode.pdf')); ?>" method="POST" id="barcodePrintForm" onsubmit="handleFormSubmit(this)">
                <?php echo csrf_field(); ?>
                
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mb-4 gap-3">
                    <div class="form-text fw-bold">
                        <span id="selectedCount">Terpilih: 0</span> / 12 barang
                    </div>
                    <button type="submit" class="btn btn-umkm w-100 w-sm-auto" id="printButton" disabled>
                        <i class="bi bi-printer"></i> Cetak Barcode
                    </button>
                </div>

                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari berdasarkan nama barang...">
                </div>

                <div class="table-responsive">
                    <table class="table table-umkm">
                        <thead>
                            <tr>
                                <th style="width: 5%;">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                                    </div>
                                </th>
                                <th style="width: 5%;">No</th>
                                <th>Nama Barang</th>
                                <th class="d-none d-md-table-cell">Barcode</th>
                                <th class="d-none d-sm-table-cell">Harga Jual</th>
                                <th class="d-none d-md-table-cell">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $good): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input good-checkbox" type="checkbox" name="selected_goods[]" value="<?php echo e($good->id); ?>" id="goodCheckbox<?php echo e($good->id); ?>" <?php echo e($good->barcode ? '' : 'disabled'); ?>>
                                    </div>
                                </td>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <strong><?php echo e($good->nama); ?></strong>
                                    <?php if(!$good->barcode): ?>
                                        <span class="badge bg-danger ms-2">Tidak ada Barcode</span>
                                    <?php endif; ?>
                                </td>
                                <td class="d-none d-md-table-cell"><?php echo e($good->barcode ?? '-'); ?></td>
                                <td class="d-none d-sm-table-cell">Rp. <?php echo e(number_format($good->harga, 0, ',', '.')); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo e($good->stok); ?> unit</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        <h5>Belum ada data barang</h5>
                                        <p>Tidak ada barang yang tersedia untuk dicetak barcodenya.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function handleFormSubmit(form) {
    const button = form.querySelector('button[type="submit"]');
    if (button && !button.disabled) {
        const originalButtonHTML = button.innerHTML;

        button.disabled = true;
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Mencetak...
        `;

        setTimeout(function() {
            button.disabled = false;
            button.innerHTML = originalButtonHTML;
            updateSelectionCount();
        }, 3000);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.good-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const printButton = document.getElementById('printButton');
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('.table-umkm tbody');
    const maxSelection = 12;

    function updateSelectionCount() {
        const checkedCount = document.querySelectorAll('.good-checkbox:checked').length;
        selectedCountSpan.textContent = `Terpilih: ${checkedCount}`;

        if (checkedCount > maxSelection) {
            printButton.disabled = true;
            selectedCountSpan.classList.add('text-danger');
            selectedCountSpan.textContent = `Terpilih: ${checkedCount} (Maks. ${maxSelection})`;
        } else if (checkedCount === 0) {
            printButton.disabled = true;
            selectedCountSpan.classList.remove('text-danger');
        } else {
            printButton.disabled = false;
            selectedCountSpan.classList.remove('text-danger');
        }
        updateSelectAllState();
    }

    function updateSelectAllState() {
        const visibleEnabledCheckboxes = Array.from(document.querySelectorAll('.good-checkbox:not(:disabled)')).filter(cb => cb.closest('tr').style.display !== 'none');
        const checkedVisibleEnabledCheckboxes = Array.from(document.querySelectorAll('.good-checkbox:checked:not(:disabled)')).filter(cb => cb.closest('tr').style.display !== 'none');

        if (visibleEnabledCheckboxes.length > 0 && visibleEnabledCheckboxes.length === checkedVisibleEnabledCheckboxes.length) {
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.checked = false;
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        const isCheckingAll = this.checked;
        const itemsToSelect = Array.from(checkboxes).filter(cb => !cb.disabled && cb.closest('tr').style.display !== 'none');

        if (isCheckingAll) {
            itemsToSelect.forEach(cb => cb.checked = false);
            itemsToSelect.slice(0, maxSelection).forEach(cb => cb.checked = true);
        } else {
            itemsToSelect.forEach(cb => cb.checked = false);
        }

        updateSelectionCount();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const currentCheckedCount = document.querySelectorAll('.good-checkbox:checked').length;

            if (currentCheckedCount > maxSelection && this.checked) {
                this.checked = false;
                alert(`Anda hanya dapat memilih maksimal ${maxSelection} barang.`);
            }
            updateSelectionCount();
        });
    });

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();

        tableBody.querySelectorAll('tr').forEach(row => {
            const itemNameCell = row.querySelector('td:nth-child(3)');
            if (itemNameCell) {
                const itemName = itemNameCell.textContent.toLowerCase();
                if (itemName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
        updateSelectAllState();
    });

    updateSelectionCount();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Repo_Git\Gerai-umkm-mart-finalisasi\resources\views/dashboard/goods/cetakbarcode.blade.php ENDPATH**/ ?>