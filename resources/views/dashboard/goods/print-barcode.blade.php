<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $good->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .form-card {
            max-width: 420px;
            margin: 0 auto 20px;
            border: 2px solid #000;
            padding: 16px;
        }
        .barcode-container {
            text-align: center;
            border: 2px solid #000;
            padding: 20px;
            margin: 20px auto;
            width: 300px;
        }
        .form-group {
            margin-bottom: 12px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 10px;
            font-size: 14px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .action-buttons button,
        .action-buttons a {
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            border: 1px solid #000;
            background: #fff;
            color: #000;
            cursor: pointer;
        }
        .product-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .barcode-code {
            font-size: 14px;
            margin-top: 10px;
        }
        .price {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        @if (session()->has('error'))
            <div style="color: #c00; margin-bottom: 12px; font-weight: bold;">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div style="color: #c00; margin-bottom: 12px; font-weight: bold;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('goods.printbarcode.pdf', $good->id) }}" method="POST" class="form-card">
            @csrf
            <div class="form-group">
                <label for="qty">Jumlah Cetak</label>
                <input type="number" name="qty" id="qty" min="1" max="208" value="{{ old('qty', 1) }}" required>
            </div>

            <div class="form-group">
                <label for="size">Ukuran Barcode</label>
                <select name="size" id="size" required>
                    <option value="small" {{ old('size', 'small') === 'small' ? 'selected' : '' }}>Kecil (20mm)</option>
                    <option value="medium" {{ old('size') === 'medium' ? 'selected' : '' }}>Sedang (26mm)</option>
                    <option value="large" {{ old('size') === 'large' ? 'selected' : '' }}>Besar (32mm)</option>
                </select>
            </div>

            <div class="action-buttons">
                <button type="submit">Generate PDF</button>
                <a href="{{ url('/dashboard/goods/' . $good->id . '/edit') }}">Close</a>
            </div>
        </form>
    </div>

    <div class="barcode-container">
        <div class="product-name">{{ $good->nama }}</div>
        <canvas id="barcode"></canvas>
        <div class="barcode-code">{{ $good->barcode }}</div>
        <div class="price">Rp {{ number_format($good->harga) }}</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode("#barcode", "{{ $good->barcode }}", {
            format: "CODE128",
            width: 2,
            height: 80,
            displayValue: false
        });
    </script>
</body>
</html>
