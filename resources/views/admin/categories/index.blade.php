<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Halaman Kategori Obat') }}
            </h2>
            <a href="{{route('admin.categories.create')}}" class="font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Success -->
            @if(session('success'))
            <div class="mb-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white flex flex-col gap-y-5 dark:bg-gray-800 overflow-hidden p-6 sm:p-10 shadow-sm sm:rounded-lg">
                
                @forelse($categories as $category)
                    <!-- Desktop & Tablet Layout -->
                    <div class="item-card hidden sm:flex flex-row justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-5 last:border-b-0 last:pb-0">
                        <!-- Icon & Name -->
                        <div class="flex flex-row items-center gap-x-4 flex-1">
                            <img 
                                src="{{Storage::url($category->icon)}}" 
                                alt="{{$category->name}}" 
                                class="w-[60px] h-[60px] rounded-lg object-cover flex-shrink-0"
                            >
                            <h3 class="text-xl font-bold text-indigo-400 dark:text-indigo-300">
                                {{$category->name}}
                            </h3>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-row items-center gap-x-3">
                            <a href="{{route('admin.categories.edit', $category)}}" class="py-2 px-4 rounded-full text-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-800 transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
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
                        <!-- Icon & Name -->
                        <div class="flex items-center gap-x-3">
                            <img 
                                src="{{Storage::url($category->icon)}}" 
                                alt="{{$category->name}}" 
                                class="w-[50px] h-[50px] rounded-lg object-cover flex-shrink-0"
                            >
                            <h3 class="text-base font-bold text-indigo-400 dark:text-indigo-300 flex-1">
                                {{$category->name}}
                            </h3>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-x-2">
                            <a href="{{route('admin.categories.edit', $category)}}" class="flex-1 py-2 text-center rounded-full text-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-800 transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="flex-1">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <p class="mt-4 text-lg text-slate-500 dark:text-slate-400">
                            Belum ada kategori ditambahkan
                        </p>
                        <a href="{{route('admin.categories.create')}}" class="mt-4 inline-block font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                            Tambah Kategori Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
