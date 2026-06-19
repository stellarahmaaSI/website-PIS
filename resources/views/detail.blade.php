@extends('layouts.app')

@section('title', 'Detail Batik')

@section('content')

<div class="container">
    <div class="row">

        <!-- GAMBAR -->
        <div class="col-md-6">
            @if($batik->gambar_batik)
                @if(Str::startsWith($batik->gambar_batik, 'batik/'))
                    <img src="{{ asset('storage/' . $batik->gambar_batik) }}" class="img-fluid rounded">
                @else
                    <img src="{{ asset('images/' . $batik->gambar_batik) }}" class="img-fluid rounded">
                @endif
            @else
                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 350px;">
                    <span class="text-muted">Tidak ada gambar</span>
                </div>
            @endif
        </div>

        <!-- DETAIL -->
        <div class="col-md-6">
            <h3>{{ $batik->nama_batik }}</h3>
            <p>Rp {{ number_format($batik->harga, 0, ',', '.') }}</p>

            <p>{{ $batik->deskripsi ?? 'Belum ada deskripsi.' }}</p>

            <!-- ERROR BACKEND -->
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- FORM -->
            <form action="/cart/add/{{ $batik->id_batik }}" 
                  method="GET" 
                  onsubmit="return validateQty()">

                <p class="text-muted">Min: 3 | Maks: 6</p>

                <!-- QTY -->
                <div class="d-flex align-items-center mb-2">
                    <button type="button" onclick="kurang()" class="btn btn-secondary">-</button>

                    <input type="number" id="qty" name="qty" value="1" 
                           min="1"
                           class="form-control text-center mx-2" 
                           style="width:80px;">

                    <button type="button" onclick="tambah()" class="btn btn-secondary">+</button>
                </div>

                <!-- PESAN VALIDASI -->
                <p id="min-msg" class="text-danger" style="display:none;">
                    Minimal pembelian adalah 3
                </p>

                <p id="max-msg" class="text-danger" style="display:none;">
                    Maksimum pembelian adalah 6
                </p>

                <!-- BUTTON -->
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-danger">
                        Masukkan ke Keranjang
                    </button>

                    <button type="button" onclick="pesanSekarang()" class="btn btn-danger">
                        Pesan Sekarang
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

<script>
// tombol +
function tambah() {
    let qty = document.getElementById('qty');
    qty.value = parseInt(qty.value) + 1;
}

// tombol -
function kurang() {
    let qty = document.getElementById('qty');
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}

// ambil elemen
const qtyInput = document.getElementById('qty');
const minMsg = document.getElementById('min-msg');
const maxMsg = document.getElementById('max-msg');

// validasi realtime
qtyInput.addEventListener('input', function() {
    let qty = parseInt(this.value);

    minMsg.style.display = 'none';
    maxMsg.style.display = 'none';

    if (qty < 3) {
        minMsg.style.display = 'block';
    } else if (qty > 6) {
        maxMsg.style.display = 'block';
    }
});

// validasi saat submit (TANPA ALERT)
function validateQty() {
    let qty = parseInt(document.getElementById('qty').value);

    minMsg.style.display = 'none';
    maxMsg.style.display = 'none';

    if (qty < 3) {
        minMsg.style.display = 'block';
        return false;
    }

    if (qty > 6) {
        maxMsg.style.display = 'block';
        return false;
    }

    return true;
}

// tombol pesan sekarang
function pesanSekarang() {
    let qty = parseInt(document.getElementById('qty').value);

    minMsg.style.display = 'none';
    maxMsg.style.display = 'none';

    if (qty < 3) {
        minMsg.style.display = 'block';
        return;
    }

    if (qty > 6) {
        maxMsg.style.display = 'block';
        return;
    }

    window.location.href = `/checkout?product_id={{ $batik->id_batik }}&qty=` + qty;
}
</script>

@endsection