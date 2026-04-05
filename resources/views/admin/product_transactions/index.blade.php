@if(Auth::user()->hasRole('buyer'))
    @include('admin.product_transactions.partials.index.buyer')
@else
    <x-app-layout>
        <x-slot name="header">
            <div class="flex flex-row w-full justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Apotek Orders
                </h2>
                <div class="flex gap-3 mb-4">
                    <a href="{{ route('product_transactions.exportPdf') }}"
                        class="py-2 px-4 bg-indigo-500 text-white rounded-full font-bold">
                        Export PDF
                    </a>
                    <a href="{{ route('product_transactions.exportExcel') }}"
                        class="py-2 px-4 bg-indigo-500 text-white rounded-full font-bold">
                        Export Excel
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white flex flex-col gap-y-5 dark:bg-gray-800 overflow-hidden p-10 shadow-sm sm:rounded-lg">
                        @forelse($product_transactions as $transaction)
                            <div class="item-card flex flex-row justify-between items-center">
                                <a href="{{ route('product_transactions.show', $transaction) }}">
                                <div class="flex flex-row items-center gap-x-3">
                                    <div>
                                        <p class="text-base text-slate-500">
                                            Total Transaksi
                                        </p>
                                        <h3 class="text-xl font-bold text-indigo-400">
                                            Rp {{number_format($transaction->total_amount, 0, ',', '.')}}
                                        </h3>
                                    </div>
                                </div>
                                </a>
                                <div class="hidden md:flex flex-col">
                                    <p class="text-base text-slate-500">
                                        Date
                                    </p>
                                    <h3 class="text-xl font-bold text-indigo-400">
                                        {{$transaction->created_at}}
                                    </h3>
                                </div>
                                <span class="py-1 px-3 rounded-full font-bold
                                    @if($transaction->status === 'pending') bg-gray-400 text-white
                                    @elseif($transaction->status === 'paid') bg-green-400 text-white
                                    @elseif($transaction->status === 'process') bg-blue-400 text-white
                                    @elseif($transaction->status === 'shipped') bg-yellow-500 text-white
                                    @elseif($transaction->status === 'delivered') bg-indigo-500 text-white
                                    @elseif($transaction->status === 'success') bg-green-500 text-white
                                    @elseif($transaction->status === 'canceled') bg-red-400 text-white
                                    @endif
                                ">
                                    {{ strtoupper($transaction->status) }}
                                </span>
                                <div class="hidden md:flex flex-row items-center gap-x-3">
                                    <a href="{{route('product_transactions.show', $transaction)}}" class="py-3 px-5 rounded-full text-white bg-indigo-700">View Details</a>
                                </div>
                            </div>
                            <hr class="my-3">
                        @empty
                        <p>
                            Belum tersedia transaksi terbaru
                        </p>
                        @endforelse
                    </div>
            </div>
        </div>
    </x-app-layout>
@endif
    
