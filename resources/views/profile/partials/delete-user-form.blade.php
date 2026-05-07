<div class="fw-bold mb-2 text-danger">Hapus Akun</div>
<div class="text-muted mb-3">Aksi ini permanen. Masukkan password untuk konfirmasi.</div>

<button type="button" class="btn btn-outline-danger rounded-4" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
    <i class="bi bi-trash3 me-1"></i> Hapus Akun
</button>

<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <div class="fw-bold">Konfirmasi Hapus Akun</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="mb-2">Masukkan password untuk melanjutkan.</div>
                    <input type="password" name="password" class="form-control rounded-4 @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="Password">
                    @if($errors->userDeletion->has('password'))<div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>@endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark rounded-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-4">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
