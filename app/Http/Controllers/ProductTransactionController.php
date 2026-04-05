<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use App\Models\ProductTransaction;
use App\Models\TransactionDetail;
use App\Models\MedicationReminder;
use App\Models\MedicationRule;
use App\Models\RecommendationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ProductTransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class ProductTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $user = Auth::user();

        if($user->hasRole('buyer')){
            $product_transactions = $user->product_transactions()->orderBy('id', 'DESC')->get();
        }else {
            $product_transactions = ProductTransaction::orderBy('id', 'DESC')->get();
        }
        return view('admin.product_transactions.index', [
            'product_transactions' => $product_transactions
        ]);
        return view('admin.product_transactions.details', [
            'product_transactions' => $product_transactions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function checkoutSuccess()
    {
        return view('frontend.splash.checkout');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user = Auth::user();

        // Validasi hanya selected_address_id dan proof
        $validated = $request->validate([
            'selected_address_id' => 'required|exists:user_addresses,id',
            'proof' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            // Ambil alamat yang dipilih
            $address = UserAddress::findOrFail($validated['selected_address_id']);
            
            // Pastikan alamat milik user yang login
            if ($address->user_id !== $user->id) {
                throw new \Exception('Invalid address');
            }

            $subTotalCents = 0;

            $cartItems = $user->carts;

            foreach($cartItems as $item){
                $subTotalCents += $item->product->price * $item->quantity * 100;
            }

            // Kalkulasi Harga
            // $taxCents = (int) round(11 * $subTotalCents  / 100);
            // $insuranceCents = (int) round(23 * $subTotalCents  / 100);
            $deliveryFeeCents = 10000 * 100;
            $grandTotalCents = $subTotalCents + $deliveryFeeCents;
            $grandTotal = $grandTotalCents / 100;

            // Simpan proof
            $proofPath = $request->file('proof')->store('payment_proofs', 'public');

            // Create transaction dengan data dari address (SNAPSHOT)
            $newTransaction = ProductTransaction::create([
                'user_id' => $user->id,
                'user_address_id' => $address->id,  // Reference ke address
                'recipient_name' => $address->recipient_name,  // Reference ke address
                'address' => $address->address,      // Snapshot
                'city' => $address->city,            // Snapshot
                'post_code' => $address->post_code,  // Snapshot
                'phone_number' => $address->phone_number, // Snapshot
                'notes' => $address->notes,          // Snapshot
                'total_amount' => $grandTotal,
                'is_paid' => false,
                'proof' => $proofPath,
            ]);

            foreach($cartItems as $item){
                TransactionDetail::create([
                    'product_transaction_id' => $newTransaction->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);

                $userAge = $user->age ?? null; // pastikan atribut age tersedia di User

                // 1. Ambil aturan pakai obat dari MedicationRule
                $medRule = MedicationRule::where('product_id', $item->product_id)->first();
                if ($medRule) {
                    // Penyesuaian dosis
                    $dosage = $medRule->dosage;
                    if ($userAge !== null && $userAge < 18) {
                        $dosage = '1/2 ' . $dosage; // contoh, anak-anak minum setengah dosis
                    }
                }

                $item->delete();
            }

            $recData = session('last_recommendation');

            if ($recData && Auth::check()) {
                // Ambil semua product_id yang dibeli
                $purchasedProductIds = [];
                foreach ($newTransaction->transactionDetails as $detail) {
                    $purchasedProductIds[] = $detail->product_id;
                }

                RecommendationHistory::create([
                    'user_id' => Auth::id(),
                    'product_transaction_id' => $newTransaction->id,
                    'disease_id' => $recData['disease_id'],
                    'disease_name' => $recData['disease_name'],
                    'confidence' => $recData['confidence'] ?? 0,
                    'selected_symptoms' => $recData['selected_symptoms'],      // ← TANPA json_encode
                    'recommended_products' => $recData['recommended_products'],// ← TANPA json_encode
                    'purchased_product_ids' => $purchasedProductIds,           // ← ARRAY mentah
                    'is_confirmed' => false
                ]);

                // Bersihkan session
                session()->forget('last_recommendation');
            }

            DB::commit();

            return redirect()->route('product_transactions.checkout.success')
                ->with('success', 'Pesanan berhasil dibuat.');
            
        } catch(\Exception $e){
            DB::rollback();
            return back()->withErrors(['error' => 'System error: ' . $e->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(ProductTransaction $productTransaction)
    {
        //
        $productTransaction = ProductTransaction::with('transactionDetails.product')->find($productTransaction->id);
        return view('admin.product_transactions.details', [
            'productTransaction' => $productTransaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTransaction $productTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductTransaction $productTransaction)
    {
        //
        $action = $request->input('action');

        if ($action === 'approve_payment') {
            // Approve payment hanya di pending & belum lunas
            if (!$productTransaction->is_paid && $productTransaction->status === 'pending') {
                $productTransaction->update([
                    'is_paid' => true,
                    'status' => 'process',
                ]);
            }
        } elseif ($action === 'mark_shipped') {
            // Mark shipped hanya jika lunas & sedang proses
            if ($productTransaction->is_paid && $productTransaction->status === 'process') {
                $productTransaction->update([
                    'status' => 'shipped',
                ]);
            }
        } elseif ($action === 'cancel') {
            // Cancel hanya boleh jika belum lunas & belum success/shipped/delivered
            if (!$productTransaction->is_paid && in_array($productTransaction->status, ['pending', 'process'])) {
                $productTransaction->update([
                    'status' => 'canceled',
                ]);
            }
        }
        
        return redirect()->back();
    }

    public function delivered(Request $request, ProductTransaction $productTransaction)
    {
        // Validasi bahwa hanya buyer yang sedang login yang bisa update!
        if (Auth::id() === $productTransaction->user_id && $productTransaction->status === 'shipped') {
            // 1. Update Status Transaksi    
            $productTransaction->update([
                'status' => 'delivered'
            ]);
            // Optional: bisa langsung ubah ke 'success' jika tidak ada step lain, atau biarkan delivered saja
            $productTransaction->update([
                'status' => 'success'
            ]);

            // 2. Update Recommendation History (Jika ada)
            $recommendationHistory = \App\Models\RecommendationHistory::where('product_transaction_id', $productTransaction->id)
                ->where('is_confirmed', false)
                ->first();

            if ($recommendationHistory) {
                $recommendationHistory->update([
                    'is_confirmed' => true,
                ]);
            }

            // 3. LOGIKA PENGINGAT OBAT (CREATE OR EXTEND)
            foreach ($productTransaction->transactionDetails as $detail) {
                $product = $detail->product;
                $user = $productTransaction->user;
                $userAge = $user->age ?? null;
                $quantity = $detail->quantity; // Ambil jumlah barang yang dibeli

                // Ambil aturan dosis yang sesuai umur buyer
                $rule = MedicationRule::where('product_id', $product->id)
                    ->where(function($q) use ($userAge){
                        $q->where('min_age', '<=', $userAge);
                        $q->where(function($q2) use ($userAge){
                            $q2->where('max_age', '>=', $userAge)
                            ->orWhereNull('max_age');
                        });
                    })->orderBy('min_age', 'DESC')->first();

                if ($rule && $user) {
                    // Hitung total durasi berdasarkan quantity
                    // Misal 1 obat habis 5 hari. Beli 2 obat = 10 hari.
                    $durationPerItem = (int)($rule->duration ?? 7); 
                    $totalDurationDays = $durationPerItem * $quantity;

                    // CEK: Apakah sudah ada reminder AKTIF untuk obat ini?
                    // (Cek reminder yang end_date-nya belum lewat hari ini)
                    $activeReminders = MedicationReminder::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->where('status', 'active')
                        ->where('end_date', '>=', now()->toDateString())
                        ->get();

                    if ($activeReminders->count() > 0) {
                        // SKENARIO A: SUDAH ADA -> PERPANJANG DURASI (EXTEND)
                        // Update semua jadwal (pagi, siang, sore) untuk obat ini
                        foreach ($activeReminders as $reminder) {
                            $currentEndDate = Carbon::parse($reminder->end_date);
                            $newEndDate = $currentEndDate->addDays($totalDurationDays);
                            
                            $reminder->update([
                                'end_date' => $newEndDate->toDateString()
                            ]);
                        }
                    } else {
                        // SKENARIO B: BELUM ADA -> BUAT BARU (CREATE)
                        $dosage = $rule->default_dosage;
                        $frequency = (int)($rule->default_frequency ?? 1);
                        $interval = 24 / $frequency;
                        $startTime = now(); // Atau set jam default misal 07:00

                        $scheduleTimes = [];
                        for ($i = 0; $i < $frequency; $i++) {
                            // Hitung jam: misal 3x sehari -> 07:00, 15:00, 23:00 (mulai dari jam sekarang)
                            $scheduleTimes[] = $startTime->copy()->addHours($interval * $i)->format('H:i');
                        }

                        foreach ($scheduleTimes as $time) {
                            MedicationReminder::create([
                                'user_id' => $user->id,
                                'product_id' => $product->id,
                                'schedule_time' => $time,
                                'frequency' => $frequency,
                                'start_date' => now()->toDateString(),
                                'end_date' => now()->addDays($totalDurationDays)->toDateString(), // Durasi x Qty
                                'dosage' => $dosage,
                                'status' => 'active'
                            ]);
                        }

                        // Kirim notifikasi konfirmasi HANYA SEKALI (di luar loop jadwal)
                        $user->notify(new \App\Notifications\MedicationReminderNotification(
                            'Pengingat Diaktifkan',
                            "Jadwal minum obat {$product->name} telah dibuat otomatis untuk {$totalDurationDays} hari ke depan."
                        ));
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Terima kasih, pesanan berhasil dikonfirmasi terkirim!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTransaction $productTransaction)
    {
        //
    }

    public function exportPdf() {
        $transactions = ProductTransaction::with('transactionDetails.product')->orderBy('created_at', 'DESC')->get();
        $pdf = Pdf::loadView('admin.product_transactions.pdf', compact('transactions'));
        return $pdf->download('laporan-penjualan-apotek.pdf');
    }

    public function exportExcel() {
        return Excel::download(new ProductTransactionsExport, 'laporan-penjualan-apotek.xlsx');
    }
}
