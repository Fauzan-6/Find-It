@php
    $map = [
        'pending'  => ['warning-subtle', 'warning-emphasis', 'Menunggu'],
        'approved' => ['success-subtle', 'success-emphasis', 'Disetujui'],
        'rejected' => ['danger-subtle', 'danger-emphasis', 'Ditolak'],
        'active'   => ['info-subtle', 'info-emphasis', 'Aktif'],
        'resolved' => ['secondary-subtle', 'secondary-emphasis', 'Selesai'],
    ];
    [$bg, $text, $label] = $map[$status] ?? ['secondary-subtle', 'secondary-emphasis', $status];
@endphp
<span class="badge bg-{{ $bg }} text-{{ $text }}">{{ $label }}</span>
