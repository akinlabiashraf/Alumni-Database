@extends('layouts.app')

@section('title', 'Platform Features — Summit University Alumni Network')
@section('meta_description', 'Explore the capabilities of the Summit University Alumni Database: verification engine, privacy protection, faceted search, and member portal.')

@section('content')
<!-- ======================= Page Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Entrance Gate.jpg') }}') center/cover no-repeat; padding: 140px 0 70px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 15px;">
      <i class="fa fa-cubes"></i> Modern Technology Platform
    </div>
    <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px;">Platform Architecture & Features</h1>
    <p style="color: #cbd5e1; font-size: 16px; max-width: 650px; margin: 0 auto;">
      A secure, privacy-governed alumni ecosystem purpose-built for Summit University Offa graduates.
    </p>
  </div>
</section>

<!-- ======================= Deep Dive Features ===================== -->
<section style="background: #ffffff; padding: 80px 0;">
  <div class="container">
    
    <!-- Feature 1: Verification Engine -->
    <div class="row" style="display: flex; flex-wrap: wrap; align-items: center; margin-bottom: 80px;">
      <div class="col-md-6 col-sm-12" data-aos="fade-right">
        <div class="summit-badge-verified" style="margin-bottom: 12px;">
          <i class="fa fa-shield"></i> Security & Integrity
        </div>
        <h2 style="font-size: 28px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 15px;">
          1. Two-Step Matriculation Verification
        </h2>
        <p style="font-size: 15px; color: var(--summit-muted); line-height: 26px; margin-bottom: 20px;">
          To guarantee 100% authentic community membership, access requires validating official Summit University credentials against our master registry.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; line-height: 30px; font-size: 14px; color: var(--summit-dark); font-weight: 600;">
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Real-time matching against graduating sets (2019 to 2025)</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Cryptographic HMAC anti-tampering verification tokens</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Atomic claim locking preventing duplicate or fraudulent registrations</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Moderator escalation queue with document proof verification</li>
        </ul>
      </div>
      <div class="col-md-6 col-sm-12" data-aos="fade-left" style="margin-top: 20px;">
        <div style="background: #f8fafc; border: 2px dashed var(--summit-green); border-radius: var(--summit-radius-lg); padding: 35px 30px; text-align: center;">
          <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--summit-green-light); color: var(--summit-green); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
            <i class="fa fa-id-card"></i>
          </div>
          <h4 style="color: var(--summit-green-dark); font-weight: 700; margin-bottom: 8px;">Instant Record Match</h4>
          <p style="font-size: 13px; color: var(--summit-muted); margin-bottom: 20px;">Try entering a sample matriculation number from your set to test the live engine.</p>
          <a href="{{ url('/register') }}" class="btn-summit-primary">
            <span>Test Verification Flow</span> <i class="fa fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Feature 2: NDPA 2023 Privacy -->
    <div class="row" style="display: flex; flex-wrap: wrap; align-items: center; margin-bottom: 80px;">
      <div class="col-md-6 col-md-push-6 col-sm-12" data-aos="fade-left">
        <div class="summit-badge-gold" style="margin-bottom: 12px;">
          <i class="fa fa-lock"></i> Legal Compliance
        </div>
        <h2 style="font-size: 28px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 15px;">
          2. Nigeria Data Protection Act (NDPA 2023)
        </h2>
        <p style="font-size: 15px; color: var(--summit-muted); line-height: 26px; margin-bottom: 20px;">
          Your privacy is non-negotiable. Our platform gives every alumnus granular, per-field privacy toggles controlling who can view sensitive information.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; line-height: 30px; font-size: 14px; color: var(--summit-dark); font-weight: 600;">
          <li><i class="fa fa-check-circle" style="color: var(--summit-gold-dark); margin-right: 8px;"></i> Independent toggles for Email, Phone, Employer, and Location</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-gold-dark); margin-right: 8px;"></i> Four visibility tiers: <code>Public</code>, <code>Alumni Only</code>, <code>Private</code></li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-gold-dark); margin-right: 8px;"></i> Direct consent audit logs and data subject rights management</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-gold-dark); margin-right: 8px;"></i> Zero unauthorized third-party commercial tracking or advertising</li>
        </ul>
      </div>
      <div class="col-md-6 col-md-pull-6 col-sm-12" data-aos="fade-right" style="margin-top: 20px;">
        <div style="background: #ffffff; border: 1px solid var(--summit-border); border-radius: var(--summit-radius-lg); padding: 30px; box-shadow: var(--summit-shadow-lg);">
          <h5 style="color: var(--summit-dark); font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
            <span>Granular Privacy Controls</span>
            <span class="summit-ndpa-badge"><i class="fa fa-shield"></i> NDPA 2023</span>
          </h5>
          <div style="font-size: 13px; line-height: 28px;">
            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
              <span>Email Visibility</span>
              <span class="badge" style="background: #10b981;">Alumni Only</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
              <span>Phone Number</span>
              <span class="badge" style="background: #ef4444;">Private (Hidden)</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
              <span>Current Employer</span>
              <span class="badge" style="background: #3b82f6;">Public</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 0;">
              <span>Direct Messaging</span>
              <span class="badge" style="background: #10b981;">Enabled</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Feature 3: Faceted Directory -->
    <div class="row" style="display: flex; flex-wrap: wrap; align-items: center;">
      <div class="col-md-6 col-sm-12" data-aos="fade-right">
        <div class="summit-badge-verified" style="margin-bottom: 12px;">
          <i class="fa fa-search"></i> Networking
        </div>
        <h2 style="font-size: 28px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 15px;">
          3. Faceted Search Alumni Directory
        </h2>
        <p style="font-size: 15px; color: var(--summit-muted); line-height: 26px; margin-bottom: 20px;">
          Find fellow Summit graduates in your city, discover alumni working at companies you admire, or recruit top talent from specific academic departments.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; line-height: 30px; font-size: 14px; color: var(--summit-dark); font-weight: 600;">
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Live faceted filters: Faculty, Department, Graduating Set</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Industry filter: Technology, Finance, Health, Media, Government</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Location filtering across Nigerian states and international cities</li>
          <li><i class="fa fa-check-circle" style="color: var(--summit-green); margin-right: 8px;"></i> Grid and list directory card view toggling</li>
        </ul>
      </div>
      <div class="col-md-6 col-sm-12" data-aos="fade-left" style="margin-top: 20px;">
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; border-radius: var(--summit-radius-lg); padding: 35px 30px; box-shadow: var(--summit-shadow-lg);">
          <h4 style="color: #ffffff; font-weight: 700; margin-bottom: 12px;">
            <i class="fa fa-users" style="color: var(--summit-gold);"></i> Connected Globally
          </h4>
          <p style="font-size: 14px; color: #94a3b8; line-height: 24px; margin-bottom: 25px;">
            Alumni from all 16 departments across Lagos, Abuja, London, Toronto, and beyond are connecting right now.
          </p>
          <a href="{{ url('/directory') }}" class="btn-summit-gold" style="padding: 11px 24px; font-size: 14px;">
            <i class="fa fa-search"></i> Open Alumni Directory
          </a>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
