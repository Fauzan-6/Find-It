@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="panel p-4">
            <h1 class="h4 mb-1">Buat akun Find-It</h1>
            <p class="text-secondary small mb-4">Nomor telepon hanya dibagikan ke lawan bicara setelah klaim disetujui.</p>
            <form method="POST" action="{{ route('register') }}" class="vstack gap-3">
                @csrf
                @foreach ([['name','Nama lengkap','text'],['email','Email','email'],['phone','Nomor WhatsApp / telepon','tel']] as [$f,$label,$t])
                    <div>
                        <label class="form-label fw-semibold" for="{{ $f }}">{{ $label }}</label>
                        <input id="{{ $f }}" type="{{ $t }}" name="{{ $f }}" value="{{ old($f) }}" class="form-control @error($f) is-invalid @enderror" required>
                        @error($f)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                @endforeach
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" for="password">Kata sandi</label>
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" for="password_confirmation">Ulangi kata sandi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <button class="btn btn-primary w-100">Buat akun</button>
            </form>
            <p class="text-center small mt-3 mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk</a></p>
        </div>
    </div>
</div>
@endsection
