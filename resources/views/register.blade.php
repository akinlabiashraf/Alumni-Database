@extends('layouts.app')

@section('title', 'Alumni Registration & Matriculation Verification — Summit University, Offa')
@section('meta_description', 'Claim your verified Summit University graduate record. 2-step registration with NDPA 2023 data protection.')

@section('content')
<!-- ======================= Page Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Hereo Section Image.jpeg') }}') center/cover no-repeat; padding: 130px 0 60px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 12px;">
      <i class="fa fa-graduation-cap"></i> Verified Graduate Onboarding
    </div>
    <h1 style="color: #ffffff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Alumni Verification & Registration</h1>
    <p style="color: #cbd5e1; font-size: 15px; max-width: 600px; margin: 0 auto;">
      All Summit University alumni profiles are linked to authenticated university records.
    </p>
  </div>
</section>

<!-- ======================= Main Wizard Area ===================== -->
<section style="background: #f8fafc; padding: 60px 0 100px;">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 col-sm-12">
        
        <!-- Navigation Tabs: Instant vs Manual -->
        <div style="background: #ffffff; border-radius: 12px 12px 0 0; padding: 8px 12px 0; border: 1px solid var(--summit-border); border-bottom: none; display: flex; gap: 8px;">
          <button type="button" id="tabInstantBtn" class="btn" style="flex: 1; padding: 14px 10px; font-weight: 700; font-size: 14px; border-radius: 8px 8px 0 0; background: var(--summit-green); color: #ffffff; border: none;">
            <i class="fa fa-bolt"></i> 1. Instant Matric Verification
          </button>
          <button type="button" id="tabManualBtn" class="btn" style="flex: 1; padding: 14px 10px; font-weight: 600; font-size: 14px; border-radius: 8px 8px 0 0; background: #f1f5f9; color: var(--summit-text); border: none;">
            <i class="fa fa-file-text-o"></i> 2. Manual Verification Request
          </button>
        </div>

        <!-- Card Container -->
        <div style="background: #ffffff; border-radius: 0 0 16px 16px; padding: 40px 35px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); border: 1px solid var(--summit-border);">
          
          <!-- ================= TAB 1: INSTANT MATRIC VERIFICATION ================= -->
          <div id="tabInstantContent">

            <!-- STEP 1: MATRIC LOOKUP -->
            <div id="wizardStep1">
              <div style="text-align: center; margin-bottom: 30px;">
                <span class="summit-badge-verified" style="margin-bottom: 8px;">Step 1 of 2</span>
                <h3 style="font-size: 22px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 6px;">
                  Verify Your Matriculation Record
                </h3>
                <p style="font-size: 14px; color: var(--summit-muted); margin: 0;">
                  Enter your official matriculation number as recorded during your study at Summit University.
                </p>
              </div>

              <div id="step1Alert" style="display: none; padding: 14px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;"></div>

              <form id="step1VerifyForm">
                <div class="form-group" style="margin-bottom: 20px;">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                    Matriculation Number <span style="color: #ef4444;">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-addon" style="background: #f8fafc; font-size: 16px; color: var(--summit-green);">
                      <i class="fa fa-id-card-o"></i>
                    </span>
                    <input type="text" id="verifyMatricInput" class="form-control" placeholder="e.g. SU/20/NAS/014" required style="height: 48px; font-size: 15px; text-transform: uppercase;">
                  </div>
                  <small class="text-muted" style="font-size: 12px; margin-top: 4px; display: block;">
                    Example formats: <code>SU/19/NAS/001</code>, <code>SU/20/NAS/014</code>, <code>SU/21/MSS/032</code>
                  </small>
                </div>

                <div class="row">
                  <div class="col-md-6 col-sm-12 form-group">
                    <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                      Graduating Set (Optional Filter)
                    </label>
                    <select id="verifyYearSelect" class="form-control" style="height: 45px;">
                      <option value="">Any Graduating Set</option>
                      <option value="2025">Class of 2025</option>
                      <option value="2024">Class of 2024</option>
                      <option value="2023">Class of 2023</option>
                      <option value="2022">Class of 2022</option>
                      <option value="2021">Class of 2021</option>
                      <option value="2020">Class of 2020</option>
                      <option value="2019">Pioneer Class of 2019</option>
                    </select>
                  </div>

                  <div class="col-md-6 col-sm-12 form-group">
                    <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                      Department (Optional Filter)
                    </label>
                    <select id="verifyDeptSelect" class="form-control" style="height: 45px;">
                      <option value="">Any Department</option>
                    </select>
                  </div>
                </div>

                <div style="margin-top: 25px;">
                  <button type="submit" id="step1VerifyBtn" class="btn btn-summit-primary" style="width: 100%; height: 48px; justify-content: center; font-size: 15px;">
                    <span>Verify Record & Proceed</span> <i class="fa fa-arrow-right"></i>
                  </button>
                </div>
              </form>

              <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 13px; color: var(--summit-muted);">
                <span>Already claimed your profile?</span>
                <a href="{{ url('/login') }}" style="font-weight: 700; color: var(--summit-green); margin-left: 6px;">Sign In to Dashboard</a>
              </div>
            </div>

            <!-- STEP 2: CLAIM PROFILE & CREATE ACCOUNT (Initially Hidden) -->
            <div id="wizardStep2" style="display: none;">
              <div style="text-align: center; margin-bottom: 25px;">
                <span class="summit-badge-gold" style="margin-bottom: 8px;">Step 2 of 2</span>
                <h3 style="font-size: 22px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 6px;">
                  Confirm Record & Create Your Account
                </h3>
                <p style="font-size: 14px; color: var(--summit-muted); margin: 0;">
                  We found your official graduation record. Please confirm your details and choose a password.
                </p>
              </div>

              <!-- Matched Record Preview Card -->
              <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 22px 25px; margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                  <div>
                    <span class="summit-badge-verified" style="margin-bottom: 5px;">
                      <i class="fa fa-check-circle"></i> Authenticated Academic Record
                    </span>
                    <h4 id="previewFullName" style="font-size: 18px; font-weight: 800; color: var(--summit-green-dark); margin: 4px 0 0;">
                      Ibrahim Babatunde Alabi
                    </h4>
                  </div>
                  <div style="text-align: right;">
                    <span id="previewMatric" class="badge" style="background: var(--summit-green); font-size: 13px; padding: 6px 12px;">SU/20/NAS/014</span>
                  </div>
                </div>

                <div class="row" style="font-size: 13px; color: var(--summit-text); line-height: 22px; margin-top: 10px;">
                  <div class="col-sm-6">
                    <strong>Degree:</strong> <span id="previewDegree">B.Sc. Computer Science</span><br>
                    <strong>Faculty:</strong> <span id="previewFaculty">Natural & Applied Sciences</span>
                  </div>
                  <div class="col-sm-6">
                    <strong>Department:</strong> <span id="previewDepartment">Computer Science</span><br>
                    <strong>Graduating Set:</strong> <span id="previewYear">Class of 2020</span>
                  </div>
                </div>
              </div>

              <div id="step2Alert" style="display: none; padding: 14px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;"></div>

              <!-- Registration Form -->
              <form id="step2RegisterForm">
                <input type="hidden" id="verifiedMatricHidden">
                <input type="hidden" id="verificationTokenHidden">

                <div class="row">
                  <div class="col-md-6 col-sm-12 form-group">
                    <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                      Full Legal Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="regNameInput" class="form-control" required style="height: 45px;">
                  </div>
                  
                  <div class="col-md-6 col-sm-12 form-group">
                    <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                      Current Contact Email <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" id="regEmailInput" class="form-control" placeholder="you@domain.com" required style="height: 45px;">
                    <small class="text-muted" style="font-size: 11px;">This will be used for logins & password resets.</small>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 col-sm-12 form-group">
                    <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                      Phone Number (Optional)
                    </label>
                    <input type="tel" id="regPhoneInput" class="form-control" placeholder="e.g. +234 803 123 4567" style="height: 45px;">
                  </div>

                  <div class="col-md-6 col-sm-12 form-group">
                    <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                      Choose Password <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="password" id="regPasswordInput" class="form-control" placeholder="At least 8 letters & numbers" required style="height: 45px;">
                  </div>
                </div>

                <div class="form-group">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">
                    Confirm Password <span style="color: #ef4444;">*</span>
                  </label>
                  <input type="password" id="regPasswordConfirmInput" class="form-control" placeholder="Re-enter password" required style="height: 45px;">
                </div>

                <!-- NDPA 2023 Consent Checkbox -->
                <div style="background: #f8fafc; border: 1px solid var(--summit-border); border-radius: 8px; padding: 15px; margin: 20px 0;">
                  <label style="font-weight: 500; font-size: 13px; color: var(--summit-dark); margin: 0; display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="regNdpaConsent" required style="margin-top: 3px;">
                    <span>
                      I consent to the collection and processing of my alumni credentials under the 
                      <strong>Nigeria Data Protection Act (NDPA 2023)</strong> and agree to the 
                      <a href="{{ url('/privacy') }}" target="_blank" style="color: var(--summit-green); font-weight: 600;">Privacy Policy</a> and 
                      <a href="{{ url('/terms') }}" target="_blank" style="color: var(--summit-green); font-weight: 600;">Terms of Membership</a>.
                    </span>
                  </label>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 25px;">
                  <button type="button" id="backToStep1Btn" class="btn btn-default" style="padding: 12px 20px; font-weight: 600;">
                    <i class="fa fa-arrow-left"></i> Back
                  </button>
                  <button type="submit" id="step2RegisterBtn" class="btn btn-summit-primary" style="flex: 1; height: 48px; justify-content: center; font-size: 15px;">
                    <span>Claim Profile & Complete Registration</span> <i class="fa fa-check"></i>
                  </button>
                </div>
              </form>
            </div>

          </div>

          <!-- ================= TAB 2: MANUAL VERIFICATION FALLBACK ================= -->
          <div id="tabManualContent" style="display: none;">
            <div style="text-align: center; margin-bottom: 25px;">
              <span class="summit-badge-gold" style="margin-bottom: 8px;">Moderator Queue</span>
              <h3 style="font-size: 22px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 6px;">
                Request Manual Record Verification
              </h3>
              <p style="font-size: 14px; color: var(--summit-muted); margin: 0;">
                If your matriculation number is missing or unmatched in the registry, submit your documents for manual verification by the Directorate.
              </p>
            </div>

            <div id="manualAlert" style="display: none; padding: 14px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;"></div>

            <form id="manualVerifyForm" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6 col-sm-12 form-group">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Full Legal Name *</label>
                  <input type="text" id="manualName" class="form-control" placeholder="As written on university records" required style="height: 45px;">
                </div>

                <div class="col-md-6 col-sm-12 form-group">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Matriculation Number *</label>
                  <input type="text" id="manualMatric" class="form-control" placeholder="e.g. SU/18/NAS/020" required style="height: 45px; text-transform: uppercase;">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 col-sm-12 form-group">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Graduation Year *</label>
                  <input type="number" id="manualYear" class="form-control" placeholder="e.g. 2019" min="2016" max="2030" required style="height: 45px;">
                </div>

                <div class="col-md-6 col-sm-12 form-group">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Department *</label>
                  <input type="text" id="manualDept" class="form-control" placeholder="e.g. Computer Science" required style="height: 45px;">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 col-sm-12 form-group">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Contact Email *</label>
                  <input type="email" id="manualEmail" class="form-control" placeholder="you@domain.com" required style="height: 45px;">
                </div>

                <div class="col-md-6 col-sm-12 form-group">
                  <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Phone Number (Optional)</label>
                  <input type="tel" id="manualPhone" class="form-control" placeholder="e.g. +234 803 123 4567" style="height: 45px;">
                </div>
              </div>

              <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Upload Supporting Document (Optional but Recommended)</label>
                <input type="file" id="manualDoc" class="form-control" accept=".pdf,.jpg,.jpeg,.png" style="height: 45px; padding-top: 8px;">
                <small class="text-muted" style="font-size: 11px;">Upload your Statement of Result, Certificate, or University ID card (PDF, JPG, PNG under 10MB).</small>
              </div>

              <div class="form-group">
                <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Additional Notes</label>
                <textarea id="manualNotes" class="form-control" rows="3" placeholder="Explain any special circumstance (e.g., pioneer set archives, name spelling variation)..."></textarea>
              </div>

              <div style="margin-top: 25px;">
                <button type="submit" id="manualSubmitBtn" class="btn btn-summit-primary" style="width: 100%; height: 48px; justify-content: center; font-size: 15px;">
                  <span>Submit Manual Verification Ticket</span> <i class="fa fa-paper-plane"></i>
                </button>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
  // Tab Switchers
  const tabInstantBtn = document.getElementById('tabInstantBtn');
  const tabManualBtn = document.getElementById('tabManualBtn');
  const tabInstantContent = document.getElementById('tabInstantContent');
  const tabManualContent = document.getElementById('tabManualContent');

  function showInstantTab() {
    tabInstantBtn.style.background = 'var(--summit-green)';
    tabInstantBtn.style.color = '#ffffff';
    tabManualBtn.style.background = '#f1f5f9';
    tabManualBtn.style.color = 'var(--summit-text)';
    tabInstantContent.style.display = 'block';
    tabManualContent.style.display = 'none';
  }

  function showManualTab() {
    tabManualBtn.style.background = 'var(--summit-green)';
    tabManualBtn.style.color = '#ffffff';
    tabInstantBtn.style.background = '#f1f5f9';
    tabInstantBtn.style.color = 'var(--summit-text)';
    tabManualContent.style.display = 'block';
    tabInstantContent.style.display = 'none';
  }

  tabInstantBtn.addEventListener('click', showInstantTab);
  tabManualBtn.addEventListener('click', showManualTab);

  if (window.location.hash === '#manual') {
    showManualTab();
  }

  // Pre-load departments for Step 1 filter dropdown
  try {
    const acadRes = await SummitAPI.get('/academic-data');
    if (acadRes.success && acadRes.faculties) {
      const deptSelect = document.getElementById('verifyDeptSelect');
      acadRes.faculties.forEach(fac => {
        const optgroup = document.createElement('optgroup');
        optgroup.label = fac.name;
        fac.departments.forEach(dept => {
          const opt = document.createElement('option');
          opt.value = dept.id;
          opt.textContent = `${dept.name} (${dept.code})`;
          optgroup.appendChild(opt);
        });
        deptSelect.appendChild(optgroup);
      });
    }
  } catch (e) {
    console.error(e);
  }

  // Step 1: Matric Verification
  const step1Form = document.getElementById('step1VerifyForm');
  const step1Alert = document.getElementById('step1Alert');
  const step1Btn = document.getElementById('step1VerifyBtn');
  const wizardStep1 = document.getElementById('wizardStep1');
  const wizardStep2 = document.getElementById('wizardStep2');

  step1Form.addEventListener('submit', async (e) => {
    e.preventDefault();
    step1Alert.style.display = 'none';

    const matric = document.getElementById('verifyMatricInput').value.trim();
    const year = document.getElementById('verifyYearSelect').value;
    const deptId = document.getElementById('verifyDeptSelect').value;

    step1Btn.disabled = true;
    step1Btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Checking Database...';

    const res = await SummitAPI.post('/verify-matric', {
      matric_number: matric,
      graduation_year: year ? parseInt(year) : null,
      department_id: deptId ? parseInt(deptId) : null,
    });

    step1Btn.disabled = false;
    step1Btn.innerHTML = '<span>Verify Record & Proceed</span> <i class="fa fa-arrow-right"></i>';

    if (res.success && res.status === 'verified') {
      // Transition to Step 2
      document.getElementById('verifiedMatricHidden').value = res.graduate.matric_number;
      document.getElementById('verificationTokenHidden').value = res.verification_token;
      
      document.getElementById('previewFullName').textContent = res.graduate.full_name;
      document.getElementById('previewMatric').textContent = res.graduate.matric_number;
      document.getElementById('previewDegree').textContent = res.graduate.degree + (res.graduate.class_of_degree ? ` (${res.graduate.class_of_degree})` : '');
      document.getElementById('previewFaculty').textContent = res.graduate.faculty;
      document.getElementById('previewDepartment').textContent = res.graduate.department;
      document.getElementById('previewYear').textContent = `Class of ${res.graduate.graduation_year}`;

      document.getElementById('regNameInput').value = res.graduate.full_name;

      wizardStep1.style.display = 'none';
      wizardStep2.style.display = 'block';
    } else if (res.status === 'already_claimed') {
      step1Alert.className = 'alert alert-warning';
      step1Alert.innerHTML = `<strong><i class="fa fa-exclamation-triangle"></i> Record Already Claimed:</strong> ${res.message} If this is an error, please <a href="mailto:${res.support_email}" style="font-weight: 700; text-decoration: underline;">contact support</a>.`;
      step1Alert.style.display = 'block';
    } else {
      step1Alert.className = 'alert alert-danger';
      step1Alert.innerHTML = `<strong><i class="fa fa-times-circle"></i> Unmatched Record:</strong> ${res.message} <br>You can <a href="javascript:void(0)" id="switchToManualLink" style="font-weight: 700; text-decoration: underline;">request manual review</a> with documentation.`;
      step1Alert.style.display = 'block';

      document.getElementById('switchToManualLink')?.addEventListener('click', () => {
        showManualTab();
        document.getElementById('manualMatric').value = matric;
      });
    }
  });

  // Back button from Step 2 to Step 1
  document.getElementById('backToStep1Btn').addEventListener('click', () => {
    wizardStep2.style.display = 'none';
    wizardStep1.style.display = 'block';
  });

  // Step 2: Final Registration
  const step2Form = document.getElementById('step2RegisterForm');
  const step2Alert = document.getElementById('step2Alert');
  const step2Btn = document.getElementById('step2RegisterBtn');

  step2Form.addEventListener('submit', async (e) => {
    e.preventDefault();
    step2Alert.style.display = 'none';

    const password = document.getElementById('regPasswordInput').value;
    const confirmPassword = document.getElementById('regPasswordConfirmInput').value;

    if (password !== confirmPassword) {
      step2Alert.className = 'alert alert-danger';
      step2Alert.textContent = 'Passwords do not match. Please verify and try again.';
      step2Alert.style.display = 'block';
      return;
    }

    const payload = {
      matric_number: document.getElementById('verifiedMatricHidden').value,
      verification_token: document.getElementById('verificationTokenHidden').value,
      name: document.getElementById('regNameInput').value.trim(),
      email: document.getElementById('regEmailInput').value.trim(),
      phone: document.getElementById('regPhoneInput').value.trim(),
      password: password,
      password_confirmation: confirmPassword,
      ndpa_consent: document.getElementById('regNdpaConsent').checked,
    };

    step2Btn.disabled = true;
    step2Btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating Account & Claiming Record...';

    const res = await SummitAPI.post('/register', payload);

    step2Btn.disabled = false;
    step2Btn.innerHTML = '<span>Claim Profile & Complete Registration</span> <i class="fa fa-check"></i>';

    if (res.success) {
      SummitAuth.setSession(res.token, res.user);
      SummitAPI.toast('Welcome to the Summit Alumni Network!', 'success');

      step2Alert.className = 'alert alert-success';
      step2Alert.innerHTML = `<strong><i class="fa fa-check-circle"></i> Congratulations!</strong> Profile claimed successfully. Redirecting to your dashboard...`;
      step2Alert.style.display = 'block';

      setTimeout(() => {
        window.location.href = res.redirect || '/dashboard';
      }, 1000);
    } else {
      step2Alert.className = 'alert alert-danger';
      step2Alert.innerHTML = `<strong><i class="fa fa-exclamation-circle"></i> Error:</strong> ${res.message}`;
      step2Alert.style.display = 'block';
    }
  });

  // Tab 2: Manual Verification Form Submission
  const manualForm = document.getElementById('manualVerifyForm');
  const manualAlert = document.getElementById('manualAlert');
  const manualBtn = document.getElementById('manualSubmitBtn');

  manualForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    manualAlert.style.display = 'none';

    const formData = new FormData();
    formData.append('full_name', document.getElementById('manualName').value.trim());
    formData.append('matric_number', document.getElementById('manualMatric').value.trim());
    formData.append('graduation_year', document.getElementById('manualYear').value);
    formData.append('department_name', document.getElementById('manualDept').value.trim());
    formData.append('email', document.getElementById('manualEmail').value.trim());
    formData.append('phone', document.getElementById('manualPhone').value.trim());
    formData.append('notes', document.getElementById('manualNotes').value.trim());

    const fileInput = document.getElementById('manualDoc');
    if (fileInput.files.length > 0) {
      formData.append('id_document', fileInput.files[0]);
    }

    manualBtn.disabled = true;
    manualBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting Ticket...';

    const res = await SummitAPI.post('/verify-manual', formData);

    manualBtn.disabled = false;
    manualBtn.innerHTML = '<span>Submit Manual Verification Ticket</span> <i class="fa fa-paper-plane"></i>';

    if (res.success) {
      manualAlert.className = 'alert alert-success';
      manualAlert.innerHTML = `<strong><i class="fa fa-check-circle"></i> Ticket Submitted!</strong><br>Reference ID: <strong>${res.reference_number}</strong>.<br>${res.instructions}`;
      manualAlert.style.display = 'block';
      manualForm.reset();
    } else {
      manualAlert.className = 'alert alert-danger';
      manualAlert.innerHTML = `<strong><i class="fa fa-exclamation-circle"></i> Submission Failed:</strong> ${res.message}`;
      manualAlert.style.display = 'block';
    }
  });

  // Handle URL pre-fill (?matric=SU/20/NAS/014)
  const urlParams = new URLSearchParams(window.location.search);
  const paramMatric = urlParams.get('matric');
  if (paramMatric) {
    document.getElementById('verifyMatricInput').value = paramMatric;
    // Auto-trigger verification if pre-filled from Home widget
    step1Btn.click();
  }
});
</script>
@endpush
