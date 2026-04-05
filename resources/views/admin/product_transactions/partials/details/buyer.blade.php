<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order | Parma</title>
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
          padding-left: 1.5rem; /* px-6 equivalent */
          padding-right: 1.5rem; /* px-6 equivalent */
      }
      .scrolled {
          background-color: #ffffff; /* Warna solid saat scroll */
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
    </style>
  </head>

  <body>
      <!-- Topbar -->
      <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
          <div class="flex items-center justify-between gap-2 wrapper">
            <a href="{{route('product_transactions.index')}}" class="p-2 bg-white rounded-full">
              <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
            </a>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
              Detail Order
            </p>
          </div>
      </section>

      {{-- <!-- Floating navigation -->
      <nav id="main-floating-nav" class="fixed z-50 bottom-[30px] bg-black rounded-[50px] pt-[18px] px-10 left-1/2 -translate-x-1/2 w-80">
          <div class="flex items-center justify-center gap-5 flex-nowrap">
            <a href="{{ route ('frontend.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
            <img src="{{asset('assets/svgs/ic-grid.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary"
              alt="">
            <p
              class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
              Home
            </p>
          </a>
          <a href="{{ route ('frontend.search') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
            <img src="{{asset('assets/svgs/magnifying-glass-20-solid.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary"
              alt="">
            <p
              class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
              Search
            </p>
          </a>
          <a href="{{ route('product_transactions.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group is-active">
              <img src="{{asset('assets/svgs/ic-note.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
              <p class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
                Orders
              </p>
          </a>
          <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
            <img src="{{asset('assets/svgs/ic-profile.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary"
              alt="">
            <p
              class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
              Profile
            </p>
          </a>
        </div>
      </nav> --}}

      {{-- resources/views/product_transactions/partials/buyer.blade.php --}}
      <section class="wrapper flex flex-col gap-2.5">
        <div class="p-6 bg-white rounded-3xl">
          <div class="mb-4 flex justify-between items-center">
              <div>
                  <p class="text-base text-gray-600">Tanggal Transaksi:</p>
              </div>
              <div>
                  <p class="text-sm font-semibold text-gray-700">{{ $productTransaction->created_at->format('d M Y') }}</p>
              </div>
          </div>
          
          <div class="flex justify-between items-center">
              <div>
                  <p class="text-xs text-gray-400 mb-1">Total</p>
                  <p class="text-xl font-bold text-indigo-700">Rp. {{ number_format($productTransaction->total_amount,0,',','.') }}</p>
              </div>
              <div>
                  <p class="mb-4"></p>
                  @php
                    // Untuk tampilan buyer, translate status DB ke tampilan
                    $displayStatus = $productTransaction->status === 'success' ? 'delivered' : $productTransaction->status;
                  @endphp
                  <span class="px-4 py-2 rounded-full font-bold text-xs
                    @if($productTransaction->status === 'pending') bg-gray-400 text-white
                    @elseif($productTransaction->status === 'paid') bg-green-400 text-white
                    @elseif($productTransaction->status === 'process') bg-blue-400 text-white
                    @elseif($productTransaction->status === 'shipped') bg-yellow-500 text-white
                    @elseif($productTransaction->status === 'delivered') bg-indigo-500 text-white
                    @elseif($productTransaction->status === 'success') bg-green-500 text-white
                    @elseif($productTransaction->status === 'canceled') bg-red-500 text-white
                    @endif
                    ">
                    {{ strtoupper($displayStatus) }}
                  </span>
              </div>
          </div>
        </div>
      </section>
      <section class="wrapper flex flex-col gap-2.5">
        <div class="p-6 bg-white rounded-3xl">
            <!-- Daftar Produk -->
            <div class="text-lg font-semibold mb-4 text-indigo-950">Daftar Pesanan</div>
              <div class="flex flex-col gap-3">
                  @foreach($productTransaction->transactionDetails as $detail)
                      <div class="px-3 py-2">
                          <div class="flex items-center gap-3 mb-4">
                              <img src="{{ Storage::url($detail->product->photo) }}" class="w-14 h-14 rounded object-cover" />
                              <div>
                                  <div class="flex items-center gap-3">
                                      <span class="font-bold text-indigo-800">{{ $detail->product->name }}</span>
                                      <span class="px-2 py-0.5 rounded-full bg-indigo-400 text-white text-xs font-bold ml-1">
                                          x{{ $detail->quantity }}
                                      </span>
                                  </div>
                                  <div class="flex items-center gap-1 mt-1">
                                      <div class="text-xs text-gray-500">
                                          {{ $detail->product->category->name ?? '-' }}
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="mt-2 flex justify-end">
                              <span class="font-bold text-base text-indigo-700">
                                  Total Rp {{ number_format($detail->price * $detail->quantity,0,',','.') }}
                              </span>
                          </div>
                      </div>
                  @endforeach
              </div>
            {{-- <!-- Bukti Pembayaran -->
            @if($productTransaction->proof)
                <div class="border-b my-3"></div>
                <div class="flex flex-col items-center gap-2 mt-1">
                    <div class="font-semibold text-gray-700 mb-1 text-center">Bukti Pembayaran</div>
                    <img src="{{ Storage::url($productTransaction->proof) }}" alt="Bukti Pembayaran" class="w-full max-w-xs mx-auto rounded shadow">
                </div>
            @endif --}}
        </div>
      </section>
      <section class="wrapper flex flex-col gap-2.5">
        <div class="p-6 bg-white rounded-3xl">
            <!-- Alamat Pengiriman -->
            <div class="text-lg font-semibold mt-2 mb-3 text-indigo-950 flex items-center gap-2">
                <span>Alamat Pengiriman</span>
                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
            </div>
            <div class="text-sm text-gray-700 mt-2">

                <!-- Penerima -->
                <div class="mb-4">
                    <span class="font-bold">Penerima</span>
                    <div class="ml-1 mt-1">{{ $productTransaction->recipient_name }}</div>
                </div>


                <!-- Alamat dan Kota -->
                <div class="mb-4">
                    <span class="font-bold">Alamat</span>
                    <div class="ml-1 mt-1">
                        {{ $productTransaction->address }}, {{ $productTransaction->city }},
                        {{ $productTransaction->post_code }}
                    </div>
                </div>

                <!-- Telepon -->
                <div class="mb-4">
                    <span class="font-bold">Telepon:</span>
                    <div class="ml-1 mt-1">{{ $productTransaction->phone_number }}</div>
                </div>
            </div>
            @if($productTransaction->notes)
                <div class="text-xs text-gray-500 italic mt-2">Catatan: {{ $productTransaction->notes }}</div>
            @endif
        </div>
        

        <!-- Floating Confirm Button -->
        @if($productTransaction->status === 'shipped')
          <form method="POST" action="{{ route('product_transactions.deliver', $productTransaction) }}" class="fixed z-50 bottom-[30px] left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[425px] mt-6">
              @csrf
              @method('PUT')
              <button type="submit" 
                class="w-full py-3 px-5 rounded-full bg-primary text-white text-lg font-bold flex items-center justify-center gap-x-2 transition-colors duration-150">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                  Pesanan Sudah Diterima
              </button>
          </form>
        @endif
        <div class="p-6 bg-white rounded-3xl mt-4">
            <!-- Tombol Contact Apoteker via Whatsapp -->
            <div class="text-lg font-semibold mb-3 text-indigo-950 flex items-center gap-2">
                <span>Terdapat kendala?</span>
            </div>

            <a href="#" class="w-full py-3 px-5 rounded-full bg-green-500 text-white text-lg font-bold flex items-center justify-center gap-x-2"
              id="wa-apoteker-btn">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
              </svg>
              Chat Apoteker
            </a>
        </div>
      </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
  </body>

</html>
