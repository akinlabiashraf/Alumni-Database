@extends('layouts.dashboard')

@section('title', 'Profile & Career Settings — Summit University, Offa')

@push('styles')
<style>
  .settings-section {
    background: #ffffff;
    border-radius: var(--summit-radius-lg);
    padding: 30px;
    border: 1px solid var(--summit-border);
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  }
  .settings-section-header {
    border-bottom: 1px solid var(--summit-border);
    padding-bottom: 15px;
    margin-bottom: 22px;
  }
  .settings-section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--summit-green-dark);
    margin: 0 0 4px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .settings-section-desc {
    font-size: 12px;
    color: var(--summit-muted);
    margin: 0;
  }
  .avatar-upload-box {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 20px;
    background: #f8fafc;
    border-radius: var(--summit-radius);
    border: 1px dashed #cbd5e1;
    margin-bottom: 25px;
  }
  .avatar-preview-img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--summit-green);
    background: var(--summit-green-light);
  }
  .timeline-entry-card {
    background: #f8fafc;
    border: 1px solid var(--summit-border);
    border-radius: var(--summit-radius);
    padding: 18px;
    margin-bottom: 15px;
    position: relative;
  }
  .timeline-entry-remove {
    position: absolute;
    top: 14px;
    right: 14px;
    color: #ef4444;
    cursor: pointer;
    font-size: 14px;
    border: none;
    background: none;
  }
  .skill-chips-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
    min-height: 38px;
  }
  .skill-chip {
    background: var(--summit-green-light);
    color: var(--summit-green-dark);
    border: 1px solid rgba(0, 104, 55, 0.2);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .skill-chip-remove {
    cursor: pointer;
    color: #ef4444;
    font-weight: 700;
  }
  .readonly-credential-pill {
    background: #f1f5f9;
    padding: 10px 14px;
    border-radius: var(--summit-radius);
    border: 1px solid #e2e8f0;
    font-size: 12px;
    margin-bottom: 12px;
  }
</style>
@endpush

@section('content')
<div style="margin-bottom: 25px;">
  <h2 style="font-size: 24px; font-weight: 700; color: var(--summit-green-dark); margin: 0 0 6px;">
    Profile & Career Management
  </h2>
  <p style="margin: 0; color: var(--summit-muted); font-size: 13px;">
    Update your biography, employment, skills, and academic history. Your data is protected according to NDPA 2023 regulations.
  </p>
</div>

<!-- Avatar & Profile Photo Section -->
<div class="settings-section">
  <div class="settings-section-header">
    <h3 class="settings-section-title"><i class="fa fa-camera" style="color: var(--summit-green);"></i> Profile Photograph</h3>
    <p class="settings-section-desc">Upload a clear professional headshot. Max file size: 3MB (JPEG, PNG, WEBP).</p>
  </div>

  <div class="avatar-upload-box">
    <img id="profileAvatarPreview" src="{{ asset('assets/img/user-profile.png') }}" class="avatar-preview-img" alt="Avatar Preview">
    <div>
      <input type="file" id="avatarFileInput" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;">
      <button type="button" class="btn-summit-primary" style="font-size: 12px; padding: 7px 16px;" onclick="document.getElementById('avatarFileInput').click()">
        <i class="fa fa-upload"></i> Upload New Photo
      </button>
      <div id="avatarUploadSpinner" style="display: none; margin-top: 8px; font-size: 12px; color: var(--summit-green);">
        <i class="fa fa-spinner fa-spin"></i> Uploading and saving photograph...
      </div>
      <p style="margin: 8px 0 0; font-size: 11px; color: var(--summit-muted);">
        A professional photo helps fellow alumni and university recruiters recognize you in the directory.
      </p>
    </div>
  </div>
</div>

<!-- Verified University Records (Read-Only) -->
<div class="settings-section">
  <div class="settings-section-header">
    <h3 class="settings-section-title"><i class="fa fa-university" style="color: var(--summit-green);"></i> Summit University Credentials</h3>
    <p class="settings-section-desc">Official matriculation record verified by the Directorate of Academic Affairs.</p>
  </div>

  <div class="row">
    <div class="col-md-4 col-sm-6">
      <div class="readonly-credential-pill">
        <div style="font-size: 11px; color: var(--summit-muted); text-transform: uppercase; font-weight: 600;">Matriculation No.</div>
        <div id="credMatric" style="font-size: 13px; font-weight: 700; color: var(--summit-green-dark); margin-top: 2px;">
          <i class="fa fa-shield text-success"></i> Loading...
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-6">
      <div class="readonly-credential-pill">
        <div style="font-size: 11px; color: var(--summit-muted); text-transform: uppercase; font-weight: 600;">Graduating Class</div>
        <div id="credYear" style="font-size: 13px; font-weight: 700; color: var(--summit-green-dark); margin-top: 2px;">
          Loading...
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="readonly-credential-pill">
        <div style="font-size: 11px; color: var(--summit-muted); text-transform: uppercase; font-weight: 600;">Degree & Honors</div>
        <div id="credDegree" style="font-size: 13px; font-weight: 700; color: var(--summit-green-dark); margin-top: 2px;">
          Loading...
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Form for all profile information -->
<form id="profileSettingsForm">
  <!-- Basic Contact & Professional Headline -->
  <div class="settings-section">
    <div class="settings-section-header">
      <h3 class="settings-section-title"><i class="fa fa-user" style="color: var(--summit-green);"></i> Basic Information & Headline</h3>
      <p class="settings-section-desc">Manage your full name, phone number, and professional summary tag.</p>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Full Name</label>
          <input type="text" id="fieldName" class="form-control" placeholder="Enter your full name" required>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Phone Number</label>
          <input type="text" id="fieldPhone" class="form-control" placeholder="e.g. +234 803 123 4567">
          <small class="text-muted" style="font-size: 11px;">You can set this to private in Privacy Settings.</small>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Professional Headline</label>
      <input type="text" id="fieldHeadline" class="form-control" placeholder="e.g. Senior Software Engineer at Paystack | Fintech Enthusiast" maxlength="255">
      <small class="text-muted" style="font-size: 11px;">A short one-line phrase that appears below your name in directory searches.</small>
    </div>

    <div class="form-group">
      <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Executive Bio / About Me</label>
      <textarea id="fieldBio" class="form-control" rows="4" placeholder="Share your professional journey, milestones, and personal passions..." maxlength="3000"></textarea>
      <small class="text-muted" style="font-size: 11px;">Supports up to 3,000 characters.</small>
    </div>
  </div>

  <!-- Current Employment & Location -->
  <div class="settings-section">
    <div class="settings-section-header">
      <h3 class="settings-section-title"><i class="fa fa-briefcase" style="color: var(--summit-green);"></i> Employment & Location</h3>
      <p class="settings-section-desc">Current organization, industry specialization, and residential location.</p>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Current Job Title / Role</label>
          <input type="text" id="fieldJobTitle" class="form-control" placeholder="e.g. Lead Product Designer">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Current Company / Employer</label>
          <input type="text" id="fieldCompany" class="form-control" placeholder="e.g. Flutterwave, MTN, Ministry of Health">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Industry</label>
          <select id="fieldIndustry" class="form-control">
            <option value="">Select Primary Industry</option>
            <option value="Information Technology & Software">Information Technology & Software</option>
            <option value="Financial Services & Banking">Financial Services & Banking</option>
            <option value="Healthcare & Pharmaceuticals">Healthcare & Pharmaceuticals</option>
            <option value="Education & Research">Education & Research</option>
            <option value="Engineering & Construction">Engineering & Construction</option>
            <option value="Energy, Oil & Gas">Energy, Oil & Gas</option>
            <option value="Telecommunications">Telecommunications</option>
            <option value="Public Sector & Government">Public Sector & Government</option>
            <option value="Media, Arts & Entertainment">Media, Arts & Entertainment</option>
            <option value="Agriculture & Agribusiness">Agriculture & Agribusiness</option>
            <option value="Consulting & Legal">Consulting & Legal</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Work Arrangement</label>
          <input type="text" id="fieldWorkLocation" class="form-control" placeholder="e.g. On-site, Hybrid, Remote">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-4">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">City</label>
          <input type="text" id="fieldCity" class="form-control" placeholder="e.g. Lagos, Abuja, Ilorin, London">
        </div>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">State / Region</label>
          <input type="text" id="fieldState" class="form-control" placeholder="e.g. Lagos, Kwara, FCT">
        </div>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);">Country</label>
          <input type="text" id="fieldCountry" class="form-control" placeholder="e.g. Nigeria">
        </div>
      </div>
    </div>
  </div>

  <!-- Skills & Key Competencies -->
  <div class="settings-section">
    <div class="settings-section-header">
      <h3 class="settings-section-title"><i class="fa fa-tags" style="color: var(--summit-green);"></i> Skills & Technical Expertise</h3>
      <p class="settings-section-desc">Add skills to help fellow alumni identify mentorship or collaboration opportunities.</p>
    </div>

    <div class="input-group">
      <input type="text" id="skillInput" class="form-control" placeholder="Type a skill (e.g. Data Science, Python, Financial Modeling) and press Enter">
      <span class="input-group-btn">
        <button type="button" id="btnAddSkill" class="btn btn-summit-primary" style="border-radius: 0 4px 4px 0;">
          <i class="fa fa-plus"></i> Add Skill
        </button>
      </span>
    </div>

    <div class="skill-chips-container" id="skillChipsContainer">
      <!-- Chips rendered here -->
    </div>
  </div>

  <!-- Career Experience Timeline -->
  <div class="settings-section">
    <div class="settings-section-header" style="display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h3 class="settings-section-title"><i class="fa fa-line-chart" style="color: var(--summit-green);"></i> Career Timeline</h3>
        <p class="settings-section-desc">Chronological history of your career roles and achievements.</p>
      </div>
      <button type="button" id="btnAddCareer" class="btn-summit-outline" style="font-size: 11px; padding: 6px 12px;">
        <i class="fa fa-plus"></i> Add Position
      </button>
    </div>

    <div id="careerTimelineList">
      <!-- Career items rendered here -->
    </div>
  </div>

  <!-- Higher Education & Certifications -->
  <div class="settings-section">
    <div class="settings-section-header" style="display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h3 class="settings-section-title"><i class="fa fa-graduation-cap" style="color: var(--summit-green);"></i> Additional Education & Certifications</h3>
        <p class="settings-section-desc">Master's degrees, PhDs, or professional certifications earned.</p>
      </div>
      <button type="button" id="btnAddEducation" class="btn-summit-outline" style="font-size: 11px; padding: 6px 12px;">
        <i class="fa fa-plus"></i> Add Qualification
      </button>
    </div>

    <div id="educationTimelineList">
      <!-- Education items rendered here -->
    </div>
  </div>

  <!-- Social & Professional Links -->
  <div class="settings-section">
    <div class="settings-section-header">
      <h3 class="settings-section-title"><i class="fa fa-share-alt" style="color: var(--summit-green);"></i> Social & Professional Profiles</h3>
      <p class="settings-section-desc">Connect your professional external profiles.</p>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);"><i class="fa fa-linkedin" style="color: #0077b5;"></i> LinkedIn URL</label>
          <input type="url" id="fieldLinkedin" class="form-control" placeholder="https://linkedin.com/in/username">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);"><i class="fa fa-twitter" style="color: #1da1f2;"></i> Twitter / X URL</label>
          <input type="url" id="fieldTwitter" class="form-control" placeholder="https://x.com/username">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);"><i class="fa fa-github" style="color: #333;"></i> GitHub URL</label>
          <input type="url" id="fieldGithub" class="form-control" placeholder="https://github.com/username">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label style="font-size: 12px; font-weight: 700; color: var(--summit-text);"><i class="fa fa-globe" style="color: var(--summit-green);"></i> Portfolio / Website URL</label>
          <input type="url" id="fieldPortfolio" class="form-control" placeholder="https://yourwebsite.com">
        </div>
      </div>
    </div>
  </div>

  <!-- Submit Bar -->
  <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 20px 25px; border: 1px solid var(--summit-border); display: flex; justify-content: space-between; align-items: center;">
    <div>
      <span style="font-size: 12px; color: var(--summit-muted);">
        <i class="fa fa-shield" style="color: var(--summit-green);"></i> All changes are encrypted and governed under NDPA 2023.
      </span>
    </div>
    <button type="submit" id="btnSaveProfile" class="btn-summit-primary" style="font-size: 14px; padding: 10px 28px;">
      <i class="fa fa-check"></i> Save Profile Changes
    </button>
  </div>
</form>
@endsection

@push('scripts')
<script>
  let skillsList = [];
  let careerList = [];
  let educationList = [];

  document.addEventListener('DOMContentLoaded', async () => {
    // 1. Fetch user data from /api/me
    const res = await SummitAPI.get('/me');
    if (!res.success || !res.user) {
      SummitAPI.toast('Failed to load profile data.', 'error');
      return;
    }

    const { user } = res;
    const profile = user.profile || {};
    const record = user.graduate_record || {};

    // Readonly credentials
    const matricEl = document.getElementById('credMatric');
    if (matricEl) matricEl.innerHTML = `<i class="fa fa-shield" style="color: var(--summit-green);"></i> ${user.matric_number}`;

    const yearEl = document.getElementById('credYear');
    if (yearEl) yearEl.textContent = `Class of ${record.graduation_year || 'N/A'} • ${record.department?.name || 'Department'}`;

    const degEl = document.getElementById('credDegree');
    if (degEl) degEl.textContent = record.degree ? `${record.degree} ${record.class_of_degree ? '(' + record.class_of_degree + ')' : ''}` : 'Summit Degree';

    // Populate basic fields
    document.getElementById('fieldName').value = user.name || '';
    document.getElementById('fieldPhone').value = user.phone || '';
    document.getElementById('fieldHeadline').value = profile.headline || '';
    document.getElementById('fieldBio').value = profile.bio || '';

    // Employment & Location
    document.getElementById('fieldJobTitle').value = profile.current_job_title || '';
    document.getElementById('fieldCompany').value = profile.current_company || '';
    if (profile.industry) document.getElementById('fieldIndustry').value = profile.industry;
    document.getElementById('fieldWorkLocation').value = profile.work_location || '';
    document.getElementById('fieldCity').value = profile.city || '';
    document.getElementById('fieldState').value = profile.state || '';
    document.getElementById('fieldCountry').value = profile.country || 'Nigeria';

    // Social Links
    document.getElementById('fieldLinkedin').value = profile.linkedin_url || '';
    document.getElementById('fieldTwitter').value = profile.twitter_url || '';
    document.getElementById('fieldGithub').value = profile.github_url || '';
    document.getElementById('fieldPortfolio').value = profile.portfolio_url || '';

    // Avatar preview
    if (profile.avatar) {
      document.getElementById('profileAvatarPreview').src = profile.avatar;
    }

    // Skills
    skillsList = Array.isArray(profile.skills) ? [...profile.skills] : [];
    renderSkills();

    // Career History
    careerList = Array.isArray(profile.career_history) ? [...profile.career_history] : [];
    renderCareer();

    // Education History
    educationList = Array.isArray(profile.education_history) ? [...profile.education_history] : [];
    renderEducation();
  });

  // Avatar Upload Handler
  const avatarInput = document.getElementById('avatarFileInput');
  avatarInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    if (file.size > 3 * 1024 * 1024) {
      SummitAPI.toast('File is too large. Avatar must be under 3MB.', 'error');
      return;
    }

    const formData = new FormData();
    formData.append('avatar', file);

    const spinner = document.getElementById('avatarUploadSpinner');
    spinner.style.display = 'block';

    try {
      const res = await SummitAPI.post('/profile/avatar', formData);
      spinner.style.display = 'none';

      if (res.success && res.avatar_url) {
        document.getElementById('profileAvatarPreview').src = res.avatar_url;
        const sidebarAvatar = document.getElementById('sidebarUserAvatar');
        if (sidebarAvatar) sidebarAvatar.src = res.avatar_url;
        SummitAPI.toast('Profile photo updated successfully!', 'success');
      } else {
        SummitAPI.toast(res.message || 'Photo upload failed.', 'error');
      }
    } catch (err) {
      spinner.style.display = 'none';
      SummitAPI.toast('Error uploading file.', 'error');
    }
  });

  // Skills Tag Logic
  function renderSkills() {
    const container = document.getElementById('skillChipsContainer');
    if (!skillsList.length) {
      container.innerHTML = '<span style="font-size: 12px; color: var(--summit-muted);">No skills added yet. Type a skill above and click Add.</span>';
      return;
    }

    container.innerHTML = skillsList.map((skill, idx) => `
      <span class="skill-chip">
        ${skill}
        <span class="skill-chip-remove" onclick="removeSkill(${idx})">&times;</span>
      </span>
    `).join('');
  }

  function addSkill(skill) {
    const trimmed = skill.trim();
    if (trimmed && !skillsList.includes(trimmed)) {
      skillsList.push(trimmed);
      renderSkills();
    }
    document.getElementById('skillInput').value = '';
  }

  window.removeSkill = function(idx) {
    skillsList.splice(idx, 1);
    renderSkills();
  };

  document.getElementById('btnAddSkill').addEventListener('click', () => {
    addSkill(document.getElementById('skillInput').value);
  });

  document.getElementById('skillInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      addSkill(e.target.value);
    }
  });

  // Career Timeline Logic
  function renderCareer() {
    const container = document.getElementById('careerTimelineList');
    if (!careerList.length) {
      container.innerHTML = `
        <div style="text-align: center; padding: 20px; color: var(--summit-muted); font-size: 12px;">
          No career experiences added yet. Click "+ Add Position" to add your professional roles.
        </div>
      `;
      return;
    }

    container.innerHTML = careerList.map((item, idx) => `
      <div class="timeline-entry-card">
        <button type="button" class="timeline-entry-remove" onclick="removeCareer(${idx})" title="Remove Experience">
          <i class="fa fa-trash"></i>
        </button>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Role / Position</label>
              <input type="text" class="form-control input-sm" value="${item.role || ''}" onchange="updateCareer(${idx}, 'role', this.value)" placeholder="e.g. Lead Engineer">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Company / Employer</label>
              <input type="text" class="form-control input-sm" value="${item.company || ''}" onchange="updateCareer(${idx}, 'company', this.value)" placeholder="e.g. Microsoft">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Start Year</label>
              <input type="text" class="form-control input-sm" value="${item.start_year || ''}" onchange="updateCareer(${idx}, 'start_year', this.value)" placeholder="e.g. 2021">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">End Year</label>
              <input type="text" class="form-control input-sm" value="${item.end_year || ''}" onchange="updateCareer(${idx}, 'end_year', this.value)" placeholder="e.g. Present">
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Key Role Summary</label>
              <input type="text" class="form-control input-sm" value="${item.description || ''}" onchange="updateCareer(${idx}, 'description', this.value)" placeholder="Brief highlights">
            </div>
          </div>
        </div>
      </div>
    `).join('');
  }

  document.getElementById('btnAddCareer').addEventListener('click', () => {
    careerList.push({ role: '', company: '', start_year: '', end_year: 'Present', description: '' });
    renderCareer();
  });

  window.removeCareer = function(idx) {
    careerList.splice(idx, 1);
    renderCareer();
  };

  window.updateCareer = function(idx, key, val) {
    if (careerList[idx]) careerList[idx][key] = val;
  };

  // Education Timeline Logic
  function renderEducation() {
    const container = document.getElementById('educationTimelineList');
    if (!educationList.length) {
      container.innerHTML = `
        <div style="text-align: center; padding: 20px; color: var(--summit-muted); font-size: 12px;">
          No additional education recorded. Click "+ Add Qualification" to record postgraduate degrees or certifications.
        </div>
      `;
      return;
    }

    container.innerHTML = educationList.map((item, idx) => `
      <div class="timeline-entry-card">
        <button type="button" class="timeline-entry-remove" onclick="removeEducation(${idx})" title="Remove Entry">
          <i class="fa fa-trash"></i>
        </button>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Institution / School</label>
              <input type="text" class="form-control input-sm" value="${item.school || ''}" onchange="updateEducation(${idx}, 'school', this.value)" placeholder="e.g. University of Lagos">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Degree / Qualification</label>
              <input type="text" class="form-control input-sm" value="${item.degree || ''}" onchange="updateEducation(${idx}, 'degree', this.value)" placeholder="e.g. M.Sc. Artificial Intelligence">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Year Awarded</label>
              <input type="text" class="form-control input-sm" value="${item.year || ''}" onchange="updateEducation(${idx}, 'year', this.value)" placeholder="e.g. 2024">
            </div>
          </div>
          <div class="col-sm-8">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-size: 11px; font-weight: 700;">Notes / Specialization</label>
              <input type="text" class="form-control input-sm" value="${item.description || ''}" onchange="updateEducation(${idx}, 'description', this.value)" placeholder="Distinction, Project, etc.">
            </div>
          </div>
        </div>
      </div>
    `).join('');
  }

  document.getElementById('btnAddEducation').addEventListener('click', () => {
    educationList.push({ school: '', degree: '', year: '', description: '' });
    renderEducation();
  });

  window.removeEducation = function(idx) {
    educationList.splice(idx, 1);
    renderEducation();
  };

  window.updateEducation = function(idx, key, val) {
    if (educationList[idx]) educationList[idx][key] = val;
  };

  // Form Submission
  document.getElementById('profileSettingsForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnSaveProfile');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving Profile...';

    const payload = {
      name: document.getElementById('fieldName').value,
      phone: document.getElementById('fieldPhone').value,
      headline: document.getElementById('fieldHeadline').value,
      bio: document.getElementById('fieldBio').value,
      current_job_title: document.getElementById('fieldJobTitle').value,
      current_company: document.getElementById('fieldCompany').value,
      industry: document.getElementById('fieldIndustry').value,
      work_location: document.getElementById('fieldWorkLocation').value,
      city: document.getElementById('fieldCity').value,
      state: document.getElementById('fieldState').value,
      country: document.getElementById('fieldCountry').value,
      linkedin_url: document.getElementById('fieldLinkedin').value || null,
      twitter_url: document.getElementById('fieldTwitter').value || null,
      github_url: document.getElementById('fieldGithub').value || null,
      portfolio_url: document.getElementById('fieldPortfolio').value || null,
      skills: skillsList,
      career_history: careerList.filter(c => c.company || c.role),
      education_history: educationList.filter(e => e.school || e.degree),
    };

    try {
      const res = await SummitAPI.put('/profile', payload);
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-check"></i> Save Profile Changes';

      if (res.success) {
        SummitAPI.toast('Profile details updated successfully!', 'success');
        if (res.user && res.user.name) {
          document.querySelectorAll('.summit-user-name').forEach(el => el.textContent = res.user.name);
        }
      } else {
        SummitAPI.toast(res.message || 'Validation error while saving profile.', 'error');
      }
    } catch (err) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-check"></i> Save Profile Changes';
      SummitAPI.toast('Failed to save profile. Please check your connection.', 'error');
    }
  });
</script>
@endpush
