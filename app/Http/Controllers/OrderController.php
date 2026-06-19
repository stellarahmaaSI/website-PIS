<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Batik;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    // =========================
    // CHECKOUT
    // =========================
    public function checkout(Request $request)
    {
        $pelanggan = Pelanggan::find(session('pelanggan_id'));

        if ($request->product_id) {

            $product = Batik::where('id_batik', $request->product_id)->first();

            $items = collect([
                (object)[
                    'product' => $product,
                    'quantity' => $request->qty
                ]
            ]);

            return view('checkout', compact('items', 'pelanggan'));
        }

        $cart = Cart::where('user_id', session('pelanggan_id'))->first();

        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        return view('checkout', compact('items', 'pelanggan'));
    }

    // =========================
    // PROCESS CHECKOUT
    // =========================
    public function processCheckout(Request $request)
    {
        $cart = Cart::where('user_id', session('pelanggan_id'))->first();

        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        $total = 0;

        foreach ($items as $item) {
            $total += $item->product->harga * $item->quantity;
        }

        $order = Order::create([
            'user_id' => session('pelanggan_id'),
            'total_price' => $total,
            'status' => 'pending',
            'payment_method' => 'midtrans',
            'payment_status' => 'unpaid'
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->harga
            ]);
        }

        CartItem::where('cart_id', $cart->id)->delete();

        return redirect('/payment/' . $order->id);
    }

    // =========================
    // PAYMENT (MIDTRANS SNAP)
    // =========================
    public function payment($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // CONFIG MIDTRANS
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $pelanggan = Pelanggan::find(session('pelanggan_id'));
        $email = $pelanggan ? $pelanggan->email : 'customer@email.com';
        $nama = $pelanggan ? $pelanggan->nama : 'Customer';

        // PARAMETER TRANSAKSI
        $params = [
            'transaction_details' => [
                'order_id' => $order->id . '-' . time(),
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $nama,
                'email' => $email,
            ]
        ];

        // SNAP TOKEN
        $snapToken = Snap::getSnapToken($params);

        return view('payment', compact('order', 'snapToken'));
    }

    // =========================
    // LIST ORDER
    // =========================
    public function index()
    {
        $orders = Order::where('user_id', session('pelanggan_id'))->get();

        return view('orders', compact('orders'));
    }

    // =========================
    // KIRIM
    // =========================
    public function kirim($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'dikirim';
        $order->save();

        return back()->with('success', 'Pesanan dikirim');
    }

    // =========================
    // SELESAI
    // =========================
    public function selesai($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'selesai';
        $order->save();

        return back()->with('success', 'Pesanan selesai');
    }
}