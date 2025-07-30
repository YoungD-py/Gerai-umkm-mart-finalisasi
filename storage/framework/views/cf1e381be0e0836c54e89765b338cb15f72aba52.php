<!DOCTYPE html>
<html>
<head>
    <title>Cetak Barcode Barang</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 10mm;
        }

        .barcode-table {
            width: 100%;
            border-collapse: collapse;
            page-break-after: always;
        }

        .barcode-table:last-child {
            page-break-after: avoid;
        }

        .barcode-cell {
            width: 25%;
            height: 20mm;
            text-align: center;
            padding: 1mm;
            vertical-align: top;
            border: 0.5px solid #ccc;
            box-sizing: border-box;
            overflow: hidden;
        }

        .barcode-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .barcode-item img {
            width: 90%;
            height: 25px;
            margin-bottom: 1mm;
        }

        .barcode-item p {
            margin: 0;
            padding: 0;
            font-size: 6.5pt;
            line-height: 1.1;
        }

        .barcode-item .item-name {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .barcode-item .item-price {
            font-weight: bold;
            margin-bottom: 0.5mm;
        }

        .barcode-item .item-barcode-value {
            font-size: 5.5pt;
            margin-top: 0.3mm;
        }
    </style>
</head>
<body>
    <?php
        $chunkedBarcodes = array_chunk($barcodesData, 10);
    ?>

    <?php $__currentLoopData = $chunkedBarcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pageBarcodes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <table class="barcode-table">
            <?php $__currentLoopData = array_chunk($pageBarcodes, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="barcode-cell">
                            <div class="barcode-item">
                                <p class="item-name"><?php echo e($data['nama']); ?></p>
                                <p class="item-price">Rp <?php echo e(number_format($data['harga'], 0, ',', '.')); ?></p>
                                <img src="data:image/svg+xml;base64,<?php echo e(base64_encode($data['barcode_svg'])); ?>" alt="Barcode">
                                <p class="item-barcode-value"><?php echo e($data['barcode_value']); ?></p>
                            </div>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(count($row) < 4): ?>
                        <?php for($i = 0; $i < (4 - count($row)); $i++): ?>
                            <td class="barcode-cell"></td>
                        <?php endfor; ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>
</html><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/multiple_barcode_pdf.blade.php ENDPATH**/ ?>