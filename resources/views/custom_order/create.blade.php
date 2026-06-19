@extends('layouts.app')

@section('title', 'Pesan Custom Batik - Custom Batik')

@section('content')
<style>
    .order-header {
        background: linear-gradient(135deg, #6B4423 0%, #4A2C1A 100%);
        color: #F5E6D3;
        padding: 40px 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        text-align: center;
    }

    .order-header h1 {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .order-container {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #D4AF37;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        color: #6B4423;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #D4AF37;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: #6B4423;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control, .form-select, textarea {
        width: 100%;
        border: 1px solid #D4AF37;
        border-radius: 6px;
        padding: 12px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-control:focus, .form-select:focus, textarea:focus {
        outline: none;
        border-color: #6B4423;
        box-shadow: 0 0 0 3px rgba(107, 68, 35, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #6B4423 0%, #4A2C1A 100%);
        color: #F5E6D3;
        border: none;
        padding: 14px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 68, 35, 0.3);
    }

    .login-prompt {
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid #D4AF37;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: center;
    }

    .login-prompt p {
        margin-bottom: 15px;
        color: #6B4423;
    }

    .login-btn {
        display: inline-block;
        background: linear-gradient(135deg, #6B4423 0%, #4A2C1A 100%);
        color: #F5E6D3;
        padding: 10px 30px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .login-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(107, 68, 35, 0.3);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .order-container {
            padding: 20px;
        }
    }
</style>

<div class="order-header">
    <h1>Pesan Custom Batik</h1>
    <p>Wujudkan desain batik impian Anda bersama kami</p>
</div>



<div class="order-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('custom_order.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Informasi Pemesan -->
        <div class="form-section">
            <h3>Informasi Pemesan</h3>
            
            @if($pelanggan)
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="{{ $pelanggan->nama }}" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $pelanggan->email }}" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" value="{{ $pelanggan->nomor_telepon }}" disabled>
                </div>
            @else
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="nama_pemesan" class="form-control @error('nama_pemesan') is-invalid @enderror" value="{{ old('nama_pemesan') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email_pemesan" class="form-control @error('email_pemesan') is-invalid @enderror" value="{{ old('email_pemesan') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon *</label>
                    <input type="text" name="telepon_pemesan" class="form-control @error('telepon_pemesan') is-invalid @enderror" value="{{ old('telepon_pemesan') }}" required>
                </div>
            @endif
        </div>

        <!-- Detail Pesanan -->
        <div class="form-section">
            <h3>Detail Pesanan</h3>

            <div class="form-group">
                <label class="form-label">Judul Desain *</label>
                <input type="text" name="judul_desain" class="form-control @error('judul_desain') is-invalid @enderror" value="{{ old('judul_desain') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Desain *</label>
                <textarea name="deskripsi_desain" class="form-control @error('deskripsi_desain') is-invalid @enderror" rows="4" required>{{ old('deskripsi_desain') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Warna Utama *</label>
                    <input type="text" name="warna_utama" class="form-control @error('warna_utama') is-invalid @enderror" value="{{ old('warna_utama') }}" placeholder="Contoh: Coklat, Biru" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Ukuran (Panjang x Lebar) *</label>
                    <input type="text" name="ukuran" class="form-control @error('ukuran') is-invalid @enderror" value="{{ old('ukuran') }}" placeholder="Contoh: 2m x 1m" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kuantitas *</label>
                <input type="number" name="kuantitas" class="form-control @error('kuantitas') is-invalid @enderror" value="{{ old('kuantitas', 1) }}" min="1" required>
            </div>

            <div class="form-group">
                <label class="form-label">Bahan *</label>
                <select name="bahan" class="form-select @error('bahan') is-invalid @enderror" required>
                    <option value="">-- Pilih Bahan --</option>
                    <option value="katun" {{ old('bahan') == 'katun' ? 'selected' : '' }}>Katun Premium</option>
                    <option value="sutra" {{ old('bahan') == 'sutra' ? 'selected' : '' }}>Sutra</option>
                    <option value="rayon" {{ old('bahan') == 'rayon' ? 'selected' : '' }}>Rayon</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deadline Pengerjaan (hari) *</label>
                <input type="number" name="deadline_hari" class="form-control @error('deadline_hari') is-invalid @enderror" value="{{ old('deadline_hari', 14) }}" min="3" required>
            </div>
        </div>

        <!-- File Design -->
        <div class="form-section">
            <h3>File Desain (Opsional)</h3>

            <div class="form-group">
                <label class="form-label">Upload Referensi Desain</label>
                <input type="file" name="file_desain" class="form-control @error('file_desain') is-invalid @enderror" accept="image/*,.pdf">
                <small style="color: #8B6239;">Format: JPG, PNG, PDF (Maksimal 5MB)</small>
            </div>
        </div>

        <button type="submit" class="btn-submit">Kirim Pesanan Custom</button>
    </form>
</div>

@endsection
