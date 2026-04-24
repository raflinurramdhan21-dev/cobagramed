# TODO: Implementasi View Produk, Detail Produk, dan Pembayaran Midtrans

## Plan Summary
Membuat flow: Daftar Produk -> Detail Produk -> Buat Order -> Detail Order + Bayar (Midtrans Snap)

## Steps

- [x] 1. Create `ProductController` with `index()` and `show()` methods
- [x] 2. Create `resources/views/products/index.blade.php`
- [x] 3. Create `resources/views/products/show.blade.php`
- [x] 4. Update `OrderController` — add `store()` method
- [x] 5. Update `SnapTokenController` — add AJAX endpoint for token
- [x] 6. Update `resources/views/orders/show.blade.php` — integrate Midtrans Snap.js
- [x] 7. Update `routes/web.php` — add all new routes
- [x] 8. Run migrations/seeds if needed and test

