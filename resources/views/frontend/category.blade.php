<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Products | Parma</title>
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
    <section id="topbar" class="fixed top-0 z-20 w-full transition duration-300"">
      <div class="flex items-center justify-between gap-2 wrapper">
            <a href="{{route('frontend.index')}}" class="p-2 bg-white rounded-full">
              <img src="{{asset('assets/svgs/ic-arrow-left.svg')}}" class="size-5" alt="">
            </a>
            <p id="topbar-title" class="absolute text-base font-semibold top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition duration-100 text-gray-800">
              {{ $category->name }}
            </p>
            <button type="button" class="p-2 bg-white rounded-full">
                <img src="{{asset('assets/svgs/ic-triple-dots.svg')}}" class="size-5" alt="">
            </button>
        </div>
    </section>

    <!-- Floating navigation -->
		<nav id="main-floating-nav" class="fixed z-50 bottom-[30px] bg-black rounded-[50px] pt-[18px] px-10 left-1/2 -translate-x-1/2 w-80">
			<div class="flex items-center justify-center gap-5 flex-nowrap">
				<a href="{{ route('frontend.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
					<img src="{{asset('assets/svgs/ic-grid.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
					<p
						class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
						Home
					</p>
				</a>
				<a href="{{ route('frontend.search') }}" class="flex flex-col items-center justify-center gap-1 px-1 group is-active">
					<img src="{{asset('assets/svgs/magnifying-glass-20-solid.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary"
						alt="">
					<p
						class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
						Search
					</p>
				</a>
				<a href="{{ route('product_transactions.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
					<img src="{{asset('assets/svgs/ic-note.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
					<p
						class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
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
		</nav>

    <!-- Search Results -->
    <section class="wrapper flex flex-col gap-2.5" id="productResults">
        <p id="resultTitle" class="text-base font-bold">
          Results
        </p>
        <div class="flex flex-col gap-4">
          @forelse ($products as $product)
              <!-- Product -->
              <div class="py-3.5 pl-4 pr-[22px] bg-white rounded-2xl flex gap-2 items-center relative product-box" data-name="{{ strtolower($product->name) }}">
              <img src="{{Storage::url($product->photo)}}" class="w-full max-w-[70px] max-h-[70px] object-contain"
                  alt="">
              <div class="flex flex-wrap items-center justify-between w-full gap-1">
                  <div class="flex flex-col gap-1">
                  <a href="{{route('frontend.product.details', $product->slug)}}" class="text-base font-semibold stretched-link whitespace-nowrap w-[150px] truncate">
                      {{$product->name}}
                  </a>
                  <p class="text-sm text-grey">
                      Rp. {{ number_format($product->price,0,',','.') }}
                  </p>
                  </div>
                  {{-- <div class="flex">
                  <img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
                  <img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
                  <img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
                  <img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
                  <img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
                  </div> --}}
              </div>
              </div>
          @empty
              Produk tidak tersedia
          @endforelse
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/sliderConfig.js')}}" type="module"></script>
    <script src="{{asset('scripts/promoCarousel.js')}}" type="module"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchProduct');
        const productBoxes = document.querySelectorAll('.product-box');
    });
    </script>

  </body>

</html>