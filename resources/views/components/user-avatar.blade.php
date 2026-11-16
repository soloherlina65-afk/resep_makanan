@props(['user'])
@php($avatarUrl = $user->avatarUrl())

<span {{ $attributes->merge([
    'style' => 'display:inline-grid;place-items:center;overflow:hidden;border-radius:50%;',
    'role' => 'img',
    'aria-label' => 'Foto profil ' . $user->name,
]) }}>
    @if ($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
    @else
        <span aria-hidden="true">{{ strtoupper(substr(trim($user->name), 0, 1)) }}</span>
    @endif
</span>
