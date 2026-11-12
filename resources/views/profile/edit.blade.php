@php($user = $user ?? auth()->user())
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan Profil - Resep Kita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --orange:#7a8f5c; --orange-dark:#5f7446; --sage:#7a8f5c; --sage-light:#e4ebd8; --brown:#542313; --muted:#806b61; --line:#f0dfd2; }
        * { box-sizing:border-box; }
        body { margin:0; background:#fffaf5; color:var(--brown); font-family:Figtree,Arial,sans-serif; }
        .settings-shell { display:flex; min-height:100vh; }
        .settings-sidebar { width:218px; flex:0 0 218px; padding:27px 16px; background:#fff; border-right:1px solid var(--line); }
        .brand { display:flex; align-items:center; gap:9px; padding:0 10px 31px; color:var(--brown); font-size:21px; font-weight:900; line-height:.9; text-decoration:none; }
        .brand span { color:var(--orange-dark); }
        .brand-icon { display:grid; place-items:center; width:39px; height:39px; font-size:31px; }
        .menu-label { margin:0 10px 12px; color:#947a6c; font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
        .side-nav { display:grid; gap:5px; }
        .side-nav a,.logout { display:flex; align-items:center; gap:13px; padding:11px 12px; border-radius:11px; color:#613f32; font-size:14px; font-weight:600; text-decoration:none; }
        .side-nav a:hover,.side-nav a.active { background:var(--orange); color:#fff; }
        .nav-icon { width:20px; text-align:center; font-size:19px; }
        .side-divider { margin:20px 10px; border:0; border-top:1px solid #f1e4dc; }
        .logout { width:100%; border:0; background:#fff0e3; color:#b9571d; cursor:pointer; text-align:left; }
        .settings-main { width:min(100%,1120px); margin:0 auto; padding:29px 24px 45px; }
        .settings-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; }
        .settings-top h1 { margin:0; color:var(--brown); font-size:33px; }
        .settings-top p { margin:4px 0 0; color:var(--muted); font-size:14px; }
        .top-user { display:flex; align-items:center; gap:10px; }
        .avatar-small,.profile-avatar { display:grid; place-items:center; border-radius:50%; background:#ffe0c5; color:#ad4b1b; font-weight:800; }
        .avatar-small { width:38px; height:38px; }
        .settings-grid { display:grid; grid-template-columns:320px minmax(0,1fr); gap:16px; align-items:start; }
        .panel { padding:20px; border:1px solid var(--line); border-radius:16px; background:#fff; box-shadow:0 7px 22px rgba(104,51,15,.05); }
        .panel-title { display:flex; align-items:center; gap:10px; margin:0 0 16px; font-size:18px; }
        .panel-title span { color:var(--orange); font-size:29px; }
        .profile-avatar { width:142px; height:142px; margin:4px auto 10px; overflow:hidden; font-size:43px; }
        .profile-avatar img { width:100%; height:100%; object-fit:cover; }
        .upload-label { display:block; width:max-content; margin:0 auto 20px; padding:8px 19px; border-radius:20px; background:var(--sage-light); color:#5f7446; font-size:11px; font-weight:800; cursor:pointer; }
        .field { margin-bottom:14px; }
        .field label { display:block; margin-bottom:6px; color:#543629; font-size:12px; font-weight:800; }
        .input,.textarea { width:100%; border:1px solid #eadbd1; border-radius:8px; outline:0; background:#fffdfa; color:var(--brown); font:inherit; font-size:12px; }
        .input { height:38px; padding:0 12px; }
        .textarea { min-height:86px; padding:10px 12px; resize:vertical; }
        .input:focus,.textarea:focus { border-color:var(--orange); box-shadow:0 0 0 3px rgba(245,130,31,.12); }
        .input:disabled { background:#f3f0ee; color:#8c7c75; }
        .button { width:100%; min-height:40px; border:0; border-radius:9px; background:var(--orange); color:#fff; font-size:12px; font-weight:800; cursor:pointer; }
        .button:hover { background:var(--orange-dark); }
        .right-stack { display:grid; gap:16px; }
        .password-panel { position:relative; overflow:hidden; }
        .password-panel:after { content:'🔒'; position:absolute; right:35px; top:48px; font-size:68px; opacity:.13; }
        .field-password { position:relative; }
        .field-password .input { padding-right:38px; }
        .toggle-password { position:absolute; right:10px; bottom:9px; border:0; background:transparent; color:var(--brown); cursor:pointer; }
        .preference { display:flex; align-items:center; gap:12px; padding:12px 0; border-bottom:1px solid #f4e9e2; }
        .preference:last-of-type { border-bottom:0; }
        .preference-icon { width:25px; color:var(--orange); font-size:22px; text-align:center; }
        .preference-text { flex:1; }
        .preference-text strong { display:block; font-size:12px; }
        .preference-text small { display:block; margin-top:3px; color:var(--muted); font-size:10px; line-height:1.35; }
        .switch { position:relative; width:41px; height:23px; flex:0 0 41px; }
        .switch input { opacity:0; width:0; height:0; }
        .slider { position:absolute; inset:0; border-radius:20px; background:#d8cbc4; cursor:pointer; transition:.2s; }
        .slider:before { content:''; position:absolute; width:17px; height:17px; left:3px; top:3px; border-radius:50%; background:#fff; transition:.2s; }
        .switch input:checked + .slider { background:var(--orange); }
        .switch input:checked + .slider:before { transform:translateX(18px); }
        .status { margin:10px 0 0; color:var(--sage); font-size:12px; font-weight:700; }
        .error { margin-top:4px; color:#c83d2d; font-size:11px; }
        .delete-panel { margin-top:16px; }
        .delete-panel h2 { margin:0 0 6px; font-size:16px; }
        .delete-panel p { margin:0 0 10px; color:var(--muted); font-size:12px; }
        @media (max-width:760px) {
            .settings-shell { display:block; }
            .settings-sidebar { width:100%; padding:14px; border-right:0; border-bottom:1px solid var(--line); }
            .brand { padding-bottom:14px; }
            .menu-label,.side-divider { display:none; }
            .side-nav { grid-template-columns:repeat(2,1fr); }
            .settings-main { padding:20px 14px 35px; }
            .settings-top h1 { font-size:27px; }
            .top-user { display:none; }
            .settings-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<div class="settings-shell">
    <aside class="settings-sidebar">
        <a class="brand" href="{{ route('home') }}"><span class="brand-icon">♨</span><span>RESEP<br><span>KITA</span></span></a>
        <p class="menu-label">Menu utama</p>
        <nav class="side-nav">
            <a href="{{ route('home') }}"><span class="nav-icon">⌂</span>Beranda</a>
            <a href="{{ route('dashboard') }}"><span class="nav-icon">▦</span>Dashboard</a>
            <a href="{{ route('recipes.favorites') }}"><span class="nav-icon">♡</span>Resep Favorit</a>
            <a href="{{ route('recipes.liked') }}"><span class="nav-icon">♥</span>Resep Disukai</a>
            <a href="{{ route('recipes.mine') }}"><span class="nav-icon">♨</span>Resep Saya</a>
        </nav>
        <hr class="side-divider">
        <a href="{{ route('profile.edit') }}" class="side-nav active"><span class="nav-icon">⚙</span>Pengaturan Profil</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout"><span class="nav-icon">↪</span>Logout</button></form>
    </aside>
    <main class="settings-main">
        <header class="settings-top"><div><h1>Pengaturan Profil</h1><p>Kelola informasi akun dan preferensi kamu.</p></div><div class="top-user"><span>♧</span><span class="avatar-small">{{ strtoupper(substr($user->name,0,1)) }}</span>⌄</div></header>
        <div class="settings-grid">
            <section class="panel">
                <h2 class="panel-title"><span>♟</span>Informasi Profil</h2>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('patch')
                    <div class="profile-avatar">@if($user->avatar)<img src="{{ asset('storage/'.$user->avatar) }}" alt="Foto profil">@else{{ strtoupper(substr($user->name,0,1)) }}@endif</div>
                    <label class="upload-label" for="avatar">📷 &nbsp;Ubah Foto</label>
                    <input id="avatar" name="avatar" type="file" accept=".jpg,.jpeg,.png,.webp" hidden>
                    @error('avatar')<div class="error">{{ $message }}</div>@enderror
                    <div class="field"><label for="name">Nama Lengkap <span class="required">*</span></label><input class="input" id="name" name="name" value="{{ old('name',$user->name) }}" required>@error('name')<div class="error">{{ $message }}</div>@enderror</div>
                    <div class="field"><label for="email">Email Address <span class="required">*</span></label><input class="input" id="email" name="email" type="email" value="{{ old('email',$user->email) }}" required>@error('email')<div class="error">{{ $message }}</div>@enderror</div>
                    <div class="field"><label for="bio">Bio</label><textarea class="textarea" id="bio" name="bio" maxlength="200" placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio',$user->bio) }}</textarea></div>
                    <button class="button" type="submit">▣ &nbsp;Simpan Perubahan</button>
                </form>
                @if(session('status') === 'profile-updated')<p class="status">Profil berhasil diperbarui.</p>@endif
            </section>
            <div class="right-stack">
                <section class="panel password-panel">
                    <h2 class="panel-title"><span>🔒</span>Ubah Password</h2>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf @method('put')
                        <div class="field field-password"><label for="current_password">Password Saat Ini</label><input class="input" id="current_password" name="current_password" type="password" autocomplete="current-password" placeholder="Masukkan password saat ini"><button class="toggle-password" type="button" data-target="current_password">♧</button>@error('current_password','updatePassword')<div class="error">{{ $message }}</div>@enderror</div>
                        <div class="field field-password"><label for="password">Password Baru</label><input class="input" id="password" name="password" type="password" autocomplete="new-password" placeholder="Masukkan password baru"><button class="toggle-password" type="button" data-target="password">♧</button>@error('password','updatePassword')<div class="error">{{ $message }}</div>@enderror</div>
                        <div class="field field-password"><label for="password_confirmation">Konfirmasi Password Baru</label><input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi password baru"><button class="toggle-password" type="button" data-target="password_confirmation">♧</button></div>
                        <button class="button" type="submit">🔒 &nbsp;Update Password</button>
                    </form>
                    @if(session('status') === 'password-updated')<p class="status">Password berhasil diperbarui.</p>@endif
                </section>
                <section class="panel">
                    <h2 class="panel-title"><span>♟</span>Preferensi Notifikasi</h2>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf @method('patch')
                        <input type="hidden" name="name" value="{{ $user->name }}"><input type="hidden" name="email" value="{{ $user->email }}"><input type="hidden" name="bio" value="{{ $user->bio }}">
                        @foreach([['notify_new_recipes','♨','Notifikasi resep baru','Dapatkan notifikasi resep terbaru dari komunitas'],['notify_likes','♥','Notifikasi like dan komentar','Dapatkan notifikasi saat ada yang menyukai resep kamu'],['notify_favorites','★','Notifikasi resep favorit','Dapatkan notifikasi resep yang sesuai dengan preferensi kamu']] as [$name,$icon,$title,$description])
                            <div class="preference"><span class="preference-icon">{{ $icon }}</span><div class="preference-text"><strong>{{ $title }}</strong><small>{{ $description }}</small></div><label class="switch"><input type="hidden" name="{{ $name }}" value="0"><input type="checkbox" name="{{ $name }}" value="1" @checked($user->{$name})><span class="slider"></span></label></div>
                        @endforeach
                        <button class="button" type="submit">▣ &nbsp;Simpan Preferensi</button>
                    </form>
                </section>
            </div>
        </div>
        <section class="panel delete-panel">@include('profile.partials.delete-user-form')</section>
    </main>
</div>
<script>
document.querySelectorAll('.toggle-password').forEach(function (button) {
    button.addEventListener('click', function () {
        const input = document.getElementById(button.dataset.target);
        input.type = input.type === 'password' ? 'text' : 'password';
    });
});
document.getElementById('avatar').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) document.querySelector('.profile-avatar').innerHTML = '<img src="' + URL.createObjectURL(file) + '" alt="Pratinjau foto profil">';
});
</script>
</body>
</html>
