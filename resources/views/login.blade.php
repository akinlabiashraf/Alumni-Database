@extends('layouts.app')

@section('title', 'Sign In to Alumni Portal — Summit University, Offa')
@section('meta_description', 'Sign in to your Summit University Alumni account using your registered email address or official matriculation number.')

@section('content')
<!-- ======================= Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Senate Building.jpg') }}') center/cover no-repeat; padding: 130px 0 50px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 12px;">
      <i class="fa fa-lock"></i> Member Authentication
    </div>
    <h1 style="color: #ffffff; font-size: 32px; font-weight: 800; margin-bottom: 8px;">Sign In to Alumni Portal</h1>
    <p style="color: #cbd5e1; font-size: 15px; max-width: 600px; margin: 0 auto;">
      Access your member dashboard, directory networking, and personal privacy settings.
    </p>
  </div>
</section>

<!-- ======================= Login Box ===================== -->
<section style="background: #f8fafc; padding: 60px 0 100px;">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12">
        
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 40px 35px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); border: 1px solid var(--summit-border); border-top: 5px solid var(--summit-green);">
          
          <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ asset('assets/img/summit/Summit university Logo.webp') }}" alt="Summit Logo" style="height: 60px; margin-bottom: 15px;">
            <h3 style="font-size: 22px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 6px;">Welcome Back</h3>
            <p style="font-size: 13px; color: var(--summit-muted); margin: 0;">Sign in with your Email or official Matriculation Number</p>
          </div>

          <div id="pageLoginAlert" style="display: none; padding: 14px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;"></div>

          <form id="pageLoginForm">
            <div class="form-group" style="margin-bottom: 20px;">
              <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                Email Address or Matric Number <span style="color: #ef4444;">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-addon" style="background: #f8fafc; color: var(--summit-green);">
                  <i class="fa fa-user"></i>
                </span>
                <input type="text" id="pageLoginInput" class="form-control" placeholder="e.g. SU/20/NAS/014 or amina@example.com" required style="height: 48px; font-size: 14px;">
              </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark); margin: 0;">
                  Password <span style="color: #ef4444;">*</span>
                </label>
                <a href="{{ url('/forgot-password') }}" style="font-size: 12px; color: var(--summit-green); font-weight: 600;">
                  Forgot password?
                </a>
              </div>
              <div class="input-group">
                <span class="input-group-addon" style="background: #f8fafc; color: var(--summit-green);">
                  <i class="fa fa-lock"></i>
                </span>
                <input type="password" id="pagePasswordInput" class="form-control" placeholder="Enter your password" required style="height: 48px; font-size: 14px;">
              </div>
            </div>

            <div class="form-group" style="margin-top: 25px;">
              <button type="submit" id="pageLoginBtn" class="btn btn-summit-primary" style="width: 100%; height: 48px; justify-content: center; font-size: 15px;">
                <span>Sign In to Dashboard</span> <i class="fa fa-arrow-right"></i>
              </button>
            </div>
          </form>

          <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 13px; color: var(--summit-muted);">
            <span>Not yet registered?</span>
            <a href="{{ url('/register') }}" style="font-weight: 700; color: var(--summit-green); margin-left: 5px;">
              Verify Matric & Claim Profile
            </a>
          </div>

        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('pageLoginForm');
  const alertBox = document.getElementById('pageLoginAlert');
  const submitBtn = document.getElementById('pageLoginBtn');

  // Check if redirected with query params
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('login') === 'required') {
    alertBox.className = 'alert alert-info';
    alertBox.innerHTML = '<i class="fa fa-info-circle"></i> Please sign in to access your member dashboard.';
    alertBox.style.display = 'block';
  }

  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.style.display = 'none';

      const login = document.getElementById('pageLoginInput').value.trim();
      const password = document.getElementById('pagePasswordInput').value;

      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Authenticating...';

      const res = await SummitAPI.post('/login', { login, password });

      submitBtn.disabled = false;
      submitBtn.innerHTML = '<span>Sign In to Dashboard</span> <i class="fa fa-arrow-right"></i>';

      if (!res.success) {
        alertBox.className = 'alert alert-danger';
        alertBox.innerHTML = `<strong><i class="fa fa-exclamation-circle"></i> Error:</strong> ${res.message}`;
        alertBox.style.display = 'block';
        return;
      }

      // Save token & user in local storage session
      SummitAuth.setSession(res.token, res.user);
      SummitAPI.toast('Welcome back, ' + res.user.name + '!', 'success');

      setTimeout(() => {
        window.location.href = res.redirect || '/dashboard';
      }, 600);
    });
  }
});
</script>
@endpush
