@extends('layouts.app')

@section('title', 'Koleksi Batik')

@section('content')

<h2 class="mb-4">Koleksi Batik</h2>

<div class="row">
    @foreach($batiks as $batik)
        <div class="col-md-4">
            
            <a href="{{ route('batik.show', $batik->id_batik) }}" style="text-decoration:none; color:black;">
                <div class="card p-3 mb-3">

                    {{-- GAMBAR --}}
                    @if($batik->gambar_batik)
                        @if(Str::startsWith($batik->gambar_batik, 'batik/'))
                            <img src="{{ asset('storage/' . $batik->gambar_batik) }}" class="img-fluid mb-2" style="height:200px; object-fit:cover;">
                        @else
                            <img src="{{ asset('images/' . $batik->gambar_batik) }}" class="img-fluid mb-2" style="height:200px; object-fit:cover;">
                        @endif
                    @else
                        <img src="https://via.placeholder.com/300" class="img-fluid mb-2" style="height:200px; object-fit:cover;">
                    @endif

                    {{-- NAMA --}}
                    <h5>{{ $batik->nama_batik }}</h5>

                    {{-- HARGA --}}
                    <p>Rp {{ number_format($batik->harga, 0, ',', '.') }}</p>

                    <button class="btn btn-outline-dark">
                        Lihat Detail
                    </button>

                </div>
            </a>

        </div>
    @endforeach
</div>

@endsection