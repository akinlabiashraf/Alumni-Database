@extends('layouts.dashboard')

@section('title', 'Change Account Password — Summit University, Offa')

@push('styles')
<style>
  .password-card {
    background: #ffffff;
    border-radius: var(--summit-radius-lg);
    padding: 30px;
    border: 1px solid var(--summit-border);
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    max-width: 640px;
  }
  .password-strength-bar {
    height: 4px;
    border-radius: 4px;
    background: #e2e8f0;
    margin-top: 6px;
    transition: all 0.3s ease;
  }
  .password-strength-text {
    font-size: 11px;
    font-weight: 600;
    margin-top: 4px;
  }
  .password-wrapper {
    position: relative;
  }
  .toggle-pwd-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--summit-muted);
    cursor: pointer;
    font-size: 14px;
    padding: 4px;
  }
  .security-tip-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: var(--summit-radius);
    padding: 20px;
    margin-top: 25px;
    max-width: 640px;
  }
</style>
@endpush

@section('content')
<div style="margin-bottom: 25px;">
  <h2 style="font-size: 24px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 6px;">
    Change Account Password
  </h2>
  <p style="margin: 0; color: var(--summit-muted); font-size: 13px;">
    Ensure your Summit University alumni account remains secure by using a strong, unique passphrase.
  </p>
</div>

<div class="password-card">
  <form id="changePasswordForm">
    <!-- Current Password -->
    <div class="form-group" style="margin-bottom: 20px;">
      <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Current Password</label>
      <div class="password-wrapper">
        <input type="password" id="currentPassword" class="form-control" placeholder="Enter your current password" required>
        <button type="button" class="toggle-pwd-btn" onclick="togglePasswordVisibility('currentPassword', this)">
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>

    <!-- New Password -->
    <div class="form-group" style="margin-bottom: 15px;">
      <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">New Password</label>
      <div class="password-wrapper">
        <input type="password" id="newPassword" class="form-control" placeholder="Minimum 8 characters with letters and numbers" required minlength="8">
        <button type="button" class="toggle-pwd-btn" onclick="togglePasswordVisibility('newPassword', this)">
          <i class="fa fa-eye"></i>
        </button>
      </div>
      <div class="password-strength-bar" id="strengthBar"></div>
      <div class="password-strength-text" id="strengthText" style="color: var(--summit-muted);">Password must be at least 8 characters.</div>
    </div>

    <!-- Confirm New Password -->
    <div class="form-group" style="margin-bottom: 25px;">
      <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Confirm New Password</label>
      <div class="password-wrapper">
        <input type="password" id="newPasswordConfirmation" class="form-control" placeholder="Re-enter your new password" required>
        <button type="button" class="toggle-pwd-btn" onclick="togglePasswordVisibility('newPasswordConfirmation', this)">
          <i class="fa fa-eye"></i>
        </button>
      </div>
      <div id="pwdMatchError" style="display: none; color: #ef4444; font-size: 11px; font-weight: 600; margin-top: 4px;">
        <i class="fa fa-times-circle"></i> Passwords do not match.
      </div>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <button type="submit" id="btnChangePassword" class="btn-summit-primary" style="font-size: 13px; padding: 10px 24px;">
        <i class="fa fa-key"></i> Update Password
      </button>
      <button type="reset" class="btn-summit-outline" style="font-size: 13px; padding: 9px 18px;">
        Reset Form
      </button>
    </div>
  </form>
</div>

<!-- Security Guidance -->
<div class="security-tip-card">
  <h5 style="margin: 0 0 10px; font-weight: 700; color: var(--summit-green-dark); font-size: 13px;">
    <i class="fa fa-shield" style="color: var(--summit-green); margin-right: 6px;"></i> Password Best Practices
  </h5>
  <ul style="margin: 0; padding-left: 18px; font-size: 12px; color: var(--summit-text); line-height: 1.6;">
    <li>Use at least 8 characters, combining uppercase and lowercase letters with numbers.</li>
    <li>Do not use personal identifiers such as your matriculation number, phone number, or date of birth.</li>
    <li>Avoid reusing passwords from other social accounts or email services.</li>
    <li>Summit University IT staff will never ask you for your account password.</li>
  </ul>
</div>
@endsection

@push('scripts')
<script>
  function togglePasswordVisibility(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (field.type === 'password') {
      field.type = 'text';
      icon.className = 'fa fa-eye-slash';
    } else {
      field.type = 'password';
      icon.className = 'fa fa-eye';
    }
  }

  const newPwdInput = document.getElementById('newPassword');
  const confirmPwdInput = document.getElementById('newPasswordConfirmation');
  const strengthBar = document.getElementById('strengthBar');
  const strengthText = document.getElementById('strengthText');
  const matchError = document.getElementById('pwdMatchError');

  // Password strength meter
  newPwdInput.addEventListener('input', () => {
    const val = newPwdInput.value;
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    if (val.length === 0) {
      strengthBar.style.width = '0%';
      strengthBar.style.background = '#e2e8f0';
      strengthText.textContent = 'Password must be at least 8 characters.';
      strengthText.style.color = 'var(--summit-muted)';
    } else if (score <= 1) {
      strengthBar.style.width = '25%';
      strengthBar.style.background = '#ef4444';
      strengthText.textContent = 'Weak: Add numbers and mixed case letters.';
      strengthText.style.color = '#ef4444';
    } else if (score === 2) {
      strengthBar.style.width = '60%';
      strengthBar.style.background = '#f59e0b';
      strengthText.textContent = 'Moderate: Good start, add a symbol or number.';
      strengthText.style.color = '#f59e0b';
    } else {
      strengthBar.style.width = '100%';
      strengthBar.style.background = '#10b981';
      strengthText.textContent = 'Strong: Secure password.';
      strengthText.style.color = '#10b981';
    }

    checkMatch();
  });

  confirmPwdInput.addEventListener('input', checkMatch);

  function checkMatch() {
    if (confirmPwdInput.value.length > 0 && newPwdInput.value !== confirmPwdInput.value) {
      matchError.style.display = 'block';
    } else {
      matchError.style.display = 'none';
    }
  }

  document.getElementById('changePasswordForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    if (newPwdInput.value !== confirmPwdInput.value) {
      SummitAPI.toast('New passwords do not match.', 'error');
      matchError.style.display = 'block';
      return;
    }

    const btn = document.getElementById('btnChangePassword');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating Password...';

    const payload = {
      current_password: document.getElementById('currentPassword').value,
      new_password: newPwdInput.value,
      new_password_confirmation: confirmPwdInput.value,
    };

    try {
      const res = await SummitAPI.post('/change-password', payload);
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-key"></i> Update Password';

      if (res.success) {
        SummitAPI.toast('Password updated successfully!', 'success');
        document.getElementById('changePasswordForm').reset();
        strengthBar.style.width = '0%';
        strengthText.textContent = 'Password must be at least 8 characters.';
        strengthText.style.color = 'var(--summit-muted)';
      } else {
        SummitAPI.toast(res.message || 'Unable to update password.', 'error');
      }
    } catch (err) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-key"></i> Update Password';
      SummitAPI.toast('Network error. Please try again.', 'error');
    }
  });
</script>
@endpush
