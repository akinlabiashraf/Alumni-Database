@extends('layouts.dashboard')

@section('title', 'NDPA Privacy & Data Governance — Summit University, Offa')

@push('styles')
<style>
  .ndpa-hero {
    background: linear-gradient(135deg, #064e3b 0%, var(--summit-green) 100%);
    border-radius: var(--summit-radius-lg);
    padding: 26px 28px;
    color: #ffffff;
    margin-bottom: 25px;
    border-left: 5px solid var(--summit-gold);
  }
  .privacy-card {
    background: #ffffff;
    border-radius: var(--summit-radius-lg);
    padding: 24px;
    border: 1px solid var(--summit-border);
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
  }
  .privacy-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    border-bottom: 1px solid var(--summit-border);
    gap: 20px;
  }
  .privacy-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
  .privacy-label-group {
    flex: 1;
  }
  .privacy-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--summit-green-dark);
    margin: 0 0 3px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .privacy-desc {
    font-size: 12px;
    color: var(--summit-muted);
    margin: 0;
    line-height: 1.4;
  }
  .privacy-control {
    width: 220px;
    flex-shrink: 0;
  }
  .ndpa-legal-box {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: var(--summit-radius);
    padding: 20px;
    font-size: 12px;
    color: #334155;
    line-height: 1.6;
    margin-top: 25px;
  }
</style>
@endpush

@section('content')
<div style="margin-bottom: 20px;">
  <h2 style="font-size: 24px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 6px;">
    NDPA 2023 Privacy & Data Governance
  </h2>
  <p style="margin: 0; color: var(--summit-muted); font-size: 13px;">
    Control your personal data visibility across the alumni network. Your choices are enforced in real-time.
  </p>
</div>

<!-- NDPA Compliance Banner -->
<div class="ndpa-hero">
  <div style="display: flex; align-items: flex-start; gap: 16px;">
    <div style="font-size: 32px; color: var(--summit-gold);">
      <i class="fa fa-shield"></i>
    </div>
    <div>
      <h4 style="margin: 0 0 6px; color: #ffffff; font-weight: 700; font-size: 16px;">
        Nigeria Data Protection Act (NDPA 2023) Guaranteed
      </h4>
      <p style="margin: 0; font-size: 13px; opacity: 0.95; line-height: 1.5;">
        Summit University values your fundamental right to privacy. In accordance with NDPA 2023 standards, you maintain full control over which peers and public visitors can view your contact details, employment history, and physical location.
      </p>
    </div>
  </div>
</div>

<form id="privacyForm">
  <div class="privacy-card">
    <div style="border-bottom: 1px solid var(--summit-border); padding-bottom: 14px; margin-bottom: 10px;">
      <h3 style="font-size: 16px; font-weight: 700; color: var(--summit-green-dark); margin: 0;">
        <i class="fa fa-sliders" style="color: var(--summit-green); margin-right: 6px;"></i> Granular Visibility Controls
      </h3>
    </div>

    <!-- 1. Overall Profile Visibility -->
    <div class="privacy-row">
      <div class="privacy-label-group">
        <h4 class="privacy-title">
          <i class="fa fa-user-circle-o" style="color: var(--summit-green);"></i> Alumni Profile Visibility
        </h4>
        <p class="privacy-desc">
          Controls whether your profile card appears in general alumni directory search results.
        </p>
      </div>
      <div class="privacy-control">
        <select id="fieldProfileVisibility" class="form-control input-sm">
          <option value="public">Public (Everyone)</option>
          <option value="alumni_only" selected>Alumni Only (Verified Members)</option>
          <option value="private">Private (Hidden from Directory)</option>
        </select>
      </div>
    </div>

    <!-- 2. Email Address Visibility -->
    <div class="privacy-row">
      <div class="privacy-label-group">
        <h4 class="privacy-title">
          <i class="fa fa-envelope" style="color: #0284c7;"></i> Email Address
        </h4>
        <p class="privacy-desc">
          Determines who can see your registered email address on your public profile.
        </p>
      </div>
      <div class="privacy-control">
        <select id="fieldEmailVisibility" class="form-control input-sm">
          <option value="public">Public (Everyone)</option>
          <option value="alumni_only" selected>Alumni Only (Verified Members)</option>
          <option value="private">Private (Only Administrators)</option>
        </select>
      </div>
    </div>

    <!-- 3. Phone Number Visibility -->
    <div class="privacy-row">
      <div class="privacy-label-group">
        <h4 class="privacy-title">
          <i class="fa fa-phone" style="color: #16a34a;"></i> Mobile Phone Number
        </h4>
        <p class="privacy-desc">
          High-sensitivity identifier. Defaulted to Private for maximum security under NDPA.
        </p>
      </div>
      <div class="privacy-control">
        <select id="fieldPhoneVisibility" class="form-control input-sm">
          <option value="public">Public (Everyone)</option>
          <option value="alumni_only">Alumni Only (Verified Members)</option>
          <option value="private" selected>Private (Strictly Shielded)</option>
        </select>
      </div>
    </div>

    <!-- 4. Employment & Workplace Visibility -->
    <div class="privacy-row">
      <div class="privacy-label-group">
        <h4 class="privacy-title">
          <i class="fa fa-briefcase" style="color: #b45309;"></i> Current Workplace & Title
        </h4>
        <p class="privacy-desc">
          Controls whether external recruiters and peers can see your current job title and employer.
        </p>
      </div>
      <div class="privacy-control">
        <select id="fieldWorkVisibility" class="form-control input-sm">
          <option value="public" selected>Public (Everyone)</option>
          <option value="alumni_only">Alumni Only (Verified Members)</option>
          <option value="private">Private (Concealed)</option>
        </select>
      </div>
    </div>

    <!-- 5. Geographical Location Visibility -->
    <div class="privacy-row">
      <div class="privacy-label-group">
        <h4 class="privacy-title">
          <i class="fa fa-map-marker" style="color: #dc2626;"></i> City & State Location
        </h4>
        <p class="privacy-desc">
          Controls visibility of your resident city and state for geographic peer grouping.
        </p>
      </div>
      <div class="privacy-control">
        <select id="fieldLocationVisibility" class="form-control input-sm">
          <option value="public">Public (Everyone)</option>
          <option value="alumni_only" selected>Alumni Only (Verified Members)</option>
          <option value="private">Private (Hidden)</option>
        </select>
      </div>
    </div>

    <!-- 6. Networking & Inquiries -->
    <div class="privacy-row">
      <div class="privacy-label-group">
        <h4 class="privacy-title">
          <i class="fa fa-comments" style="color: #6366f1;"></i> Direct Network Outreach
        </h4>
        <p class="privacy-desc">
          Allow fellow verified Summit graduates to reach out to you through the portal contact channel.
        </p>
      </div>
      <div class="privacy-control" style="display: flex; align-items: center;">
        <label style="font-weight: 600; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
          <input type="checkbox" id="fieldAllowMessaging" checked style="width: 18px; height: 18px;">
          <span>Allow Peer Outreach</span>
        </label>
      </div>
    </div>
  </div>

  <!-- Action Bar -->
  <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 20px 25px; border: 1px solid var(--summit-border); display: flex; justify-content: space-between; align-items: center;">
    <div>
      <span class="summit-ndpa-badge">
        <i class="fa fa-lock" style="color: var(--summit-green);"></i> NDPA 2023 Compliant Settings
      </span>
    </div>
    <button type="submit" id="btnSavePrivacy" class="btn-summit-primary" style="font-size: 14px; padding: 10px 26px;">
      <i class="fa fa-check"></i> Save Privacy Preferences
    </button>
  </div>
</form>

<!-- NDPA Data Subject Rights (Portability & Erasure) -->
<div class="privacy-card" style="margin-top: 25px; border-left: 4px solid var(--summit-gold);">
  <div class="privacy-card-header" style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid var(--summit-border);">
    <h3 style="font-size: 16px; font-weight: 700; color: var(--summit-green-dark); margin: 0; display: flex; align-items: center; gap: 8px;">
      <i class="fa fa-shield" style="color: var(--summit-gold);"></i> NDPA 2023 Statutory Subject Rights
    </h3>
  </div>

  <div class="row">
    <!-- Right to Data Portability -->
    <div class="col-md-6" style="margin-bottom: 15px;">
      <h4 style="font-size: 14px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 5px;">
        <i class="fa fa-download" style="color: var(--summit-green);"></i> Right to Data Portability
      </h4>
      <p style="font-size: 12px; color: var(--summit-muted); margin-bottom: 12px;">
        Export and download a machine-readable archive of all your personal records, degree claims, and privacy preferences stored on the platform.
      </p>
      <button type="button" id="btnExportData" class="btn btn-sm btn-default" style="font-weight: 600; border-color: var(--summit-border);">
        <i class="fa fa-file-code-o"></i> Download My Data Archive (.JSON)
      </button>
    </div>

    <!-- Right to Erasure -->
    <div class="col-md-6">
      <h4 style="font-size: 14px; font-weight: 700; color: #dc2626; margin: 0 0 5px;">
        <i class="fa fa-trash-o" style="color: #dc2626;"></i> Right to Erasure (Account Deletion)
      </h4>
      <p style="font-size: 12px; color: var(--summit-muted); margin-bottom: 12px;">
        Permanently remove your account, career history, and contact details from the platform. Your official graduation record remains safely archived in the university registrar vault.
      </p>
      <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteAccountModal" style="font-weight: 600;">
        <i class="fa fa-user-times"></i> Request Account Deletion
      </button>
    </div>
  </div>
</div>

<!-- NDPA Rights Explanatory Box -->
<div class="ndpa-legal-box">
  <div style="font-weight: 700; color: var(--summit-green-dark); margin-bottom: 6px; font-size: 13px;">
    <i class="fa fa-info-circle"></i> Your Rights Under the Nigeria Data Protection Act 2023
  </div>
  Under Section 24 and Part V of the NDPA 2023, you have the statutory right to withdraw consent, restrict processing, or request the rectification of any inaccurate personal data stored within the Summit University Alumni Database. For official inquiries regarding university data audits or formal data erasure requests, please contact our Data Protection Officer at <strong>dpo@summituniversity.edu.ng</strong>.
</div>

<!-- ================= Modal: Account Deletion Confirmation ================= -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header" style="background: #dc2626; color: #fff; padding: 15px 20px;">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title" style="font-weight: 700; font-size: 15px;">Confirm Account Erasure</h4>
      </div>
      <form id="deleteAccountForm">
        <div class="modal-body" style="padding: 20px;">
          <p style="font-size: 12px; color: #475569; margin-bottom: 14px;">
            This action is <strong>irreversible</strong>. Enter your password to permanently delete your account and personal profile under NDPA 2023.
          </p>
          <div class="form-group">
            <label style="font-size: 12px; font-weight: 600;">Confirm Password *</label>
            <input type="password" id="deleteAccountPassword" class="form-control" placeholder="Enter your current password" required>
          </div>
        </div>
        <div class="modal-footer" style="padding: 12px 20px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" id="btnConfirmDeleteAccount">Permanently Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async () => {
    // Load current privacy settings from /api/me
    const res = await SummitAPI.get('/me');
    if (res.success && res.user && res.user.privacy_setting) {
      const ps = res.user.privacy_setting;
      if (ps.profile_visibility) document.getElementById('fieldProfileVisibility').value = ps.profile_visibility;
      if (ps.email_visibility) document.getElementById('fieldEmailVisibility').value = ps.email_visibility;
      if (ps.phone_visibility) document.getElementById('fieldPhoneVisibility').value = ps.phone_visibility;
      if (ps.work_visibility) document.getElementById('fieldWorkVisibility').value = ps.work_visibility;
      if (ps.location_visibility) document.getElementById('fieldLocationVisibility').value = ps.location_visibility;
      document.getElementById('fieldAllowMessaging').checked = ps.allow_messaging !== false;
    }
  });

  document.getElementById('privacyForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnSavePrivacy');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving Settings...';

    const payload = {
      profile_visibility: document.getElementById('fieldProfileVisibility').value,
      email_visibility: document.getElementById('fieldEmailVisibility').value,
      phone_visibility: document.getElementById('fieldPhoneVisibility').value,
      work_visibility: document.getElementById('fieldWorkVisibility').value,
      location_visibility: document.getElementById('fieldLocationVisibility').value,
      allow_messaging: document.getElementById('fieldAllowMessaging').checked,
    };

    try {
      const res = await SummitAPI.post('/privacy', payload);
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-check"></i> Save Privacy Preferences';

      if (res.success) {
        SummitAPI.toast('NDPA privacy preferences updated successfully!', 'success');
      } else {
        SummitAPI.toast(res.message || 'Failed to update privacy settings.', 'error');
      }
    } catch (err) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-check"></i> Save Privacy Preferences';
      SummitAPI.toast('Network error while saving privacy settings.', 'error');
    }
  });

  // Handle NDPA Data Export
  document.getElementById('btnExportData').addEventListener('click', async () => {
    const btn = document.getElementById('btnExportData');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Compiling Archive...';

    try {
      const res = await SummitAPI.get('/me/export-data');
      if (res.success && res.data) {
        const jsonStr = JSON.stringify(res.data, null, 2);
        const blob = new Blob([jsonStr], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `summit_alumni_data_export_${new Date().toISOString().slice(0,10)}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        SummitAPI.toast('Personal data archive downloaded successfully!', 'success');
      } else {
        SummitAPI.toast('Failed to compile data archive.', 'error');
      }
    } catch (err) {
      SummitAPI.toast('Error exporting personal data: ' + err.message, 'error');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-file-code-o"></i> Download My Data Archive (.JSON)';
    }
  });

  // Handle Account Deletion
  document.getElementById('deleteAccountForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const pwd = document.getElementById('deleteAccountPassword').value;
    const btn = document.getElementById('btnConfirmDeleteAccount');
    btn.disabled = true;
    btn.textContent = 'Erasing account...';

    try {
      const res = await SummitAPI.post('/me/delete-account', { password: pwd });
      if (res.success) {
        alert('Your account has been successfully deleted in accordance with the NDPA 2023. You will now be redirected to the homepage.');
        localStorage.clear();
        window.location.href = '/';
      } else {
        alert('Deletion failed: ' + res.message);
        btn.disabled = false;
        btn.textContent = 'Permanently Delete';
      }
    } catch (err) {
      alert('Error during deletion: ' + err.message);
      btn.disabled = false;
      btn.textContent = 'Permanently Delete';
    }
  });
</script>
@endpush
