@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="text-dark">
            <div class="h4 fw-bold mb-0">Profil</div>
            <div class="text-muted">Ubah data akun dan foto profil.</div>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-lg-6">
            <div class="gr-card p-4">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="col-lg-6">
            <div class="gr-card p-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="col-12">
            <div class="gr-card p-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($errors->userDeletion->isNotEmpty())
        <script>
            (() => {
                const modalEl = document.getElementById('confirmUserDeletionModal');
                if (!modalEl || !window.bootstrap) return;
                const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            })();
        </script>
    @endif
@endpush
