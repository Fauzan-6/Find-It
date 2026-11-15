<a href="{{ route('items.show', $item) }}" class="item-card">
    <div class="item-thumb">
        @if ($item->image_url)
            <img src="{{ $item->image_url }}" alt="Foto {{ $item->title }}" loading="lazy">
        @else
            <i class="bi bi-image"></i>
        @endif
    </div>
    <div class="p-3 d-flex flex-column gap-2 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center">
            <span class="badge-type badge-{{ $item->type }}">{{ $item->type === 'hilang' ? 'Hilang' : 'Ditemukan' }}</span>
            <span class="item-meta">{{ $item->category }}</span>
        </div>
        <h3 class="h6 mb-0">{{ $item->title }}</h3>
        <div class="item-meta mt-auto">
            <div><i class="bi bi-geo-alt me-1"></i>{{ $item->campus_location }}</div>
            <div><i class="bi bi-calendar3 me-1"></i>{{ $item->date_occurred->translatedFormat('j M Y') }}</div>
        </div>
    </div>
</a>
