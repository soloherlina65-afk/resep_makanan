@extends('app')

@section('title', 'Pengikut - RACIKARA')

@section('content')
<style>
    .profile-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        padding: 9px 14px;
        border: 1px solid #d5e0c5;
        border-radius: 10px;
        background: #fff;
        color: #5f7446;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s, border-color .2s, transform .2s;
    }
    .profile-back:hover { transform: translateX(-2px); border-color: #b7c8a0; background: #f4f8ef; }
    .profile-back:focus-visible { outline: 3px solid rgba(122, 143, 92, .3); outline-offset: 2px; }
</style>
<div class="container py-5">

    <a href="{{ route('users.show', $user->id) }}" class="profile-back" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
        <span aria-hidden="true">&larr;</span>Kembali
    </a>

    <h3 class="fw-bold mt-3 mb-4">
        Pengikut {{ $user->name }}
    </h3>

    <div class="card border-0 shadow-sm rounded-4 p-3">

        @forelse($users as $follower)
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">

                <a href="{{ route('users.show', $follower->id) }}"
                   class="d-flex align-items-center gap-3 text-decoration-none text-dark">

                    <x-user-avatar
                        :user="$follower"
                        class="rounded-circle"
                        style="width:50px;height:50px;"
                    />

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
