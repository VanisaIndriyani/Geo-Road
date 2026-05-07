<div class="fw-bold mb-3">Ubah Password</div>

@if (session('status') === 'password-updated')
    <div class="alert alert-success rounded-4 py-2 mb-3">Password berhasil diperbarui.</div>
@endif

<form method="post" action="{{ route('password.update') }}" class="row g-3">
    @csrf
    @method('put')

    <div class="col-12">
        <label class="form-label fw-semibold">Password Saat Ini</label>
        <input type="password" name="current_password" autocomplete="current-password" class="form-control rounded-4 @if($errors->updatePassword->has('current_password')) is-invalid @endif">
        @if($errors->updatePassword->has('current_password'))<div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>@endif
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Password Baru</label>
        <input type="password" name="password" autocomplete="new-password" class="form-control rounded-4 @if($errors->updatePassword->has('password')) is-invalid @endif">
        @if($errors->updatePassword->has('password'))<div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>@endif
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" autocomplete="new-password" class="form-control rounded-4 @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif">
        @if($errors->updatePassword->has('password_confirmation'))<div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>@endif
    </div>

    <div class="col-12 d-grid d-md-flex justify-content-md-end">
        <button type="submit" class="btn gr-btn-gold rounded-4 px-4">
            <i class="bi bi-save me-1"></i> Simpan
        </button>
    </div>
</form>
