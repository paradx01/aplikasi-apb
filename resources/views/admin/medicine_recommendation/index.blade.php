<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Rekomendasi Obat per Penyakit
            </h2>
            <a href="{{ route('admin.medicine-recommendation.create') }}"
               class="font-bold py-2 px-4 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition">
                Tambah Rekomendasi
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if($rules->count() === 0)
                    <div class="text-center py-10">
                        <p class="text-slate-500 dark:text-slate-400">
                            Belum ada rekomendasi obat yang dibuat.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <tr>
                                    <th class="px-4 py-2">Penyakit</th>
                                    <th class="px-4 py-2">Produk</th>
                                    <th class="px-4 py-2">Rentan Usia</th>
                                    <th class="px-4 py-2">Prioritas</th>
                                    <th class="px-4 py-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($rules as $rule)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-2 align-top">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $rule->disease->disease_name ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-2 align-top">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $rule->product->name ?? '-' }}
                                            </div>
                                            @if(!empty($rule->notes))
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                    {{ $rule->notes }}
                                                </p>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2 align-top">
                                            <span class="text-gray-800 dark:text-gray-100">
                                                {{ $rule->min_age }} - {{ $rule->max_age }} tahun
                                            </span>
                                        </td>


                                        <td class="px-4 py-2 align-top">
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200">
                                                {{ $rule->priority }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-2 align-top text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="{{ route('admin.medicine-recommendation.edit', $rule->id) }}"
                                                class="p-2 rounded-full text-white bg-indigo-600 hover:bg-indigo-700 transition"
                                                title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                                a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>

                                                <form action="{{ route('admin.medicine-recommendation.destroy', $rule->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus rekomendasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-2 rounded-full text-white bg-red-600 hover:bg-red-700 transition"
                                                            title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                    a2 2 0 01-1.995-1.858L5 7m5 4v6
                                                                    m4-6v6m1-10V4a1 1 0 00-1-1h-4
                                                                    a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $rules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
