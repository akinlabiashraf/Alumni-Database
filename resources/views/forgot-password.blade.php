@extends('layouts.app')

@section('title', 'Forgot Password — Summit University Alumni Portal')
@section('meta_description', 'Recover your Summit University Alumni portal access password.')

@section('content')
<!-- ======================= Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Senate Building.jpg') }}') center/cover no-repeat; padding: 130px 0 50px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 12px;">
      <i class="fa fa-key"></i> Account Recovery
    </div>
    <h1 style="color: #ffffff; font-size: 32px; font-weight: 800; margin-bottom: 8px;">Reset Your Password</h1>
    <p style="color: #cbd5e1; font-size: 15px; max-width: 600px; margin: 0 auto;">
      We'll help you regain secure access to your verified alumni profile.
    </p>
  </div>
</section>

<!-- ======================= Recovery Box ===================== -->
<section style="background: #f8fafc; padding: 60px 0 100px;">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12">
        
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 40px 35px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); border: 1px solid var(--summit-border);">
          
          <div style="text-align: center; margin-bottom: 25px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--summit-gold-light); color: var(--summit-gold-dark); display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 15px;">
              <i class="fa fa-lock"></i>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--summit-dark); margin: 0 0 6px;">Password Recovery</h3>
            <p style="font-size: 13px; color: var(--summit-muted); margin: 0;">
              Enter your registered email address or matriculation number.
            </p>
          </div>

          <div id="recoveryAlert" style="display: none; padding: 14px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;"></div>

          <form id="recoveryForm">
            <div class="form-group" style="margin-bottom: 20px;">
              <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                Registered Email or Matric Number <span style="color: #ef4444;">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-addon" style="background: #f8fafc; color: var(--summit-green);">
                  <i class="fa fa-envelope"></i>
                </span>
                <input type="text" id="recoveryInput" class="form-control" placeholder="e.g. amina@example.com or SU/20/NAS/014" required style="height: 48px; font-size: 14px;">
              </div>
            </div>

            <div class="form-group" style="margin-top: 25px;">
              <button type="submit" id="recoveryBtn" class="btn btn-summit-primary" style="width: 100%; height: 48px; justify-content: center; font-size: 15px;">
                <span>Send Password Reset Link</span> <i class="fa fa-paper-plane"></i>
              </button>
            </div>
          </form>

          <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 13px; color: var(--summit-muted);">
            <span>Remembered your password?</span>
            <a href="{{ url('/login') }}" style="font-weight: 700; color: var(--summit-green); margin-left: 5px;">
              Sign In Here
            </a>
          </div>

          <div style="background: #f0fdf4; border-radius: 8px; padding: 15px; margin-top: 20px; font-size: 12px; color: #166534; line-height: 18px;">
            <i class="fa fa-info-circle"></i> Need immediate assistance? Contact the Directorate at <a href="mailto:alumni@summituniversity.edu.ng" style="font-weight: 700; color: #166534; text-decoration: underline;">alumni@summituniversity.edu.ng</a>.
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
  const form = document.getElementById('recoveryForm');
  const alertBox = document.getElementById('recoveryAlert');
  const btn = document.getElementById('recoveryBtn');

  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const val = document.getElementById('recoveryInput').value.trim();
      if (!val) return;

      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

      setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = '<span>Send Password Reset Link</span> <i class="fa fa-paper-plane"></i>';

        alertBox.className = 'alert alert-success';
        alertBox.innerHTML = `<strong><i class="fa fa-check-circle"></i> Request Received!</strong><br>If a matching alumni record exists for "${val}", password recovery instructions have been dispatched to the registered contact address.`;
        alertBox.style.display = 'block';
        form.reset();
      }, 1200);
    });
  }
});
</script>
@endpush
