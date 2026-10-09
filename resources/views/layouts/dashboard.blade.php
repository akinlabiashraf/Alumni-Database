<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>@yield('title', 'Alumni Portal Dashboard — Summit University, Offa')</title>

  <!-- Favicon & PWA -->
  <link rel="shortcut icon" href="{{ asset('assets/img/summit/Summit university Logo.webp') }}" type="image/webp">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#006837">

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/icons/css/icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/animate/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootsnav.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/summit-brand.css') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    .summit-dash-wrapper {
      background: #f8fafc;
      min-height: 100vh;
      padding-top: 80px;
    }
    .summit-sidebar {
      background: #ffffff;
      border-radius: var(--summit-radius-lg);
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      padding: 25px 20px;
      margin-bottom: 30px;
      border: 1px solid var(--summit-border);
    }
    .summit-sidebar-user {
      text-align: center;
      padding-bottom: 20px;
      border-bottom: 1px solid var(--summit-border);
      margin-bottom: 20px;
    }
    .summit-sidebar-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 3px solid var(--summit-green);
      object-fit: cover;
      margin: 0 auto 12px;
      display: block;
      background: var(--summit-green-light);
    }
    .summit-sidebar-menu {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .summit-sidebar-menu li a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      color: var(--summit-text);
      font-weight: 600;
      font-size: 13px;
      border-radius: var(--summit-radius);
      transition: all 0.2s ease;
      margin-bottom: 4px;
    }
    .summit-sidebar-menu li a:hover,
    .summit-sidebar-menu li.active a {
      background: var(--summit-green-light);
      color: var(--summit-green-dark);
    }
    .summit-sidebar-menu li a i {
      font-size: 16px;
      width: 20px;
      text-align: center;
      color: var(--summit-green);
    }
    .summit-dash-card {
      background: #ffffff;
      border-radius: var(--summit-radius-lg);
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      border: 1px solid var(--summit-border);
      margin-bottom: 30px;
    }
  </style>

  @stack('styles')
</head>

<body class="utf_skin_area">
  <!-- Navbar -->
  @include('partials.navbar')

  <div class="summit-dash-wrapper">
    <div class="container" style="padding-top: 30px; padding-bottom: 60px;">
      <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 col-sm-4">
          <div class="summit-sidebar">
            <div class="summit-sidebar-user">
              <img id="sidebarUserAvatar" src="{{ asset('assets/img/user-profile.png') }}" class="summit-sidebar-avatar" alt="User Avatar">
              <h5 class="summit-user-name" style="margin: 0 0 5px; font-weight: 700; color: var(--summit-green-dark);">Summit Alumnus</h5>
              <div id="sidebarUserRole" class="summit-badge-verified">
                <i class="fa fa-shield"></i> Verified Alumnus
              </div>
            </div>

            <ul class="summit-sidebar-menu">
              <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ url('/dashboard') }}"><i class="fa fa-tachometer"></i> Dashboard Overview</a>
              </li>
              <li class="{{ request()->is('profile-settings*') ? 'active' : '' }}">
                <a href="{{ url('/profile-settings') }}"><i class="fa fa-user"></i> Profile & Career</a>
              </li>
              <li class="{{ request()->is('directory*') ? 'active' : '' }}">
                <a href="{{ url('/directory') }}"><i class="fa fa-users"></i> Search Classmates</a>
              </li>
              <li class="{{ request()->is('privacy-settings*') ? 'active' : '' }}">
                <a href="{{ url('/privacy-settings') }}"><i class="fa fa-lock"></i> Privacy & NDPA</a>
              </li>
              <li class="{{ request()->is('change-password*') ? 'active' : '' }}">
                <a href="{{ url('/change-password') }}"><i class="fa fa-key"></i> Change Password</a>
              </li>
              
              <!-- Admin / Moderator Section -->
              <li id="sidebarAdminLink" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--summit-border);">
                <a href="{{ url('/admin/dashboard') }}" style="color: #b45309;"><i class="fa fa-cog" style="color: #b45309;"></i> Admin Portal</a>
              </li>

              <li style="margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--summit-border);">
                <a href="javascript:void(0)" class="summit-btn-logout" style="color: #dc2626;"><i class="fa fa-sign-out" style="color: #dc2626;"></i> Log Out</a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9 col-sm-8">
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <!-- Universal Master Footer -->
  @include('partials.footer')

  <!-- Core Scripts -->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootsnav.js') }}"></script>
  <script src="{{ asset('assets/js/modules/api.js') }}"></script>
  <script src="{{ asset('assets/js/modules/auth.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      // Client-side authentication check for dashboard views
      if (!SummitAuth.isAuthenticated()) {
        window.location.href = '/?login=required';
        return;
      }

      // Fetch user profile from /api/me
      const res = await SummitAPI.get('/me');
      if (res.success && res.user) {
        document.querySelectorAll('.summit-user-name').forEach(el => el.textContent = res.user.name);
        
        if (res.user.profile && res.user.profile.avatar) {
          const avatarEl = document.getElementById('sidebarUserAvatar');
          if (avatarEl) avatarEl.src = res.user.profile.avatar;
        }

        if (res.user.role === 'admin' || res.user.role === 'moderator') {
          const adminLink = document.getElementById('sidebarAdminLink');
          if (adminLink) adminLink.style.display = 'block';
        }
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
