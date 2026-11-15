@extends('layouts.app')
@section('title', 'Arsip admin')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-0">Arsip laporan</h1>
        <p class="text-secondary mb-0">Riwayat laporan yang sudah selesai atau ditolak.</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-outline-dark">Kembali ke moderasi</a>
</div>

<ul class="nav nav-pills mb-3">
    @foreach (['all' => 'Semua', 'resolved' => 'Selesai', 'rejected' => 'Ditolak'] as $key => $label)
        <li class="nav-item"><a class="nav-link {{ $filter === $key ? 'active bg-dark' : 'text-dark' }}" href="{{ route('admin.archive', ['filter' => $key]) }}">{{ $label }}</a></li>
    @endforeach
</ul>

<div class="panel table-responsive">
    <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Barang</th><th>Pelapor</th><th>Jenis</th><th>Moderasi</th><th>Status</th><th>Diperbarui</th></tr></thead>
        <tbody>
        @forelse ($items as $item)
            <tr>
                <td><a href="{{ route('items.show', $item) }}" class="fw-semibold text-decoration-none">{{ $item->title }}</a>
                    <div class="item-meta">{{ $item->campus_location }}</div></td>
                <td>{{ $item->user->name }}<div class="item-meta">{{ $item->user->email }}</div></td>
                <td><span class="badge-type badge-{{ $item->type }}">{{ $item->type === 'hilang' ? 'Hilang' : 'Ditemukan' }}</span></td>
                <td>@include('partials.status-badge', ['status' => $item->moderation_status])</td>
                <td>@include('partials.status-badge', ['status' => $item->item_status])</td>
                <td class="item-meta">{{ $item->updated_at->translatedFormat('j M Y, H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-secondary py-5">Arsip masih kosong.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
