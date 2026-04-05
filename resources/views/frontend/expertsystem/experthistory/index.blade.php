<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Rekomendasi | Parma</title>
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
  </head>

  <body>
    <!-- Topbar -->
    <section id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
      <div class="flex items-center justify-between gap-2 wrapper">
        <a href="{{route('frontend.index')}}" class="p-2 bg-white rounded-full">
          <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
        </a>
        <p id="topbar-title"
           class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
          Riwayat Rekomendasi Obat
        </p>
      </div>
    </section>

    {{-- Content --}}
    <main class="pb-32 pt-4">
      <section class="wrapper flex flex-col gap-3">

        @if($histories->isEmpty())
          <div class="bg-white rounded-2xl p-5 text-center">
            <p class="text-sm text-grey">
              Belum ada riwayat rekomendasi obat yang selesai.
            </p>
          </div>
        @else
          <div class="flex flex-col gap-3">
            @foreach($histories as $history)
              <div class="bg-white rounded-2xl p-4 flex flex-col gap-3 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                  <div class="flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-grey mb-0.5">
                      Diagnosa
                    </p>
                    <p class="text-base font-semibold text-black">
                      {{ $history->disease_name }}
                    </p>
                    <p class="text-xs text-grey mt-0.5">
                      {{ $history->created_at->format('d M Y, H:i') }}
                    </p>
                    @if($history->confidence)
                      <p class="text-xs text-primary mt-0.5">
                        Confidence: {{ number_format($history->confidence, 1) }}%
                      </p>
                    @endif
                    @if($history->transaction)
                      <p class="text-xs text-grey mt-0.5">
                        Total:
                        <span class="font-semibold text-black">
                          Rp {{ number_format($history->transaction->total_amount, 0, ',', '.') }}
                        </span>
                      </p>
                    @endif
                  </div>
                  <a href="{{ route('frontend.expertsystem.historyDetails', $history->id) }}"
                     class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white rounded-full bg-primary whitespace-nowrap">
                    Lihat Detail
                  </a>
                </div>

                {{-- Preview obat dibeli --}}
                @php
                  $purchasedProducts = collect($history->recommended_products)
                    ->whereIn('id', $history->purchased_product_ids ?? []);
                @endphp
                <div class="flex flex-wrap gap-1 mt-1">
                  @forelse($purchasedProducts as $product)
                    <span class="px-2 py-1 rounded-full bg-emerald-100 text-[11px] font-semibold text-emerald-700">
                      {{ $product['name'] }}
                    </span>
                  @empty
                    <span class="text-[11px] text-grey">
                      Tidak ada obat yang dibeli
                    </span>
                  @endforelse
                </div>
              </div>
            @endforeach
          </div>

          <div class="mt-4">
            {{ $histories->links() }}
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
