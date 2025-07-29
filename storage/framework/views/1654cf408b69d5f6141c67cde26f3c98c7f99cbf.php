<!DOCTYPE html>
<html>
<head>
    <title>Nota Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            max-width: 300px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .transaction-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .transaction-info table {
            width: 100%;
        }
        .transaction-info td {
            padding: 2px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items-table th,
        .items-table td {
            padding: 4px;
            text-align: left;
            font-size: 10px;
            border-bottom: 1px solid #ddd;
        }
        .items-table th {
            font-weight: bold;
            border-bottom: 2px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        .total-section table {
            width: 100%;
            font-size: 11px;
        }
        .total-section td {
            padding: 2px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>GERAI UMKM MART </h2>
        <p>Jl. Perak Timur No.620, Perak Utara, Kec. Pabean Cantikan, Surabaya, Jawa Timur 60165</p>
        <p>Telp: 0813-3242-1401</p>
    </div>

    <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="transaction-info">
        <table>
            <tr>
                <td width="40%">No. Nota</td>
                <td width="5%">:</td>
                <td width="55%"><?php echo e($trans->no_nota); ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><?php echo e($trans->created_at->format('d/m/Y H:i')); ?></td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>:</td>
                <td><?php echo e($trans->user->nama ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Metode Bayar</td>
                <td>:</td>
                <td><?php echo e($trans->metode_pembayaran); ?></td>
            </tr>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <table class="items-table">
        <thead>
            <tr>
                <th width="40%">Item</th>
                <th width="15%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Harga</th>
                <th width="25%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($order->good->nama ?? 'N/A'); ?></td>
                <td class="text-center"><?php echo e($order->qty); ?></td>
                <td class="text-right"><?php echo e(number_format($order->price, 0, ',', '.')); ?></td>
                <td class="text-right"><?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="total-section">
        <table>
            <tr>
                <td width="60%"><strong>TOTAL</strong></td>
                <td width="40%" class="text-right"><strong>Rp <?php echo e(number_format($trans->total_harga, 0, ',', '.')); ?></strong></td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="text-right">Rp <?php echo e(number_format($trans->bayar, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="text-right">Rp <?php echo e(number_format($trans->kembalian, 0, ',', '.')); ?></td>
            </tr>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="footer">
        <p>*** TERIMA KASIH ***</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        <p><?php echo e(date('d/m/Y H:i:s')); ?></p>
    </div>
</body>
</html>
<?php /**PATH D:\Repo_Git\Gerai-umkm-mart-finalisasi\resources\views/nota_pdf.blade.php ENDPATH**/ ?>