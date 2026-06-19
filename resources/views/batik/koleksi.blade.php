@extends('layouts.app')

@section('title', 'Koleksi Batik - Custom Batik')

@section('content')
<style>
    .gallery-header {
        background: linear-gradient(135deg, #6B4423 0%, #4A2C1A 100%);
        color: #F5E6D3;
        padding: 40px 20px;
        border-radius: 10px;
        margin-bottom: 40px;
        text-align: center;
    }

    .gallery-header h1 {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .batik-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid #F5E6D3;
    }

    .batik-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(107, 68, 35, 0.25);
        border-color: #D4AF37;
    }

    .batik-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: #f0f0f0;
    }

    .batik-info {
        padding: 20px;
    }

    .batik-name {
        font-size: 18px;
        font-weight: bold;
        color: #6B4423;
        margin-bottom: 8px;
    }

    .batik-desc {
        font-size: 13px;
        color: #8B6239;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .batik-footer {
        display: flex;
        gap: 10px;
    }

    .btn-view {
        flex: 1;
        background: linear-gradient(135deg, #6B4423 0%, #4A2C1A 100%);
        color: #F5E6D3;
        border: none;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
    }

    .btn-view:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(107, 68, 35, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #8B6239;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>

<div class="gallery-header">
    <h1>Koleksi Batik Kami</h1>
    <p>Temukan desain batik tradisional yang indah dan eksklusif</p>
</div>

<div class="gallery-grid">
    @forelse($batik as $item)
        <div class="batik-card">
            @if($item->gambar_batik)
                @if(Str::startsWith($item->gambar_batik, 'batik/'))
                    <img src="{{ asset('storage/' . $item->gambar_batik) }}" alt="{{ $item->nama_batik }}" class="batik-image">
                @else
                    <img src="{{ asset('images/' . $item->gambar_batik) }}" alt="{{ $item->nama_batik }}" class="batik-image">
                @endif
            @else
                <div class="batik-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #F5E6D3 0%, #E8D4C0 100%);">
                    <span style="color: #8B6239; font-size: 24px;">No Image</span>
                </div>
            @endif
            <div class="batik-info">
                <div class="batik-name">{{ $item->nama_batik }}</div>
                <div class="batik-desc">{{ Str::limit($item->deskripsi, 80) }}</div>
                <div class="batik-footer">
                    <a href="{{ route('batik.show', $item->id_batik) }}" class="btn-view">Lihat Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state" style="grid-column: 1/-1;">
            <div class="empty-state-icon">🎨</div>
            <h3>Koleksi Batik Kosong</h3>
            <p>Silakan kembali lagi nanti untuk melihat koleksi batik kami</p>
        </div>
    @endforelse
</div>

@endsection
