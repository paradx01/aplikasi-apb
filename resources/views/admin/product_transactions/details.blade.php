@if(Auth::user()->hasRole('buyer'))
                @include('admin.product_transactions.partials.details.buyer', ['productTransaction' => $productTransaction])
            @else
<x-app-layout>
        <x-slot name="header">
            <div class="flex flex-row w-full justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Details of Transaction
                </h2>
                <div class="flex gap-3 mb-4">
                </div>
            </div>
        </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white flex flex-col gap-y-5 dark:bg-gray-800 overflow-hidden p-10 shadow-sm sm:rounded-lg">
                    <div class="item-card flex gap-y-3 flex-col md:flex-row justify-between md:items-center">
                        <div class="flex flex-row items-center gap-x-3">
                            <div>
                                <p class="text-base text-slate-500">
                                    Total Transaksi
                                </p>
                                <h3 class="text-xl font-bold text-indigo-400">
                                    Rp {{number_format($productTransaction->total_amount, 0, ',', '.')}}
                                </h3>
                            </div>
                        </div>
                        <div>
                            <p class="text-base text-slate-500">
                                Date
                            </p>
                            <h3 class="text-xl font-bold text-indigo-400">
                                {{$productTransaction->created_at}}
                            </h3>
                        </div>
                        <div>
                            <p class="text-base text-slate-500 mb-1">Status Pembayaran</p>
                            @if($productTransaction->is_paid)
                                <span class="px-3 py-1 bg-green-500 rounded-full text-white font-bold">Lunas</span>
                            @else
                                <span class="px-3 py-1 bg-orange-500 rounded-full text-white font-bold">Belum Lunas</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-base text-slate-500 mb-1">Status Order</p>
                            <span class="px-3 py-1 rounded-full font-bold
                                @if($productTransaction->status === 'pending') bg-gray-400 text-white
                                @elseif($productTransaction->status === 'process') bg-blue-400 text-white
                                @elseif($productTransaction->status === 'shipped') bg-yellow-400 text-white
                                @elseif($productTransaction->status === 'delivered') bg-indigo-400 text-white
                                @elseif($productTransaction->status === 'success') bg-green-500 text-white
                                @elseif($productTransaction->status === 'canceled') bg-red-400 text-white
                                @endif
                                ">
                                {{ strtoupper($productTransaction->status) }}
                            </span>
                        </div>
                    </div>
                    <hr class="my-3">

                    <h3 class="text-xl font-bold text-indigo-400">
                        List of Items
                    </h3>

                    <grid class="grid-cols-1 md:grid-cols-4 grid gap-x-10">
                        <div class="flex flex-col gap-y-5 col-span-2">
                            @forelse($productTransaction->transactionDetails as $detail)
                                <div class="item-card flex flex-row justify-between items-center">
                                    <div class="flex flex-row items-center gap-x-3">
                                        <img src="{{Storage::url($detail->product->photo)}}" alt="" class="w-[50px] h-[50px]">
                                        <div>
                                            <h3 class="text-xl font-bold text-indigo-400">
                                                {{$detail->product->name}}
                                            </h3>
                                            <p class="text-base text-slate-500">
                                                Rp. {{$detail->product->price}}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-base text-slate-500">
                                        {{$detail->product->category->name}}
                                    </p>
                                    <span class="px-2 py-1 rounded-full bg-indigo-400 text-white text-xs font-bold ml-1">
                                          x{{ $detail->quantity }}
                                      </span>
                                </div>
                            @empty
                            @endforelse
                            <br>
                            <h3 class="text-xl font-bold text-indigo-950">
                                Details of Delivery
                            </h3>
                            <div class="item-card flex flex-row justify-between items-center">
                                <p class="text-base text-slate-500">
                                    Penerima
                                </p>
                                <h3 class="text-xl font-bold text-indigo-950">
                                    {{ $productTransaction->recipient_name }}
                                </h3>
                            </div>
                            <div class="item-card flex flex-row justify-between items-center">
                                <p class="text-base text-slate-500">
                                    Address
                                </p>
                                <h3 class="text-right text-xl font-bold text-indigo-950">
                                    {{ $productTransaction->address }}
                                </h3>
                            </div>
                            <div class="item-card flex flex-row justify-between items-center">
                                <p class="text-base text-slate-500">
                                    City
                                </p>
                                <h3 class="text-xl font-bold text-indigo-950">
                                    {{$productTransaction->city}}
                                </h3>
                            </div>
                            <div class="item-card flex flex-row justify-between items-center">
                                <p class="text-base text-slate-500">
                                    Post Code
                                </p>
                                <h3 class="text-xl font-bold text-indigo-950">
                                    {{$productTransaction->post_code}}
                                </h3>
                            </div>
                            <div class="item-card flex flex-row justify-between items-center">
                                <p class="text-base text-slate-500">
                                    Phone Number
                                </p>
                                <h3 class="text-xl font-bold text-indigo-950">
                                    {{$productTransaction->phone_number}}
                                </h3>
                            </div>
                            <div class="item-card flex flex-col items-start">
                                <p class="text-base text-slate-500">
                                    Notes
                                </p>
                                <h3 class="text-lg font-bold text-indigo-950">
                                    {{$productTransaction->notes}}  
                                </h3>
                            </div>
                        </div>
                        <div class="flex flex-col gap-y-5 col-span-2 items-end">
                            <h3 class="text-xl font-bold text-indigo-950">
                                Proof of Payment
                            </h3>
                            <img src="{{Storage::url($productTransaction->proof)}}" alt="buktipembayaran" class="w-[300px] h-[400px]">
                        </div>
                    </grid>
                    <hr class="my-3">
                    <section class="flex flex-col gap-3 mt-6">
                        @if(!$productTransaction->is_paid && $productTransaction->status === 'pending')
                            <form method="POST" action="{{ route('product_transactions.update', $productTransaction) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="approve_payment">
                                <button class="py-2 px-4 bg-indigo-600 rounded text-white font-bold w-full">Approve Pembayaran & Proses</button>
                            </form>
                            <form method="POST" action="{{ route('product_transactions.update', $productTransaction) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="cancel">
                                <button class="py-2 px-4 bg-red-500 rounded text-white font-bold w-full">Batalkan Transaksi</button>
                            </form>
                        @elseif($productTransaction->is_paid && $productTransaction->status === 'process')
                                <form method="POST" action="{{ route('product_transactions.update', $productTransaction) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="mark_shipped">
                                <button class="py-2 px-4 bg-yellow-500 rounded text-white font-bold w-full">Kirim/Antar Pesanan</button>
                            </form>
                        @elseif($productTransaction->status === 'shipped')   
                            <p class="text-base text-slate-500 pl-2">Chat Buyer</p>
                            <a href="#" target="_blank" class="w-fit font-bold py-3 px-5 rounded-full text-white bg-green-500">
                                WhatsApp Customer
                            </a>
                        @endif
                    </section>
                </div>
        </div>
    </div>
</x-app-layout>
@endif