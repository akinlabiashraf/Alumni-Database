@extends('layouts.app')

@section('title', 'Summit University Alumni Network & Directory — Offa')
@section('meta_description', 'Official Alumni Portal for Summit University, Offa. Verify matriculation number, connect with classmates across graduating sets, and advance your career.')

@section('content')
<!-- ======================= Start Hero Section ===================== -->
<section class="summit-hero-area" style="position: relative; background: linear-gradient(135deg, rgba(0, 77, 40, 0.92) 0%, rgba(0, 104, 55, 0.88) 100%), url('{{ asset('assets/img/summit/Summit University Hereo Section Image.jpeg') }}') center/cover no-repeat; padding: 180px 0 120px; color: #ffffff; min-height: 650px; display: flex; align-items: center;">
  <div class="container">
    <div class="row" style="display: flex; flex-wrap: wrap; align-items: center;">
      <!-- Hero Headline & Introduction -->
      <div class="col-md-7 col-sm-12" data-aos="fade-right">
        <div class="summit-badge-gold" style="margin-bottom: 20px; background: rgba(212, 175, 55, 0.2); border-color: var(--summit-gold); color: #fff;">
          <i class="fa fa-graduation-cap"></i> Official Directorate of Alumni Relations
        </div>
        <h1 style="color: #ffffff; font-size: 44px; font-weight: 800; line-height: 1.25; margin-bottom: 20px; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
          Empowering Summit Graduates <span style="color: var(--summit-gold);">Worldwide</span>
        </h1>
        <p style="font-size: 17px; line-height: 28px; color: #e2e8f0; margin-bottom: 35px; max-width: 580px;">
          Reconnect with classmates from the pioneer 2019 set through 2025. Verify your matriculation record, protect your data under NDPA 2023, and advance your professional journey.
        </p>

        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
          <a href="{{ url('/register') }}" class="btn-summit-gold" style="padding: 13px 28px; font-size: 15px; border-radius: 8px;">
            <i class="fa fa-check-circle"></i> Claim Your Graduate Profile
          </a>
          <a href="{{ url('/directory') }}" class="btn-summit-outline" style="border-color: #ffffff; color: #ffffff !important; padding: 13px 26px; font-size: 15px; border-radius: 8px;">
            <i class="fa fa-search"></i> Browse Directory
          </a>
        </div>
      </div>

      <!-- Quick Matric Verification Widget Card -->
      <div class="col-md-5 col-sm-12" data-aos="fade-left" style="margin-top: 30px;">
        <div style="background: #ffffff; border-radius: 16px; padding: 35px 30px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35); color: var(--summit-text); position: relative; border-top: 5px solid var(--summit-gold);">
          
          <div style="text-align: center; margin-bottom: 25px;">
            <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--summit-green-light); color: var(--summit-green); display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 12px;">
              <i class="fa fa-shield"></i>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 6px;">Instant Matric Verification</h3>
            <p style="font-size: 13px; color: var(--summit-muted); margin: 0;">Check your record in the master registry</p>
          </div>

          <form id="heroVerifyForm">
            <div class="form-group" style="margin-bottom: 20px;">
              <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Matriculation Number</label>
              <div class="input-group">
                <span class="input-group-addon" style="background: #f8fafc; border-right: none;"><i class="fa fa-id-card-o" style="color: var(--summit-green);"></i></span>
                <input type="text" id="heroMatricInput" class="form-control" placeholder="e.g. SU/20/NAS/014" required style="height: 48px; font-size: 14px; text-transform: uppercase;">
              </div>
              <small class="text-muted" style="font-size: 11px; display: block; margin-top: 5px;">Works for all degrees (FNAS, FMSS, FHUM, FEES).</small>
            </div>

            <div id="heroVerifyAlert" style="display: none; font-size: 13px; padding: 12px; border-radius: 6px; margin-bottom: 15px;"></div>

            <button type="submit" id="heroVerifyBtn" class="btn btn-summit-primary" style="width: 100%; height: 48px; justify-content: center; font-size: 14px;">
              <span>Verify Record Now</span> <i class="fa fa-arrow-right"></i>
            </button>
          </form>

          <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 12px; color: var(--summit-muted);">
            <i class="fa fa-lock" style="color: var(--summit-green);"></i> NDPA 2023 Compliant • 100% Data Confidentiality
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ======================= End Hero Section ===================== -->

<!-- ======================= Start Statistics Counter ===================== -->
<section style="background: #ffffff; padding: 50px 0; border-bottom: 1px solid var(--summit-border);">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;" data-aos="zoom-in" data-aos-delay="100">
        <h2 style="font-size: 40px; font-weight: 800; color: var(--summit-green); margin: 0 0 5px;">2019–2025</h2>
        <span style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--summit-muted); letter-spacing: 0.5px;">Graduating Sets</span>
      </div>
      <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;" data-aos="zoom-in" data-aos-delay="200">
        <h2 style="font-size: 40px; font-weight: 800; color: var(--summit-green); margin: 0 0 5px;">4</h2>
        <span style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--summit-muted); letter-spacing: 0.5px;">Accredited Faculties</span>
      </div>
      <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;" data-aos="zoom-in" data-aos-delay="300">
        <h2 style="font-size: 40px; font-weight: 800; color: var(--summit-green); margin: 0 0 5px;">16</h2>
        <span style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--summit-muted); letter-spacing: 0.5px;">Academic Departments</span>
      </div>
      <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;" data-aos="zoom-in" data-aos-delay="400">
        <h2 style="font-size: 40px; font-weight: 800; color: var(--summit-gold-dark); margin: 0 0 5px;">100%</h2>
        <span style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--summit-muted); letter-spacing: 0.5px;">NDPA 2023 Protected</span>
      </div>
    </div>
  </div>
</section>
<!-- ======================= End Statistics Counter ===================== -->

<!-- ======================= Start Pillars / Features ===================== -->
<section style="background: #f8fafc; padding: 90px 0;">
  <div class="container">
    <div class="text-center" style="margin-bottom: 60px;" data-aos="fade-up">
      <div class="summit-badge-verified" style="margin-bottom: 10px;">
        <i class="fa fa-star"></i> University Standards
      </div>
      <h2 style="font-size: 32px; font-weight: 700; color: var(--summit-green-dark);">Why Join the Summit Alumni Platform?</h2>
      <p style="font-size: 15px; color: var(--summit-muted); max-width: 620px; margin: 0 auto;">
        Designed to foster authentic academic records integrity, global networking, and privacy governance.
      </p>
    </div>

    <div class="row">
      <!-- Feature 1 -->
      <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 35px 25px; box-shadow: var(--summit-shadow); height: 100%; border: 1px solid var(--summit-border); transition: transform 0.3s ease;">
          <div style="width: 56px; height: 56px; border-radius: 12px; background: var(--summit-green-light); color: var(--summit-green); display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">
            <i class="fa fa-certificate"></i>
          </div>
          <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--summit-dark);">2-Step Matric Verification</h4>
          <p style="font-size: 14px; color: var(--summit-muted); line-height: 24px; margin: 0;">
            Every account is tied to authenticated university records. No fake profiles, zero impersonation, and atomic duplicate locking.
          </p>
        </div>
      </div>

      <!-- Feature 2 -->
      <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 35px 25px; box-shadow: var(--summit-shadow); height: 100%; border: 1px solid var(--summit-border); transition: transform 0.3s ease;">
          <div style="width: 56px; height: 56px; border-radius: 12px; background: var(--summit-gold-light); color: var(--summit-gold-dark); display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">
            <i class="fa fa-lock"></i>
          </div>
          <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--summit-dark);">Granular NDPA Privacy</h4>
          <p style="font-size: 14px; color: var(--summit-muted); line-height: 24px; margin: 0;">
            Complies with the Nigeria Data Protection Act 2023. You control field-level visibility: choose who sees your phone, email, or employer.
          </p>
        </div>
      </div>

      <!-- Feature 3 -->
      <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 35px 25px; box-shadow: var(--summit-shadow); height: 100%; border: 1px solid var(--summit-border); transition: transform 0.3s ease;">
          <div style="width: 56px; height: 56px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">
            <i class="fa fa-users"></i>
          </div>
          <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--summit-dark);">Global Member Directory</h4>
          <p style="font-size: 14px; color: var(--summit-muted); line-height: 24px; margin: 0;">
            Search classmates by faculty, department, graduating year, location, and industry skills. Reconnect and collaborate across borders.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ======================= End Features ===================== -->

<!-- ======================= Start Campus Heritage Banner ===================== -->
<section style="background: #ffffff; padding: 90px 0;">
  <div class="container">
    <div class="row" style="display: flex; flex-wrap: wrap; align-items: center;">
      <div class="col-md-6 col-sm-12" data-aos="fade-right">
        <div style="position: relative; border-radius: var(--summit-radius-lg); overflow: hidden; box-shadow: var(--summit-shadow-lg);">
          <img src="{{ asset('assets/img/summit/Summit University Senate Building.jpg') }}" alt="Senate Building" style="width: 100%; height: auto; display: block;">
          <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); color: #fff;">
            <h5 style="color: #fff; margin: 0 0 4px; font-size: 16px;">Senate Building, Main Campus</h5>
            <span style="font-size: 12px; color: var(--summit-gold-light);">Summit University, Offa, Kwara State</span>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-sm-12" data-aos="fade-left" style="padding-left: 40px;">
        <div class="summit-badge-gold" style="margin-bottom: 15px;">
          <i class="fa fa-university"></i> Summit University Heritage
        </div>
        <h2 style="font-size: 32px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 20px;">
          Faith, Knowledge & Excellence in Leadership
        </h2>
        <p style="font-size: 15px; color: var(--summit-muted); line-height: 26px; margin-bottom: 25px;">
          Established with a commitment to holistic education, Summit University Offa has graduated visionary minds leading in technology, finance, life sciences, literature, and governance.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0 0 30px; line-height: 32px; font-weight: 600; font-size: 14px; color: var(--summit-dark);">
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 10px;"></i> Direct link to University Alumni & Advancement Directorate</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 10px;"></i> Support for transcript verification and reference requests</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 10px;"></i> Mentorship pipeline for current undergraduates</li>
        </ul>
        <a href="{{ url('/about') }}" class="btn-summit-primary">
          <span>Read Directorate Vision</span> <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- ======================= End Campus Heritage Banner ===================== -->

<!-- ======================= Start Call to Action ===================== -->
<section style="background: linear-gradient(135deg, var(--summit-green-dark) 0%, var(--summit-green) 100%); padding: 75px 0; color: #ffffff;">
  <div class="container text-center" data-aos="zoom-in">
    <h2 style="color: #ffffff; font-size: 32px; font-weight: 800; margin-bottom: 15px;">
      Ready to Reconnect With Your Classmates?
    </h2>
    <p style="color: #e2e8f0; font-size: 16px; max-width: 650px; margin: 0 auto 35px;">
      Verification takes less than 60 seconds. Enter your matriculation number, claim your record, and step into the Summit Alumni community today.
    </p>
    <a href="{{ url('/register') }}" class="btn-summit-gold" style="padding: 14px 34px; font-size: 16px; border-radius: 8px;">
      <i class="fa fa-shield"></i> Start Free Verification Now
    </a>
  </div>
</section>
<!-- ======================= End Call to Action ===================== -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('heroVerifyForm');
  const alertBox = document.getElementById('heroVerifyAlert');
  const btn = document.getElementById('heroVerifyBtn');

  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.style.display = 'none';

      const matric = document.getElementById('heroMatricInput').value.trim();
      if (!matric) return;

      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Checking Records...';

      const res = await SummitAPI.post('/verify-matric', { matric_number: matric });

      btn.disabled = false;
      btn.innerHTML = '<span>Verify Record Now</span> <i class="fa fa-arrow-right"></i>';

      if (res.success && res.status === 'verified') {
        alertBox.className = 'alert alert-success';
        alertBox.innerHTML = `<strong><i class="fa fa-check-circle"></i> Record Found!</strong><br>${res.graduate.full_name} (${res.graduate.degree}, Class of ${res.graduate.graduation_year}). Redirecting to complete registration...`;
        alertBox.style.display = 'block';

        setTimeout(() => {
          window.location.href = `/register?matric=${encodeURIComponent(matric)}&token=${encodeURIComponent(res.verification_token)}`;
        }, 1500);
      } else if (res.status === 'already_claimed') {
        alertBox.className = 'alert alert-warning';
        alertBox.innerHTML = `<strong><i class="fa fa-exclamation-triangle"></i> Already Claimed</strong><br>${res.message}`;
        alertBox.style.display = 'block';
      } else {
        alertBox.className = 'alert alert-danger';
        alertBox.innerHTML = `<strong><i class="fa fa-times-circle"></i> Unmatched Record</strong><br>${res.message} <a href="/register#manual" style="text-decoration: underline; font-weight: 700;">Request manual review</a>.`;
        alertBox.style.display = 'block';
      }
    });
  }
});
</script>
@endpush
