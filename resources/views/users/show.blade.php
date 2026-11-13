@extends('app')

@section('title', 'Profil Pengguna - RACIKARA')

@section('content')
<div class="container py-5">

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center gap-4 flex-wrap">

            <img
                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                alt="Foto profil"
                width="110"
                height="110"
                class="rounded-circle"
                style="object-fit: cover;"
            >

            <div class="flex-grow-1">
                <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                <p class="text-muted mb-2">{{ $user->bio ?? 'Belum ada bio.' }}</p>

                <div class="d-flex gap-4 flex-wrap">
                    <span><strong>{{ $user->recipes_count }}</strong> Resep</span>
                    <a href="{{ route('users.followers', $user->id) }}" class="text-decoration-none text-dark">
                        <strong>{{ $user->followers_count }}</strong> Pengikut
                    </a>
                    <a href="{{ route('users.following', $user->id) }}" class="text-decoration-none text-dark">
                        <strong>{{ $user->following_count }}</strong> Mengikuti
                    </a>
                </div>
            </div>

            @auth
                @if(auth()->id() !== $user->id)
                    @if($isFollowing)
                        <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-secondary rounded-pill px-4">
                                Mengikuti
                            </button>
                        </form>
                    @else
                        <form action="{{ route('users.follow', $user->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-warning rounded-pill px-4">
                                + Ikuti
                            </button>
                        </form>
                    @endif
                @endif
            @endauth

        </div>
    </div>

    <h4 class="fw-bold mb-3">Resep dari {{ $user->name }}</h4>

    <div class="row g-4">
        @forelse($recipes as $recipe)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    @if($recipe->foto)
                        <img
                            src="{{ asset('storage/' . $recipe->foto) }}"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;"
                            alt="{{ $recipe->nama_resep }}"
                        >
                    @endif

                    <div class="card-body">
                        <h5 class="fw-bold">{{ $recipe->nama_resep }}</h5>
                        <p class="text-muted">{{ Str::limit($recipe->deskripsi, 90) }}</p>
                        <a href="{{ route('recipes.show', $recipe->id) }}"
                           class="btn btn-outline-warning rounded-pill">
                            Lihat Resep
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Pengguna ini belum memiliki resep.</p>
        @endforelse
    </div>

</div>
@endsection
