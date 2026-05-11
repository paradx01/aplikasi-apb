<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Obat | Parma</title>
    <script src="https://cdn.tailwindcss.com"></script>  
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
    <style>
        html, body { 
            width: 100%; 
            overflow-x: hidden !important; 
            padding-top: 2.5rem;
        }
        wrapper {
            max-width: 425px; 
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .scrolled {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }

        /* Product card hover effect */
        .product-card {
            transition: all 0.2s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Price highlight */
        .price-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    @include('partials.pwa')
</head>
<body>
    <!-- Topbar -->
    <section id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
        <div class="flex items-center justify-between gap-2 wrapper">
            <button onclick="goBackToDiagnosa()" 
                    class="p-2 bg-white rounded-full shadow-sm border border-gray-200">
                <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="Back">
            </button>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
                Rekomendasi Obat
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="wrapper mt-6 pb-32">
        
        <!-- Breadcrumb Progress -->
        <div class="mb-6 bg-white rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                
                <!-- Step 1: Completed -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Umum</span>
                </div>

                <div class="flex-1 h-0.5 bg-primary mx-2"></div>

                <!-- Step 2: Completed -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Kritis</span>
                </div>

                <div class="flex-1 h-0.5 bg-primary mx-2"></div>

                <!-- Step 3: Completed -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Diagnosa</span>
                </div>

                <div class="flex-1 h-0.5 bg-primary mx-2"></div>

                <!-- Step 4: Active -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center mb-2 ring-4 ring-primary/20">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-primary">Obat</span>
                </div>

            </div>
        </div>

        <!-- Warning jika diagnosis tidak pasti -->
        @php
            $isLowConfidence = (request()->input('confidence', 100) < 50) || !request()->input('has_critical', false);
        @endphp

        @if($isLowConfidence)
        <div class="mb-6">
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-3">
                <div class="flex gap-2">
                    <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-xs text-red-800 font-semibold mb-1">
                            ⚠️ Perhatian: Diagnosis Tidak Pasti
                        </p>
                        <p class="text-xs text-red-700 leading-relaxed">
                            Rekomendasi ini berdasarkan diagnosis yang <strong>belum pasti</strong>. 
                            <strong>WAJIB konsultasi dengan apoteker atau dokter</strong> sebelum mengonsumsi obat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Disease Info Card (IMPROVED) -->
        <div class="mb-6 bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            
            <!-- Header: Disease Name & Confidence -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                        {{ $diseaseData['disease']->disease_name }}
                        
                        <!-- Badge jika ada critical match -->
                        @if($diseaseData['matched_critical'] > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $diseaseData['matched_critical'] }} KHAS
                            </span>
                        @endif
                    </h3>
                    
                    @if($diseaseData['disease']->description)
                        <p class="text-xs text-gray-500 line-clamp-2 mb-2">
                            {{ $diseaseData['disease']->description }}
                        </p>
                    @endif
                </div>

                <!-- Confidence Badge -->
                <div class="flex-shrink-0 ml-3">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">
                            {{ number_format($diseaseData['confidence'], 1) }}%
                        </div>
                        <div class="text-xs text-gray-500">Kesesuaian</div>
                    </div>
                </div>
            </div>

            <!-- Confidence Bar -->
            <div class="mb-3">
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full
                                {{ $diseaseData['confidence'] >= 70 ? 'bg-green-500' : 
                                   ($diseaseData['confidence'] >= 50 ? 'bg-yellow-500' : 'bg-orange-500') }}"
                         style="width: {{ $diseaseData['confidence'] }}%">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        @if($products->count() > 0 || $productsWithWarning->count() > 0)
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                @if($isLowConfidence)
                    Obat untuk Meredakan Gejala
                @else
                    Rekomendasi Obat
                @endif
            </h2>
        @endif

        <!-- Products List (Safe/Recommended) -->
        @if($products->count() > 0)
            <div class="space-y-3 mb-6">
                @foreach($products as $product)
                    <a href="{{ route('frontend.product.details', $product->slug) }}" 
                    class="product-card block bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                        
                        <div class="flex gap-3">
                            <!-- Product Image -->
                            <div class="w-20 h-20 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                                @if($product->photo)
                                    <img src="{{ Storage::url($product->photo) }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">
                                    {{ $product->name }}
                                </h3>
                                
                                <!-- Category Badge -->
                                @if($product->category)
                                    <span class="inline-block text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded mb-2">
                                        {{ $product->category->name }}
                                    </span>
                                @endif

                                <!-- Price -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-base font-bold text-primary">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        @if($product->stock > 0)
                                            <p class="text-xs text-green-600 font-medium">
                                                Stok: {{ $product->stock }}
                                            </p>
                                        @else
                                            <p class="text-xs text-red-600 font-medium">
                                                Stok Habis
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <!-- Arrow Icon -->
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>

                                <!-- Pregnancy Category Badge (if applicable) -->
                                @if(isset($product->pregnancy_category) && $user->is_pregnant)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center text-xs font-semibold 
                                                    {{ $product->pregnancy_category == 'A' || $product->pregnancy_category == 'B' ? 
                                                        'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100' }} 
                                                    px-2 py-1 rounded">
                                            Kategori Kehamilan: {{ $product->pregnancy_category }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>
        @endif

        <!-- Products with Warning (for pregnant users) -->
        @if($productsWithWarning->count() > 0)
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full">
                        ⚠️ Perlu Perhatian Khusus
                    </span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <div class="space-y-3">
                    @foreach($productsWithWarning as $product)
                        <div class="bg-white rounded-2xl p-4 shadow-sm border-2 border-yellow-200">
                            
                            <!-- Warning Banner -->
                            @if(isset($product->pregnancy_warning))
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                                    <div class="flex gap-2">
                                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-xs text-yellow-800 leading-relaxed">
                                            {{ $product->pregnancy_warning }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- FIX: Ganti route name -->
                            <a href="{{ route('frontend.product.details', $product->slug) }}" 
                            class="product-card block">
                                
                                <div class="flex gap-3">
                                    <!-- Product Image -->
                                    <div class="w-20 h-20 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                                        @if($product->photo)
                                            <img src="{{ Storage::url($product->photo) }}" 
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">
                                            {{ $product->name }}
                                        </h3>
                                        
                                        <!-- Category & Pregnancy Category -->
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            @if($product->category)
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                                    {{ $product->category->name }}
                                                </span>
                                            @endif
                                            @if(isset($product->pregnancy_category))
                                                <span class="text-xs font-semibold
                                                            {{ $product->pregnancy_category == 'C' ? 'text-yellow-700 bg-yellow-100' : 'text-red-700 bg-red-100' }}
                                                            px-2 py-0.5 rounded">
                                                    Kategori {{ $product->pregnancy_category }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Price -->
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-base font-bold text-primary">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </p>
                                                @if($product->stock > 0)
                                                    <p class="text-xs text-green-600 font-medium">Stok: {{ $product->stock }}</p>
                                                @else
                                                    <p class="text-xs text-red-600 font-medium">Stok Habis</p>
                                                @endif
                                            </div>
                                            
                                            <!-- Arrow Icon -->
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if(count($products) == 0 && count($productsWithWarning) == 0)
            <div class="bg-white rounded-2xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">Tidak Ada Rekomendasi Obat</h3>
                <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                    Saat ini belum ada obat yang sesuai dengan profil kesehatan Anda. 
                    Silakan konsultasi dengan apoteker atau dokter untuk mendapatkan saran yang tepat.
                </p>
                <a href="{{ route('frontend.index') }}" 
                   class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-full font-semibold text-sm">
                    Kembali ke Beranda
                </a>
            </div>
        @endif

        <!-- Important Disclaimer -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-3">
            <div class="flex gap-2">
                <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-xs text-blue-800 font-semibold mb-1">
                        Catatan Penting
                    </p>
                    <ul class="text-xs text-blue-700 leading-relaxed space-y-1">
                        <li>• Baca aturan pakai dan komposisi obat sebelum mengonsumsi</li>
                        <li>• Konsultasi dengan apoteker jika memiliki alergi atau kondisi khusus</li>
                        <li>• Jika gejala tidak membaik dalam 3 hari, segera ke dokter</li>
                        <li>• Sistem ini BUKAN pengganti konsultasi medis profesional</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if(count($products) > 0 || count($productsWithWarning) > 0)
            <div class="mt-6 grid grid-cols-2 gap-3">
                <a href="{{ route('frontend.expertsystem.index') }}" 
                   onclick="resetExpertFlow()"
                   class="block text-center bg-white border-2 border-gray-300 text-gray-700 font-semibold py-3 rounded-full hover:bg-gray-50 transition">
                    Diagnosa Lagi
                </a>
                <a href="{{ route('frontend.index') }}" 
                   class="block text-center bg-primary text-white font-semibold py-3 rounded-full hover:bg-opacity-90 transition">
                    Ke Beranda
                </a>
            </div>
        @endif

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
    <script>
        function goBackToDiagnosa() {
            // Cek apakah ada history sebelumnya
            if (window.history.length > 1 && document.referrer) {
                const referrerPath = new URL(document.referrer).pathname;
                
                // Jika dari diagnosa, boleh back
                if (referrerPath.includes('/expert-system/diagnosa')) {
                    window.history.back();
                } else {
                    // Fallback ke diagnosa
                    window.location.href = "{{ route('frontend.expertsystem.gejalaUmum') }}";
                }
            } else {
                // Tidak ada history, redirect ke index
                window.location.href = "{{ route('frontend.expertsystem.index') }}";
            }
        }
        // Clear expert system flow state setelah selesai
        function resetExpertFlow() {
            sessionStorage.removeItem('visitedPages');
            const keys = Object.keys(sessionStorage);
            keys.forEach(key => {
                if (key.startsWith('formState_')) {
                    sessionStorage.removeItem(key);
                }
            });
        }
    </script>
</body>
</html>
