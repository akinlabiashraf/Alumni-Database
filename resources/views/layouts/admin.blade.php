<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>@yield('title', 'Admin Portal — Summit University, Offa')</title>

  <!-- Favicon & PWA -->
  <link rel="shortcut icon" href="{{ asset('assets/img/summit/Summit university Logo.webp') }}" type="image/webp">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#006837">

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/icons/css/icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/animate/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/summit-brand.css') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --admin-sidebar-w: 260px;
      --admin-primary: #006837;
      --admin-primary-dark: #004d28;
      --admin-gold: #D4AF37;
      --admin-bg: #f8fafc;
      --admin-card-bg: #ffffff;
      --admin-border: #e2e8f0;
      --admin-text-main: #0f172a;
      --admin-text-muted: #64748b;
    }
    body {
      background-color: var(--admin-bg);
      font-family: 'Poppins', sans-serif;
      color: var(--admin-text-main);
      overflow-x: hidden;
    }
    .admin-layout-wrapper {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }
    /* Sidebar */
    .admin-sidebar {
      width: var(--admin-sidebar-w);
      background: #0f172a;
      color: #ffffff;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 1000;
      transition: transform 0.3s ease;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
    }
    .admin-sidebar-brand {
      padding: 22px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      background: #020617;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      text-decoration: none;
    }
    .admin-sidebar-brand img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }
    .admin-sidebar-brand-title {
      font-size: 15px;
      font-weight: 700;
      color: #ffffff;
      line-height: 1.2;
    }
    .admin-sidebar-brand-sub {
      font-size: 11px;
      color: var(--admin-gold);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .admin-sidebar-nav {
      list-style: none;
      padding: 18px 12px;
      margin: 0;
      flex-grow: 1;
      overflow-y: auto;
    }
    .admin-nav-header {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #94a3b8;
      padding: 10px 14px 6px;
      font-weight: 700;
    }
    .admin-nav-item a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 14px;
      color: #cbd5e1;
      font-size: 13px;
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.2s ease;
      text-decoration: none;
      margin-bottom: 4px;
      position: relative;
    }
    .admin-nav-item a:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #ffffff;
    }
    .admin-nav-item.active a {
      background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
      color: #ffffff;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(0, 104, 55, 0.3);
    }
    .admin-nav-item a i {
      font-size: 16px;
      width: 20px;
      text-align: center;
      color: #94a3b8;
      transition: color 0.2s ease;
    }
    .admin-nav-item.active a i,
    .admin-nav-item a:hover i {
      color: #ffffff;
    }
    .admin-badge-count {
      margin-left: auto;
      background: #ef4444;
      color: #ffffff;
      font-size: 11px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 12px;
    }
    .admin-sidebar-footer {
      padding: 16px;
      background: #020617;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .admin-user-card {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .admin-user-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--admin-primary);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 15px;
      border: 2px solid var(--admin-gold);
    }
    .admin-user-info {
      flex-grow: 1;
      overflow: hidden;
    }
    .admin-user-name {
      font-size: 13px;
      font-weight: 600;
      color: #ffffff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .admin-user-role {
      font-size: 11px;
      color: var(--admin-gold);
      font-weight: 500;
      text-transform: capitalize;
    }

    /* Main Content Wrapper */
    .admin-main-wrapper {
      flex-grow: 1;
      margin-left: var(--admin-sidebar-w);
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    /* Top Header */
    .admin-topbar {
      height: 70px;
      background: #ffffff;
      border-bottom: 1px solid var(--admin-border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 30px;
      position: sticky;
      top: 0;
      z-index: 99;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }
    .admin-topbar-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .admin-sidebar-toggle {
      background: none;
      border: none;
      font-size: 20px;
      color: var(--admin-text-main);
      cursor: pointer;
      display: none;
    }
    .admin-topbar-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--admin-primary-dark);
      margin: 0;
    }
    .admin-topbar-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .btn-topbar-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 600;
      color: var(--admin-text-muted);
      background: #f1f5f9;
      padding: 7px 14px;
      border-radius: 6px;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .btn-topbar-link:hover {
      background: #e2e8f0;
      color: var(--admin-text-main);
    }
    .btn-topbar-logout {
      background: #fee2e2;
      color: #dc2626;
    }
    .btn-topbar-logout:hover {
      background: #fecaca;
      color: #b91c1c;
    }
    .admin-content-body {
      padding: 30px;
      flex-grow: 1;
    }

    /* Standard Cards & Tables */
    .admin-card {
      background: #ffffff;
      border-radius: 12px;
      border: 1px solid var(--admin-border);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
      padding: 24px;
      margin-bottom: 25px;
    }
    .admin-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--admin-border);
    }
    .admin-card-title {
      font-size: 16px;
      font-weight: 700;
      color: var(--admin-primary-dark);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .admin-table-responsive {
      overflow-x: auto;
    }
    .admin-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    .admin-table th {
      background: #f8fafc;
      color: #475569;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 12px 16px;
      border-bottom: 2px solid var(--admin-border);
      white-space: nowrap;
    }
    .admin-table td {
      padding: 14px 16px;
      font-size: 13px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
      color: #334155;
    }
    .admin-table tr:hover td {
      background: #f8fafc;
    }

    /* Badges */
    .badge-status {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      text-transform: capitalize;
    }
    .badge-claimed, .badge-active, .badge-approved {
      background: #dcfce7;
      color: #15803d;
    }
    .badge-unclaimed, .badge-pending {
      background: #fef3c7;
      color: #b45309;
    }
    .badge-rejected, .badge-suspended {
      background: #fee2e2;
      color: #dc2626;
    }
    .badge-role-admin {
      background: #ede9fe;
      color: #6d28d9;
    }
    .badge-role-moderator {
      background: #e0f2fe;
      color: #0369a1;
    }
    .badge-role-alumnus {
      background: #f0fdf4;
      color: #166534;
    }

    /* Buttons */
    .btn-admin-primary {
      background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-primary-dark) 100%);
      color: #ffffff;
      border: none;
      padding: 9px 18px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
    }
    .btn-admin-primary:hover {
      box-shadow: 0 4px 12px rgba(0, 104, 55, 0.3);
      color: #ffffff;
      transform: translateY(-1px);
    }
    .btn-admin-gold {
      background: linear-gradient(135deg, #e5b83b 0%, #c99c27 100%);
      color: #1e293b;
      border: none;
      padding: 9px 18px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
    }
    .btn-admin-gold:hover {
      box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
      color: #1e293b;
    }
    .btn-admin-outline {
      background: transparent;
      border: 1px solid var(--admin-border);
      color: #475569;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-admin-outline:hover {
      background: #f1f5f9;
      color: #0f172a;
    }

    @media (max-width: 991px) {
      .admin-sidebar {
        transform: translateX(-100%);
      }
      .admin-sidebar.open {
        transform: translateX(0);
      }
      .admin-main-wrapper {
        margin-left: 0;
      }
      .admin-sidebar-toggle {
        display: block;
      }
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="admin-layout-wrapper">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
      <a href="{{ url('/admin/dashboard') }}" class="admin-sidebar-brand">
        <img src="{{ asset('assets/img/summit/Summit university Logo.webp') }}" alt="Summit University Logo">
        <div>
          <div class="admin-sidebar-brand-title">Summit University</div>
          <div class="admin-sidebar-brand-sub">Admin Directorate</div>
        </div>
      </a>

      <ul class="admin-sidebar-nav">
        <li class="admin-nav-header">Analytics & Overview</li>
        <li class="admin-nav-item {{ request()->is('admin/dashboard') || request()->is('admin') ? 'active' : '' }}">
          <a href="{{ url('/admin/dashboard') }}">
            <i class="fa fa-tachometer"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <li class="admin-nav-header">Alumni Records & Verification</li>
        <li class="admin-nav-item {{ request()->is('admin/records*') ? 'active' : '' }}">
          <a href="{{ url('/admin/records') }}">
            <i class="fa fa-graduation-cap"></i>
            <span>Master Records & CSV</span>
          </a>
        </li>
        <li class="admin-nav-item {{ request()->is('admin/verifications*') ? 'active' : '' }}">
          <a href="{{ url('/admin/verifications') }}">
            <i class="fa fa-id-card-o"></i>
            <span>Manual Queue</span>
            <span class="admin-badge-count" id="sidebarPendingBadge" style="display: none;">0</span>
          </a>
        </li>

        <li class="admin-nav-header">System & Access</li>
        <li class="admin-nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
          <a href="{{ url('/admin/users') }}">
            <i class="fa fa-users"></i>
            <span>User Accounts</span>
          </a>
        </li>
        <li class="admin-nav-item {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
          <a href="{{ url('/admin/audit-logs') }}">
            <i class="fa fa-shield"></i>
            <span>Security & Audit</span>
          </a>
        </li>

        <li class="admin-nav-header">Quick Navigation</li>
        <li class="admin-nav-item">
          <a href="{{ url('/dashboard') }}">
            <i class="fa fa-user-circle"></i>
            <span>My Alumni Dashboard</span>
          </a>
        </li>
        <li class="admin-nav-item">
          <a href="{{ url('/') }}" target="_blank">
            <i class="fa fa-external-link"></i>
            <span>View Public Site</span>
          </a>
        </li>
      </ul>

      <div class="admin-sidebar-footer">
        <div class="admin-user-card">
          <div class="admin-user-avatar" id="adminUserInitial">A</div>
          <div class="admin-user-info">
            <div class="admin-user-name" id="adminUserName">Administrator</div>
            <div class="admin-user-role" id="adminUserRole">Super Admin</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main-wrapper">
      <header class="admin-topbar">
        <div class="admin-topbar-left">
          <button class="admin-sidebar-toggle" id="sidebarToggleBtn" aria-label="Toggle Sidebar">
            <i class="fa fa-bars"></i>
          </button>
          <h1 class="admin-topbar-title">@yield('page_title', 'Directorate Control Center')</h1>
        </div>

        <div class="admin-topbar-right">
          <a href="{{ url('/directory') }}" class="btn-topbar-link hidden-xs" target="_blank">
            <i class="fa fa-search"></i> Directory View
          </a>
          <a href="javascript:void(0)" class="btn-topbar-link btn-topbar-logout summit-admin-logout">
            <i class="fa fa-sign-out"></i> Log Out
          </a>
        </div>
      </header>

      <main class="admin-content-body">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Core Scripts -->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/modules/api.js') }}"></script>
  <script src="{{ asset('assets/js/modules/auth.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      // Toggle sidebar on mobile
      const toggleBtn = document.getElementById('sidebarToggleBtn');
      const sidebar = document.getElementById('adminSidebar');
      if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
          sidebar.classList.toggle('open');
        });
      }

      // Check authentication and verify admin/moderator role
      if (!SummitAuth.isAuthenticated()) {
        window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
        return;
      }

      const res = await SummitAPI.get('/me');
      if (!res.success || !res.user) {
        window.location.href = '/login';
        return;
      }

      const user = res.user;
      if (user.role !== 'admin' && user.role !== 'moderator') {
        alert('Access denied. Administrator or Moderator privileges are required to access this portal.');
        window.location.href = '/dashboard';
        return;
      }

      // Populate user info in sidebar
      const nameEl = document.getElementById('adminUserName');
      const roleEl = document.getElementById('adminUserRole');
      const initialEl = document.getElementById('adminUserInitial');
      if (nameEl) nameEl.textContent = user.name;
      if (roleEl) roleEl.textContent = user.role.toUpperCase();
      if (initialEl && user.name) initialEl.textContent = user.name.charAt(0).toUpperCase();

      // Logout handler
      document.querySelectorAll('.summit-admin-logout').forEach(btn => {
        btn.addEventListener('click', async (e) => {
          e.preventDefault();
          if (confirm('Are you sure you want to log out of the admin portal?')) {
            await SummitAuth.logout();
            window.location.href = '/login';
          }
        });
      });

      // Fetch pending verifications count for badge
      try {
        const dashRes = await SummitAPI.get('/admin/dashboard');
        if (dashRes.success && dashRes.metrics) {
          const count = dashRes.metrics.pending_verifications;
          const badge = document.getElementById('sidebarPendingBadge');
          if (badge && count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
          }
        }
      } catch (err) {
        console.error('Failed to load pending badge:', err);
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
