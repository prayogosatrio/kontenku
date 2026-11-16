<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KontenKu') - KontenKu</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; color: #1f2937; background: #f8fafc; line-height: 1.5; }
        a { color: #4338ca; }
        h1 { margin: 0 0 4px; font-size: 26px; }
        h2 { margin-top: 32px; font-size: 19px; }
        .subtitle { color: #6b7280; margin: 0 0 20px; }

        /* Navbar */
        body > nav { background: #312e81; padding: 12px 32px; display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
        body > nav .brand { color: #fff; font-weight: bold; font-size: 18px; text-decoration: none; }
        body > nav ul { list-style: none; display: flex; gap: 4px; margin: 0; padding: 0; flex: 1; flex-wrap: wrap; }
        body > nav ul li a { color: #c7d2fe; text-decoration: none; padding: 6px 12px; border-radius: 6px; display: block; }
        body > nav ul li a:hover { background: #3730a3; color: #fff; }
        body > nav ul li a.active { background: #4f46e5; color: #fff; font-weight: bold; }
        body > nav .navbar-user { display: flex; align-items: center; gap: 12px; color: #c7d2fe; font-size: 14px; }
        body > nav .btn-logout { background: none; border: 1px solid #a5b4fc; color: #e0e7ff; padding: 4px 12px; border-radius: 6px; cursor: pointer; font-size: 14px; }
        body > nav .btn-logout:hover { background: #4338ca; color: #fff; }

        main { max-width: 1040px; margin: 0 auto; padding: 28px 32px 48px; }
        footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb; }

        /* Alert */
        .alert { padding: 10px 14px; border-radius: 6px; margin-bottom: 18px; border: 1px solid; }
        .alert-success { background: #d1fae5; color: #065f46; border-color: #6ee7b7; }
        .alert-error { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .alert-info { background: #fef3c7; color: #92400e; border-color: #fcd34d; }

        /* Tombol */
        .btn { display: inline-block; padding: 7px 14px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-family: inherit; }
        .btn:hover { background: #4338ca; }
        .btn-secondary { background: #fff; color: #374151; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background: #f3f4f6; }
        .btn-small { padding: 3px 10px; font-size: 13px; }
        .btn-link { background: none; border: none; padding: 0; color: #b91c1c; cursor: pointer; font-size: inherit; font-family: inherit; text-decoration: underline; }
        form.inline { display: inline; }
        .toolbar { display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap; margin-bottom: 14px; }
        .filter { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .filter input, .filter select { padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit; }

        /* Tabel */
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #e5e7eb; padding: 9px 12px; text-align: left; vertical-align: top; font-size: 14px; }
        thead th { background: #f1f5f9; }
        table.info th { width: 190px; background: #f1f5f9; }
        .aksi { white-space: nowrap; }
        .muted { color: #6b7280; font-size: 13px; }

        /* Badge status: teksnya selalu tertulis, jadi tidak bergantung pada warna saja */
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; white-space: nowrap; }
        .badge-draft { background: #e5e7eb; color: #374151; }
        .badge-dijadwalkan, .badge-berjalan { background: #fef3c7; color: #92400e; }
        .badge-tayang, .badge-selesai, .badge-aktif { background: #d1fae5; color: #065f46; }
        .badge-nonaktif { background: #fee2e2; color: #991b1b; }

        /* Form */
        form.form-box { max-width: 560px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 24px 24px; }
        form.form-box label { display: block; margin-top: 14px; font-weight: bold; font-size: 14px; }
        form.form-box input, form.form-box select, form.form-box textarea { width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit; }
        form.form-box .hint { color: #6b7280; font-size: 13px; margin-top: 4px; }
        form.form-box .actions { margin-top: 22px; display: flex; gap: 10px; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }

        /* Kartu statistik dashboard */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 20px; }
        .stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; }
        .stat-card .value { font-size: 34px; font-weight: bold; color: #312e81; }
        .stat-card .label { color: #6b7280; font-size: 14px; }

        /* Pagination (view custom: resources/views/vendor/pagination/custom.blade.php) */
        .pagination { margin-top: 16px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; font-size: 14px; }
        .pagination a { text-decoration: none; }
        .pagination a:hover { text-decoration: underline; }
        .pagination .pagination-current { font-weight: bold; color: #312e81; }
        .pagination .pagination-disabled { color: #9ca3af; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <main>
        @include('partials.alert')

        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} KontenKu - Sistem Manajemen Kalender Konten Media Sosial
    </footer>
</body>
</html>
