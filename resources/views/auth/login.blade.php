<x-guest-layout>
    <div class="text-center mb-3">
        <div class="d-inline-flex align-items-center justify-content-center rounded-4 mb-2" style="width:52px;height:52px;background:rgba(15,23,42,.92);box-shadow:0 18px 50px rgba(2,6,23,.20)">
            <i class="bi bi-map-fill" style="color:rgba(250,204,21,1);font-size:22px"></i>
        </div>
        <div class="gr-brand fs-3">Geo-Road</div>
    </div>

    <div class="mb-3">
        
        <div class="text-muted">Masuk untuk mengelola data dan WebGIS.</div>
    </div>

    @if (session('status'))
        <div class="alert alert-success rounded-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-3">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input id="email" type="email" name="email" class="form-control rounded-4 @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <input id="password" type="password" name="password" class="form-control rounded-start-4 @error('password') is-invalid @enderror" required autocomplete="current-password">
                <button type="button" class="btn gr-btn-eye rounded-end-4" data-toggle-password="1" data-target="#password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
        
        </div>

        <button type="submit" class="btn gr-btn-gold rounded-4 w-100 py-2">
            <i class="bi bi-box-arrow-in-right me-2"></i> Login
        </button>
    </form>
</x-guest-layout>
