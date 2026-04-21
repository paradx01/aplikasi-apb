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
      @include('partials.pwa')
</head>

  <body>
      <!-- Topbar -->
      <section  id="topbar" class="fixed top-0 z-20 w-full transition duration-300">
          <div class="flex items-center justify-between gap-2 wrapper">
            <a href="{{route('frontend.index')}}" class="p-2 bg-white rounded-full">
              <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
            </a>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
              My Orders
            </p>
          </div>
      </section>

      <!-- Floating navigation -->
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
				<a href="{{ route('product_transactions.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group {{ request()->routeIs('product_transactions.index') ? 'is-active' : '' }}">
                    <img src="{{asset('assets/svgs/ic-note.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
                    <p class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
                        Orders
                    </p>
                </a>
				<a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
					<img src="{{asset('assets/svgs/ic-profile.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary"
						alt="">
					<p
						class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
						Profile
					</p>
				</a>
			</div>
		</nav>

      {{-- resources/views/product_transactions/partials/buyer.blade.php --}}
        <div class="wrapper flex flex-col gap-2">
            @forelse($product_transactions as $transaction)
                <div class="item-card flex flex-col rounded-3xl p-5 bg-white mb-2">
                    <div class="mb-4 flex justify-start">    
                        <div>
                            <span class="px-4 py-2 rounded-full font-bold text-xs
                                @if($transaction->status === 'pending') bg-gray-400 text-white
                                @elseif($transaction->status === 'paid') bg-green-400 text-white
                                @elseif($transaction->status === 'process') bg-blue-400 text-white
                                @elseif($transaction->status === 'shipped') bg-yellow-500 text-white
                                @elseif($transaction->status === 'delivered') bg-indigo-500 text-white
                                @elseif($transaction->status === 'success') bg-green-500 text-white
                                @elseif($transaction->status === 'canceled') bg-red-500 text-white
                                @endif
                            ">
                                {{ strtoupper($transaction->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-4 flex justify-between items-center">
                        <div>
                            <p class="text-base text-gray-600">Tanggal Transaksi:</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{$transaction->created_at->format('d M Y')}}</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="mb-2 text-xs text-gray-400">Total</p>
                            <p class="text-xl font-bold text-indigo-700">Rp. {{ number_format($transaction->total_amount,0,',','.') }}</p>
                        </div>
                        <div>
                            <p class="mb-6"></p>
                            <a href="{{route('product_transactions.show', $transaction)}}" class="ml-4 px-4 py-2 rounded-full bg-orange-400 text-white text-xs font-semibold">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-sm text-gray-600 mt-10">Belum tersedia transaksi terbaru</p>
            @endforelse
        </div>

      

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/global.js')}}"></script>
    <script src="{{asset('scripts/fixedTopbar.js')}}"></script>
  </body>

</html>
