<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Rekomendasi | Parma</title>
    <link rel="shortcut icon" href="{{asset('assets/svgs/logo-mark.svg')}}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
    <style>
      html, body { 
        width: 100%; 
        overflow-x: hidden !important; 
        padding-top: 2.5rem;
        scroll-behavior: smooth;
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
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                    0 2px 4px -2px rgba(0, 0, 0, 0.06);
      }
    </style>
      @include('partials.pwa')
</head>

  <body>
    <!-- Topbar -->
    <section id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
      <div class="flex items-center justify-between gap-2 wrapper">
        <a href="{{route('frontend.expertsystem.listHistory')}}" class="p-2 bg-white rounded-full">
          <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
        </a>
        <p id="topbar-title"
           class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
          Detail Riwayat Rekomendasi
        </p>
      </div>
    </section>
{{-- 
    <!-- Floating navigation -->
    <nav id="main-floating-nav"
         class="fixed z-50 bottom-[30px] bg-black rounded-[50px] pt-[18px] px-10 left-1/2 -translate-x-1/2 w-80">
      <div class="flex items-center justify-center gap-5 flex-nowrap">
        <a href="{{ route('frontend.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
          <img src="{{asset('assets/svgs/ic-grid.svg')}}"
               class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
          <p class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
            Home
          </p>
        </a>
        <a href="{{ route('frontend.search') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
          <img src="{{asset('assets/svgs/magnifying-glass-20-solid.svg')}}"
               class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
          <p class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
            Search
          </p>
        </a>
        <a href="{{ route('product_transactions.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
          <img src="{{asset('assets/svgs/ic-note.svg')}}"
               class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
          <p class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
            Orders
          </p>
        </a>
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center gap-1 px-1 group is-active">
          <img src="{{asset('assets/svgs/ic-profile.svg')}}"
               class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
          <p class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
            Profile
          </p>
        </a>
      </div>
    </nav> --}}

    {{-- Content --}}
    <main class="pb-32 pt-4">
      <section class="wrapper flex flex-col gap-4">

        {{-- Card Diagnosa --}}
        <div class="bg-white rounded-2xl p-4 flex flex-col gap-2 shadow-sm">
          <p class="text-[11px] uppercase tracking-wide text-grey">
            Informasi Diagnosa
          </p>
          <div class="grid grid-cols-1 gap-2 text-sm">
            <div>
              <p class="text-xs text-grey">Penyakit</p>
              <p class="text-base font-semibold text-black">
                {{ $history->disease_name }}
              </p>
            </div>
            <div>
              <p class="text-xs text-grey">Tanggal Diagnosa</p>
              <p class="text-sm font-semibold text-black">
                {{ $history->created_at->format('d M Y, H:i') }}
              </p>
            </div>
            @if($history->confidence)
              <div>
                <p class="text-xs text-grey">Confidence</p>
                <p class="text-sm font-semibold text-primary">
                  {{ number_format($history->confidence, 1) }}%
                </p>
              </div>
            @endif
            @if($history->transaction)
              <div>
                <p class="text-xs text-grey">Total Transaksi</p>
                <p class="text-sm font-semibold text-black">
                  Rp {{ number_format($history->transaction->total_amount, 0, ',', '.') }}
                </p>
              </div>
            @endif
          </div>
        </div>

        {{-- Card Gejala
        <div class="bg-white rounded-2xl p-4 flex flex-col gap-2 shadow-sm">
          <p class="text-[11px] uppercase tracking-wide text-grey">
            Gejala yang Dipilih
          </p>
          <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($history->selected_symptoms as $symptom)
              <li class="text-sm text-black">
                {{ $symptom['name'] }}
              </li>
            @endforeach
          </ul>
        </div> --}}

        {{-- Card Rekomendasi Obat --}}
        <div class="bg-white rounded-2xl p-4 flex flex-col gap-3 shadow-sm">
          <p class="text-[11px] uppercase tracking-wide text-grey">
            Obat yang Direkomendasikan
          </p>

          <div class="flex flex-col gap-2">
            @foreach($history->recommended_products as $product)
              @php
                $isPurchased = in_array($product['id'], $history->purchased_product_ids ?? []);
              @endphp

              <div class="flex items-center justify-between p-3 border rounded-xl text-sm
                  {{ $isPurchased ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200' }}">
                <div class="flex-1">
                  <p class="font-semibold {{ $isPurchased ? 'text-emerald-700' : 'text-black' }}">
                    {{ $product['name'] }}
                    @if($isPurchased)
                      <span class="ml-2 px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[10px] font-bold">
                        DIBELI
                      </span>
                    @endif
                  </p>
                  <div class="mt-1 flex flex-wrap gap-3 text-[11px] text-grey">
                    @if(isset($product['priority']) && $product['priority'])
                      <span>Prioritas: {{ $product['priority'] }}</span>
                    @endif
                    @if(!empty($product['has_warning']))
                      <span class="text-amber-600">
                        Ada peringatan khusus
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Card Detail Transaksi (opsional) --}}
        @if($history->transaction)
          <div class="bg-white rounded-2xl p-4 flex flex-col gap-2 shadow-sm">
            <p class="text-[11px] uppercase tracking-wide text-grey">
              Rincian Obat yang Dibeli
            </p>
            <div class="divide-y divide-gray-100 text-sm">
              @foreach($history->transaction->transactionDetails as $detail)
                <div class="py-2 flex items-center justify-between gap-2">
                  <div class="flex-1">
                    <p class="font-semibold text-black">
                      {{ $detail->product->name ?? 'Produk dihapus' }}
                    </p>
                    <p class="text-xs text-grey">
                      Rp {{ number_format($detail->price, 0, ',', '.') }} x {{ $detail->quantity }}
                    </p>
                  </div>
                  <p class="text-sm font-semibold text-black whitespace-nowrap">
                    Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                  </p>
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>
    <script src="{{asset('scripts/sliderConfig.js')}}" type="module"></script>
    <script src="{{asset('scripts/promoCarousel.js')}}" type="module"></script>
  </body>
</html>
