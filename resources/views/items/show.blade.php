@extends('layouts.app')
@section('title', $item->title)
@section('content')
<a href="{{ url()->previous() === url()->current() ? route('home') : url()->previous() }}" class="text-decoration-none text-secondary small">
    <i class="bi bi-arrow-left me-1"></i>Kembali
</a>

@if ($item->moderation_status !== 'approved')
    <div class="alert alert-warning mt-3">
        Laporan ini belum tampil di katalog publik. Status moderasi: @include('partials.status-badge', ['status' => $item->moderation_status]).
    </div>
@elseif ($item->item_status === 'resolved')
    <div class="alert alert-secondary mt-3">Laporan ini sudah selesai dan tidak menerima klaim baru.</div>
@endif

<div class="row g-4 mt-1">
    <div class="col-lg-7">
        @if ($item->image_url)
            <img src="{{ $item->image_url }}" class="detail-img" alt="Foto {{ $item->title }}">
        @else
            <div class="detail-img d-grid place-items-center text-secondary" style="height:320px;display:grid;place-items:center"><i class="bi bi-image fs-1"></i></div>
        @endif
    </div>

    <div class="col-lg-5">
        <div class="panel p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge-type badge-{{ $item->type }}">{{ $item->type === 'hilang' ? 'Hilang' : 'Ditemukan' }}</span>
                <span class="item-meta">{{ $item->category }}</span>
            </div>
            <h1 class="h3">{{ $item->title }}</h1>
            <p class="mb-3" style="white-space: pre-line">{{ $item->description }}</p>
            <dl class="row mb-0 small">
                <dt class="col-5 text-secondary fw-semibold">Lokasi</dt><dd class="col-7">{{ $item->campus_location }}</dd>
                <dt class="col-5 text-secondary fw-semibold">Tanggal kejadian</dt><dd class="col-7">{{ $item->date_occurred->translatedFormat('j F Y') }}</dd>
                <dt class="col-5 text-secondary fw-semibold">Pelapor</dt><dd class="col-7">{{ $item->user->name }}</dd>
                <dt class="col-5 text-secondary fw-semibold">Dilaporkan</dt><dd class="col-7 mb-0">{{ $item->created_at->diffForHumans() }}</dd>
            </dl>
        </div>

        {{-- ===== Klaim / verifikasi kepemilikan ===== --}}
        @if ($item->moderation_status === 'approved' && $item->item_status === 'active')
            <div class="panel p-4 mt-3">
                <h2 class="h5">{{ $item->type === 'ditemukan' ? 'Ini barang saya' : 'Saya menemukan barang ini' }}</h2>

                @guest
                    <p class="text-secondary small">Masuk dulu untuk mengajukan klaim.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Masuk untuk mengajukan klaim</a>

                @elseif ($item->isOwnedBy(auth()->user()))
                    <p class="text-secondary small mb-0">Ini laporan Anda. Klaim yang masuk bisa diputuskan di <a href="{{ route('dashboard') }}">dashboard</a>.</p>

                @elseif (auth()->user()->isAdmin())
                    <p class="text-secondary small mb-0">Admin tidak dapat mengajukan klaim.</p>

                @elseif ($myClaim)
                    <p class="mb-2">Status klaim Anda: @include('partials.status-badge', ['status' => $myClaim->status])</p>
                    @if ($myClaim->status === 'approved')
                        <div class="alert alert-success mb-0">
                            Klaim disetujui. Hubungi {{ $item->user->name }} di
                            <strong>{{ $item->user->phone }}</strong> untuk mengatur serah terima.
                        </div>
                    @elseif ($myClaim->status === 'pending')
                        <p class="text-secondary small mb-0">Pelapor sedang memeriksa bukti Anda. Kontak akan terbuka setelah disetujui.</p>
                    @else
                        <p class="text-secondary small mb-0">Klaim ditolak oleh pelapor.</p>
                    @endif

                @else
                    <p class="text-secondary small">
                        @if ($item->type === 'ditemukan')
                            Jelaskan ciri yang hanya diketahui pemilik asli (isi dompet, goresan, nomor seri, wallpaper layar, dll.).
                        @else
                            Jelaskan di mana dan kapan Anda menemukannya, serta ciri yang bisa dicocokkan pemiliknya.
                        @endif
                        Kontak pelapor terbuka setelah klaim disetujui.
                    </p>
                    <form method="POST" action="{{ route('claims.store', $item) }}" class="vstack gap-3">
                        @csrf
                        <div>
                            <label for="proof_description" class="form-label fw-semibold">Bukti kepemilikan</label>
                            <textarea id="proof_description" name="proof_description" rows="5" class="form-control @error('proof_description') is-invalid @enderror" required>{{ old('proof_description') }}</textarea>
                            @error('proof_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-primary">Kirim klaim</button>
                    </form>
                @endguest
            </div>
        @endif
    </div>
</div>
@endsection
