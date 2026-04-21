<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Berhasil | Parma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @include('partials.pwa')
</head>
<body>
    @include('components.flash-toast')

    <section class="wrapper min-h-screen flex items-center">
        <div class="container mx-auto px-4 py-8 max-w-md">
            <div>
                <img src="{{ asset('assets/images/checkout.png') }}"
                    alt="Checkout Success"
                    class="w-[90px] h-[70px] object-contain mx-auto mb-8">
            </div>

            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
                Yeay! Pesananmu berhasil dibuat.
            </h2>

            <p class="text-gray-600 text-center mb-8 px-4">
                Pesananmu sedang kami proses. Kamu bisa cek status terbaru di halaman "My Orders".
            </p>

            <div class="px-4">
                <a href="{{ route('product_transactions.index') }}"
                    class="block w-full bg-primary text-white font-semibold text-center py-4 rounded-full">
                    My Orders
                </a>
            </div>
        </div>
    </section>
</body>
</html>
