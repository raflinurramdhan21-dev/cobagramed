<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;

class NotificationController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function callback(Request $request)
    {
        $transaction_status = $request->input('transaction_status');
        $payment_type = $request->input('payment_type');
        $order_id = $request->input('order_id');
        $fraud_status = $request->input('fraud_status');

        $order = Order::findOrFail($order_id);
        $payment = $order->payments()->first();

        if ($transaction_status == 'settlement') {
            // Pembayaran Berhasil
            $order->update(['status' => 'paid']);
            if ($payment) {
                $payment->update(['status' => 'settlement']);
            }
        } elseif ($transaction_status == 'pending') {
            // Menunggu Pembayaran
            $order->update(['status' => 'pending']);
            if ($payment) {
                $payment->update(['status' => 'pending']);
            }
        } elseif ($transaction_status == 'expire') {
            // Kadaluarsa
            $order->update(['status' => 'expired']);
            if ($payment) {
                $payment->update(['status' => 'expired']);
            }
        } elseif ($transaction_status == 'cancel') {
            // Dibatalkan
            $order->update(['status' => 'cancelled']);
            if ($payment) {
                $payment->update(['status' => 'cancelled']);
            }
        }

        return response()->json(['message' => 'Notification Handled']);
    }
}
