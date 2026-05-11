<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details | Parma</title>
    <link rel="shortcut icon" href="{{ asset('assets/svgs/logo-mark.svg') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <style>
        html,
        body {
            width: 100%;
            overflow-x: hidden !important;
            padding-top: 3.5rem;
            scroll-behavior: smooth;
        }

        .wrapper {
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
    </style>
    @include('partials.pwa')
</head>

<body>
    <!-- Topbar -->
    <section id="topbar" class="fixed top-0 z-50 w-full transition duration-300">
        <div class="flex items-center justify-between gap-2 wrapper">
            <a href="{{ route('frontend.index') }}" class="p-2 bg-white rounded-full shadow-sm">
                <img src="{{ asset('assets/svgs/ic-arrow-left.svg') }}" class="size-5" alt="">
            </a>
            <p id="topbar-title"
                class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-300 text-gray-800">
                Details
            </p>
            <button type="button" class="p-2 bg-white rounded-full shadow-sm">
                <img src="{{ asset('assets/svgs/ic-triple-dots.svg') }}" class="size-5" alt="">
            </button>
        </div>
    </section>

    <!-- Product Image -->
    <img src="{{ Storage::url($product->photo) }}" class="h-[220px] w-auto mx-auto relative z-10" alt="">

    <!-- Main Content -->
    <section
        class="bg-white rounded-t-[60px] pt-[60px] px-6 pb-[120px] -mt-9 flex flex-col gap-5 max-w-[425px] mx-auto">

        <!-- Product Header -->
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <p class="font-bold text-[22px] mb-2">
                        {{ $product->name }}
                    </p>
                    <div class="flex items-center gap-2">
                        <img src="{{ Storage::url($product->category->icon) }}" class="size-[25px]" alt="">
                        <p class="font-semibold text-balance text-[14px]">
                            {{ $product->category->name }}
                        </p>
                    </div>
                </div>
                {{-- <div class="flex items-center gap-1">
          <img src="{{asset('assets/svgs/ic-thumb-shape-filled.svg')}}" class="size-6" alt="">
          <p class="font-semibold text-sm">
            Best Seller
          </p>
        </div> --}}
            </div>
        </div>

        <!-- Safety Warning Banner (Integrasi Sistem Pakar) -->
        @auth
            @if (count($safetyWarnings) > 0 || $pregnancyWarning || $ageWarning)
                <div class="flex flex-col gap-3 mb-4">

                    {{-- Kontraindikasi Warning --}}
                    @if (count($safetyWarnings) > 0)
                        <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-red-800 mb-1">Peringatan Kontraindikasi</p>
                                    <p class="text-xs text-red-700 mb-2">Obat ini memiliki kontraindikasi dengan kondisi
                                        medis Anda:</p>
                                    <ul class="text-xs text-red-700 list-disc list-inside space-y-0.5">
                                        @foreach ($safetyWarnings as $warning)
                                            <li class="font-semibold">{{ $warning }}</li>
                                        @endforeach
                                    </ul>
                                    <p class="text-xs text-red-600 mt-2 italic">Konsultasikan dengan apoteker sebelum
                                        membeli obat ini.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Kehamilan Warning --}}
                    @if ($pregnancyWarning)
                        <div
                            class="{{ $pregnancyWarning['level'] === 'danger' ? 'bg-red-50 border-red-500' : 'bg-yellow-50 border-yellow-500' }} border-l-4 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 {{ $pregnancyWarning['level'] === 'danger' ? 'text-red-500' : 'text-yellow-500' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-bold {{ $pregnancyWarning['level'] === 'danger' ? 'text-red-800' : 'text-yellow-800' }} mb-1">
                                        Peringatan Kehamilan</p>
                                    <p
                                        class="text-xs {{ $pregnancyWarning['level'] === 'danger' ? 'text-red-700' : 'text-yellow-700' }}">
                                        {{ $pregnancyWarning['message'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Umur Warning --}}
                    @if ($ageWarning)
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-yellow-800 mb-1">Peringatan Usia</p>
                                    <p class="text-xs text-yellow-700">{{ $ageWarning }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            @endif
        @endauth

        <!-- Features Badges -->
        <div class="flex flex-row justify-center gap-2 mx-2 relative px-4 mb-4">
            @php
                // Cek apakah produk aman untuk anak berdasarkan medication_rules
                // Produk dianggap aman anak jika ada aturan pakai "Anak-anak" DENGAN dosis yang terisi
                $isChildSafe = $product->medicationRules->contains(function ($rule) {
                    $condition = strtolower($rule->special_condition ?? '');
                    $hasDosage = !empty($rule->default_dosage);
                    return str_contains($condition, 'anak') && $hasDosage;
                });

                $features = [
                    ['icon' => 'ic-cup-filled.svg', 'label' => 'Obat Bebas'],
                    ['icon' => 'ic-clipboard-tick-filled.svg', 'label' => $isChildSafe ? 'Aman Anak' : 'Dewasa'],
                    ['icon' => 'ic-shiled-tick-filled.svg', 'label' => 'BPOM'],
                ];
            @endphp
            @foreach ($features as $f)
                <div
                    class="w-[98px] border border-[#f1f1fa] rounded-2xl p-3.5 flex flex-col items-center justify-center text-center mr-2 last:mr-0">
                    <img src="{{ asset('assets/svgs/' . $f['icon']) }}" class="size-10 mb-2" alt="">
                    <span class="text-sm font-semibold leading-tight">{{ $f['label'] }}</span>
                </div>
            @endforeach
        </div>

        <!-- Detail Produk -->
        <div class="border border-[#f1f1fa] rounded-2xl p-5 flex flex-col gap-4 shadow">
            <h4 class="font-bold text-base text-gray-700 mb-1">Detail Produk</h4>

            <div>
                <div class="text-sm font-semibold mb-1">Indikasi Umum</div>
                <div class="text-[14px] leading-6 text-gray-800">{{ $product->indications }}</div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-1">Zat Aktif</div>
                <div class="text-[14px] leading-6 text-gray-800">{{ $product->active_ingredients }}</div>
            </div>

            <div>
                <div class="text-sm font-semibold mb-1">Komposisi</div>
                <div class="text-[14px] leading-6 text-gray-800">{{ $product->composition }}</div>
            </div>
        </div>

        <!-- Section Keamanan -->
        @php
            $pregnancyDesc = [
                'A' => 'Aman, uji tuntas pada ibu & janin (praktis hampir tidak ada risiko)',
                'B' => 'Risiko rendah – dipakai boleh jika manfaat melebihi risiko',
                'C' => 'Risiko sedang – boleh jika manfaat melebihi risiko (banyak produk masuk kategori C)',
                'D' => 'Berisiko nyata pada janin, hanya digunakan jika sangat dibutuhkan',
                'X' => 'Contraindicated — dilarang keras untuk ibu hamil',
            ];
            $cat = $product->pregnancy_category ?? '-';
        @endphp

        <div class="border border-[#f1f1fa] rounded-2xl p-5 flex flex-col gap-3 shadow">
            <h4 class="font-bold text-base text-gray-700 mb-2">Informasi Keamanan</h4>

            <!-- Kategori Kehamilan -->
            <div>
                <div class="flex items-center gap-x-4 mb-2">
                    <div class="text-sm font-bold">Kategori Kehamilan</div>
                    <div class="inline-block px-3 py-1 rounded text-sm font-bold text-primary bg-orange-100">
                        {{ $cat }}
                    </div>
                </div>
                <div class="text-[14px] leading-6 text-gray-800">
                    {{ $pregnancyDesc[$cat] ?? '-' }}
                </div>
            </div>

            <!-- Efek Samping -->
            <div>
                <div class="text-sm font-semibold mb-1">Efek Samping</div>
                <div class="text-[14px] leading-6 text-gray-800">
                    {{ $product->side_effects ?? '-' }}
                </div>
            </div>

            <!-- Kontraindikasi -->
            <div>
                <div class="text-sm font-semibold mb-1">Kontraindikasi</div>
                <div class="text-[14px] leading-6 text-gray-800">
                    @if ($product->contraindications)
                        {{ str_replace(',', ', ', $product->contraindications) }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

        <!-- Section Dosis & Aturan Pakai -->
        @php
            // Ambil semua rules dari produk
            $rules = $product->medicationRules;

            // Filter: hanya rule yang punya rentang umur bermakna
            // (minimal salah satu min/max age tidak 0 dan tidak null)
            $filteredRules = $rules->filter(function ($rule) {
                $min = $rule->min_age;
                $max = $rule->max_age;

                // Kalau dua-duanya null atau dua-duanya 0 → abaikan
                if ((is_null($min) && is_null($max)) || ($min == 0 && $max == 0)) {
                    return false;
                }

                return true;
            });
        @endphp

        @if ($filteredRules->count() > 0)
            <div class="border border-[#f1f1fa] rounded-2xl p-5 flex flex-col gap-3 shadow">
                @foreach ($filteredRules as $rule)
                    <div class="flex flex-col mb-4">
                        <!-- Badge Info -->
                        <div class="flex gap-2 text-xs mb-2">
                            <span class="bg-orange-100 text-primary px-3 py-1 rounded-xl font-semibold">
                                {{ $rule->special_condition ?? 'Umum' }}
                                {{-- Tampilkan umur hanya kalau tidak 0–0 --}}
                                @if (!is_null($rule->min_age) || !is_null($rule->max_age))
                                    @if (!($rule->min_age == 0 && $rule->max_age == 0))
                                        | {{ $rule->min_age ?? 0 }} - {{ $rule->max_age ?? 0 }} tahun
                                    @endif
                                @endif
                            </span>
                        </div>

                        <!-- Dosage Info -->
                        <div class="mt-1 ml-2 mb-2">
                            <div class="text-sm font-semibold mb-1">
                                Dosis: {{ $rule->default_dosage ?? '-' }}
                            </div>
                            @if ($rule->usage_instruction)
                                <span class="text-[14px] leading-6 text-gray-800">
                                    ({{ $rule->usage_instruction }})
                                </span>
                            @endif
                        </div>

                        <!-- Frequency & Duration -->
                        <div class="mt-1 ml-2 text-xs text-gray-500">
                            Frekuensi: {{ $rule->default_frequency ?? '-' }}x / hari &nbsp; | &nbsp; Durasi:
                            {{ $rule->duration ?? '-' }} hari
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Rekomendasi Obat Alternatif yang Aman -->
        @auth
          @if(isset($alternativeProducts) && $alternativeProducts->count() > 0)
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex flex-col gap-3">
              <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <h4 class="font-bold text-base text-green-800">Rekomendasi Obat Alternatif</h4>
              </div>
              <p class="text-xs text-green-700 -mt-1">Obat berikut dalam kategori yang sama dan aman untuk kondisi medis Anda:</p>
              
              <div class="grid grid-cols-2 gap-3 mt-2">
                @foreach($alternativeProducts as $alt)
                  <a href="{{ route('frontend.product.details', $alt->slug) }}" class="bg-white rounded-xl p-3 flex flex-col gap-2 items-center shadow-sm hover:shadow-md transition-shadow">
                    <img src="{{ Storage::url($alt->photo) }}" class="h-[60px] w-full object-contain" alt="{{ $alt->name }}">
                    <div class="w-full text-center">
                      <p class="text-xs font-semibold truncate">{{ $alt->name }}</p>
                      <p class="text-xs text-grey">Rp {{ number_format($alt->price, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-[10px] font-semibold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Aman</span>
                  </a>
                @endforeach
              </div>
            </div>
          @endif
        @endauth

        <!-- Fixed Checkout Footer -->
        <section id="fixed-checkout-footer" class="fixed inset-x-0 bottom-0 z-40 p-5 bg-white shadow-2xl">
            <div class="max-w-[425px] mx-auto flex items-center justify-between">
                <!-- Price Info -->
                <div class="flex flex-col gap-0.5">
                    <p class="text-sm text-grey">
                        Harga (per item)
                    </p>
                    <p class="text-lg min-[350px]:text-2xl font-bold">
                        Rp. {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ route('carts.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex w-max text-white font-bold text-base bg-primary rounded-full px-[30px] py-3 justify-center items-center whitespace-nowrap">
                        Add to Cart
                    </button>
                </form>
            </div>
        </section>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
        <script src="{{ asset('scripts/sliderConfig.js') }}" type="module"></script>
        <script src="{{ asset('scripts/fixedTopbar.js') }}"></script>

</body>

</html>
