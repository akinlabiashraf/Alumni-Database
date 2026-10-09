<!-- ======================= Start Navigation ===================== -->
<nav class="navbar navbar-default navbar-mobile navbar-fixed white no-background bootsnav">
  <div class="container"> 
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
        <i class="fa fa-bars"></i>
      </button>
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('assets/img/summit/Summit university Logo.webp') }}" class="summit-navbar-logo" alt="Summit University Logo">
        <div class="summit-navbar-text hidden-xs">
          <span class="summit-navbar-title">Summit University</span>
          <span class="summit-navbar-subtitle">Alumni Network & Directory</span>
        </div>
      </a> 
    </div>

    <div class="collapse navbar-collapse" id="navbar-menu">
      <ul class="nav navbar-nav navbar-left" data-in="fadeInDown" data-out="fadeOutUp">
        <li class="{{ request()->is('/') ? 'active' : '' }}">
          <a href="{{ url('/') }}">Home</a>
        </li>
        <li class="{{ request()->is('directory*') ? 'active' : '' }}">
          <a href="{{ url('/directory') }}">Alumni Directory</a>
        </li>
        <li class="{{ request()->is('about*') ? 'active' : '' }}">
          <a href="{{ url('/about') }}">About Directorate</a>
        </li>
        <li class="{{ request()->is('features*') ? 'active' : '' }}">
          <a href="{{ url('/features') }}">Features</a>
        </li>
        <li class="{{ request()->is('how-it-works*') ? 'active' : '' }}">
          <a href="{{ url('/how-it-works') }}">How It Works</a>
        </li>
        <li class="{{ request()->is('contact*') ? 'active' : '' }}">
          <a href="{{ url('/contact') }}">Contact</a>
        </li>
      </ul>

      <!-- Right Action Buttons -->
      <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
        <!-- Guest State (Unauthenticated) -->
        <li class="summit-nav-guest">
          <a href="javascript:void(0)" data-toggle="modal" data-target="#summitLoginModal" class="summit-btn-signin">
            <i class="fa fa-sign-in"></i> Sign In
          </a>
        </li>
        <li class="summit-nav-guest" style="padding-top: 20px; padding-bottom: 20px;">
          <a href="{{ url('/register') }}" class="btn-summit-primary" style="padding: 9px 18px !important; margin-left: 10px; border-radius: 6px;">
            <i class="fa fa-check-circle"></i> Verify & Register
          </a>
        </li>

        <!-- Authenticated State (Logged In) -->
        <li class="dropdown summit-nav-auth" style="display: none;">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-user-circle fa-lg" style="color: var(--summit-green);"></i>
            <span class="summit-user-name">Alumnus</span>
            <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown-menu animated fadeOutUp">
            <li><a href="{{ url('/dashboard') }}"><i class="fa fa-tachometer"></i> My Dashboard</a></li>
            <li class="summit-nav-admin-link" style="display: none;"><a href="{{ url('/admin/dashboard') }}" style="color: #b45309;"><i class="fa fa-cogs" style="color: #b45309;"></i> Admin Portal</a></li>
            <li><a href="{{ url('/profile-settings') }}"><i class="fa fa-user"></i> Profile Settings</a></li>
            <li><a href="{{ url('/directory') }}"><i class="fa fa-users"></i> Search Classmates</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="javascript:void(0)" class="summit-btn-logout" style="color: #dc2626;"><i class="fa fa-sign-out"></i> Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- ======================= End Navigation ===================== -->
