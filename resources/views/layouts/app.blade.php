<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KasirQ') — Aplikasi Kasir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-primary: #0f1117;
            --bg-secondary: #1a1d27;
            --bg-card: #1e2130;
            --bg-hover: #252840;
            --border: #2d3150;
            --accent: #6366f1;
            --accent-hover: #5254cc;
            --accent-glow: rgba(99,102,241,0.3);
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --text-primary: #f0f2ff;
            --text-secondary: #8b92b8;
            --text-muted: #555e8a;
            --sidebar-w: 240px;
            --topbar-h: 64px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }

        .sidebar-brand .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 800; color: #fff;
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        .sidebar-brand span {
            font-size: 18px; font-weight: 700;
            background: linear-gradient(135deg, #e0e0ff, #a5b4fc);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }

        .nav-section-title {
            font-size: 10px; font-weight: 600; letter-spacing: 1px;
            color: var(--text-muted); text-transform: uppercase;
            padding: 8px 12px; margin-top: 8px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            color: var(--text-secondary); text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: all .2s ease;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(139,92,246,0.1));
            color: #a5b4fc;
            border: 1px solid rgba(99,102,241,0.3);
        }

        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: var(--bg-hover);
            margin-bottom: 8px;
        }

        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }

        .user-info { overflow: hidden; }
        .user-name { font-size: 13px; font-weight: 600; truncate; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: var(--text-muted); }

        .btn-logout {
            display: flex; align-items: center; gap: 8px;
            width: 100%; padding: 9px 12px; border-radius: 10px;
            background: transparent; border: 1px solid var(--border);
            color: var(--text-secondary); font-size: 13px; font-weight: 500;
            cursor: pointer; transition: all .2s; text-align: left;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.1); color: var(--danger); border-color: rgba(239,68,68,0.3); }

        /* ── Main ── */
        .main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        .topbar {
            height: var(--topbar-h);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; position: sticky; top: 0; z-index: 50;
        }

        .topbar-title { font-size: 18px; font-weight: 700; }

        .page-content { padding: 28px; flex: 1; }

        /* ── Cards ── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
        }

        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title { font-size: 16px; font-weight: 600; }

        /* ── Stat cards ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 28px; }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 20px;
            position: relative; overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.3); }

        .stat-card::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 80px; height: 80px; border-radius: 50%;
            opacity: .12;
        }

        .stat-card.indigo::before { background: var(--accent); }
        .stat-card.green::before { background: var(--success); }
        .stat-card.amber::before { background: var(--warning); }
        .stat-card.blue::before { background: var(--info); }

        .stat-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 12px;
        }

        .stat-icon.indigo { background: rgba(99,102,241,0.15); }
        .stat-icon.green { background: rgba(16,185,129,0.15); }
        .stat-icon.amber { background: rgba(245,158,11,0.15); }
        .stat-icon.blue { background: rgba(59,130,246,0.15); }

        .stat-label { font-size: 12px; color: var(--text-secondary); margin-bottom: 4px; font-weight: 500; }
        .stat-value { font-size: 22px; font-weight: 700; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 16px; border-radius: 10px; border: none;
            font-size: 14px; font-weight: 500; cursor: pointer;
            text-decoration: none; transition: all .2s;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-hover); box-shadow: 0 4px 12px var(--accent-glow); }
        .btn-secondary { background: var(--bg-hover); color: var(--text-primary); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--border); }
        .btn-danger { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger:hover { background: rgba(239,68,68,0.2); }
        .btn-success { background: rgba(16,185,129,0.1); color: var(--success); border: 1px solid rgba(16,185,129,0.2); }
        .btn-success:hover { background: rgba(16,185,129,0.2); }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }

        /* ── Form ── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 10px 14px; border-radius: 10px;
            background: var(--bg-hover); border: 1px solid var(--border);
            color: var(--text-primary); font-size: 14px; font-family: inherit;
            transition: border-color .2s;
            outline: none;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
        .form-control::placeholder { color: var(--text-muted); }

        /* ── Table ── */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left; font-size: 12px; font-weight: 600;
            color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px;
            padding: 10px 16px; border-bottom: 1px solid var(--border);
        }
        td {
            padding: 14px 16px; font-size: 14px;
            border-bottom: 1px solid rgba(45,49,80,.5);
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg-hover); }

        /* ── Badge ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .badge-success { background: rgba(16,185,129,.15); color: #34d399; }
        .badge-danger { background: rgba(239,68,68,.15); color: #f87171; }
        .badge-warning { background: rgba(245,158,11,.15); color: #fbbf24; }
        .badge-info { background: rgba(59,130,246,.15); color: #60a5fa; }
        .badge-purple { background: rgba(139,92,246,.15); color: #a78bfa; }
        .badge-gray { background: rgba(107,114,128,.15); color: #9ca3af; }

        /* ── Alert ── */
        .alert {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
            font-size: 14px; animation: slideIn .3s ease;
        }
        .alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.2); color: #34d399; }
        .alert-error { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2); color: #f87171; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 4px; align-items: center; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            display: flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; border-radius: 8px;
            font-size: 13px; font-weight: 500; text-decoration: none;
            padding: 0 8px;
        }
        .pagination a { background: var(--bg-hover); color: var(--text-secondary); border: 1px solid var(--border); }
        .pagination a:hover { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination span.active { background: var(--accent); color: #fff; border: 1px solid var(--accent); }
        .pagination span.disabled { color: var(--text-muted); background: var(--bg-hover); border: 1px solid var(--border); opacity: .5; }

        /* ── Misc ── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .text-sm { font-size: 13px; }
        .text-xs { font-size: 11px; }
        .text-muted { color: var(--text-secondary); }
        .font-semibold { font-weight: 600; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .text-green { color: var(--success); }
        .text-red { color: var(--danger); }
        .text-accent { color: var(--accent); }
        .text-right { text-align: right; }
        .w-full { width: 100%; }
        .mt-auto { margin-top: auto; }
        select.form-control option { background: var(--bg-card); }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
        }
        /* Pagination */
        .custom-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
        }
        @media (max-width: 640px) {
            .custom-pagination { flex-direction: column; gap: 16px; }
        }
        .custom-pagination .page-numbers {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .custom-pagination .page-link {
            padding: 8px 14px;
            border-radius: 8px;
            background: var(--bg-hover);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            border: 1px solid var(--border);
            cursor: pointer;
            display: inline-block;
        }
        .custom-pagination a.page-link:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }
        .custom-pagination .page-link.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }
        .custom-pagination .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon">K</div>
            <span>KasirQ</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('pos.index') }}" class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Kasir / POS
            </a>
            <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Transaksi
            </a>

            @if(auth()->user()->isAdmin())
            <div class="nav-section-title">Manajemen</div>
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Produk
            </a>
            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Pengguna
            </a>
            <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-2">
                <span class="text-sm text-muted">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
