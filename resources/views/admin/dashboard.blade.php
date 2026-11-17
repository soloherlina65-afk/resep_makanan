@php
    $imageMap = [
        'Ayam Kecap' => 'images/ayamkecap.png',
        'Udang Saus Padang' => 'images/udangsauspadang.png',
        'Nasi Goreng Spesial' => 'images/nasigorengspesial.png',
        'Pancake Klasik Madu' => 'images/pancakeklasikmadu.png',
        'Mocalatte Spesial' => 'images/mocalatte.png',
        'Waterlemon' => 'images/waterlemon.png',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Racikara · Area Admin — Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --admin-bg: #f6f7f2;
            --admin-surface: #fff;
            --admin-border: #e4e9dc;
            --admin-primary: #4f6336;
            --admin-primary-hover: #3e4f2a;
            --admin-soft: #e5ecda;
            --admin-soft-hover: #d6e2c5;
            --admin-soft-text: #3b4d26;
            --admin-text: #1e241b;
            --admin-muted: #6e7768;
            --admin-shadow: 0 2px 12px -2px rgba(79, 99, 54, .05), 0 1px 4px -1px rgba(30, 36, 27, .03);
            font-family: "Plus Jakarta Sans", Inter, Arial, sans-serif;
        }
        * { box-sizing: border-box; }
        body { min-width: 320px; min-height: 100vh; margin: 0; background: var(--admin-bg); color: var(--admin-text); -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        button, input, select { font: inherit; }
        button { cursor: pointer; }
        .admin-app { min-height: 100vh; }
        .sidebar {
            position: fixed; z-index: 40; inset: 0 auto 0 0; display: flex; width: 288px; flex-direction: column;
            justify-content: space-between; transform: translateX(-100%); border-right: 1px solid var(--admin-border);
            background: var(--admin-surface); transition: transform .25s ease;
        }
        .sidebar.is-open { transform: translateX(0); }
        .sidebar-top { display: flex; min-height: 0; flex: 1; flex-direction: column; overflow-y: auto; }
        .brand { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 21px 24px; border-bottom: 1px solid var(--admin-border); }
        .brand-link { display: flex; align-items: center; gap: 12px; }
        .brand-logo { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; object-position: center; }
        .brand-name { display: block; font-size: 18px; font-weight: 800; letter-spacing: -.03em; }
        .brand-tag { display: inline-block; margin-top: 3px; padding: 3px 9px; border-radius: 999px; background: var(--admin-soft); color: var(--admin-soft-text); font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .icon-button { display: inline-grid; width: 36px; height: 36px; place-items: center; border: 0; border-radius: 10px; background: transparent; color: var(--admin-muted); }
        .icon-button:hover { background: var(--admin-soft); color: var(--admin-primary); }
        .icon { width: 18px; height: 18px; flex: 0 0 auto; fill: none; stroke: currentColor; stroke-linecap: round; stroke-linejoin: round; stroke-width: 1.8; }
        .nav-groups { display: grid; gap: 24px; flex: 1; padding: 21px 16px; }
        .nav-heading { margin: 0 12px 9px; color: var(--admin-muted); font-size: 10px; font-weight: 800; letter-spacing: .11em; text-transform: uppercase; }
        .nav-list { display: grid; gap: 3px; margin: 0; padding: 0; list-style: none; }
        .nav-link { display: flex; min-height: 42px; align-items: center; gap: 12px; padding: 9px 12px; border-radius: 11px; color: var(--admin-muted); font-size: 12px; font-weight: 600; transition: background .15s, color .15s; }
        .nav-link:hover { background: rgba(229, 236, 218, .65); color: var(--admin-text); }
        .nav-link.active { background: var(--admin-primary); color: #fff; box-shadow: 0 2px 7px rgba(79, 99, 54, .16); }
        .nav-count { margin-left: auto; padding: 3px 8px; border-radius: 999px; background: rgba(30, 36, 27, .06); color: inherit; font-size: 10px; }
        .nav-link.active .nav-count { background: rgba(255, 255, 255, .18); }
        .sidebar-bottom { display: grid; gap: 8px; padding: 14px; border-top: 1px solid var(--admin-border); background: rgba(246, 247, 242, .7); }
        .switch-link, .sidebar-action { display: flex; min-height: 38px; align-items: center; justify-content: center; gap: 8px; padding: 8px 10px; border: 1px solid transparent; border-radius: 10px; color: var(--admin-muted); font-size: 11px; font-weight: 700; }
        .switch-link { justify-content: space-between; background: var(--admin-soft); color: var(--admin-soft-text); }
        .switch-link:hover { background: var(--admin-soft-hover); }
        .sidebar-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 7px; }
        .sidebar-action:hover { border-color: var(--admin-border); background: #fff; color: var(--admin-primary); }
        .sidebar-action.logout { color: #9a4540; }
        .sidebar-action.logout:hover { border-color: #f1d8d6; background: #fff4f3; }
        .sidebar-backdrop { position: fixed; z-index: 30; inset: 0; border: 0; background: rgba(30, 36, 27, .34); backdrop-filter: blur(2px); }
        .admin-main { min-width: 0; min-height: 100vh; }
        .topbar { position: sticky; z-index: 20; top: 0; display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 15px clamp(16px, 3vw, 36px); border-bottom: 1px solid rgba(228, 233, 220, .85); background: rgba(246, 247, 242, .92); backdrop-filter: blur(12px); }
        .topbar-title { display: flex; min-width: 0; align-items: center; gap: 12px; }
        .breadcrumb { display: flex; align-items: center; gap: 6px; margin-bottom: 3px; color: var(--admin-muted); font-size: 10px; font-weight: 600; }
        .breadcrumb strong { color: var(--admin-primary); }
        .page-title { margin: 0; font-size: clamp(20px, 2.4vw, 27px); font-weight: 800; letter-spacing: -.035em; line-height: 1.2; }
        .topbar-tools { display: flex; align-items: center; gap: 9px; }
        .search-form { display: flex; width: clamp(180px, 23vw, 280px); align-items: center; gap: 8px; }
        .search-field { position: relative; min-width: 0; flex: 1; }
        .search-field .icon { position: absolute; top: 50%; left: 12px; width: 16px; height: 16px; transform: translateY(-50%); color: var(--admin-muted); pointer-events: none; }
        .search-field input { width: 100%; height: 39px; padding: 0 11px 0 36px; border: 1px solid var(--admin-border); border-radius: 11px; outline: 0; background: #fff; color: var(--admin-text); font-size: 11px; }
        .search-field input:focus, .filter-select:focus { border-color: var(--admin-primary); box-shadow: 0 0 0 3px rgba(79, 99, 54, .1); }
        .button { display: inline-flex; min-height: 38px; align-items: center; justify-content: center; gap: 7px; padding: 8px 13px; border: 1px solid transparent; border-radius: 10px; background: var(--admin-primary); color: #fff; font-size: 11px; font-weight: 700; white-space: nowrap; transition: background .15s, transform .15s; }
        .button:hover { transform: translateY(-1px); background: var(--admin-primary-hover); }
        .button.secondary { border-color: var(--admin-border); background: #fff; color: var(--admin-primary); }
        .button.secondary:hover { background: var(--admin-soft); }
        .button.danger { background: #a53d32; }
        .site-link { display: none; }
        .page-content { width: min(1480px, 100%); margin: 0 auto; padding: 24px clamp(16px, 3vw, 36px) 42px; }
        .content-stack { display: grid; gap: 20px; }
        .section-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
        .section-title { margin: 0; color: var(--admin-muted); font-size: 11px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
        .today-label { padding: 6px 11px; border: 1px solid var(--admin-border); border-radius: 999px; background: #fff; color: var(--admin-muted); font-size: 10px; }
        .stats-primary { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
        .stats-secondary { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-top: 12px; }
        .stat-card { min-width: 0; padding: 17px; border: 1px solid var(--admin-border); border-radius: 16px; background: var(--admin-surface); box-shadow: var(--admin-shadow); transition: box-shadow .18s, border-color .18s, transform .18s; }
        .stat-card:hover { transform: translateY(-2px); border-color: rgba(79, 99, 54, .28); box-shadow: 0 8px 24px -4px rgba(79, 99, 54, .1), 0 2px 6px rgba(30, 36, 27, .04); }
        .stat-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
        .stat-label { margin: 0; color: var(--admin-muted); font-size: 11px; font-weight: 600; }
        .stat-value { margin: 7px 0 0; color: var(--admin-primary); font-size: 27px; font-weight: 800; letter-spacing: -.045em; line-height: 1; }
        .stat-icon { display: grid; width: 38px; height: 38px; flex: 0 0 38px; place-items: center; border-radius: 50%; background: var(--admin-soft); color: var(--admin-soft-text); }
        .stat-icon .icon { width: 18px; height: 18px; }
        .stat-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 14px; padding-top: 10px; border-top: 1px solid rgba(228, 233, 220, .8); color: var(--admin-muted); font-size: 9px; }
        .stat-pill { padding: 3px 7px; border-radius: 999px; background: var(--admin-soft); color: var(--admin-soft-text); font-size: 9px; font-weight: 700; white-space: nowrap; }
        .stat-pill.warning { background: #fff3dc; color: #815b16; }
        .panel { overflow: hidden; border: 1px solid var(--admin-border); border-radius: 16px; background: #fff; box-shadow: var(--admin-shadow); }
        .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 17px 20px; border-bottom: 1px solid var(--admin-border); }
        .panel-title { margin: 0; font-size: 14px; font-weight: 800; letter-spacing: -.02em; }
        .panel-subtitle { margin: 4px 0 0; color: var(--admin-muted); font-size: 10px; }
        .quick-links { display: flex; flex-wrap: wrap; gap: 8px; padding: 17px 20px 19px; }
        .quick-link { display: inline-flex; min-height: 36px; align-items: center; gap: 7px; padding: 8px 12px; border: 1px solid transparent; border-radius: 999px; background: var(--admin-soft); color: var(--admin-soft-text); font-size: 10px; font-weight: 700; transition: background .15s, transform .15s; }
        .quick-link:hover { transform: translateY(-1px); background: var(--admin-soft-hover); }
        .quick-link .icon { width: 15px; height: 15px; }
        .quick-count { display: grid; min-width: 18px; height: 18px; place-items: center; padding: 0 4px; border-radius: 50%; background: var(--admin-primary); color: #fff; font-size: 9px; }
        .recipe-tools { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
        .filter-select { min-height: 36px; max-width: 220px; padding: 7px 30px 7px 10px; border: 1px solid var(--admin-border); border-radius: 10px; outline: 0; background: var(--admin-bg); color: var(--admin-text); font-size: 10px; }
        .table-scroll { overflow-x: auto; }
        .recipe-table { width: 100%; border-collapse: collapse; text-align: left; }
        .recipe-table th { padding: 11px 15px; border-bottom: 1px solid var(--admin-border); background: rgba(246, 247, 242, .75); color: var(--admin-muted); font-size: 9px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
        .recipe-table td { padding: 12px 15px; border-bottom: 1px solid rgba(228, 233, 220, .75); font-size: 10px; vertical-align: middle; }
        .recipe-table tbody tr:hover { background: rgba(246, 247, 242, .72); }
        .recipe-cell { display: flex; min-width: 200px; align-items: center; gap: 10px; }
        .recipe-thumb { display: grid; width: 39px; height: 39px; flex: 0 0 39px; place-items: center; overflow: hidden; border: 1px solid var(--admin-border); border-radius: 11px; background: var(--admin-soft); color: var(--admin-primary); font-size: 17px; }
        .recipe-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .recipe-name { display: block; max-width: 330px; overflow: hidden; color: var(--admin-text); font-size: 10px; font-weight: 800; text-overflow: ellipsis; white-space: nowrap; }
        .recipe-meta { margin: 3px 0 0; color: var(--admin-muted); font-size: 9px; }
        .category-pill { display: inline-block; padding: 4px 9px; border-radius: 999px; background: var(--admin-soft); color: var(--admin-soft-text); font-size: 9px; font-weight: 700; white-space: nowrap; }
        .owner-cell { display: flex; align-items: center; gap: 7px; white-space: nowrap; }
        .owner-avatar { display: grid; width: 24px; height: 24px; place-items: center; border-radius: 50%; background: var(--admin-soft); color: var(--admin-primary); font-size: 9px; font-weight: 800; }
        .recipe-actions { display: flex; justify-content: flex-end; gap: 3px; white-space: nowrap; }
        .recipe-actions .icon-button { width: 30px; height: 30px; }
        .recipe-actions .icon { width: 15px; height: 15px; }
        .empty-state { padding: 38px 16px; color: var(--admin-muted); text-align: center; }
        .empty-icon { display: grid; width: 42px; height: 42px; margin: 0 auto 10px; place-items: center; border-radius: 50%; background: var(--admin-soft); color: var(--admin-primary); }
        .empty-state strong { display: block; color: var(--admin-text); font-size: 12px; }
        .empty-state p { margin: 5px 0 0; font-size: 10px; }
        .table-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 18px; background: rgba(246, 247, 242, .5); color: var(--admin-muted); font-size: 9px; }
        .pagination-wrap { padding: 12px 18px; border-top: 1px solid var(--admin-border); }
        .pagination-wrap nav { display: flex; justify-content: space-between; }
        .activity-list { display: grid; }
        .activity-row { display: grid; grid-template-columns: minmax(110px, .7fr) minmax(0, 2fr) auto; align-items: center; gap: 12px; padding: 12px 18px; border-bottom: 1px solid rgba(228, 233, 220, .75); font-size: 10px; }
        .activity-row:last-child { border-bottom: 0; }
        .activity-user { overflow: hidden; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .activity-description, .activity-time { color: var(--admin-muted); }
        .activity-description { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .activity-time { font-size: 9px; white-space: nowrap; }
        .mobile-menu { display: inline-grid; width: 38px; height: 38px; flex: 0 0 38px; place-items: center; border: 1px solid var(--admin-border); border-radius: 11px; background: #fff; color: var(--admin-text); }
        [hidden] { display: none !important; }
        @media (min-width: 1024px) {
            .admin-app { display: flex; }
            .sidebar { position: sticky; top: 0; transform: none; flex: 0 0 288px; }
            .sidebar-close, .mobile-menu, .sidebar-backdrop { display: none !important; }
            .admin-main { flex: 1; }
        }
        @media (max-width: 1023px) {
            .topbar { align-items: flex-start; }
            .topbar-tools { flex: 1; justify-content: flex-end; }
        }
        @media (max-width: 720px) {
            .topbar { flex-direction: column; gap: 12px; }
            .topbar-title, .topbar-tools { width: 100%; }
            .topbar-tools { justify-content: flex-start; }
            .search-form { width: auto; flex: 1; }
            .search-button-label { display: none; }
            .site-link { display: none; }
            .add-recipe-link span { display: none; }
            .stats-primary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stats-secondary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .panel-head { align-items: flex-start; flex-direction: column; }
            .recipe-tools { width: 100%; }
            .filter-select { max-width: none; flex: 1; }
            .activity-row { grid-template-columns: minmax(0, 1fr) auto; gap: 5px 10px; }
            .activity-description { grid-column: 1 / -1; grid-row: 2; }
            .activity-time { grid-column: 2; grid-row: 1; }
        }
        @media (max-width: 420px) {
            .stats-primary, .stats-secondary { gap: 8px; }
            .stat-card { padding: 13px; }
            .stat-value { font-size: 23px; }
            .stat-footer { align-items: flex-start; flex-direction: column; }
            .today-label { font-size: 9px; }
            .table-footer { align-items: flex-start; flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" class="icon-library" aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden">
    <symbol id="icon-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></symbol>
    <symbol id="icon-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
    <symbol id="icon-book" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    <symbol id="icon-layers" viewBox="0 0 24 24"><path d="m12 2 9 5-9 5-9-5 9-5Z"/><path d="m3 12 9 5 9-5M3 17l9 5 9-5"/></symbol>
    <symbol id="icon-message" viewBox="0 0 24 24"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/></symbol>
    <symbol id="icon-heart" viewBox="0 0 24 24"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/></symbol>
    <symbol id="icon-calendar" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"/></symbol>
    <symbol id="icon-follow" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M19 8v6M16 11h6"/></symbol>
    <symbol id="icon-utensils" viewBox="0 0 24 24"><path d="M3 2v7a4 4 0 0 0 4 4v9M7 2v5M11 2v5a4 4 0 0 1-4 4M17 2v20M17 2c3 2 4 5 4 8h-4"/></symbol>
    <symbol id="icon-tags" viewBox="0 0 24 24"><path d="m20.6 13.4-7.2 7.2a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1"/></symbol>
    <symbol id="icon-shield" viewBox="0 0 24 24"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="icon-bell" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></symbol>
    <symbol id="icon-activity" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></symbol>
    <symbol id="icon-settings" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.7-1.4-2.4 1.4-1.1a8 8 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.7 1.4 2.4-1.4 1.1a8 8 0 0 1 0 2Z"/></symbol>
    <symbol id="icon-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></symbol>
    <symbol id="icon-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="icon-arrow" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="icon-external" viewBox="0 0 24 24"><path d="M15 3h6v6M10 14 21 3"/><path d="M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"/></symbol>
    <symbol id="icon-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    <symbol id="icon-close" viewBox="0 0 24 24"><path d="m18 6-12 12M6 6l12 12"/></symbol>
    <symbol id="icon-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="icon-edit" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></symbol>
    <symbol id="icon-trash" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6"/></symbol>
    <symbol id="icon-logout" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/></symbol>
    <symbol id="icon-empty" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4M8 11h6"/></symbol>
</svg>

<div class="admin-app">
    <button id="sidebar-backdrop" class="sidebar-backdrop" type="button" aria-label="Tutup menu" hidden></button>

    <aside id="sidebar" class="sidebar" aria-label="Navigasi admin">
        <div class="sidebar-top">
            <div class="brand">
                <a class="brand-link" href="{{ route('admin.dashboard') }}">
                    <img class="brand-logo" src="{{ asset('images/racikara-logo.png') }}" alt="Logo Racikara">
                    <span><span class="brand-name">Racikara</span><span class="brand-tag">Area Admin</span></span>
                </a>
                <button class="icon-button sidebar-close" type="button" data-sidebar-close aria-label="Tutup menu">
                    <svg class="icon"><use href="#icon-close"/></svg>
                </button>
            </div>

            <nav class="nav-groups">
                <div>
                    <p class="nav-heading">Menu utama</p>
                    <ul class="nav-list">
                        <li><a class="nav-link active" href="{{ route('admin.dashboard') }}" aria-current="page"><svg class="icon"><use href="#icon-dashboard"/></svg>Dashboard</a></li>
                        <li><a class="nav-link" href="{{ route('admin.recipes.index') }}"><svg class="icon"><use href="#icon-utensils"/></svg>Kelola Resep<span class="nav-count">{{ $recipesCount }}</span></a></li>
                        <li><a class="nav-link" href="{{ route('admin.categories.index') }}"><svg class="icon"><use href="#icon-tags"/></svg>Kelola Kategori<span class="nav-count">{{ $categoriesCount }}</span></a></li>
                        <li><a class="nav-link" href="{{ route('admin.users.index') }}"><svg class="icon"><use href="#icon-users"/></svg>Kelola Pengguna<span class="nav-count">{{ $usersCount }}</span></a></li>
                        <li>
                            <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                                <svg class="icon"><use href="#icon-message"/></svg>Moderasi Komentar
                                @if ($pendingReviewsCount > 0)
                                    <span class="nav-count">{{ $pendingReviewsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li><a class="nav-link" href="{{ route('about') }}#team"><svg class="icon"><use href="#icon-shield"/></svg>Kelola Anggota Tim</a></li>
                    </ul>
                </div>
                <div>
                    <p class="nav-heading">Sistem &amp; analitik</p>
                    <ul class="nav-list">
                        <li><a class="nav-link" href="{{ route('admin.notifications.index') }}"><svg class="icon"><use href="#icon-bell"/></svg>Notifikasi</a></li>
                        <li><a class="nav-link" href="{{ route('admin.activities.index') }}"><svg class="icon"><use href="#icon-activity"/></svg>Aktivitas Pengguna</a></li>
                        <li><a class="nav-link" href="{{ route('admin.settings.edit') }}"><svg class="icon"><use href="#icon-settings"/></svg>Pengaturan Aplikasi</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <a class="switch-link" href="{{ route('dashboard') }}"><span><svg class="icon" style="vertical-align:middle;margin-right:6px"><use href="#icon-users"/></svg>Dashboard Pengguna</span><svg class="icon"><use href="#icon-external"/></svg></a>
            <div class="sidebar-actions">
                <a class="sidebar-action" href="{{ route('home') }}"><svg class="icon"><use href="#icon-arrow"/></svg>Ke Situs</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="sidebar-action logout" type="submit" style="width:100%"><svg class="icon"><use href="#icon-logout"/></svg>Logout</button>
                </form>
            </div>
        </div>
    </aside>

    <div class="admin-main">
        <header class="topbar">
            <div class="topbar-title">
                <button class="mobile-menu" type="button" data-sidebar-open aria-label="Buka menu">
                    <svg class="icon"><use href="#icon-menu"/></svg>
                </button>
                <div>
                    <div class="breadcrumb"><span>Racikara</span><span>·</span><span>Area Admin</span><span>/</span><strong>Dashboard</strong></div>
                    <h1 class="page-title">Dashboard Admin</h1>
                </div>
            </div>
            <div class="topbar-tools">
                <form class="search-form" method="GET" action="{{ route('admin.dashboard') }}">
                    <div class="search-field">
                        <svg class="icon"><use href="#icon-search"/></svg>
                        <input type="search" name="search" value="{{ $search }}" placeholder="Cari resep, kategori, pemilik..." aria-label="Cari resep, kategori, atau pemilik">
                    </div>
                    @if ($categoryId)<input type="hidden" name="category_id" value="{{ $categoryId }}">@endif
                    <button class="button" type="submit"><svg class="icon"><use href="#icon-search"/></svg><span class="search-button-label">Cari</span></button>
                </form>
                <a class="button secondary site-link" href="{{ route('home') }}" onclick="if (window.history.length > 1) { window.history.back(); return false; }">Kembali<svg class="icon"><use href="#icon-arrow"/></svg></a>
                <a class="button add-recipe-link" href="{{ route('recipes.create') }}"><svg class="icon"><use href="#icon-plus"/></svg><span>Tambah Resep</span></a>
            </div>
        </header>

        <main class="page-content">
            <div class="content-stack">
                <section aria-label="Ringkasan statistik">
                    <div class="section-heading">
                        <h2 class="section-title">Ringkasan Platform</h2>
                        <span class="today-label">Data terkini dari aplikasi</span>
                    </div>
                    <div class="stats-primary">
                        <article class="stat-card">
                            <div class="stat-top"><div><p class="stat-label">Pengguna</p><p class="stat-value">{{ number_format($usersCount) }}</p></div><span class="stat-icon"><svg class="icon"><use href="#icon-users"/></svg></span></div>
                            <div class="stat-footer"><span>Akun terdaftar</span><span class="stat-pill">Semua pengguna</span></div>
                        </article>
                        <article class="stat-card">
                            <div class="stat-top"><div><p class="stat-label">Resep</p><p class="stat-value">{{ number_format($recipesCount) }}</p></div><span class="stat-icon"><svg class="icon"><use href="#icon-book"/></svg></span></div>
                            <div class="stat-footer"><span>Resep dalam aplikasi</span><span class="stat-pill">Koleksi resep</span></div>
                        </article>
                        <article class="stat-card">
                            <div class="stat-top"><div><p class="stat-label">Kategori</p><p class="stat-value">{{ number_format($categoriesCount) }}</p></div><span class="stat-icon"><svg class="icon"><use href="#icon-layers"/></svg></span></div>
                            <div class="stat-footer"><span>Kelompok hidangan</span><span class="stat-pill">Kategori resep</span></div>
                        </article>
                        <article class="stat-card">
                            <div class="stat-top"><div><p class="stat-label">Rating &amp; komentar</p><p class="stat-value">{{ number_format($reviewsCount) }}</p></div><span class="stat-icon"><svg class="icon"><use href="#icon-message"/></svg></span></div>
                            <div class="stat-footer"><span>Ulasan dari pengguna</span><span class="stat-pill {{ $pendingReviewsCount > 0 ? 'warning' : '' }}">{{ number_format($pendingReviewsCount) }} disembunyikan</span></div>
                        </article>
                    </div>
                    <div class="stats-secondary">
                        <article class="stat-card">
                            <div class="stat-top"><div><p class="stat-label">Favorit &amp; like</p><p class="stat-value">{{ number_format($favoritesCount) }}</p></div><span class="stat-icon"><svg class="icon"><use href="#icon-heart"/></svg></span></div>
                            <div class="stat-footer"><span>Apresiasi pada resep</span><span class="stat-pill">Interaksi</span></div>
                        </article>
                        <article class="stat-card">
                            <div class="stat-top"><div><p class="stat-label">Menu mingguan</p><p class="stat-value">{{ number_format($mealPlansCount) }}</p></div><span class="stat-icon"><svg class="icon"><use href="#icon-calendar"/></svg></span></div>
                            <div class="stat-footer"><span>Rencana memasak tersimpan</span><span class="stat-pill">Perencana menu</span></div>
                        </article>
                        <article class="stat-card">
                            <div class="stat-top"><div><p class="stat-label">Aktivitas mengikuti</p><p class="stat-value">{{ number_format($followsCount) }}</p></div><span class="stat-icon"><svg class="icon"><use href="#icon-follow"/></svg></span></div>
                            <div class="stat-footer"><span>Relasi antar pengguna</span><span class="stat-pill">Komunitas</span></div>
                        </article>
                    </div>
                </section>

                <section class="panel" aria-label="Akses cepat">
                    <div class="panel-head">
                        <div><h2 class="panel-title">Akses Cepat Modul</h2><p class="panel-subtitle">Pintasan ke halaman pengelolaan Racikara</p></div>
                    </div>
                    <div class="quick-links">
                        <a class="quick-link" href="{{ route('admin.recipes.index') }}"><svg class="icon"><use href="#icon-utensils"/></svg>Kelola Resep</a>
                        <a class="quick-link" href="{{ route('admin.categories.index') }}"><svg class="icon"><use href="#icon-tags"/></svg>Kelola Kategori</a>
                        <a class="quick-link" href="{{ route('admin.users.index') }}"><svg class="icon"><use href="#icon-users"/></svg>Kelola Pengguna</a>
                        <a class="quick-link" href="{{ route('admin.reviews.index') }}">
                            <svg class="icon"><use href="#icon-message"/></svg>Moderasi Komentar
                            @if ($pendingReviewsCount > 0)
                                <span class="quick-count">{{ $pendingReviewsCount }}</span>
                            @endif
                        </a>
                        <a class="quick-link" href="{{ route('about') }}#team"><svg class="icon"><use href="#icon-shield"/></svg>Kelola Anggota Tim</a>
                        <a class="quick-link" href="{{ route('admin.notifications.index') }}"><svg class="icon"><use href="#icon-bell"/></svg>Notifikasi</a>
                        <a class="quick-link" href="{{ route('admin.activities.index') }}"><svg class="icon"><use href="#icon-activity"/></svg>Aktivitas Pengguna</a>
                        <a class="quick-link" href="{{ route('admin.settings.edit') }}"><svg class="icon"><use href="#icon-settings"/></svg>Pengaturan Aplikasi</a>
                    </div>
                </section>

                <section class="panel" aria-label="Resep terbaru">
                    <div class="panel-head">
                        <div>
                            <h2 class="panel-title">Resep terbaru</h2>
                            <p class="panel-subtitle">Daftar resep yang baru ditambahkan ke Racikara</p>
                        </div>
                        <form class="recipe-tools" method="GET" action="{{ route('admin.dashboard') }}">
                            <label class="sr-only" for="category-filter">Filter kategori</label>
                            <select class="filter-select" id="category-filter" name="category_id">
                                <option value="">Semua kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="search" value="{{ $search }}">
                            <button class="button secondary" type="submit">Filter</button>
                            @if ($search !== '' || $categoryId !== null)
                                <a class="button secondary" href="{{ route('admin.dashboard') }}">Reset</a>
                            @endif
                        </form>
                    </div>
                    @if ($latestRecipes->isNotEmpty())
                        <div class="table-scroll">
                            <table class="recipe-table">
                                <thead>
                                    <tr><th>Resep</th><th>Kategori</th><th>Pemilik</th><th>Dibuat</th><th style="text-align:right">Aksi</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestRecipes as $recipe)
                                        @php
                                            $photo = $recipe->foto ?: ($imageMap[$recipe->nama_resep] ?? null);
                                            $photoUrl = $photo
                                                ? (str_starts_with($photo, 'images/') ? asset($photo) : asset('storage/'.ltrim($photo, '/')))
                                                : null;
                                            $ownerName = $recipe->user?->name ?? 'Pengguna dihapus';
                                            $ownerInitials = collect(preg_split('/\s+/', trim($ownerName)))
                                                ->filter()
                                                ->take(2)
                                                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                                ->implode('');
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="recipe-cell">
                                                    <span class="recipe-thumb">@if ($photoUrl)<img src="{{ $photoUrl }}" alt="{{ $recipe->nama_resep }}">@else🍲@endif</span>
                                                    <span><a class="recipe-name" href="{{ route('recipes.show', $recipe) }}">{{ $recipe->nama_resep }}</a><span class="recipe-meta">{{ $recipe->waktu_masak }} menit · {{ $recipe->porsi }} porsi</span></span>
                                                </div>
                                            </td>
                                            <td><span class="category-pill">{{ $recipe->category?->nama_kategori ?? 'Tanpa kategori' }}</span></td>
                                            <td><span class="owner-cell"><span class="owner-avatar">{{ $ownerInitials ?: '?' }}</span>{{ $ownerName }}</span></td>
                                            <td>{{ $recipe->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="recipe-actions">
                                                    <a class="icon-button" href="{{ route('recipes.show', $recipe) }}" title="Lihat resep" aria-label="Lihat {{ $recipe->nama_resep }}"><svg class="icon"><use href="#icon-eye"/></svg></a>
                                                    <a class="icon-button" href="{{ route('recipes.edit', $recipe) }}" title="Edit resep" aria-label="Edit {{ $recipe->nama_resep }}"><svg class="icon"><use href="#icon-edit"/></svg></a>
                                                    <form method="POST" action="{{ route('admin.recipes.destroy', $recipe) }}" onsubmit="return confirm('Hapus resep {{ $recipe->nama_resep }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="icon-button" type="submit" title="Hapus resep" aria-label="Hapus {{ $recipe->nama_resep }}"><svg class="icon"><use href="#icon-trash"/></svg></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="table-footer">
                            <span>Menampilkan {{ $latestRecipes->firstItem() }}–{{ $latestRecipes->lastItem() }} dari {{ $latestRecipes->total() }} resep</span>
                            <span>Racikara Culinary CMS · v1.0</span>
                        </div>
                        @if ($latestRecipes->hasPages())
                            <div class="pagination-wrap">{{ $latestRecipes->links() }}</div>
                        @endif
                    @else
                        <div class="empty-state">
                            <span class="empty-icon"><svg class="icon"><use href="#icon-empty"/></svg></span>
                            <strong>Resep tidak ditemukan</strong>
                            <p>Coba ubah kata kunci pencarian atau filter kategori.</p>
                        </div>
                    @endif
                </section>

                <section class="panel" aria-label="Aktivitas pengguna terbaru">
                    <div class="panel-head">
                        <div><h2 class="panel-title">Aktivitas terbaru</h2><p class="panel-subtitle">Perubahan terakhir yang tercatat di aplikasi</p></div>
                        <a class="button secondary" href="{{ route('admin.activities.index') }}">Lihat semua<svg class="icon"><use href="#icon-arrow"/></svg></a>
                    </div>
                    @if ($latestActivities->isNotEmpty())
                        <div class="activity-list">
                            @foreach ($latestActivities as $activity)
                                <div class="activity-row">
                                    <span class="activity-user">{{ $activity->user?->name ?? 'Sistem/pengguna dihapus' }}</span>
                                    <span class="activity-description">{{ $activity->description }}</span>
                                    <time class="activity-time" datetime="{{ $activity->created_at->toIso8601String() }}">{{ $activity->created_at->diffForHumans() }}</time>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state"><strong>Belum ada aktivitas tercatat</strong><p>Aktivitas pengguna akan tampil di sini.</p></div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>
<script>
    (() => {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const openButton = document.querySelector('[data-sidebar-open]');
        const closeButtons = document.querySelectorAll('[data-sidebar-close]');

        const setSidebarOpen = (isOpen) => {
            sidebar.classList.toggle('is-open', isOpen);
            backdrop.hidden = !isOpen;
            document.body.style.overflow = isOpen ? 'hidden' : '';
            if (openButton) {
                openButton.setAttribute('aria-expanded', String(isOpen));
            }
        };

        openButton?.addEventListener('click', () => setSidebarOpen(true));
        backdrop.addEventListener('click', () => setSidebarOpen(false));
        closeButtons.forEach((button) => button.addEventListener('click', () => setSidebarOpen(false)));
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setSidebarOpen(false);
            }
        });
    })();
</script>
</body>
</html>
