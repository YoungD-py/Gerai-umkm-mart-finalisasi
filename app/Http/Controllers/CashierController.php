<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Good;
use App\Models\Transaction;
use App\Models\Order;
use PDF;
use Illuminate\Support\Facades\Log;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.cashiers.index', [
            'active' => 'cashier',
            'transactions' => Transaction::latest()->get(),
        ]);
    }

    /**
     * Quick create transaction and redirect to order creation
     *
     * @return \Illuminate\Http\Response
     */
    public function quickTransaction()
    {
        // Generate nomor nota yang lebih rapi
        $tanggal = date('Ymd'); // Format: 20250109
        $waktu = date('His');   // Format: 085511
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); // 001-999
        $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random; // 20250109-085511-001

        // Pastikan nomor nota unik
        while (Transaction::where('no_nota', $no_nota_baru)->exists()) {
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random;
        }

        // Create transaction with current user and default payment method
        $transactionData = [
            'no_nota' => $no_nota_baru,
            'tgl_transaksi' => date('Y-m-d'),
            'user_id' => auth()->user()->id, // Otomatis dari user yang login
            'metode_pembayaran' => 'CASH', // Default metode pembayaran
            'status' => 'gagal',
            'total_harga' => 0,
            'bayar' => 0,
            'kembalian' => 0,
        ];

        Transaction::create($transactionData);

        return redirect('/dashboard/cashier/createorder?no_nota=' . $no_nota_baru);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createtransaction()
    {
        return view('dashboard.cashiers.transaction.create', [
            'active' => 'cashier',
            'users' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createorder(Request $request)
    {
        // Handle both GET and POST requests
        $no_nota = $request->input('no_nota') ?? $request->get('no_nota');

        if (!$no_nota) {
            return redirect('/dashboard/cashier/createtransaction')->with('error', 'Nomor nota tidak ditemukan. Silakan buat transaksi baru.');
        }

        // Get or create transaction
        $transaction = Transaction::where('no_nota', $no_nota)->first();

        if (!$transaction) {
            return redirect('/dashboard/cashier/createtransaction')->with('error', 'Transaksi tidak ditemukan. Silakan buat transaksi baru.');
        }

        return view('dashboard.cashiers.order.create', [
            'active' => 'cashier',
            'goods' => Good::where('stok', '>', 0)->get(), // Still pass all goods for initial data, though search will filter
            'no_nota' => $no_nota,
            'transaction' => $transaction,
            'orders' => Order::where('no_nota', $no_nota)->with('good')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storetransaction(Request $request)
    {
        // Generate nomor nota yang lebih rapi
        $tanggal = date('Ymd'); // Format: 20250109
        $waktu = date('His');   // Format: 085511
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); // 001-999
        $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random; // 20250109-085511-001

        // Pastikan nomor nota unik
        while (Transaction::where('no_nota', $no_nota_baru)->exists()) {
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random;
        }

        $validatedData = $request->validate([
            'tgl_transaksi' => 'required',
            'user_id' => 'required',
            'metode_pembayaran' => 'required',
        ]);

        // Tambahkan nomor nota yang sudah digenerate
        $validatedData['no_nota'] = $no_nota_baru;

        // Add default status
        $validatedData['status'] = 'gagal';
        $validatedData['total_harga'] = 0;
        $validatedData['bayar'] = 0;
        $validatedData['kembalian'] = 0;

        $transaction = Transaction::create($validatedData);

        return redirect('/dashboard/cashier/createorder?no_nota=' . $no_nota_baru);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeorder(Request $request)
    {
        try {
            $request->validate([
                'no_nota' => 'required',
                'good_id' => 'required|exists:goods,id',
                'qty' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0'
            ]);

            $barang = Good::findOrFail($request->good_id);

            // Check stock
            if ($barang->stok < $request->qty) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang->stok);
            }

            // Get current transaction total to determine tebus murah eligibility
            $currentOrders = Order::where('no_nota', $request->no_nota)->get();
            $currentTotal = $currentOrders->sum('subtotal');

            // Determine price based on quantity and transaction total
            $qty = $request->qty;
            $unitPrice = $barang->harga; // Default to retail price

            // Check tebus murah first (higher priority)
            if ($barang->is_tebus_murah_active && 
                $currentTotal >= $barang->min_total_tebus_murah && 
                $barang->harga_tebus_murah > 0) {
                $unitPrice = $barang->harga_tebus_murah;
            }
            // Then check wholesale
            elseif ($barang->is_grosir_active && 
                    $qty >= $barang->min_qty_grosir && 
                    $barang->harga_grosir > 0) {
                $unitPrice = $barang->harga_grosir;
            }

            $subtotal = $unitPrice * $qty;

            // Create order
            Order::create([
                'no_nota' => $request->no_nota,
                'good_id' => $request->good_id,
                'qty' => $qty,
                'subtotal' => $subtotal,
                'price' => $unitPrice,
            ]);

            return redirect('/dashboard/cashier/createorder?no_nota=' . $request->no_nota)
                           ->with('success', 'Pesanan berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store order from barcode scan
     */
    public function storeOrderFromBarcode(Request $request)
    {
        try {
            Log::info('Barcode scan request received', $request->all());

            $barcode = $request->input('barcode');
            $qty = $request->input('qty', 1);
            $no_nota = $request->input('no_nota');

            // Validate input
            if (!$barcode || !$no_nota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data barcode atau nomor nota tidak lengkap!'
                ]);
            }

            // Clean barcode
            $barcode = trim($barcode);

            // Try to find product by barcode with multiple strategies
            $barang = Good::where('barcode', $barcode)->first();

            // If not found by exact match, try case insensitive
            if (!$barang) {
                $barang = Good::whereRaw('LOWER(barcode) = ?', [strtolower($barcode)])->first();
            }

            // If not found, try partial match
            if (!$barang) {
                $barang = Good::where('barcode', 'LIKE', '%' . $barcode . '%')->first();
            }

            // If still not found, try by name
            if (!$barang) {
                $barang = Good::where('nama', 'LIKE', '%' . $barcode . '%')->first();
            }

            if (!$barang) {
                Log::warning('Barcode not found: ' . $barcode);
                return response()->json([
                    'success' => false,
                    'message' => 'Barang dengan barcode "' . $barcode . '" tidak ditemukan!'
                ]);
            }

            if ($barang->stok < $qty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok barang tidak mencukupi! Stok tersedia: ' . $barang->stok
                ]);
            }

            // Get current transaction total to determine tebus murah eligibility
            $currentOrders = Order::where('no_nota', $no_nota)->get();
            $currentTotal = $currentOrders->sum('subtotal');

            // Determine price based on quantity and transaction total
            $unitPrice = $barang->harga; // Default to retail price

            // Check tebus murah first (higher priority)
            if ($barang->is_tebus_murah_active && 
                $currentTotal >= $barang->min_total_tebus_murah && 
                $barang->harga_tebus_murah > 0) {
                $unitPrice = $barang->harga_tebus_murah;
            }
            // Then check wholesale
            elseif ($barang->is_grosir_active && 
                    $qty >= $barang->min_qty_grosir && 
                    $barang->harga_grosir > 0) {
                $unitPrice = $barang->harga_grosir;
            }

            $subtotal = $unitPrice * $qty;

            // Create order
            $order = Order::create([
                'no_nota' => $no_nota,
                'good_id' => $barang->id,
                'qty' => $qty,
                'subtotal' => $subtotal,
                'price' => $unitPrice,
            ]);

            Log::info('Order created successfully', ['order_id' => $order->id, 'barcode' => $barcode]);

            return response()->json([
                'success' => true,
                'message' => 'Barang "' . $barang->nama . '" berhasil ditambahkan!',
                'data' => [
                    'nama' => $barang->nama,
                    'harga' => number_format($unitPrice),
                    'qty' => $qty,
                    'subtotal' => number_format($subtotal)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in storeOrderFromBarcode: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Search goods by name or barcode.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchGoods(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $goods = Good::where('stok', '>', 0)
                     ->where(function($q) use ($query) {
                         $q->where('nama', 'LIKE', '%' . $query . '%')
                           ->orWhere('barcode', 'LIKE', '%' . $query . '%');
                     })
                     ->limit(10) // Limit results for performance
                     ->get();

        return response()->json($goods);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finishing(Request $request)
    {
        // Update transaction - ambil nama_pembeli dari request (dari form checkout)
        $rules = [
            'status' => $request->status,
            'total_harga' => $request->total_harga,
            'bayar' => $request->bayar,
            'kembalian' => $request->kembalian,
            'metode_pembayaran' => $request->nama_pembeli, // nama_pembeli dari form checkout
        ];

        $transaction = Transaction::find($request->id);
        $transaction->update($rules);

        // Log untuk debug
        Log::info('Finishing transaction', [
            'transaction_id' => $request->id,
            'status' => $request->status,
            'no_nota' => $transaction->no_nota
        ]);

        // Kurangi stok jika status lunas
        if (strtolower(trim($request->status)) === 'lunas') {
            $orders = Order::where('no_nota', $transaction->no_nota)->get();
            
            Log::info('Processing stock reduction', [
                'no_nota' => $transaction->no_nota,
                'orders_count' => $orders->count()
            ]);

            foreach ($orders as $order) {
                $good = Good::find($order->good_id);
                if ($good) {
                    $oldStock = $good->stok;
                    $newStock = $oldStock - $order->qty;
                    
                    // Update stok menggunakan raw query untuk memastikan
                    \DB::table('goods')
                        ->where('id', $good->id)
                        ->update(['stok' => $newStock]);
                    
                    Log::info('Stock updated', [
                        'good_id' => $good->id,
                        'good_name' => $good->nama,
                        'old_stock' => $oldStock,
                        'qty_reduced' => $order->qty,
                        'new_stock' => $newStock
                    ]);
                }
            }
        }

        return redirect('/dashboard/cashier')->with('success', 'Transaksi Sukses!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nota(Request $request)
    {
        $transaction = Transaction::where('no_nota', $request['no_nota'])->get();
        $orders = Order::where('no_nota', $request['no_nota'])->with('good')->get();

        $pdf = PDF::loadview('nota_pdf', [
            'transaction' => $transaction,
            'orders' => $orders,
        ]);

        return $pdf->download('nota-' . $request['no_nota'] . '.pdf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $transaction = Transaction::where('no_nota', $request->no_nota)->first();

        return view('dashboard.cashiers.checkout', [
            'active' => 'cashier',
            'no_nota' => $request->no_nota,
            'total_harga' => $request->total_harga,
            'users' => User::all(),
            'transaction' => $transaction,
        ]);
    }
}
