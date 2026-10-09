<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="author" content="Summit University, Offa">
  <meta name="description" content="@yield('meta_description', 'Official Alumni Network and Graduate Verification Platform for Summit University, Offa, Kwara State, Nigeria.')">
  
  <title>@yield('title', 'Summit University Alumni Network & Directory — Offa')</title>

  <!-- Favicon & PWA -->
  <link rel="shortcut icon" href="{{ asset('assets/img/summit/Summit university Logo.webp') }}" type="image/webp">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#006837">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <!-- Core Plugins & Stylesheets -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap-select.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/icons/css/icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/animate/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootsnav.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/nice-select/css/nice-select.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/aos-master/aos.css') }}">
  
  <!-- Base Template Stylesheet -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
  
  <!-- Summit University Brand Design System -->
  <link rel="stylesheet" href="{{ asset('assets/css/summit-brand.css') }}">

  <!-- Google Fonts: Montserrat & Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @stack('styles')
</head>

<body class="utf_skin_area">
  <!-- Page Preloader -->
  <div class="page_preloader"></div>

  <!-- Universal Master Navbar -->
  @include('partials.navbar')

  <!-- Main View Content -->
  <main>
    @yield('content')
  </main>

  <!-- Universal Master Footer -->
  @include('partials.footer')

  <!-- Universal Login Modal -->
  @include('partials.login-modal')

  <!-- Scroll to Top -->
  <div><a href="#" class="scrollup"><i class="fa fa-angle-up"></i></a></div>

  <!-- Core Scripts -->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootsnav.js') }}"></script>
  <script src="{{ asset('assets/js/viewportchecker.js') }}"></script>
  <script src="{{ asset('assets/js/slick.js') }}"></script>
  <script src="{{ asset('assets/plugins/aos-master/aos.js') }}"></script>
  <script src="{{ asset('assets/plugins/nice-select/js/jquery.nice-select.min.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <!-- Client-Side API & Auth Modules -->
  <script src="{{ asset('assets/js/modules/api.js') }}"></script>
  <script src="{{ asset('assets/js/modules/auth.js') }}"></script>

  <script>
    $(window).on('load', function() {
      $(".page_preloader").fadeOut("slow");
    });
    if (typeof AOS !== 'undefined') {
      AOS.init({ duration: 800, once: true });
    }

    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW reg error:', err));
      });
    }
  </script>

  @stack('scripts')
</body>
</html>
