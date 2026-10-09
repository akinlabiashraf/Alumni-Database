@extends('layouts.app')

@section('title', 'Terms of Membership — Summit University Alumni Platform')
@section('meta_description', 'Terms and Conditions governing access to the Summit University Alumni Database and Community.')

@section('content')
<!-- ======================= Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Senate Building.jpg') }}') center/cover no-repeat; padding: 140px 0 70px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 15px;">
      <i class="fa fa-file-text-o"></i> Membership Governance
    </div>
    <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px;">Terms of Membership & Use</h1>
    <p style="color: #cbd5e1; font-size: 16px; max-width: 650px; margin: 0 auto;">
      Guidelines and code of conduct for verified members of the Summit University Alumni Network.
    </p>
  </div>
</section>

<!-- ======================= Terms Content ===================== -->
<section style="background: #ffffff; padding: 80px 0;">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-sm-12" style="font-size: 14px; line-height: 28px; color: var(--summit-text);">
        
        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 10px;">1. Eligibility & Verification Requirement</h3>
        <p>
          Full membership to the Summit University Alumni Portal is exclusively open to confirmed graduates who have completed an accredited program of study at Summit University, Offa, Kwara State, Nigeria.
        </p>
        <p>
          Each account must be authenticated against an unassigned record in the official graduate database using a valid matriculation number. Falsification of academic credentials, impersonation, or unauthorized claiming of another graduate's record constitutes grounds for immediate expulsion, account termination, and potential legal referral under applicable Nigerian laws.
        </p>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">2. User Responsibilities & Account Security</h3>
        <p>As a verified member, you agree to:</p>
        <ul>
          <li>Maintain the confidentiality of your login credentials and authentication tokens.</li>
          <li>Ensure all personal information, career milestones, and contact numbers provided on your profile remain accurate and truthful.</li>
          <li>Promptly notify the Alumni Directorate if you suspect unauthorized access or tampering with your account.</li>
        </ul>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">3. Community Code of Conduct</h3>
        <p>
          The Summit University Alumni Network is built on mutual respect, professional decorum, and Islamic & moral values. Members agree NOT to:
        </p>
        <ul>
          <li>Scrape, harvest, or bulk-export contact details of fellow alumni for unsolicited commercial advertising or spam.</li>
          <li>Post or transmit defamatory, offensive, discriminatory, or unlawful content through member messaging or public directories.</li>
          <li>Misrepresent employment endorsements or academic honors.</li>
        </ul>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">4. Intellectual Property & Institutional Identity</h3>
        <p>
          All trademarks, crests, logos, and digital assets associated with Summit University, Offa are the proprietary intellectual property of the University and the Ansar-Ud-Deen Society of Nigeria. You may not use university marks for commercial enterprises without express written authorization from the University Governing Council.
        </p>

        <h3 style="color: var(--summit-green-dark); font-weight: 700; margin-top: 35px;">5. Amendments to Terms</h3>
        <p>
          The University Directorate of Alumni Relations reserves the right to amend these terms to reflect legal changes or platform upgrades. Notice of material modifications will be posted across official channels and communicated via email.
        </p>

      </div>

      <!-- Sidebar -->
      <div class="col-md-3 col-sm-12" style="margin-top: 20px;">
        <div style="background: #f8fafc; border: 1px solid var(--summit-border); border-radius: var(--summit-radius-lg); padding: 25px 20px;">
          <h5 style="color: var(--summit-green-dark); font-weight: 700; margin-bottom: 15px;">Legal Resources</h5>
          <ul style="list-style: none; padding: 0; margin: 0; line-height: 32px; font-size: 13px;">
            <li><a href="{{ url('/terms') }}" style="color: var(--summit-green); font-weight: 700;"><i class="fa fa-angle-right"></i> Terms of Membership</a></li>
            <li><a href="{{ url('/privacy') }}" style="color: var(--summit-text);"><i class="fa fa-angle-right"></i> NDPA Privacy Policy</a></li>
            <li><a href="{{ url('/how-it-works') }}" style="color: var(--summit-text);"><i class="fa fa-angle-right"></i> Verification Guide</a></li>
            <li><a href="{{ url('/contact') }}" style="color: var(--summit-text);"><i class="fa fa-angle-right"></i> Contact Support</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
