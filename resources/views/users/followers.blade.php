@extends('app')

@section('title', 'Pengikut - RACIKARA')

@section('content')
<div class="container py-5">

    <a href="{{ route('users.show', $user->id) }}"
       class="text-decoration-none text-muted">
        &larr; Kembali ke Profil
    </a>

    <h3 class="fw-bold mt-3 mb-4">
        Pengikut {{ $user->name }}
    </h3>

    <div class="card border-0 shadow-sm rounded-4 p-3">

        @forelse($users as $follower)
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">

                <a href="{{ route('users.show', $follower->id) }}"
                   class="d-flex align-items-center gap-3 text-decoration-none text-dark">

                    <img
                        src="{{ $follower->avatar ? asset('storage/' . $follower->avatar) : asset('images/default-avatar.png') }}"
                        alt="Foto profil"
                        width="50"
                        height="50"
                        class="rounded-circle"
                        style="object-fit: cover;"
                    >

                    <div>
                        <h6 class="fw-bold mb-1">{{ $follower->name }}</h6>
                        <small class="text-muted">{{ $follower->bio ?? 'Pengguna RACIKARA' }}</small>
                    </div>
                </a>

            </div>
        @empty
            <p class="text-muted text-center py-4 mb-0">
                Belum ada pengikut.
            </p>
        @endforelse

        <div class="mt-3">
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection
