<!-- ================= Sign-in Modal ========================= -->
<div class="modal fade" id="summitLoginModal" tabindex="-1" role="dialog" aria-labelledby="summitLoginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: var(--summit-radius-lg); overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
      
      <!-- Modal Header -->
      <div style="background: linear-gradient(135deg, var(--summit-green-dark) 0%, var(--summit-green) 100%); padding: 25px 30px; color: #ffffff; position: relative;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9; font-size: 28px; font-weight: 300;">
          <span aria-hidden="true">&times;</span>
        </button>
        <div style="display: flex; align-items: center; gap: 12px;">
          <img src="{{ asset('assets/img/summit/Summit university Logo.webp') }}" alt="Logo" style="height: 42px; background: rgba(255,255,255,0.9); padding: 4px; border-radius: 6px;">
          <div>
            <h4 style="margin: 0; color: #ffffff; font-size: 18px; font-weight: 700;">Alumni Portal Sign In</h4>
            <span style="font-size: 11px; color: var(--summit-gold-light);">Summit University, Offa</span>
          </div>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="modal-body" style="padding: 30px;">
        <div id="modalLoginAlert" style="display: none;" class="alert alert-danger"></div>

        <form id="modalLoginForm">
          <div class="form-group">
            <label style="font-weight: 600; font-size: 13px; color: var(--summit-dark);">Email Address or Matric Number</label>
            <div class="input-group">
              <span class="input-group-addon" style="background: #f8fafc;"><i class="fa fa-user" style="color: var(--summit-green);"></i></span>
              <input type="text" id="modalLoginInput" class="form-control" placeholder="e.g. SU/20/NAS/014 or amina@example.com" required style="height: 45px; border-radius: 0 6px 6px 0;">
            </div>
            <small class="text-muted" style="font-size: 11px;">You can login using either your registered email or official matric number.</small>
          </div>

          <div class="form-group" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
              <label style="font-weight: 600; font-size: 13px; color: var(--summit-dark); margin: 0;">Password</label>
              <a href="{{ url('/forgot-password') }}" style="font-size: 12px; color: var(--summit-green);">Forgot password?</a>
            </div>
            <div class="input-group">
              <span class="input-group-addon" style="background: #f8fafc;"><i class="fa fa-lock" style="color: var(--summit-green);"></i></span>
              <input type="password" id="modalPasswordInput" class="form-control" placeholder="Enter your account password" required style="height: 45px; border-radius: 0 6px 6px 0;">
            </div>
          </div>

          <div class="form-group" style="margin-top: 25px;">
            <button type="submit" id="modalLoginBtn" class="btn btn-summit-primary" style="width: 100%; height: 46px; justify-content: center; font-size: 14px;">
              <span>Sign In to Alumni Portal</span> <i class="fa fa-arrow-right"></i>
            </button>
          </div>
        </form>

        <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 13px;">
          <span>Not yet registered?</span>
          <a href="{{ url('/register') }}" style="font-weight: 700; color: var(--summit-green); margin-left: 5px;">Verify Matric & Register</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('modalLoginForm');
    const alertBox = document.getElementById('modalLoginAlert');
    const submitBtn = document.getElementById('modalLoginBtn');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            alertBox.style.display = 'none';

            const login = document.getElementById('modalLoginInput').value.trim();
            const password = document.getElementById('modalPasswordInput').value;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Authenticating...';

            const res = await SummitAPI.post('/login', { login, password });

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Sign In to Alumni Portal</span> <i class="fa fa-arrow-right"></i>';

            if (!res.success) {
                alertBox.textContent = res.message || 'Authentication failed. Please check your details.';
                alertBox.style.display = 'block';
                return;
            }

            // Save session
            SummitAuth.setSession(res.token, res.user);
            SummitAPI.toast('Welcome back, ' + res.user.name + '!', 'success');

            setTimeout(() => {
                window.location.href = res.redirect || '/dashboard';
            }, 600);
        });
    }
});
</script>
