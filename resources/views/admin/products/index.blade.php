<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Halaman Produk') }}
            </h2>
            <a href="{{route('admin.products.create')}}" class="font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Success -->
            @if(session('success'))
            <div class="mb-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white flex flex-col gap-y-5 dark:bg-gray-800 overflow-hidden p-6 sm:p-10 shadow-sm sm:rounded-lg">
                
                @forelse($products as $product)
                    <!-- Desktop & Tablet Layout -->
                    <div class="item-card hidden sm:flex flex-row justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-5 last:border-b-0 last:pb-0">
                        <!-- Product Info -->
                        <div class="flex flex-row items-center gap-x-3 flex-1 min-w-0">
                            <img 
                                src="{{Storage::url($product->photo)}}" 
                                alt="{{$product->name}}" 
                                class="w-[60px] h-[60px] rounded-lg object-cover flex-shrink-0"
                            >
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-bold text-indigo-400 dark:text-indigo-300 truncate">
                                    {{$product->name}}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="hidden md:block px-6">
                            <span class="px-3 py-1 rounded-full text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{$product->category->name}}
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-row items-center gap-x-3 flex-shrink-0">
                            <a href="{{route('admin.products.edit', $product)}}" class="py-2 px-4 rounded-full text-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-800 transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="py-2 px-4 rounded-full text-sm font-semibold text-white bg-red-700 hover:bg-red-800 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Layout -->
                    <div class="item-card sm:hidden flex flex-col gap-y-3 border-b border-gray-200 dark:border-gray-700 pb-5 last:border-b-0 last:pb-0">
                        <!-- Product Header -->
                        <div class="flex items-start gap-x-3">
                            <img 
                                src="{{Storage::url($product->photo)}}" 
                                alt="{{$product->name}}" 
                                class="w-[60px] h-[60px] rounded-lg object-cover flex-shrink-0"
                            >
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-indigo-400 dark:text-indigo-300">
                                    {{$product->name}}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <span class="inline-block mt-2 px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{$product->category->name}}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-x-2">
                            <a href="{{route('admin.products.edit', $product)}}" class="flex-1 py-2 text-center rounded-full text-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-800 transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 rounded-full text-sm font-semibold text-white bg-red-700 hover:bg-red-800 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                @empty
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p class="mt-4 text-lg text-slate-500 dark:text-slate-400">
                            Belum ada produk ditambahkan
                        </p>
                        <a href="{{route('admin.products.create')}}" class="mt-4 inline-block font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                            Tambah Produk Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
