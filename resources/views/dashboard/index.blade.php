@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php $pendingIncoming = $incomingClaims->where('status', 'pending')->count(); @endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-0">Halo, {{ auth()->user()->name }}</h1>
        <p class="text-secondary mb-0">Kelola laporan dan klaim Anda di sini.</p>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-tag"><i class="bi bi-plus-lg me-1"></i>Lapor barang</a>
</div>

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-items" type="button" role="tab">Laporan saya <span class="badge text-bg-light">{{ $myItems->count() }}</span></button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-incoming" type="button" role="tab">Klaim masuk @if ($pendingIncoming)<span class="badge text-bg-warning">{{ $pendingIncoming }}</span>@endif</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-outgoing" type="button" role="tab">Klaim keluar <span class="badge text-bg-light">{{ $outgoingClaims->count() }}</span></button></li>
</ul>

<div class="tab-content">
    {{-- ===== Laporan saya ===== --}}
    <div class="tab-pane fade show active" id="tab-items" role="tabpanel">
        <div class="panel table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Barang</th><th>Jenis</th><th>Moderasi</th><th>Status</th><th>Klaim baru</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse ($myItems as $item)
                    <tr>
                        <td><a href="{{ route('items.show', $item) }}" class="fw-semibold text-decoration-none">{{ $item->title }}</a>
                            <div class="item-meta">{{ $item->campus_location }} · {{ $item->date_occurred->translatedFormat('j M Y') }}</div></td>
                        <td><span class="badge-type badge-{{ $item->type }}">{{ $item->type === 'hilang' ? 'Hilang' : 'Ditemukan' }}</span></td>
                        <td>@include('partials.status-badge', ['status' => $item->moderation_status])</td>
                        <td>@include('partials.status-badge', ['status' => $item->item_status])</td>
                        <td>{{ $item->pending_claims_count ?: '–' }}</td>
                        <td class="text-end">
                            @if ($item->moderation_status === 'approved' && $item->item_status === 'active')
                                <form method="POST" action="{{ route('items.resolve', $item) }}" onsubmit="return confirm('Tandai laporan ini selesai? Barang akan hilang dari katalog.')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-check2-circle me-1"></i>Tandai selesai</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-5">Belum ada laporan. <a href="{{ route('items.create') }}">Buat laporan pertama</a>.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== Klaim masuk ===== --}}
    <div class="tab-pane fade" id="tab-incoming" role="tabpanel">
        <div class="vstack gap-3">
        @forelse ($incomingClaims as $claim)
            <div class="panel p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <div>
                        <div class="item-meta">Klaim untuk</div>
                        <a href="{{ route('items.show', $claim->item) }}" class="fw-bold text-decoration-none">{{ $claim->item->title }}</a>
                    </div>
                    <div>@include('partials.status-badge', ['status' => $claim->status])</div>
                </div>
                <hr>
                <div class="item-meta mb-1">Diajukan oleh <strong class="text-body">{{ $claim->user->name }}</strong> · {{ $claim->created_at->diffForHumans() }}</div>
                <p class="mb-3" style="white-space: pre-line">{{ $claim->proof_description }}</p>

                @if ($claim->status === 'pending')
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('claims.approve', $claim) }}">@csrf @method('PATCH')
                            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg me-1"></i>Setujui</button></form>
                        <form method="POST" action="{{ route('claims.reject', $claim) }}">@csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg me-1"></i>Tolak</button></form>
                    </div>
                @elseif ($claim->status === 'approved')
                    <div class="small">Hubungi pengaju klaim: <strong>{{ $claim->user->phone }}</strong> · {{ $claim->user->email }}</div>
                @endif
            </div>
        @empty
            <div class="panel p-5 text-center text-secondary">Belum ada klaim masuk untuk barang Anda.</div>
        @endforelse
        </div>
    </div>

    {{-- ===== Klaim keluar ===== --}}
    <div class="tab-pane fade" id="tab-outgoing" role="tabpanel">
        <div class="panel table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Barang</th><th>Pelapor</th><th>Diajukan</th><th>Status</th><th>Kontak pelapor</th></tr></thead>
                <tbody>
                @forelse ($outgoingClaims as $claim)
                    <tr>
                        <td><a href="{{ route('items.show', $claim->item) }}" class="fw-semibold text-decoration-none">{{ $claim->item->title }}</a></td>
                        <td>{{ $claim->item->user->name }}</td>
                        <td class="item-meta">{{ $claim->created_at->diffForHumans() }}</td>
                        <td>@include('partials.status-badge', ['status' => $claim->status])</td>
                        <td>{!! $claim->status === 'approved' ? '<strong>'.e($claim->item->user->phone).'</strong>' : '<span class="text-secondary">Terbuka setelah disetujui</span>' !!}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-5">Anda belum mengajukan klaim. <a href="{{ route('home') }}">Jelajahi katalog</a>.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
