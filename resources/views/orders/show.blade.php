<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order #{{ $order->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="container mx-auto p-4">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-center">Detail Order #{{ $order->invoice_number }}</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Order Details -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Informasi Order</h2>
                    <p class="mb-2"><span class="font-medium">Nomor Invoice:</span> {{ $order->invoice_number }}</p>
                    <p class="mb-2"><span class="font-medium">Nama Pelanggan:</span> {{ $order->user->name }}</p>
                    <p class="mb-2"><span class="font-medium">Email Pelanggan:</span> {{ $order->user->email }}</p>
                    <p class="mb-2"><span class="font-medium">Status:</span> <span class="px-2 py-1 rounded-full text-sm font-semibold {{ $order->status == 'pending' ? 'bg-yellow-200 text-yellow-800' : ($order->status == 'paid' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">{{ ucfirst($order->status) }}</span></p>
                    <p class="mb-2"><span class="font-medium">Total Pembayaran:</span> Rp{{ number_format($order->gross_amount, 0, ',', '.') }}</p>
                    <p class="mb-4"><span class="font-medium">Tanggal Order:</span> {{ $order->created_at->format('d M Y H:i') }}</p>

                    <h3 class="text-lg font-semibold mb-3">Item Order</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Produk</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Harga</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Jumlah</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $detail)
                                    <tr class="border-b border-gray-200 last:border-b-0">
                                        <td class="py-3 px-4 text-sm text-gray-800">{{ $detail->product->judul_buku }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-800">Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-800">{{ $detail->quantity }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-800">Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Column: Payment Button -->
                <div class="flex flex-col items-center justify-center bg-gray-50 p-6 rounded-lg shadow-inner">
                    <p class="text-lg font-medium mb-4 text-gray-700">Siap untuk melakukan pembayaran?</p>
                    @if($order->status == 'pending')
                        <button id="pay-button" onclick="payNow()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-xl transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Bayar Sekarang
                        </button>
                        <div id="loading" class="hidden mt-4">
                            <svg class="animate-spin h-5 w-5 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">Memuat halaman pembayaran...</p>
                        </div>
                    @else
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                            Pembayaran {{ $order->status == 'paid' ? 'Berhasil' : ucfirst($order->status) }}
                        </div>
                    @endif
                    <p class="text-sm text-gray-500 mt-4">Total yang harus dibayar: <span class="font-semibold text-gray-800">Rp{{ number_format($order->gross_amount, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function payNow() {
            const payButton = document.getElementById('pay-button');
            const loading = document.getElementById('loading');

            payButton.classList.add('hidden');
            loading.classList.remove('hidden');

            fetch('{{ route("snap.token", $order) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    snap.pay(data.token, {
                        onSuccess: function(result) {
                            window.location.reload();
                        },
                        onPending: function(result) {
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal! Silakan coba lagi.');
                            payButton.classList.remove('hidden');
                            loading.classList.add('hidden');
                        },
                        onClose: function() {
                            payButton.classList.remove('hidden');
                            loading.classList.add('hidden');
                        }
                    });
                } else {
                    alert('Gagal mendapatkan token pembayaran: ' + data.message);
                    payButton.classList.remove('hidden');
                    loading.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                payButton.classList.remove('hidden');
                loading.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
