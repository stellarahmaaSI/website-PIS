@extends('layouts.app')

@section('title', 'Daftar Batik')

@section('content')
<div class="page-header">
    <h1>Galeri Batik</h1>
    <p class="mb-0">Koleksi desain batik custom kami</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Batik</span>
        <a href="{{ route('batik.create') }}" class="btn btn-sm btn-primary">+ Tambah Batik</a>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($batik as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100" style="border: none; border-left: 5px solid #D4AF37;">
                        @if($item->gambar_batik)
                            @if(Str::startsWith($item->gambar_batik, 'batik/'))
                                <img src="{{ asset('storage/' . $item->gambar_batik) }}" class="card-img-top" alt="{{ $item->nama_batik }}" style="height: 250px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/' . $item->gambar_batik) }}" class="card-img-top" alt="{{ $item->nama_batik }}" style="height: 250px; object-fit: cover;">
                            @endif
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                <span class="text-muted">Tidak ada gambar</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->nama_batik }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($item->deskripsi, 100) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <a href="{{ route('batik.edit', $item->id_batik) }}" class="btn btn-sm btn-warning w-50">Edit</a>
                                <form action="{{ route('batik.destroy', $item->id_batik) }}" method="POST" class="w-50">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Yakin?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">Tidak ada data batik</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
