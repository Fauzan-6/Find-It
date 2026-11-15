@extends('layouts.app')
@section('title', 'Lapor barang')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h1 class="h3 mb-1">Lapor barang</h1>
        <p class="text-secondary mb-4">Laporanmu ditinjau admin dulu sebelum tampil di katalog, biasanya tidak lama.</p>

        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="panel p-4 vstack gap-3">
            @csrf

            <div>
                <span class="form-label fw-semibold d-block">Jenis laporan</span>
                <div class="btn-group w-100" role="group" aria-label="Jenis laporan">
                    <input type="radio" class="btn-check" name="type" id="t-hilang" value="hilang" @checked(old('type', 'hilang') === 'hilang')>
                    <label class="btn btn-outline-dark" for="t-hilang">Saya kehilangan barang</label>
                    <input type="radio" class="btn-check" name="type" id="t-found" value="ditemukan" @checked(old('type') === 'ditemukan')>
                    <label class="btn btn-outline-dark" for="t-found">Saya menemukan barang</label>
                </div>
                @error('type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="form-label fw-semibold" for="title">Nama barang</label>
                <input id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Dompet kulit hitam" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="category">Kategori</label>
                    <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c }}" @selected(old('category') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="campus_location">Lokasi di kampus</label>
                    <select id="campus_location" name="campus_location" class="form-select @error('campus_location') is-invalid @enderror" required>
                        <option value="">Pilih lokasi</option>
                        @foreach ($locations as $l)
                            <option value="{{ $l }}" @selected(old('campus_location') === $l)>{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('campus_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div>
                <label class="form-label fw-semibold" for="date_occurred">Tanggal kejadian</label>
                <input id="date_occurred" type="date" name="date_occurred" max="{{ now()->toDateString() }}" value="{{ old('date_occurred', now()->toDateString()) }}" class="form-control @error('date_occurred') is-invalid @enderror" required>
                @error('date_occurred')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="form-label fw-semibold" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required
                    placeholder="Ciri umum barang (warna, merek, kondisi). Jangan tulis detail rahasia yang dipakai untuk verifikasi pemilik.">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="form-label fw-semibold" for="image">Foto barang <span class="text-secondary fw-normal">(opsional, maks. 2 MB)</span></label>
                <input id="image" type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <img id="preview" class="mt-2 rounded d-none" style="max-height:160px" alt="Pratinjau foto">
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                <button class="btn btn-primary">Kirim laporan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', e => {
        const f = e.target.files[0], img = document.getElementById('preview');
        if (!f) { img.classList.add('d-none'); return; }
        img.src = URL.createObjectURL(f); img.classList.remove('d-none');
    });
</script>
@endpush
