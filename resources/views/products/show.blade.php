<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->judul_buku }} - Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('products.index') }}" class="hover:text-blue-600">Produk</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">{{ $product->judul_buku }}</span>
        </nav>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                <!-- Left: Image/Illustration -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-12">
                    <svg class="w-40 h-40 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>

                <!-- Right: Details -->
                <div class="p-8 md:p-10 flex flex-col justify-center">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->judul_buku }}</h1>
                    <p class="text-gray-500 mb-6">{{ $product->penulis_buku }} &bull; {{ $product->penerbit_buku }}</p>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-600">Harga</span>
                            <span class="text-xl font-bold text-blue-600">Rp{{ number_format($product->harga_buku, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-600">Stok Tersedia</span>
                            <span class="font-medium text-gray-800">{{ $product->stok_buku }} buku</span>
                        </div>
                        <div class="pt-2">
                            <span class="text-gray-600 block mb-2">Deskripsi</span>
                            <p class="text-gray-700 leading-relaxed">{{ $product->deskripsi_buku }}</p>
                        </div>
                    </div>

                    @if ($product->stok_buku > 0)
                        <form action="{{ route('orders.store') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center gap-4 mb-4">
                                <label for="quantity" class="text-gray-700 font-medium">Jumlah:</label>
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                    <button type="button" onclick="if(document.getElementById('quantity').value>1)document.getElementById('quantity').value--" class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600">-</button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stok_buku }}" class="w-14 text-center border-x border-gray-300 py-2 focus:outline-none">
                                    <button type="button" onclick="if(document.getElementById('quantity').value < {{ $product->stok_buku }})document.getElementById('quantity').value++" class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600">+</button>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Beli Sekarang
                            </button>
                        </form>
                    @else
                        <div class="mt-auto bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center font-medium">
                            Stok Habis
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>

