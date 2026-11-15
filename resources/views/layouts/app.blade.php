<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Find-It') · Barang hilang & ditemukan kampus</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --fi-ink: #172B4D;
            --fi-paper: #F3F5F9;
            --fi-tag: #FFC400;
            --fi-lost: #D9482B;
            --fi-found: #12806A;
            --fi-line: #DDE2EC;

            --bs-body-font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            --bs-body-bg: var(--fi-paper);
            --bs-body-color: var(--fi-ink);
            --bs-primary: var(--fi-ink);
            --bs-primary-rgb: 23, 43, 77;
            --bs-link-color: var(--fi-ink);
            --bs-link-hover-color: #0b1731;
            --bs-border-color: var(--fi-line);
        }
        body { letter-spacing: -.005em; }
        h1, h2, h3, h4, h5 { letter-spacing: -.02em; font-weight: 700; }
        .btn { font-weight: 600; border-radius: .6rem; }
        .btn-primary { --bs-btn-bg: var(--fi-ink); --bs-btn-border-color: var(--fi-ink);
            --bs-btn-hover-bg: #0f1f3a; --bs-btn-hover-border-color: #0f1f3a;
            --bs-btn-active-bg: #0b1731; --bs-btn-active-border-color: #0b1731; }
        .btn-tag { background: var(--fi-tag); border-color: var(--fi-tag); color: var(--fi-ink); }
        .btn-tag:hover { background: #e6b000; border-color: #e6b000; color: var(--fi-ink); }
        .form-control, .form-select { border-radius: .6rem; border-color: var(--fi-line); }
        .form-control:focus, .form-select:focus { border-color: var(--fi-ink); box-shadow: 0 0 0 .2rem rgba(23,43,77,.15); }

        .navbar-fi { background: #fff; border-bottom: 1px solid var(--fi-line); }
        .brand-mark { background: var(--fi-tag); width: 30px; height: 30px; border-radius: 8px;
            display: inline-grid; place-items: center; margin-right: .5rem; font-size: 1rem; }
        .nav-link { font-weight: 600; color: #4a5a78; }
        .nav-link.active, .nav-link:hover { color: var(--fi-ink); }

        .panel { background: #fff; border: 1px solid var(--fi-line); border-radius: 1rem; }

        .badge-type { font-weight: 700; padding: .4em .7em; border-radius: .5rem; font-size: .72rem; }
        .badge-hilang { background: #FCE6E1; color: var(--fi-lost); }
        .badge-ditemukan { background: #DAF1EB; color: var(--fi-found); }

        .item-card { background: #fff; border: 1px solid var(--fi-line); border-radius: 1rem; overflow: hidden; height: 100%;
            display: flex; flex-direction: column; color: inherit; text-decoration: none; }
        .item-card:hover { color: inherit; border-color: var(--fi-ink); }
        .item-thumb { aspect-ratio: 4 / 3; background: #E8ECF4; display: grid; place-items: center; color: #9aa7bf; font-size: 2.5rem; overflow: hidden; }
        .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .item-meta { color: #5d6c88; font-size: .85rem; }

        .hero { background: var(--fi-ink); color: #fff; border-radius: 1.25rem; padding: 2.5rem 1.5rem; }
        @media (min-width: 768px) { .hero { padding: 3.5rem 3rem; } }
        .hero h1 { font-weight: 800; font-size: clamp(1.9rem, 4.5vw, 3rem); line-height: 1.1; max-width: 18ch; }
        .hero p { color: #c4cee3; max-width: 52ch; }

        .table > :not(caption) > * > * { padding: .85rem 1rem; }
        .detail-img { width: 100%; max-height: 460px; object-fit: cover; border-radius: 1rem; background: #E8ECF4; }
        footer { color: #6b7a96; font-size: .875rem; }
        :focus-visible { outline: 3px solid var(--fi-tag); outline-offset: 2px; }
    </style>
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-fi sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-search"></i></span>Find-It
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('items.lost') ? 'active' : '' }}" href="{{ route('items.lost') }}">Barang hilang</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('items.found') ? 'active' : '' }}" href="{{ route('items.found') }}">Barang ditemukan</a></li>
            </ul>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.index') }}"><i class="bi bi-shield-check me-1"></i>Panel admin</a>
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.archive') }}">Arsip</a>
                    @else
                        <a class="btn btn-tag btn-sm" href="{{ route('items.create') }}"><i class="bi bi-plus-lg me-1"></i>Lapor barang</a>
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="btn btn-link btn-sm text-decoration-none text-secondary">Keluar</button>
                    </form>
                @else
                    <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="container py-4 py-md-5">
    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $cls)
        @if (session($key))
            <div class="alert alert-{{ $cls }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
    @endforeach

    @yield('content')
</main>

<footer class="container pb-4 text-center">Find-It · Platform barang hilang dan ditemukan lingkungan kampus</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
