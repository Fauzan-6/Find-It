@extends('layouts.app')
@section('title', 'Moderasi laporan')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-0">Moderasi laporan</h1>
        <p class="text-secondary mb-0">Setujui laporan yang valid agar tampil di katalog.</p>
    </div>
    <a href="{{ route('admin.archive') }}" class="btn btn-outline-dark"><i class="bi bi-archive me-1"></i>Buka arsip</a>
</div>

<ul class="nav nav-pills mb-3">
    @foreach (['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ $status === $key ? 'active bg-dark' : 'text-dark' }}" href="{{ route('admin.index', ['status' => $key]) }}">
                {{ $label }} <span class="badge text-bg-light">{{ $counts[$key] ?? 0 }}</span>
            </a>
        </li>
    @endforeach
</ul>

<div class="panel table-responsive">
    <table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Barang</th><th>Pelapor</th><th>Jenis</th><th>Lokasi</th><th>Masuk</th><th class="text-end">Aksi</th></tr></thead>
        <tbody>
        @forelse ($items as $item)
            <tr>
                <td style="min-width:240px">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="item-thumb rounded" style="width:56px;height:56px;font-size:1.2rem;flex:none">
                            @if ($item->image_url)<img src="{{ $item->image_url }}" alt="">@else<i class="bi bi-image"></i>@endif
                        </div>
                        <div>
                            <a href="{{ route('items.show', $item) }}" class="fw-semibold text-decoration-none" target="_blank">{{ $item->title }}</a>
                            <div class="item-meta">{{ $item->category }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $item->user->name }}<div class="item-meta">{{ $item->user->email }}</div></td>
                <td><span class="badge-type badge-{{ $item->type }}">{{ $item->type === 'hilang' ? 'Hilang' : 'Ditemukan' }}</span></td>
                <td>{{ $item->campus_location }}</td>
                <td class="item-meta">{{ $item->created_at->diffForHumans() }}</td>
                <td class="text-end text-nowrap">
                    @if ($item->moderation_status !== 'approved')
                        <form method="POST" action="{{ route('admin.approve', $item) }}" class="d-inline">@csrf @method('PATCH')
                            <button class="btn btn-sm btn-success">Setujui</button></form>
                    @endif
                    @if ($item->moderation_status !== 'rejected')
                        <form method="POST" action="{{ route('admin.reject', $item) }}" class="d-inline" onsubmit="return confirm('Tolak laporan ini?')">@csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger">Tolak</button></form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-secondary py-5">Tidak ada laporan pada tab ini.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
