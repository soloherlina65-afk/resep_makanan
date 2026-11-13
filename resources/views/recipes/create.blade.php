<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Posting Resep Baru - Resep Kita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --orange: #7a8f5c; --orange-dark: #5f7446; --sage: #7a8f5c; --sage-light: #e4ebd8; --brown: #542313; --muted: #806b61; --line: #f0dfd2; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #fffaf5; color: var(--brown); font-family: Figtree, Arial, sans-serif; }
        .create-shell { display: flex; min-height: 100vh; }
        .create-sidebar { width: 218px; flex: 0 0 218px; padding: 27px 16px; background: #fff; border-right: 1px solid var(--line); }
        .brand { display: flex; align-items: center; gap: 9px; padding: 0 10px 31px; color: var(--brown); font-size: 21px; font-weight: 900; line-height: .9; text-decoration: none; }
        .brand span { color: var(--orange-dark); }
        .brand-icon { display: grid; place-items: center; width: 39px; height: 39px; font-size: 31px; }
        .menu-label { margin: 0 10px 12px; color: #947a6c; font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .side-nav { display: grid; gap: 5px; }
        .side-nav a, .logout { display: flex; align-items: center; gap: 13px; padding: 11px 12px; border-radius: 11px; color: #613f32; font-size: 14px; font-weight: 600; text-decoration: none; }
        .side-nav a:hover, .side-nav a.active { background: var(--orange); color: #fff; }
        .nav-icon { width: 20px; text-align: center; font-size: 19px; }
        .side-divider { margin: 20px 10px; border: 0; border-top: 1px solid #f1e4dc; }
        .logout { width: 100%; border: 0; background: #fff0e3; color: #b9571d; cursor: pointer; text-align: left; }
        .create-main { width: min(100%, 1120px); margin: 0 auto; padding: 25px 22px 40px; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .topbar h1 { margin: 0; color: var(--brown); font-size: 28px; }
        .topbar p { margin: 4px 0 0; color: var(--muted); font-size: 13px; }
        .topbar-user { display: flex; align-items: center; gap: 10px; }
        .avatar { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 50%; background: #ffe0c5; color: #ad4b1b; font-weight: 800; }
        .share-banner { display: flex; align-items: center; gap: 20px; min-height: 88px; padding: 16px 27px; border-radius: 16px; background: linear-gradient(110deg, #ffbb67, #ffd09a); }
        .share-banner .pot { font-size: 50px; }
        .share-banner h2 { margin: 0; font-size: 22px; }
        .share-banner p { margin: 3px 0 0; color: #75412a; font-size: 13px; }
        .form-card { margin-top: 14px; padding: 25px; border: 1px solid var(--line); border-radius: 17px; background: #fff; box-shadow: 0 8px 26px rgba(104, 51, 15, .06); }
        .form-heading { display: flex; align-items: center; gap: 13px; padding-bottom: 18px; border-bottom: 1px solid #f5e8df; }
        .form-heading-icon { color: var(--orange); font-size: 40px; }
        .form-heading h2 { margin: 0; font-size: 24px; }
        .form-heading p { margin: 3px 0 0; color: var(--muted); font-size: 12px; }
        .create-grid { display: grid; grid-template-columns: minmax(0, 1fr) 260px; gap: 20px; margin-top: 18px; }
        .field { margin-bottom: 14px; }
        .field label, .panel-title { display: block; margin-bottom: 6px; color: #543629; font-size: 12px; font-weight: 800; }
        .required { color: var(--orange-dark); }
        .input, .select, .textarea { width: 100%; border: 1px solid #eadbd1; border-radius: 8px; outline: 0; background: #fffdfa; color: var(--brown); font: inherit; font-size: 12px; }
        .input, .select { height: 38px; padding: 0 12px; }
        .category-search { position: relative; }
        .category-search::after { content: '⌄'; position: absolute; top: 8px; right: 12px; color: var(--orange-dark); pointer-events: none; }
        .category-search .input { padding-right: 32px; }
        .category-hint { margin-top: 5px; color: var(--sage); font-size: 10px; }
        .textarea { min-height: 72px; padding: 10px 12px; resize: vertical; }
        .input:focus, .select:focus, .textarea:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(245, 130, 31, .12); }
        .three-fields { display: grid; grid-template-columns: 1fr 1fr 1.15fr; gap: 12px; }
        .dynamic-panel { padding: 13px; border: 1px solid #f0dfd2; border-radius: 11px; background: #fffaf4; }
        .panel-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 9px; }
        .panel-top .panel-title { margin: 0; }
        .add-row { padding: 6px 9px; border: 0; border-radius: 15px; background: var(--sage-light); color: #5f7446; font-size: 10px; font-weight: 800; cursor: pointer; }
        .dynamic-row { display: flex; gap: 6px; margin-top: 7px; }
        .dynamic-row .input { height: 34px; }
        .remove-row { width: 31px; border: 0; border-radius: 7px; background: #ffe9e4; color: #c44532; cursor: pointer; }
        .upload-panel { padding: 15px; border: 1px solid #f0dfd2; border-radius: 14px; background: #fffaf5; }
        .upload-box { display: grid; place-items: center; min-height: 175px; padding: 15px; border: 1px dashed #e2c6b5; border-radius: 10px; color: var(--muted); text-align: center; cursor: pointer; }
        .upload-box strong { display: block; margin: 6px 0; color: var(--brown); font-size: 12px; }
        .upload-box small { font-size: 10px; }
        .upload-icon { color: var(--orange); font-size: 40px; }
        .preview { display: none; width: 100%; height: 145px; margin-top: 10px; border-radius: 9px; object-fit: cover; }
        .error { margin-top: 4px; color: #c83d2d; font-size: 11px; }
        .form-actions { display: flex; justify-content: space-between; gap: 10px; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f5e8df; }
        .button { display: inline-block; padding: 10px 20px; border-radius: 20px; font-size: 12px; font-weight: 800; text-decoration: none; cursor: pointer; }
        .back-button { border: 1px solid var(--orange); color: var(--orange-dark); background: #fff; }
        .post-button { border: 0; color: #fff; background: var(--orange); }
        .post-button:hover { background: var(--orange-dark); }
        @media (max-width: 760px) {
            .create-shell { display: block; }
            .create-sidebar { width: 100%; padding: 14px; border-right: 0; border-bottom: 1px solid var(--line); }
            .brand { padding-bottom: 14px; }
            .menu-label, .side-divider { display: none; }
            .side-nav { grid-template-columns: repeat(2, 1fr); }
            .create-main { padding: 20px 14px 30px; }
            .create-grid, .three-fields { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="create-shell">
    <aside class="create-sidebar">
        <a class="brand" href="{{ route('home') }}"><span class="brand-icon">♨</span><span>RESEP<br><span>KITA</span></span></a>
        <p class="menu-label">Menu utama</p>
        <nav class="side-nav">
            <a href="{{ route('home') }}"><span class="nav-icon">⌂</span>Beranda</a>
            <a href="{{ route('dashboard') }}" class="active"><span class="nav-icon">▦</span>Dashboard</a>
            <a href="{{ route('recipes.favorites') }}"><span class="nav-icon">♡</span>Resep Favorit</a>
            <a href="{{ route('recipes.liked') }}"><span class="nav-icon">♥</span>Resep Disukai</a>
            <a href="{{ route('dashboard') }}#created"><span class="nav-icon">♨</span>Resep Saya</a>
        </nav>
        <hr class="side-divider">
        <a href="{{ route('profile.edit') }}" class="side-nav"><span class="nav-icon">⚙</span>Pengaturan Profil</a>
        <form method="POST" action="{{ route('logout') }}" data-logout-form>@csrf<button class="logout" type="submit"><span class="nav-icon">↪</span>Logout</button></form>
    </aside>
    <main class="create-main">
        <header class="topbar">
            <div><h1>Selamat datang di Dashboard, {{ auth()->user()->name }} 👋</h1><p>Bagikan kreasi masakan terbaikmu.</p></div>
            <div class="topbar-user"><span>♧</span><span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span></div>
        </header>
        <section class="share-banner"><span class="pot">🍲</span><div><h2>Bagikan Resep Andalanmu!</h2><p>Tulis resep masakan favoritmu dan inspirasi untuk orang lain.</p></div></section>

        <form class="form-card" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-heading"><span class="form-heading-icon">♨</span><div><h2>Posting Resep Baru</h2><p>Lengkapi informasi resep dengan detail agar lebih mudah diikuti.</p></div></div>
            @if ($errors->any()) <div class="error" style="margin: 12px 0;">Periksa kembali data resep yang kamu masukkan.</div> @endif
            <div class="create-grid">
                <div>
                    <div class="field"><label for="nama_resep">Nama Resep <span class="required">*</span></label><input class="input" id="nama_resep" name="nama_resep" value="{{ old('nama_resep') }}" placeholder="Masukkan nama resep..." required>@error('nama_resep')<div class="error">{{ $message }}</div>@enderror</div>
                    <div class="field"><label for="category_search">Kategori <span class="required">*</span></label><div class="category-search"><input class="input" id="category_search" name="category_name" list="category_options" value="{{ old('category_name') }}" placeholder="Cari atau pilih kategori..." autocomplete="off" required><input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}"><datalist id="category_options">@foreach ($categories as $category)<option value="{{ $category->nama_kategori }}" data-id="{{ $category->id }}"></option>@endforeach</datalist></div><div class="category-hint">Ketik nama kategori untuk mencari, lalu pilih saran yang tersedia.</div><div id="category-error" class="error" hidden>Pilih kategori dari daftar yang tersedia.</div>@error('category_id')<div class="error">{{ $message }}</div>@enderror</div>
                    <div class="field"><label for="deskripsi">Deskripsi Resep <span class="required">*</span></label><textarea class="textarea" id="deskripsi" name="deskripsi" placeholder="Ceritakan sedikit tentang resep ini..." required>{{ old('deskripsi') }}</textarea>@error('deskripsi')<div class="error">{{ $message }}</div>@enderror</div>
                    <div class="three-fields">
                        <div class="field"><label for="waktu_masak">Waktu Masak</label><input class="input" id="waktu_masak" type="number" min="1" name="waktu_masak" value="{{ old('waktu_masak') }}" placeholder="Contoh: 30" required></div>
                        <div class="field"><label for="porsi">Porsi</label><input class="input" id="porsi" type="number" min="1" name="porsi" value="{{ old('porsi') }}" placeholder="Contoh: 4" required></div>
                        <div class="field"><label for="tingkat_kesulitan">Tingkat Kesulitan</label><select class="select" id="tingkat_kesulitan" name="tingkat_kesulitan" required><option value="">Pilih tingkat kesulitan</option>@foreach (['Mudah', 'Sedang', 'Sulit'] as $level)<option @selected(old('tingkat_kesulitan') === $level)>{{ $level }}</option>@endforeach</select></div>
                    </div>
                    <div class="dynamic-panel"><div class="panel-top"><span class="panel-title">🧺 Bahan-Bahan <span class="required">*</span></span><button class="add-row" type="button" data-add="ingredients">+ Tambah Bahan</button></div><div id="ingredients">@foreach (old('bahan', ['']) as $ingredient)<div class="dynamic-row"><input class="input" name="bahan[]" value="{{ $ingredient }}" placeholder="Contoh: 2 siung bawang putih" required><button class="remove-row" type="button" aria-label="Hapus bahan">×</button></div>@endforeach</div></div>
                    <div class="dynamic-panel" style="margin-top: 12px;"><div class="panel-top"><span class="panel-title">☷ Langkah-Langkah <span class="required">*</span></span><button class="add-row" type="button" data-add="steps">+ Tambah Langkah</button></div><div id="steps">@foreach (old('langkah', ['']) as $step)<div class="dynamic-row"><input class="input" name="langkah[]" value="{{ $step }}" placeholder="Tuliskan langkah memasak..." required><button class="remove-row" type="button" aria-label="Hapus langkah">×</button></div>@endforeach</div></div>
                </div>
                <aside class="upload-panel"><span class="panel-title">📷 Foto Resep</span><small style="color: var(--muted);">Unggah foto menarik untuk resep kamu</small><label class="upload-box" for="foto"><span class="upload-icon">⇧</span><strong>Klik untuk upload foto</strong><small>atau drag & drop<br>Format: JPG, PNG | Maks: 2MB</small></label><input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,.webp" hidden><img id="preview" class="preview" alt="Pratinjau foto resep">@error('foto')<div class="error">{{ $message }}</div>@enderror</aside>
            </div>
            <div class="form-actions"><a class="button back-button" href="{{ route('dashboard') }}">← &nbsp;Kembali</a><button class="button post-button" type="submit">➤ &nbsp;Posting Resep</button></div>
        </form>
    </main>
</div>
<script>
    const categoryInput = document.getElementById('category_search');
    const categoryId = document.getElementById('category_id');
    const categoryError = document.getElementById('category-error');
    const categoryOptions = Array.from(document.querySelectorAll('#category_options option'));
    function syncCategory() {
        const selected = categoryOptions.find(function (option) {
            return option.value.toLowerCase() === categoryInput.value.trim().toLowerCase();
        });
        categoryId.value = selected ? selected.dataset.id : '';
        categoryError.hidden = Boolean(selected || !categoryInput.value);
    }
    categoryInput.addEventListener('input', syncCategory);
    categoryInput.addEventListener('change', syncCategory);
    document.querySelector('form').addEventListener('submit', function (event) {
        syncCategory();
        if (!categoryId.value) {
            event.preventDefault();
            categoryError.hidden = false;
            categoryInput.focus();
        }
    });

    document.querySelectorAll('[data-add]').forEach(function (button) {
        button.addEventListener('click', function () {
            const container = document.getElementById(button.dataset.add);
            const input = document.createElement('div');
            input.className = 'dynamic-row';
            const name = button.dataset.add === 'ingredients' ? 'bahan[]' : 'langkah[]';
            const placeholder = button.dataset.add === 'ingredients' ? 'Contoh: 2 siung bawang putih' : 'Tuliskan langkah memasak...';
            input.innerHTML = '<input class="input" name="' + name + '" placeholder="' + placeholder + '" required><button class="remove-row" type="button" aria-label="Hapus">×</button>';
            container.appendChild(input);
        });
    });
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-row')) {
            const rows = event.target.closest('div').parentElement.children;
            if (rows.length > 1) event.target.closest('.dynamic-row').remove();
        }
    });
    document.getElementById('foto').addEventListener('change', function (event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        if (!file) { preview.style.display = 'none'; return; }
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    });
</script>
</body>
</html>
