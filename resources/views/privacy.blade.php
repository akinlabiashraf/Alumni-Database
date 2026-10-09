@extends('layouts.app')

@section('title', 'Privacy Policy & NDPA 2023 Compliance — Summit University, Offa')
@section('meta_description', 'Official Privacy Statement and Nigeria Data Protection Act (NDPA 2023) compliance framework for Summit University alumni.')

@section('content')
<!-- ======================= Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Senate Building.jpg') }}') center/cover no-repeat; padding: 140px 0 70px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 15px;">
      <i class="fa fa-shield"></i> Legal & Data Governance
    </div>
    <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px;">Privacy Policy & NDPA 2023 Statement</h1>
    <p style="color: #cbd5e1; font-size: 16px; max-width: 650px; margin: 0 auto;">
      How Summit University collects, protects, and governs personal alumni information.
    </p>
  </div>
</section>

<!-- ======================= Privacy Content ===================== -->
<section style="background: #ffffff; padding: 80px 0;">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-sm-12" style="font-size: 14px; line-height: 28px; color: var(--summit-text);">
        
        <div class="summit-ndpa-badge" style="margin-bottom: 25px; padding: 8px 16px; font-size: 13px;">
          <i class="fa fa-balance-scale"></i> In Full Compliance with the Nigeria Data Protection Act (NDPA 2023)
        </div>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 10px;">1. Introduction & Data Controller</h3>
        <p>
          Summit University, Offa ("the University", "we", "our") is committed to safeguarding the personal data of its graduates, faculty, students, and associates. This Privacy Policy outlines our practices regarding data collection, processing, storage, and rights under the <strong>Nigeria Data Protection Act (NDPA) 2023</strong> and international privacy standards.
        </p>
        <p>
          The Data Controller is: <strong>Summit University, PMB 4412, Irra Road, Offa, Kwara State, Nigeria</strong>.
        </p>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">2. Lawful Basis for Processing</h3>
        <p>We process your data strictly under lawful grounds recognised by the NDPA:</p>
        <ul>
          <li><strong>Statutory Academic Archives:</strong> Archiving graduation records, degrees conferred, matriculation numbers, and graduating sets is conducted under legitimate institutional statutory duties.</li>
          <li><strong>Consent:</strong> Account creation, profile publication, email/phone visibility preferences, and networking features are activated solely upon your explicit consent.</li>
          <li><strong>Contractual Performance:</strong> Providing access to the verified Alumni Portal, digital directory, and member services.</li>
        </ul>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">3. Data Subject Rights</h3>
        <p>Under the NDPA 2023, you hold full legal rights over your personal data:</p>
        <ul>
          <li><strong>Right to Access:</strong> You can view all information held about you via your Member Dashboard.</li>
          <li><strong>Right to Rectification:</strong> You can edit and update your career bio, contact details, employer, and skills anytime.</li>
          <li><strong>Right to Granular Visibility:</strong> You hold the sovereign right to toggle whether your email, phone, location, and employer are <code>Public</code>, <code>Alumni Only</code>, or <code>Private</code>.</li>
          <li><strong>Right to Erasure ("Right to be Forgotten"):</strong> You can request account deactivation, removing your profile from public directories while preserving mandatory academic archive integrity.</li>
          <li><strong>Right to Lodge a Complaint:</strong> You may submit complaints to our Data Protection Officer or the Nigeria Data Protection Commission (NDPC).</li>
        </ul>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">4. Data Security Architecture</h3>
        <p>
          We employ state-of-the-art physical, electronic, and managerial procedures to safeguard data against unauthorized access, loss, or manipulation:
        </p>
        <ul>
          <li>All communication is encrypted via modern TLS/HTTPS certificates.</li>
          <li>Authentication tokens are hashed cryptographically using SHA-256 and stored in secured database clusters.</li>
          <li>Passwords are irreversibly hashed using standard Bcrypt encryption.</li>
          <li>Verification signatures are signed using secret application key HMAC algorithms to prevent matriculation spoofing.</li>
        </ul>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">5. Contact Data Protection Officer (DPO)</h3>
        <p>
          For questions, data access requests, or privacy inquiries:
          <br>
          <strong>Email:</strong> <a href="mailto:privacy@summituniversity.edu.ng" style="color: var(--summit-green); font-weight: 600;">privacy@summituniversity.edu.ng</a> / <a href="mailto:alumni@summituniversity.edu.ng" style="color: var(--summit-green); font-weight: 600;">alumni@summituniversity.edu.ng</a>
          <br>
          <strong>Address:</strong> Data Protection Office, Directorate of Alumni Relations, Summit University Offa, Kwara State, Nigeria.
        </p>

      </div>

      <!-- Sidebar Card -->
      <div class="col-md-3 col-sm-12" style="margin-top: 20px;">
        <div style="background: #f8fafc; border: 1px solid var(--summit-border); border-radius: var(--summit-radius-lg); padding: 25px 20px;">
          <h5 style="color: var(--summit-green-dark); font-weight: 700; margin-bottom: 15px;">Legal Resources</h5>
          <ul style="list-style: none; padding: 0; margin: 0; line-height: 32px; font-size: 13px;">
            <li><a href="{{ url('/terms') }}" style="color: var(--summit-text);"><i class="fa fa-angle-right"></i> Terms of Membership</a></li>
            <li><a href="{{ url('/privacy') }}" style="color: var(--summit-green); font-weight: 700;"><i class="fa fa-angle-right"></i> NDPA Privacy Policy</a></li>
            <li><a href="{{ url('/how-it-works') }}" style="color: var(--summit-text);"><i class="fa fa-angle-right"></i> Verification Guide</a></li>
            <li><a href="{{ url('/contact') }}" style="color: var(--summit-text);"><i class="fa fa-angle-right"></i> Contact Support</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
