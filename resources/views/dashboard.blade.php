@extends('layouts.dashboard')

@section('title', 'Alumni Dashboard — Summit University, Offa')

@push('styles')
<style>
  .dash-hero {
    background: linear-gradient(135deg, var(--summit-green-dark) 0%, var(--summit-green) 100%);
    border-radius: var(--summit-radius-lg);
    padding: 35px 30px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 10px 25px rgba(0, 104, 55, 0.15);
    position: relative;
    overflow: hidden;
  }
  .dash-hero::after {
    content: '';
    position: absolute;
    right: -20px;
    bottom: -30px;
    width: 180px;
    height: 180px;
    background: radial-gradient(circle, rgba(212, 175, 55, 0.2) 0%, rgba(212, 175, 55, 0) 70%);
    border-radius: 50%;
  }
  .dash-hero-title {
    font-size: 26px;
    font-weight: 700;
    color: #ffffff;
    margin-top: 0;
    margin-bottom: 8px;
  }
  .dash-hero-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 15px;
  }
  .dash-badge {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .dash-badge i {
    color: var(--summit-gold);
  }

  /* Completion Card */
  .completion-card {
    background: #ffffff;
    border-radius: var(--summit-radius-lg);
    padding: 22px 25px;
    border: 1px solid var(--summit-border);
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  }
  .progress-bar-summit {
    background: linear-gradient(90deg, var(--summit-gold) 0%, var(--summit-green-accent) 100%);
    border-radius: 10px;
    transition: width 0.6s ease;
  }

  /* Stat Counter Cards */
  .dash-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 18px;
    margin-bottom: 25px;
  }
  .dash-stat-card {
    background: #ffffff;
    border-radius: var(--summit-radius-lg);
    padding: 22px 20px;
    border: 1px solid var(--summit-border);
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .dash-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
  }
  .dash-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .dash-stat-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--summit-green-dark);
    line-height: 1.1;
    margin-bottom: 3px;
  }
  .dash-stat-label {
    font-size: 12px;
    color: var(--summit-muted);
    font-weight: 500;
  }

  /* Suggested Classmates List */
  .classmate-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid var(--summit-border);
  }
  .classmate-item:last-child {
    border-bottom: none;
  }
  .classmate-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--summit-green-light);
    flex-shrink: 0;
  }
  .announcement-item {
    padding: 16px 0;
    border-bottom: 1px solid var(--summit-border);
  }
  .announcement-item:last-child {
    border-bottom: none;
  }
</style>
@endpush

@section('content')
<!-- Welcome Hero Header -->
<div class="dash-hero">
  <div class="row" style="align-items: center;">
    <div class="col-sm-8">
      <h1 class="dash-hero-title">Welcome back, <span id="dashHeroName">Alumnus</span>!</h1>
      <p style="margin: 0; opacity: 0.9; font-size: 14px; max-width: 580px;">
        Welcome to your official Summit University Alumni Hub. Reconnect with fellow graduates, showcase your professional milestones, and uphold the legacy of Faith, Knowledge & Service.
      </p>
      <div class="dash-hero-badges" id="dashHeroBadges">
        <span class="dash-badge"><i class="fa fa-id-badge"></i> <span id="dashMatric">SU/Alumni</span></span>
        <span class="dash-badge"><i class="fa fa-graduation-cap"></i> <span id="dashGradYear">Summit Alumnus</span></span>
        <span class="dash-badge"><i class="fa fa-shield"></i> NDPA 2023 Protected</span>
      </div>
    </div>
    <div class="col-sm-4 text-right hidden-xs" style="margin-top: 15px;">
      <a href="{{ url('/profile-settings') }}" class="btn-summit-gold" style="font-size: 13px; padding: 9px 18px;">
        <i class="fa fa-edit"></i> Edit Profile
      </a>
      <a id="btnPublicProfile" href="{{ url('/directory') }}" class="btn-summit-outline" style="background: rgba(255,255,255,0.9); font-size: 13px; padding: 8px 16px; margin-top: 8px; display: inline-block;">
        <i class="fa fa-external-link"></i> Public Card
      </a>
    </div>
  </div>
</div>

<!-- Profile Completion Progress Widget -->
<div class="completion-card">
  <div class="row" style="align-items: center;">
    <div class="col-sm-8">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
        <h5 style="margin: 0; font-weight: 700; color: var(--summit-green-dark); font-size: 15px;">
          <i class="fa fa-tachometer" style="color: var(--summit-green); margin-right: 6px;"></i> Profile Strength
        </h5>
        <span id="completionPercentText" style="font-weight: 700; color: var(--summit-green-dark); font-size: 14px;">0%</span>
      </div>
      <div class="progress" style="height: 10px; margin-bottom: 8px; border-radius: 10px; background-color: #f1f5f9;">
        <div id="completionProgressBar" class="progress-bar progress-bar-summit" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <p id="completionPrompt" style="margin: 0; font-size: 12px; color: var(--summit-muted);">
        A complete profile boosts your network discovery and connection requests across the Summit alumni community.
      </p>
    </div>
    <div class="col-sm-4 text-right" style="margin-top: 10px;">
      <a href="{{ url('/profile-settings') }}" class="btn-summit-primary" style="font-size: 12px; padding: 8px 16px;">
        <i class="fa fa-magic"></i> Complete Profile
      </a>
    </div>
  </div>
</div>

<!-- Metrics Counters -->
<div class="dash-stat-grid">
  <!-- Stat 1 -->
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: #e8f5ed; color: var(--summit-green);">
      <i class="fa fa-graduation-cap"></i>
    </div>
    <div>
      <div class="dash-stat-number" id="statClassmatesSet">0</div>
      <div class="dash-stat-label">Graduating Set Peers</div>
    </div>
  </div>

  <!-- Stat 2 -->
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: #fef9c3; color: #b45309;">
      <i class="fa fa-university"></i>
    </div>
    <div>
      <div class="dash-stat-number" id="statDeptAlumni">0</div>
      <div class="dash-stat-label">Department Alumni</div>
    </div>
  </div>

  <!-- Stat 3 -->
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: #e0f2fe; color: #0284c7;">
      <i class="fa fa-users"></i>
    </div>
    <div>
      <div class="dash-stat-number" id="statTotalAlumni">0</div>
      <div class="dash-stat-label">Verified Network</div>
    </div>
  </div>

  <!-- Stat 4 -->
  <div class="dash-stat-card">
    <div class="dash-stat-icon" style="background: #f1f5f9; color: #475569;">
      <i class="fa fa-shield"></i>
    </div>
    <div>
      <div class="dash-stat-number" style="font-size: 16px; font-weight: 700; color: #166534;">NDPA 2023</div>
      <div class="dash-stat-label">Privacy Shield Active</div>
    </div>
  </div>
</div>

<!-- Two-Column Layout -->
<div class="row">
  <!-- Left Column: Suggested Classmates & Quick Actions -->
  <div class="col-md-7">
    <!-- Suggested Classmates -->
    <div class="summit-dash-card" style="padding: 25px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid var(--summit-border); padding-bottom: 12px;">
        <h4 style="margin: 0; font-size: 17px; font-weight: 700;">
          <i class="fa fa-users" style="color: var(--summit-green); margin-right: 8px;"></i> Classmates in Your Circle
        </h4>
        <a href="{{ url('/directory') }}" style="font-size: 12px; font-weight: 600; color: var(--summit-green);">
          View Full Directory &rarr;
        </a>
      </div>

      <div id="suggestedClassmatesList">
        <!-- Rendered dynamically -->
        <div style="text-align: center; padding: 30px; color: var(--summit-muted);">
          <i class="fa fa-spinner fa-spin fa-2x" style="color: var(--summit-green);"></i>
          <p style="margin-top: 8px; font-size: 13px;">Finding classmates in your graduating set...</p>
        </div>
      </div>
    </div>

    <!-- Quick Shortcuts Card -->
    <div class="summit-dash-card" style="padding: 25px;">
      <h4 style="margin: 0 0 16px; font-size: 17px; font-weight: 700;">
        <i class="fa fa-bolt" style="color: var(--summit-gold-dark); margin-right: 8px;"></i> Quick Actions
      </h4>
      <div class="row">
        <div class="col-sm-6" style="margin-bottom: 12px;">
          <a href="{{ url('/profile-settings') }}" style="display: block; padding: 14px; background: #f8fafc; border: 1px solid var(--summit-border); border-radius: var(--summit-radius); text-decoration: none; color: inherit; transition: all 0.2s ease;">
            <div style="font-weight: 700; color: var(--summit-green-dark); font-size: 13px; margin-bottom: 4px;">
              <i class="fa fa-briefcase" style="color: var(--summit-green); margin-right: 6px;"></i> Update Career History
            </div>
            <div style="font-size: 11px; color: var(--summit-muted);">Add your current company and job role.</div>
          </a>
        </div>
        <div class="col-sm-6" style="margin-bottom: 12px;">
          <a href="{{ url('/privacy-settings') }}" style="display: block; padding: 14px; background: #f8fafc; border: 1px solid var(--summit-border); border-radius: var(--summit-radius); text-decoration: none; color: inherit; transition: all 0.2s ease;">
            <div style="font-weight: 700; color: var(--summit-green-dark); font-size: 13px; margin-bottom: 4px;">
              <i class="fa fa-lock" style="color: var(--summit-gold-dark); margin-right: 6px;"></i> NDPA Privacy Settings
            </div>
            <div style="font-size: 11px; color: var(--summit-muted);">Choose who can view your phone & email.</div>
          </a>
        </div>
        <div class="col-sm-6" style="margin-bottom: 12px;">
          <a href="{{ url('/directory') }}" style="display: block; padding: 14px; background: #f8fafc; border: 1px solid var(--summit-border); border-radius: var(--summit-radius); text-decoration: none; color: inherit; transition: all 0.2s ease;">
            <div style="font-weight: 700; color: var(--summit-green-dark); font-size: 13px; margin-bottom: 4px;">
              <i class="fa fa-search" style="color: var(--summit-green); margin-right: 6px;"></i> Faceted Directory
            </div>
            <div style="font-size: 11px; color: var(--summit-muted);">Search by Faculty, Department or Set.</div>
          </a>
        </div>
        <div class="col-sm-6" style="margin-bottom: 12px;">
          <a href="{{ url('/change-password') }}" style="display: block; padding: 14px; background: #f8fafc; border: 1px solid var(--summit-border); border-radius: var(--summit-radius); text-decoration: none; color: inherit; transition: all 0.2s ease;">
            <div style="font-weight: 700; color: var(--summit-green-dark); font-size: 13px; margin-bottom: 4px;">
              <i class="fa fa-key" style="color: #6366f1; margin-right: 6px;"></i> Security & Password
            </div>
            <div style="font-size: 11px; color: var(--summit-muted);">Manage your portal credentials.</div>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column: University News & Directorate Announcements -->
  <div class="col-md-5">
    <!-- University Announcements -->
    <div class="summit-dash-card" style="padding: 25px;">
      <h4 style="margin: 0 0 16px; font-size: 17px; font-weight: 700; border-bottom: 1px solid var(--summit-border); padding-bottom: 12px;">
        <i class="fa fa-bullhorn" style="color: var(--summit-green); margin-right: 8px;"></i> University Updates
      </h4>

      <div id="dashAnnouncementsList">
        <!-- Rendered dynamically -->
      </div>
    </div>

    <!-- Directorate Support Card -->
    <div class="summit-dash-card" style="padding: 22px; background: #f0fdf4; border-color: rgba(0, 104, 55, 0.2);">
      <h5 style="margin: 0 0 8px; font-weight: 700; color: var(--summit-green-dark); font-size: 14px;">
        <i class="fa fa-phone" style="margin-right: 6px;"></i> Directorate of Alumni Relations
      </h5>
      <p style="font-size: 12px; color: var(--summit-text); margin-bottom: 10px; line-height: 1.5;">
        Need assistance with academic transcripts, certificate verification, or membership inquiries?
      </p>
      <div style="font-size: 12px; color: var(--summit-text); line-height: 1.6;">
        <div><strong>Email:</strong> alumni@summituniversity.edu.ng</div>
        <div><strong>Campus:</strong> Permanent Site, Offa, Kwara State</div>
      </div>
      <div style="margin-top: 12px;">
        <a href="{{ url('/contact') }}" class="btn-summit-outline" style="font-size: 11px; padding: 6px 14px; background: #ffffff;">
          Contact Directorate
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async () => {
    try {
      const res = await SummitAPI.get('/dashboard/stats');
      if (!res.success) {
        SummitAPI.toast('Failed to load dashboard metrics.', 'error');
        return;
      }

      const { user, stats } = res;

      // Populate hero
      const heroName = document.getElementById('dashHeroName');
      if (heroName) heroName.textContent = user.name;

      const matricEl = document.getElementById('dashMatric');
      if (matricEl) matricEl.textContent = user.matric_number || 'Matric Verified';

      const gradYearEl = document.getElementById('dashGradYear');
      if (gradYearEl && user.academic) {
        gradYearEl.textContent = `Class of ${user.academic.graduation_year || ''} • ${user.academic.department || ''}`;
      }

      const btnPublic = document.getElementById('btnPublicProfile');
      if (btnPublic) {
        btnPublic.href = `/alumni/${user.id}`;
      }

      // Populate completion
      const pct = stats.completion_percentage || 20;
      const pctText = document.getElementById('completionPercentText');
      const progressBar = document.getElementById('completionProgressBar');
      if (pctText) pctText.textContent = `${pct}%`;
      if (progressBar) progressBar.style.width = `${pct}%`;

      const promptEl = document.getElementById('completionPrompt');
      if (promptEl) {
        if (pct >= 90) {
          promptEl.innerHTML = '<span style="color: var(--summit-green); font-weight: 600;"><i class="fa fa-check-circle"></i> Outstanding! Your profile is comprehensive and fully visible in the directory.</span>';
        } else if (pct >= 50) {
          promptEl.textContent = 'Great progress! Add your career timeline and skills to reach 100% completion.';
        } else {
          promptEl.textContent = 'Complete your profile details, career history, and avatar to maximize discovery.';
        }
      }

      // Populate stat numbers
      const classmatesSetEl = document.getElementById('statClassmatesSet');
      if (classmatesSetEl) classmatesSetEl.textContent = stats.classmates_in_set || 0;

      const deptAlumniEl = document.getElementById('statDeptAlumni');
      if (deptAlumniEl) deptAlumniEl.textContent = stats.department_alumni || 0;

      const totalAlumniEl = document.getElementById('statTotalAlumni');
      if (totalAlumniEl) totalAlumniEl.textContent = stats.total_alumni || 0;

      // Populate Suggested Classmates
      const listEl = document.getElementById('suggestedClassmatesList');
      if (listEl) {
        if (stats.suggested_classmates && stats.suggested_classmates.length > 0) {
          listEl.innerHTML = stats.suggested_classmates.map(peer => {
            const avatar = peer.avatar || '{{ asset("assets/img/user-profile.png") }}';
            const headline = peer.headline || (peer.current_job_title ? `${peer.current_job_title} at ${peer.current_company || ''}` : `${peer.department || 'Summit Alumnus'}`);
            return `
              <div class="classmate-item">
                <div style="display: flex; align-items: center; gap: 14px;">
                  <img src="${avatar}" class="classmate-avatar" alt="${peer.name}" onerror="this.src='{{ asset("assets/img/user-profile.png") }}'">
                  <div>
                    <h5 style="margin: 0 0 3px; font-size: 14px; font-weight: 700; color: var(--summit-green-dark);">
                      <a href="/alumni/${peer.id}" style="color: inherit; text-decoration: none;">${peer.name}</a>
                    </h5>
                    <div style="font-size: 12px; color: var(--summit-muted); max-width: 320px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                      ${headline}
                    </div>
                    <div style="font-size: 11px; color: var(--summit-gold-dark); font-weight: 600; margin-top: 2px;">
                      Class of ${peer.graduation_year || ''}
                    </div>
                  </div>
                </div>
                <div>
                  <a href="/alumni/${peer.id}" class="btn-summit-outline" style="font-size: 11px; padding: 5px 12px; white-space: nowrap;">
                    View Profile
                  </a>
                </div>
              </div>
            `;
          }).join('');
        } else {
          listEl.innerHTML = `
            <div style="text-align: center; padding: 25px 15px; color: var(--summit-muted);">
              <i class="fa fa-users" style="font-size: 28px; margin-bottom: 8px; opacity: 0.5;"></i>
              <p style="margin: 0; font-size: 13px;">No other registered classmates found yet in this graduating set. Encourage your peers to claim their profiles!</p>
            </div>
          `;
        }
      }

      // Populate Announcements
      const annListEl = document.getElementById('dashAnnouncementsList');
      if (annListEl && stats.announcements) {
        annListEl.innerHTML = stats.announcements.map(ann => `
          <div class="announcement-item">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
              <span class="summit-badge-gold" style="font-size: 10px; padding: 2px 8px;">${ann.category}</span>
              <span style="font-size: 11px; color: var(--summit-muted);"><i class="fa fa-calendar-o"></i> ${ann.date}</span>
            </div>
            <h5 style="margin: 0 0 4px; font-size: 14px; font-weight: 700; color: var(--summit-green-dark);">
              ${ann.title}
            </h5>
            <p style="margin: 0 0 6px; font-size: 12px; color: var(--summit-text); line-height: 1.4;">
              ${ann.summary}
            </p>
            <a href="${ann.link}" style="font-size: 11px; font-weight: 600; color: var(--summit-green);">
              Learn more &rarr;
            </a>
          </div>
        `).join('');
      }

    } catch (err) {
      console.error(err);
      SummitAPI.toast('An error occurred while loading dashboard data.', 'error');
    }
  });
</script>
@endpush
