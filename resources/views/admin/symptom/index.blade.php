<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Halaman Data Gejala') }}
            </h2>
            <a href="{{ route('admin.symptoms.create') }}" class="font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                Tambah Gejala
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
                
                @forelse($symptoms as $listGejala)
                    <!-- Desktop & Tablet Layout -->
                    <div class="item-card hidden sm:grid sm:grid-cols-12 gap-4 items-center border-b border-gray-200 dark:border-gray-700 pb-5 last:border-b-0 last:pb-0">
                        <!-- Icon & Name (col-span-5) -->
                        <div class="col-span-5 flex items-center gap-x-3">
                            <div class="w-[50px] h-[50px] flex items-center justify-center rounded-full flex-shrink-0 {{ $listGejala->type == 'Kritis' ? 'bg-red-100 dark:bg-red-900' : 'bg-blue-100 dark:bg-blue-900' }}">
                                @if($listGejala->type == 'Kritis')
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <div class="min-w-0">
                                <h3 class="text-lg font-bold text-indigo-400 dark:text-indigo-300 truncate">
                                    {{ $listGejala->symptom_name }}
                                </h3>
                                @if($listGejala->description)
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                    {{ Str::limit($listGejala->description, 60) }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Badge Type (col-span-3, centered) -->
                        <div class="col-span-3 flex justify-center">
                            @if($listGejala->type == 'Kritis')
                                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 whitespace-nowrap">
                                    Gejala Kritis
                                </span>
                            @else
                                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 whitespace-nowrap">
                                    Gejala Umum
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons (col-span-4) -->
                        <div class="col-span-4 flex justify-end items-center gap-x-3">
                            <a href="{{ route('admin.symptoms.edit', $listGejala) }}" class="py-2 px-4 rounded-full text-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-800 transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.symptoms.destroy', $listGejala) }}">
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
                        <!-- Header: Icon + Name -->
                        <div class="flex items-start gap-x-3">
                            <div class="w-[45px] h-[45px] flex items-center justify-center rounded-full flex-shrink-0 {{ $listGejala->type == 'Kritis' ? 'bg-red-100 dark:bg-red-900' : 'bg-blue-100 dark:bg-blue-900' }}">
                                @if($listGejala->type == 'Kritis')
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-indigo-400 dark:text-indigo-300">
                                    {{ $listGejala->symptom_name }}
                                </h3>
                                @if($listGejala->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ Str::limit($listGejala->description, 50) }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Badge Type -->
                        <div class="flex justify-start">
                            @if($listGejala->type == 'Kritis')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                                    Gejala Kritis
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200">
                                    Gejala Umum
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-x-2">
                            <a href="{{ route('admin.symptoms.edit', $listGejala) }}" class="flex-1 py-2 text-center rounded-full text-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-800 transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.symptoms.destroy', $listGejala) }}" class="flex-1">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-lg text-slate-500 dark:text-slate-400">
                            Belum ada gejala ditambahkan
                        </p>
                        <a href="{{ route('admin.symptoms.create') }}" class="mt-4 inline-block font-bold py-3 px-5 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                            Tambah Gejala Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
