<!DOCTYPE html>
<html>
<head>
    <title>Laporan Biaya Operasional</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            font-size: 18pt;
        }
        .header p {
            margin: 5px 0;
            font-size: 11pt;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row td {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Biaya Operasional</h2>
        
        <p>Periode: <?php echo e($startDate->format('d M Y')); ?> - <?php echo e($endDate->format('d M Y')); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Tanggal</th>
                <th>Uraian/Keterangan</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 20%;" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $biayaOperasional; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biaya): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($biaya->tanggal)->format('d M Y')); ?></td>
                    <td><?php echo e($biaya->uraian); ?></td>
                    <td style="text-align: center;"><?php echo e($biaya->qty); ?></td>
                    <td class="text-right">Rp <?php echo e(number_format($biaya->nominal, 0, ',', '.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>Total Biaya Operasional:</strong></td>
                
                <td class="text-right"><strong>Rp <?php echo e(number_format($totalBiaya, 0, ',', '.')); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: <?php echo e($generated_at->format('d M Y H:i:s')); ?>

    </div>
</body>
</html>
<?php /**PATH /var/www/resources/views/biaya_operasional_pdf.blade.php ENDPATH**/ ?>