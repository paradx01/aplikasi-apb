<?php

use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\MedicationReminderController;
use App\Http\Controllers\ProductTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\SymptomDiseaseController;
use App\Http\Controllers\RecommendationRuleController;
use App\Http\Controllers\RecommendationHistoryController;
use App\Http\Controllers\ExpertController;
use App\Models\ProductTransaction;
use App\Notifications\MedicationReminderNotification;
use Illuminate\Support\Facades\Route;

// Route publik (frontend)
Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/search', [FrontendController::class, 'search'])->name('frontend.search');
Route::get('/details/{product:slug}', [FrontendController::class, 'details'])->name('frontend.product.details');
Route::get('/category/{category}', [FrontendController::class, 'category'])->name('frontend.product.category');
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/push/subscription', [MedicationReminderController::class, 'saveSubscription'])->middleware('auth');

// Route membutuhkan auth
Route::middleware('auth')->group(function() {

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/admin/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/edit', [ProfileController::class, 'editBuyer'])->name('profile.partials.buyer.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/updated', [ProfileController::class, 'updateBuyer'])->name('profile.update.buyer');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Cart
    Route::resource('carts', CartController::class)->middleware('role:buyer');
    Route::post('cart/add/{productId}', [CartController::class, 'store'])->middleware('role:buyer')->name('carts.add');

    // Addresses List
    Route::prefix('addresses')->name('frontend.addresses.')->middleware('role:buyer')->group(function() {
        Route::get('/addresslist', [UserAddressController::class, 'index'])->name('index');
        Route::get('/create', [UserAddressController::class, 'create'])->name('create');
        Route::post('/', [UserAddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [UserAddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [UserAddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [UserAddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/set-primary', [UserAddressController::class, 'setPrimary'])->name('set-primary');
    });

    Route::get(
        'product_transactions/checkout/success',
        [ProductTransactionController::class, 'checkoutSuccess']
    )->middleware('role:buyer')->name('product_transactions.checkout.success');

    // Product Transactions (role: apoteker or buyer)
    Route::resource('product_transactions', ProductTransactionController::class)->middleware('role:apoteker|buyer');
    Route::put('product_transactions/{productTransaction}/deliver', [ProductTransactionController::class, 'delivered'])->name('product_transactions.deliver');
    
    // Export Laporan (khusus apoteker)
    Route::middleware('role:apoteker')->group(function() {
        Route::get('/export-pdf', [ProductTransactionController::class, 'exportPdf'])->name('product_transactions.exportPdf');
        Route::get('/export-excel', [ProductTransactionController::class, 'exportExcel'])->name('product_transactions.exportExcel');
    });

    // Admin panel
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::resource('products', ProductController::class)->middleware('role:apoteker');
        Route::resource('categories', CategoryController::class)->middleware('role:apoteker');
        // Admin CRUD untuk data gejala, penyakit, diagnosa, dan rekomendasi obat
        Route::resource('symptoms', SymptomController::class)->middleware('role:apoteker');
        Route::resource('diseases', DiseaseController::class)->middleware('role:apoteker');
        Route::resource('symptom-diseases', SymptomDiseaseController::class)->middleware('role:apoteker');
        Route::resource('medicine-recommendation', RecommendationRuleController::class)
            ->parameters(['medicine-recommendation' => 'recommendation'])
            ->middleware('role:apoteker');
    });

    // Medication reminders
    Route::get('/reminders', [MedicationReminderController::class, 'index'])->name('frontend.reminders.index');

    // Sistem Pakar Rekomendasi Obat (Forward Chaining)
    Route::prefix('expert-system')->name('frontend.expertsystem.')->middleware('role:buyer')->group(function() {
        Route::get('/', [ExpertController::class, 'index'])->name('index');
        Route::get('/gejala-umum', [ExpertController::class, 'showGejalaUmum'])->name('gejalaUmum');
        Route::get('/gejala-kritis', [ExpertController::class, 'showGejalaKritis'])->name('gejalaKritis');
        Route::get('/diagnosa', [ExpertController::class, 'diagnosa'])->name('diagnosa');
        Route::get('/medicine-recommendation', [ExpertController::class, 'rekomendasi'])->name('rekomendasi');
        // Riwayat rekomendasi obat
        Route::get('/riwayat-rekomendasi', [RecommendationHistoryController::class, 'index'])->name('listHistory');
        Route::get('/riwayat-rekomendasi/{id}', [RecommendationHistoryController::class, 'show'])->name('historyDetails');
    });
});

require __DIR__.'/auth.php';
