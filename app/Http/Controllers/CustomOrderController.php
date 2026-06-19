<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder;
use App\Models\Pelanggan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Batik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomOrderController extends Controller
{
    public function index()
    {
        $orders = CustomOrder::with('pelanggan')->get();
        return view('custom_order.index', compact('orders'));
    }

    public function create()
    {
        $pelangganId = session('pelanggan_id');
        $pelanggan = null;

        if ($pelangganId) {
            $pelanggan = Pelanggan::find($pelangganId);
        }

        return view('custom_order.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $pelangganId = session('pelanggan_id');

        $rules = [
            'judul_desain' => 'required|string|max:100',
            'deskripsi_desain' => 'required|string',
            'warna_utama' => 'required|string|max:50',
            'ukuran' => 'required|string|max:50',
            'kuantitas' => 'required|integer|min:1',
            'bahan' => 'required|string|in:katun,sutra,rayon',
            'deadline_hari' => 'required|integer|min:3',
            'file_desain' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ];

        if (!$pelangganId) {
            $rules['nama_pemesan'] = 'required|string|max:100';
            $rules['email_pemesan'] = 'required|email';
            $rules['telepon_pemesan'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        // Jika guest, daftarkan pelanggan secara otomatis
        if (!$pelangganId) {
            $pelanggan = Pelanggan::where('email', $validated['email_pemesan'])->first();
            if (!$pelanggan) {
                $pelanggan = Pelanggan::create([
                    'nama' => $validated['nama_pemesan'],
                    'email' => $validated['email_pemesan'],
                    'nomor_telepon' => $validated['telepon_pemesan'],
                    'alamat' => '-',
                    'password' => Hash::make('123456'), // default password
                ]);
            }
            session(['pelanggan_id' => $pelanggan->id_pelanggan, 'nama' => $pelanggan->nama]);
            $pelangganId = $pelanggan->id_pelanggan;
        }

        // Simpan file referensi desain jika ada
        $fileDesainPath = null;
        if ($request->hasFile('file_desain')) {
            $file = $request->file('file_desain');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/custom_designs'), $fileName);
            $fileDesainPath = 'uploads/custom_designs/' . $fileName;
        }

        // Buat detail tambahan untuk teks_tambahan
        $teksTambahan = "Deskripsi Desain: " . $validated['deskripsi_desain'] . "\n" .
                        "Warna Utama: " . $validated['warna_utama'] . "\n" .
                        "Ukuran: " . $validated['ukuran'] . "\n" .
                        "Kuantitas: " . $validated['kuantitas'] . " pcs\n" .
                        "Deadline: " . $validated['deadline_hari'] . " hari";

        if ($fileDesainPath) {
            $teksTambahan .= "\nFile Referensi: " . asset($fileDesainPath);
        }

        // Buat data CustomOrder
        $customOrder = CustomOrder::create([
            'id_pelanggan' => $pelangganId,
            'jenis_batik' => $validated['judul_desain'],
            'jenis_kain' => $validated['bahan'],
            'teknik' => 'Tulis & Cap Custom',
            'teks_tambahan' => $teksTambahan,
            'status' => 'pending'
        ]);

        // Hitung harga berdasarkan bahan dan kuantitas
        $hargaBahan = [
            'katun' => 150000,
            'sutra' => 300000,
            'rayon' => 120000,
        ];
        $hargaPerPcs = $hargaBahan[$validated['bahan']] ?? 150000;
        $totalHarga = $validated['kuantitas'] * $hargaPerPcs;

        // Dapatkan atau buat produk Batik Custom di tabel batik
        $batikCustom = Batik::where('nama_batik', 'Batik Custom')->first();
        if (!$batikCustom) {
            $batikCustom = Batik::create([
                'nama_batik' => 'Batik Custom',
                'deskripsi' => 'Pemesanan Batik Custom sesuai keinginan pelanggan',
                'harga' => 150000,
            ]);
        }

        // Buat Order utama
        $order = Order::create([
            'user_id' => $pelangganId,
            'total_price' => $totalHarga,
            'status' => 'pending',
            'payment_method' => 'midtrans',
            'payment_status' => 'unpaid'
        ]);

        // Buat Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $batikCustom->id_batik,
            'quantity' => $validated['kuantitas'],
            'price' => $hargaPerPcs
        ]);
        return redirect('/payment/' . $order->id)->with('success', 'Custom order berhasil ditambahkan! Silakan selesaikan pembayaran.');
    }

    public function show(CustomOrder $customOrder)
    {
        return view('custom_order.show', compact('customOrder'));
    }

    public function edit(CustomOrder $customOrder)
    {
        $pelanggan = Pelanggan::all();
        return view('custom_order.edit', compact('customOrder', 'pelanggan'));
    }

    public function update(Request $request, CustomOrder $customOrder)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'jenis_batik' => 'nullable|string|max:100',
            'jenis_kain' => 'nullable|string|max:100',
            'teknik' => 'nullable|string|max:100',
            'teks_tambahan' => 'nullable|string',
            'status' => 'nullable|in:pending,proses,selesai,dibayar'
        ]);

        $customOrder->update($validated);
        return redirect()->route('custom_order.index')->with('success', 'Custom order berhasil diperbarui');
    }

    public function destroy(CustomOrder $customOrder)
    {
        $customOrder->delete();
        return redirect()->route('custom_order.index')->with('success', 'Custom order berhasil dihapus');
    }
}
