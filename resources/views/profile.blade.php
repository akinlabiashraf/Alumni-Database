@extends('layouts.app')

@section('title', 'Alumnus Profile — Summit University, Offa')
@section('meta_description', 'View verified academic and professional profile of Summit University graduate.')

@section('content')
<!-- ======================= Header Cover ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Senate Building.jpg') }}') center/cover no-repeat; padding: 130px 0 60px; color: #ffffff;">
  <div class="container text-center">
    <div class="summit-badge-gold" style="margin-bottom: 10px;">
      <i class="fa fa-graduation-cap"></i> Verified Member Profile
    </div>
    <h1 id="profilePageTitle" style="color: #ffffff; font-size: 32px; font-weight: 800; margin: 0 0 6px;">
      Loading Alumnus Profile...
    </h1>
    <p id="profilePageSubtitle" style="color: #cbd5e1; font-size: 14px; margin: 0;">
      Summit University, Offa &bull; Directorate of Alumni Relations
    </p>
  </div>
</section>

<!-- ======================= Profile Body Area ===================== -->
<section style="background: #f8fafc; padding: 50px 0 100px;">
  <div class="container">
    
    <div id="profileLoadingSpinner" style="text-align: center; padding: 80px 0;">
      <i class="fa fa-spinner fa-spin fa-3x" style="color: var(--summit-green); margin-bottom: 15px;"></i>
      <p style="color: var(--summit-muted); font-size: 15px;">Retrieving verified credentials...</p>
    </div>

    <div id="profileErrorBox" style="display: none;">
      <div style="background: #ffffff; border-radius: 12px; padding: 60px 30px; text-align: center; border: 1px dashed var(--summit-border); max-width: 600px; margin: 0 auto;">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
          <i class="fa fa-lock"></i>
        </div>
        <h4 id="profileErrorHeading" style="font-weight: 700; color: var(--summit-dark); margin-bottom: 8px;">Access Restricted</h4>
        <p id="profileErrorMessage" style="font-size: 14px; color: var(--summit-muted); margin-bottom: 25px;">
          This profile cannot be displayed.
        </p>
        <a href="{{ url('/directory') }}" class="btn btn-summit-outline">
          <i class="fa fa-arrow-left"></i> Return to Directory
        </a>
      </div>
    </div>

    <!-- Main Profile Content (Initially hidden while fetching) -->
    <div id="profileContentArea" class="row" style="display: none;">
      
      <!-- Left Column: Bio Card & Contacts -->
      <div class="col-md-4 col-sm-12" style="margin-bottom: 30px;">
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 35px 25px; box-shadow: var(--summit-shadow); border: 1px solid var(--summit-border); text-align: center;">
          
          <img id="profAvatar" src="{{ asset('assets/img/user-profile.png') }}" alt="Avatar" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid var(--summit-green); object-fit: cover; margin: 0 auto 18px; display: block; background: var(--summit-green-light);">
          
          <h3 id="profName" style="font-size: 22px; font-weight: 800; color: var(--summit-dark); margin: 0 0 6px;">
            Alumnus Name
          </h3>

          <div style="margin-bottom: 12px;">
            <span class="summit-badge-verified">
              <i class="fa fa-check-circle"></i> Verified Summit Alumnus
            </span>
          </div>

          <p id="profHeadline" style="font-size: 14px; color: var(--summit-muted); line-height: 22px; margin-bottom: 20px;">
            Headline
          </p>

          <div style="border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 15px 0; margin-bottom: 20px; font-size: 13px; line-height: 24px; text-align: left;">
            <div><i class="fa fa-graduation-cap" style="color: var(--summit-gold-dark); width: 20px;"></i> <span id="profSet">Set of 2019</span></div>
            <div><i class="fa fa-briefcase" style="color: var(--summit-green); width: 20px;"></i> <span id="profWork">Company</span></div>
            <div><i class="fa fa-map-marker" style="color: var(--summit-gold-dark); width: 20px;"></i> <span id="profLocation">Location</span></div>
          </div>

          <!-- NDPA Protected Contact Info -->
          <div style="text-align: left; margin-bottom: 20px;">
            <h5 style="font-weight: 700; color: var(--summit-dark); margin-bottom: 12px; font-size: 13px;">
              <i class="fa fa-address-book-o" style="color: var(--summit-green);"></i> Contact Channels
            </h5>

            <div id="profEmailRow" style="font-size: 13px; margin-bottom: 8px;">
              <i class="fa fa-envelope" style="color: var(--summit-muted); width: 20px;"></i>
              <span id="profEmail">alumnus@example.com</span>
            </div>

            <div id="profPhoneRow" style="font-size: 13px; margin-bottom: 8px;">
              <i class="fa fa-phone" style="color: var(--summit-muted); width: 20px;"></i>
              <span id="profPhone">+234 800 000 0000</span>
            </div>
          </div>

          <!-- Social Links -->
          <div id="profSocialRow" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
            <a id="profLinkedin" href="#" target="_blank" class="btn btn-sm btn-default" style="display: none; border-radius: 50%; width: 36px; height: 36px; line-height: 22px;"><i class="fa fa-linkedin"></i></a>
            <a id="profGithub" href="#" target="_blank" class="btn btn-sm btn-default" style="display: none; border-radius: 50%; width: 36px; height: 36px; line-height: 22px;"><i class="fa fa-github"></i></a>
            <a id="profTwitter" href="#" target="_blank" class="btn btn-sm btn-default" style="display: none; border-radius: 50%; width: 36px; height: 36px; line-height: 22px;"><i class="fa fa-twitter"></i></a>
          </div>

          <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
            <a href="{{ url('/directory') }}" class="btn btn-summit-outline" style="width: 100%; justify-content: center; font-size: 13px;">
              <i class="fa fa-arrow-left"></i> Back to Directory
            </a>
          </div>

        </div>
      </div>

      <!-- Right Column: Academic Credentials, Bio, Career & Education -->
      <div class="col-md-8 col-sm-12">
        
        <!-- Academic Credentials Card -->
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1.5px solid #a7f3d0; border-radius: var(--summit-radius-lg); padding: 30px; margin-bottom: 30px; box-shadow: var(--summit-shadow);">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
            <h4 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--summit-green-dark);">
              <i class="fa fa-certificate" style="color: var(--summit-gold-dark);"></i> Official Academic Credentials
            </h4>
            <span class="summit-badge-gold">Verified Summit University Archive</span>
          </div>

          <div class="row" style="font-size: 14px; line-height: 26px; color: var(--summit-text);">
            <div class="col-sm-6">
              <strong>Degree Conferred:</strong> <span id="profDegree">B.Sc. Computer Science</span><br>
              <strong>Class of Degree:</strong> <span id="profClassDegree">First Class Honours</span>
            </div>
            <div class="col-sm-6">
              <strong>Faculty:</strong> <span id="profFaculty">Natural & Applied Sciences</span><br>
              <strong>Department:</strong> <span id="profDept">Computer Science</span>
            </div>
          </div>
        </div>

        <!-- Biography Card -->
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 30px; border: 1px solid var(--summit-border); box-shadow: var(--summit-shadow); margin-bottom: 30px;">
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 15px;">
            About Alumnus
          </h4>
          <p id="profBio" style="font-size: 14px; color: var(--summit-muted); line-height: 26px; margin: 0;">
            No bio provided yet.
          </p>
        </div>

        <!-- Skills Card -->
        <div id="profSkillsCard" style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 30px; border: 1px solid var(--summit-border); box-shadow: var(--summit-shadow); margin-bottom: 30px;">
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 15px;">
            Skills & Core Competencies
          </h4>
          <div id="profSkillsList" style="display: flex; gap: 8px; flex-wrap: wrap;">
            <!-- Skills badges -->
          </div>
        </div>

        <!-- Career History Timeline -->
        <div id="profCareerCard" style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 30px; border: 1px solid var(--summit-border); box-shadow: var(--summit-shadow); margin-bottom: 30px;">
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 20px;">
            <i class="fa fa-briefcase" style="color: var(--summit-green);"></i> Career & Experience
          </h4>
          <div id="profCareerList" style="border-left: 2px solid var(--summit-green-light); padding-left: 20px; margin-left: 10px;">
            <!-- Career timeline items -->
          </div>
        </div>

        <!-- Education History Timeline -->
        <div id="profEduCard" style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 30px; border: 1px solid var(--summit-border); box-shadow: var(--summit-shadow);">
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 20px;">
            <i class="fa fa-graduation-cap" style="color: var(--summit-gold-dark);"></i> Education History
          </h4>
          <div id="profEduList" style="border-left: 2px solid var(--summit-gold-light); padding-left: 20px; margin-left: 10px;">
            <!-- Education timeline items -->
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
  // Extract ID from pathname (e.g. /alumni/3)
  const pathParts = window.location.pathname.split('/');
  const alumnusId = pathParts[pathParts.length - 1];

  const spinner = document.getElementById('profileLoadingSpinner');
  const errorBox = document.getElementById('profileErrorBox');
  const contentArea = document.getElementById('profileContentArea');

  if (!alumnusId || isNaN(alumnusId)) {
    spinner.style.display = 'none';
    errorBox.style.display = 'block';
    document.getElementById('profileErrorMessage').textContent = 'Invalid alumni profile ID specified.';
    return;
  }

  const res = await SummitAPI.get(`/alumni/${alumnusId}`);
  spinner.style.display = 'none';

  if (!res.success || !res.alumnus) {
    errorBox.style.display = 'block';
    if (res.requires_auth) {
      document.getElementById('profileErrorHeading').textContent = 'Alumni Sign-In Required';
      document.getElementById('profileErrorMessage').innerHTML = `${res.message} <br><br><a href="/login" class="btn btn-summit-primary">Sign In to View</a>`;
    } else {
      document.getElementById('profileErrorMessage').textContent = res.message || 'Alumnus profile not found.';
    }
    return;
  }

  const a = res.alumnus;
  document.getElementById('profilePageTitle').textContent = a.name;
  document.getElementById('profilePageSubtitle').textContent = `${a.degree || 'Alumnus'} &bull; Class of ${a.graduation_year || ''}`;

  document.getElementById('profName').textContent = a.name;
  if (a.avatar) document.getElementById('profAvatar').src = a.avatar;
  document.getElementById('profHeadline').textContent = a.headline || 'Summit University Graduate';
  document.getElementById('profSet').textContent = `Class of ${a.graduation_year || 'N/A'}`;
  document.getElementById('profWork').textContent = a.current_job_title && a.current_company 
    ? `${a.current_job_title} @ ${a.current_company}` 
    : (a.industry || 'Industry not specified');
  document.getElementById('profLocation').textContent = a.location_display || 'Nigeria';

  // Email & Phone
  const emailEl = document.getElementById('profEmail');
  if (a.email) {
    emailEl.innerHTML = `<a href="mailto:${a.email}" style="color: var(--summit-green); font-weight: 600;">${a.email}</a>`;
  } else {
    emailEl.innerHTML = '<span class="text-muted" style="font-style: italic;">Protected per NDPA 2023</span>';
  }

  const phoneEl = document.getElementById('profPhone');
  if (a.phone) {
    phoneEl.textContent = a.phone;
  } else {
    phoneEl.innerHTML = '<span class="text-muted" style="font-style: italic;">Protected per NDPA 2023</span>';
  }

  // Social Links
  if (a.linkedin_url) {
    const lk = document.getElementById('profLinkedin');
    lk.href = a.linkedin_url;
    lk.style.display = 'inline-block';
  }
  if (a.github_url) {
    const gh = document.getElementById('profGithub');
    gh.href = a.github_url;
    gh.style.display = 'inline-block';
  }
  if (a.twitter_url) {
    const tw = document.getElementById('profTwitter');
    tw.href = a.twitter_url;
    tw.style.display = 'inline-block';
  }

  // Academic Credentials
  document.getElementById('profDegree').textContent = a.degree || 'B.Sc.';
  document.getElementById('profClassDegree').textContent = a.class_of_degree || 'Conferred';
  document.getElementById('profFaculty').textContent = a.faculty || 'Summit University';
  document.getElementById('profDept').textContent = a.department || 'Academic Department';

  // Bio
  if (a.bio) document.getElementById('profBio').textContent = a.bio;

  // Skills
  const skillsList = document.getElementById('profSkillsList');
  if (a.skills && a.skills.length > 0) {
    skillsList.innerHTML = a.skills.map(s => `
      <span class="badge" style="background: var(--summit-green-light); color: var(--summit-green-dark); font-size: 12px; font-weight: 600; padding: 6px 12px;">
        ${s}
      </span>
    `).join('');
  } else {
    document.getElementById('profSkillsCard').style.display = 'none';
  }

  // Career History
  const careerList = document.getElementById('profCareerList');
  if (a.career_history && a.career_history.length > 0) {
    careerList.innerHTML = a.career_history.map(item => `
      <div style="margin-bottom: 20px; position: relative;">
        <div style="position: absolute; left: -26px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--summit-green);"></div>
        <h5 style="margin: 0; font-weight: 700; color: var(--summit-dark); font-size: 15px;">${item.role}</h5>
        <div style="font-size: 13px; color: var(--summit-green-dark); font-weight: 600;">${item.company} &bull; <span style="color: var(--summit-muted); font-weight: 400;">${item.period}</span></div>
        ${item.description ? `<p style="font-size: 13px; color: var(--summit-muted); margin-top: 5px; line-height: 20px;">${item.description}</p>` : ''}
      </div>
    `).join('');
  } else {
    document.getElementById('profCareerCard').style.display = 'none';
  }

  // Education History
  const eduList = document.getElementById('profEduList');
  if (a.education_history && a.education_history.length > 0) {
    eduList.innerHTML = a.education_history.map(item => `
      <div style="margin-bottom: 20px; position: relative;">
        <div style="position: absolute; left: -26px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--summit-gold-dark);"></div>
        <h5 style="margin: 0; font-weight: 700; color: var(--summit-dark); font-size: 15px;">${item.degree}</h5>
        <div style="font-size: 13px; color: var(--summit-green-dark); font-weight: 600;">${item.school} &bull; <span style="color: var(--summit-muted); font-weight: 400;">${item.year}</span></div>
      </div>
    `).join('');
  } else {
    document.getElementById('profEduCard').style.display = 'none';
  }

  // Reveal content
  contentArea.style.display = 'flex';
});
</script>
@endpush
