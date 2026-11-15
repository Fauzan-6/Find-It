@extends('layouts.app')
@section('title', $title)
@section('content')

@if (! $type)
    <section class="hero mb-4">
        <h1>Kehilangan sesuatu di Fakultas Teknik? Cari di sini dulu.</h1>
        <p class="mt-3 mb-4">Lihat barang yang sudah ditemukan mahasiswa lain, atau laporkan barangmu supaya cepat kembali.</p>
        <form method="GET" action="{{ $action }}" class="row g-2" style="max-width: 640px">
            <div class="col-sm">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-lg" placeholder="Cari dompet, kunci, laptop…" aria-label="Cari barang">
            </div>
            <div class="col-sm-auto"><button class="btn btn-tag btn-lg w-100">Cari barang</button></div>
        </form>
    </section>
@else
    <div class="mb-4">
        <h1 class="h3 mb-1">{{ $title }}</h1>
        <p class="text-secondary mb-0">
            {{ $type === 'hilang' ? 'Kenal salah satu barang ini? Hubungi pelapor lewat laporan barang temuanmu.' : 'Merasa ini barangmu? Buka detailnya dan ajukan klaim dengan bukti kepemilikan.' }}
        </p>
    </div>
@endif

{{-- Filter --}}
<form method="GET" action="{{ $action }}" class="panel p-3 mb-4">
    <div class="row g-2 align-items-end">
        @if ($type)
            <div class="col-md-3">
                <label class="form-label small fw-semibold" for="q">Kata kunci</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama barang">
            </div>
        @else
            <input type="hidden" name="q" value="{{ request('q') }}">
            <div class="col-md-3">
                <label class="form-label small fw-semibold" for="type">Status</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Hilang & ditemukan</option>
                    <option value="hilang" @selected(request('type') === 'hilang')>Hilang</option>
                    <option value="ditemukan" @selected(request('type') === 'ditemukan')>Ditemukan</option>
                </select>
            </div>
        @endif
        <div class="col-md-3">
            <label class="form-label small fw-semibold" for="category">Kategori</label>
            <select id="category" name="category" class="form-select">
                <option value="">Semua kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c }}" @selected(request('category') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold" for="location">Lokasi kampus</label>
            <select id="location" name="location" class="form-select">
                <option value="">Semua lokasi</option>
                @foreach ($locations as $l)
                    <option value="{{ $l }}" @selected(request('location') === $l)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-grow-1">Terapkan filter</button>
            <a href="{{ $action }}" class="btn btn-outline-secondary" title="Atur ulang filter" aria-label="Atur ulang filter"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

@if ($items->isEmpty())
    <div class="panel p-5 text-center">
        <i class="bi bi-inbox fs-1 text-secondary"></i>
        <h2 class="h5 mt-2">Belum ada barang yang cocok</h2>
        <p class="text-secondary">Coba ubah kata kunci atau filter, atau laporkan barangmu sendiri.</p>
        <div><a href="{{ route('items.create') }}" class="btn btn-tag">Lapor barang</a></div>
    </div>
@else
    <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4">
        @foreach ($items as $item)
            <div class="col">@include('partials.item-card', ['item' => $item])</div>
        @endforeach
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
@endif
@endsection
