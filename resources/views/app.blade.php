<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Racikara')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    @yield('content')

    <script>
        document.querySelectorAll('[data-mobile-nav-toggle]').forEach(function (toggle) {
            const navigation = toggle.closest('.navbar');

            toggle.addEventListener('click', function () {
                const isOpen = navigation.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(isOpen));
                toggle.setAttribute('aria-label', isOpen ? 'Tutup menu' : 'Buka menu');
            });

            navigation.querySelectorAll('.nav-menu a, .nav-actions a').forEach(function (link) {
                link.addEventListener('click', function () {
                    navigation.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.setAttribute('aria-label', 'Buka menu');
                });
            });
        });
    </script>
</body>
</html>
