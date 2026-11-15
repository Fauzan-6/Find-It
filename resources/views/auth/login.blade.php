@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="panel p-4">
            <h1 class="h4 mb-1">Masuk ke Find-It</h1>
            <p class="text-secondary small mb-4">Masuk untuk melapor barang dan mengajukan klaim.</p>
            <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
                @csrf
                <div>
                    <label class="form-label fw-semibold" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label fw-semibold" for="password">Kata sandi</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-primary w-100">Masuk</button>
            </form>
            <p class="text-center small mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar</a></p>
        </div>
    </div>
</div>
@endsection
