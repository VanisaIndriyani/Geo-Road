<div class="fw-bold mb-3">Informasi Profil</div>

@if (session('status') === 'profile-updated')
    <div class="alert alert-success rounded-4 py-2 mb-3">Profil berhasil diperbarui.</div>
@endif

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="row g-3">
    @csrf
    @method('patch')

    <div class="col-12">
        <label class="form-label fw-semibold">Foto Profil</label>
        <div class="d-flex align-items-center gap-3">
            @if ($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="gr-avatar" style="width:56px;height:56px">
            @else
                <div class="rounded-circle bg-light border" style="width:56px;height:56px"></div>
            @endif
            <div class="flex-grow-1">
                <input type="file" name="avatar" accept="image/*" class="form-control rounded-4 @error('avatar') is-invalid @enderror">
                @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="small text-muted mt-1">Format JPG/PNG. Maks 2MB.</div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control rounded-4 @error('name') is-invalid @enderror" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control rounded-4 @error('email') is-invalid @enderror" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 d-grid d-md-flex justify-content-md-end">
        <button type="submit" class="btn gr-btn-gold rounded-4 px-4">
            <i class="bi bi-save me-1"></i> Simpan
        </button>
    </div>
</form>
