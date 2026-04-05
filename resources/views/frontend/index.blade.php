<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#38b2ac"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Landing Page | Parma</title>
		<link rel="shortcut icon" href="{{asset('assets/svgs/logo-mark.svg')}}" type="image/x-icon">
		<script src="https://cdn.tailwindcss.com"></script>
		<link rel="manifest" href="/manifest.json">
		<link rel="stylesheet" href="{{asset('css/main.css')}}">
		<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css'">
		<style>
			html, body { 
				width: 100%; 
				overflow-x: hidden !important; 
			}
			main-floating-nav {
				position: fixed !important;
			}
			/* Custom styles untuk indicator */
			.carousel-indicators {
				display: flex;
				justify-content: center;
				gap: 8px;
				padding: 12px 0;
			}
			
			.indicator-dot {
				width: 8px;
				height: 8px;
				border-radius: 50%;
				background-color: #f0f0f0;
				transition: all 0.3s ease;
				cursor: pointer;
			}
			
			.indicator-dot.active {
				background-color: #eba851;
				width: 24px;
				border-radius: 4px;
			}
			
			.carousel-cell {
				width: 100%;
			}

			/* Responsive untuk layar sangat kecil */
			@media (max-width: 410px) {
				.grid-cols-3 {
					grid-template-columns: repeat(3, minmax(0, 1fr));
				}
			}

			/* Line clamp utility jika belum ada */
			.line-clamp-2 {
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
				overflow: hidden;
			}
		</style>
	</head>

	<body>
		<!-- Topbar -->
		<section class="flex items-center justify-between gap-5 wrapper pb-4">
			<div class="flex items-center gap-3">
				{{-- <div class="bg-white rounded-full p-[3px] flex justify-center items-center">
					<img src="{{asset('assets/svgs/avatar.svg')}}" class="size-[45px] rounded-full" alt="">
				</div> --}}
				<div class="px-3">
					<p class="text-sm text-grey">
						Welcome Back!
					</p>
					<p class="text-xl font-bold capitalize text-primary">
						@auth
							{{Auth::user()->name}}
						@endauth
						@guest
							Guest
						@endguest
					</p>
				</div>
			</div>
			<div class="flex items-center gap-[10px]">
				<button type="button" class="p-2 bg-white rounded-full">
					<span class="relative">
						<a href="{{ route('frontend.reminders.index') }}" class="relative">
							<img src="{{ asset('assets/svgs/ic-notification.svg') }}" class="size-5" alt="">
							@if($hasActiveReminder)
								<span class="block rounded-full size-1.5 bg-primary absolute top-0 right-0 -translate-x-1/2"></span>
							@endif
						</a>
					</span>
				</button>
				{{-- <button type="button" class="p-2 bg-white rounded-full">
					<img src="{{asset('assets/svgs/ic-shopping-bag.svg')}}" class="size-5" alt="" href="{{ route('carts.index') }}">
				</button> --}}
			</div>
		</section>

		<!-- Floating Cart Summary (Hanya muncul jika ada item di cart) -->
		@if($my_carts && count($my_carts) > 0)
		<div id="cart-floating-nav" class="fixed z-40 bottom-[120px] bg-black rounded-[50px] py-3 px-6 left-1/2 -translate-x-1/2 w-80">
		<section class="flex items-center justify-between gap-3">
			<div>
			<p class="text-xs text-grey mb-0.5">
				Cart Total ({{ count($my_carts) }} items)
			</p>
			<p class="text-lg font-bold text-white" id="index-grand-total">
				Rp 
			</p>
			</div>
			<a href="{{ route('carts.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white rounded-full w-max bg-primary whitespace-nowrap">
			Checkout
			</a>
		</section>
		</div>
		@endif

		<!-- Floating navigation -->
		<nav id="main-floating-nav" class="fixed z-50 bottom-[30px] bg-black rounded-[50px] pt-[18px] px-10 left-1/2 -translate-x-1/2 w-80">
			<div class="flex items-center justify-center gap-5 flex-nowrap">
				<a href="{{ route('frontend.index') }}" class="flex flex-col items-center justify-center gap-1 px-1 group is-active">
					<img src="{{asset('assets/svgs/ic-grid.svg')}}" class="filter-to-grey group-[.is-active]:filter-to-primary" alt="">
					<p
						class="border-b-4 border-transparent group-[.is-active]:border-primary pb-3 text-xs text-center font-semibold text-grey group-[.is-active]:text-primary">
						Home
					</p>
				</a>
				<a href="{{ route('frontend.search') }}" class="flex flex-col items-center justify-center gap-1 px-1 group">
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

		{{-- <!-- Promo Carousel -->
		<section class="wrapper relative">
			<div id="promoCarousel" class="relative w-full rounded-2xl overflow-hidden">
                <!-- Promo Item 1 -->
                <div class="carousel-cell relative w-full h-48 bg-purple-400 rounded-2xl flex flex-col justify-center items-start p-5">
                    <h2 class="text-2xl font-extrabold text-white">DISKON 50%</h2>
                    <p class="text-white text-sm">Untuk semua vitamin dan suplemen daya tahan tubuh.</p>
                    <a href="#" class="mt-3 text-sm font-bold text-white underline">Shop Now →</a>
                    <img src="https://placehold.co/100x120/E879F9/ffffff?text=Promo+A" class="absolute right-4 bottom-0 h-40 object-cover rounded-md" alt="Promo Image A">
                </div>

                <!-- Promo Item 2 -->
                <div class="carousel-cell relative w-full h-48 bg-blue-400 rounded-2xl flex flex-col justify-center items-start p-5">
                    <h2 class="text-2xl font-extrabold text-white">GRATIS ONGKIR</h2>
                    <p class="text-white text-sm">Minimum pembelian Rp 100.000 di area Jabodetabek.</p>
                    <a href="#" class="mt-3 text-sm font-bold text-white underline">Lihat Syarat →</a>
                    <img src="https://placehold.co/100x120/60A5FA/ffffff?text=Promo+B" class="absolute right-4 bottom-0 h-40 object-cover rounded-md" alt="Promo Image B">
                </div>

                <!-- Promo Item 3 -->
                <div class="carousel-cell relative w-full h-48 bg-green-400 rounded-2xl flex flex-col justify-center items-start p-5">
                    <h2 class="text-2xl font-extrabold text-white">PROMO KHUSUS</h2>
                    <p class="text-white text-sm">Diskon 10% untuk obat kategori Pereda Nyeri & Demam.</p>
                    <a href="#" class="mt-3 text-sm font-bold text-white underline">Cek Produk →</a>
                    <img src="https://placehold.co/100x120/4ADE80/ffffff?text=Promo+C" class="absolute right-4 bottom-0 h-40 object-cover rounded-md" alt="Promo Image C">
                </div>
            </div>

            <!-- Carousel Indicators - Fixed Position Inside Carousel -->
            <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 z-10 carousel-indicators" id="carouselIndicators">
                <span class="indicator-dot active" data-index="0"></span>
                <span class="indicator-dot" data-index="1"></span>
                <span class="indicator-dot" data-index="2"></span>
            </div>
		</section> --}}

		<!-- Search Bar  -->
		<section class="wrapper relative flex flex-col gap-5 items-center justify-center">
			<form action="{{ route('frontend.search') }}" method="GET" id="searchForm" class="w-full">
				<input type="text" name="keyword" id="searchProduct"
					class="block w-full py-3.5 pl-4 pr-10 rounded-[50px] font-semibold placeholder:text-grey placeholder:font-normal text-black text-base bg-no-repeat bg-[calc(100%-16px)] bg-[url('{{asset('assets/svgs/ic-search.svg')}}')] focus:ring-2 focus:ring-primary focus:outline-none focus:border-none transition-all"
					placeholder="Search for products">
			</form>			
		</section>

		<!-- Categories -->
		@php
			// Ambil 7 kategori saja
			$shownCategories = $categories->take(7);
		@endphp

		<section class="wrapper !px-0 flex flex-col gap-2.5 mt-2">
			<p class="px-4 text-base font-bold mb-1">Kategori Obat</p>
			<div class="grid grid-cols-4 gap-3 px-4 py-4">
				@foreach($shownCategories as $category)
					<div class="flex flex-col items-center">
						<a href="{{ route('frontend.product.category', $category) }}" class="flex flex-col items-center group w-full" style="text-decoration:none;">
							<div class="bg-lilac rounded-full w-16 h-16 flex items-center justify-center mb-2">
								<img src="{{Storage::url($category->icon)}}" class="rounded-full w-16 h-16 object-contain" alt="{{ $category->name }}">
							</div>
							<span class="text-xs font-semibold text-gray-800 text-center leading-tight line-clamp-2 w-full truncate">
								{{ $category->name }}
							</span>
						</a>
					</div>
				@endforeach
				<!-- Tombol Lihat Semua -->
				<div class="flex flex-col items-center">
					<a href="#" class="flex flex-col items-center group w-full" style="text-decoration:none;">
						<div class="bg-lilac rounded-full w-16 h-16 flex items-center justify-center mb-2">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="size-6">
								<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
							</svg>
						</div>
						<span class="text-xs font-semibold text-gray-800 text-center leading-tight w-full">
							Lihat Semua
						</span>
					</a>
				</div>
			</div>
		</section>

		<!-- Sistem Rekomendasi Obat -->
		<section class="wrapper">
			<div
				class="bg-lilac py-3.5 px-5 rounded-2xl relative bg-right-bottom bg-no-repeat bg-[url('{{asset('assets/svgs/doctor-help.svg')}}')] bg-auto">
				<img src="{{asset('assets/svgs/cloud.svg')}}" class="-ml-1.5 mb-1.5" alt="">
				<div class="flex flex-col gap-4 mb-[23px]">
					<p class="text-base font-bold">
						Try our Newest System <br>
						Medicine Recommendation
					</p>
					<a href="{{ route('frontend.expertsystem.index') }}"
						class="rounded-full bg-white text-primary flex w-max gap-2.5 px-6 py-2 justify-center items-center text-base font-bold">
						Start Now
					</a>
				</div>
			</div>
		</section>

		<!-- New Products -->
		<section class="wrapper !px-0 flex flex-col gap-2.5">
			<p class="px-4 text-base font-bold">
				Produk Baru
			</p>
			<div id="proudctsSlider" class="relative px-4">
				<!-- Product -->
				@forelse($product as $produk)
				<div class="rounded-2xl bg-white py-3.5 pl-4 pr-[22px] inline-flex flex-col gap-4 items-start mr-4 relative w-[158px]">
					<img src="{{Storage::url($produk->photo)}}" class="h-[100px] w-full object-contain" alt="">
					<div>
						<a href="{{route('frontend.product.details', $produk->slug)}}" class="text-base font-semibold w-[120px] truncate stretched-link block">
							{{$produk->name}}
						</a>
						<p class="text-sm truncate text-grey">
							Rp. {{ number_format($produk->price,0,',','.') }}
						</p>
					</div>
				</div>
				@empty
				<p>
					Belum ada produk baru tersedia.
				</p>
				@endforelse
			</div>
		</section>

		<!-- All Products -->
		@php
			// Ambil 15 produk saja
			$shownProduct = $products->take(14);
		@endphp
		<section class="wrapper !px-0 flex flex-col gap-2.5 pb-40">
			<p class="px-4 text-base font-bold">List Produk</p>

			<div class="px-4">
				@if($products->isEmpty())
					<p>Belum ada produk baru tersedia.</p>
				@else
					<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
						@foreach($shownProduct as $product)
							<div class="rounded-2xl bg-white py-3.5 px-4 flex flex-col gap-4 items-start relative">
								<img src="{{ Storage::url($product->photo) }}"
									class="h-[100px] w-full object-contain"
									alt="{{ $product->name }}">
								<div class="w-full">
									<a href="{{ route('frontend.product.details', $product->slug) }}"
									class="text-sm sm:text-base font-semibold truncate block stretched-link">
										{{ $product->name }}
									</a>
									<p class="text-xs sm:text-sm truncate text-grey">
										Rp {{ number_format($product->price, 0, ',', '.') }}
									</p>
								</div>
							</div>
						@endforeach
					</div>
				@endif
			</div>
		</section>

		<!-- Most Purchased -->
		{{-- <section class="wrapper flex flex-col gap-2.5 pb-40">
			<p class="text-base font-bold">
				Most Purchased
			</p>
			<div class="flex flex-col gap-2">
				<!-- Softovac Rami -->
				@forelse($products as $product)
				<div class="py-3.5 pl-4 pr-[22px] bg-white rounded-2xl flex gap-2 items-center relative">
					<img src="{{Storage::url($product->photo)}}" class="w-full max-w-[70px] max-h-[70px] object-contain"
						alt="">
						
					<div class="flex flex-wrap items-center justify-between w-full gap-1">
						<div class="flex flex-col gap-1">
							<a href="details.html"
								class="text-base font-semibold stretched-link whitespace-nowrap w-[150px] truncate">
								{{$product->name}}
							</a>
							<p class="text-sm text-grey">
								Rp. {{ number_format($product->price,0,',','.') }}
							</p>
						</div>
						<div class="flex">
							<img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
							<img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
							<img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
							<img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
							<img src="{{asset('assets/svgs/star.svg')}}" class="size-[18px]" alt="">
						</div> 
					</div>
				</div>
				@empty
				@endforelse
			</div>
		</section> --}}

		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
		<script src="{{asset('scripts/sliderConfig.js')}}" type="module"></script>
		<script src="{{asset('scripts/promoCarousel.js')}}" type="module"></script>
		<script>
			// Push Notification Settings
			if ('serviceWorker' in navigator && 'PushManager' in window) {
				navigator.serviceWorker.register('/sw.js').then(function(reg) {
					navigator.serviceWorker.ready.then(function() {
						reg.pushManager.getSubscription().then(function(sub) {
							if (!sub) {
								Notification.requestPermission().then(function(permission) {
									if (permission === 'granted') {
										reg.pushManager.subscribe({
											userVisibleOnly: true,
											applicationServerKey: urlBase64ToUint8Array('BAiWFanBbTEyUSk5a4bp0aCMnPkVKD3N0U5IBmY3n_8vIarlCrxsatsLsIXZ6jnpgRw7UuGGhk4Zmnty5-ZiXIg')
										}).then(function(subscription) {
											fetch('/push/subscription', {
												method: 'POST',
												headers: {
													'Content-Type': 'application/json',
													'X-CSRF-TOKEN': '{{ csrf_token() }}'
												},
												body: JSON.stringify({ subscription: subscription })
											}).then(() => {
												console.log('Subscription sent to backend!');
											});
										});
									}
								});
							} else {
								console.log('Already subscribed');
							}
						});
					});
				});
			}

			function urlBase64ToUint8Array(base64String) {
				const padding = '='.repeat((4 - base64String.length % 4) % 4);
				const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
				const rawData = window.atob(base64);
				const outputArray = new Uint8Array(rawData.length);
				for (let i = 0; i < rawData.length; ++i) outputArray[i] = rawData.charCodeAt(i);
				return outputArray;
			}
			// Calculate grand total untuk landing page
			function calculateIndexCartTotal() {
				let subTotal = 0;
				let deliveryFee = 10000;
				
				// Ambil semua harga produk dari cart
				@foreach($my_carts as $cart)
				subTotal += {{ $cart->product->price }};
				@endforeach
				
				// // Hitung tax dan insurance
				// const tax = 11 * subTotal / 100;
				// const insurance = 23 * subTotal / 100;
				
				// Hitung grand total
				const grandTotal = subTotal +  deliveryFee;
				
				// Update tampilan
				const grandTotalElement = document.getElementById('index-grand-total');
				if (grandTotalElement) {
				grandTotalElement.textContent = `Rp ${grandTotal.toLocaleString('id', {
					minimumFractionDigits: 0,
					maximumFractionDigits: 0
				})}`;
				}
			}
			
			// Jalankan saat halaman load
			document.addEventListener('DOMContentLoaded', function() {
				calculateIndexCartTotal();
			});
		</script>
	</body>

</html>