@extends('layouts.app')

@section('title', 'About the Directorate of Alumni Relations — Summit University, Offa')
@section('meta_description', 'Learn about the Summit University Alumni & Advancement Directorate, our history in Offa, leadership, mission, and objectives.')

@section('content')
<!-- ======================= Page Title Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Senate Building.jpg') }}') center/cover no-repeat; padding: 140px 0 70px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 15px;">
      <i class="fa fa-university"></i> Directorate of Alumni & Advancement Relations
    </div>
    <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px;">About Our Alumni Community</h1>
    <p style="color: #cbd5e1; font-size: 16px; max-width: 650px; margin: 0 auto;">
      Fostering enduring connections between Summit University Offa and its global graduate diaspora.
    </p>
  </div>
</section>

<!-- ======================= Vision & Mission ===================== -->
<section style="background: #ffffff; padding: 80px 0;">
  <div class="container">
    <div class="row" style="display: flex; flex-wrap: wrap; align-items: center;">
      <div class="col-md-6 col-sm-12" data-aos="fade-right">
        <div class="summit-badge-verified" style="margin-bottom: 12px;">
          <i class="fa fa-book"></i> Institutional Heritage
        </div>
        <h2 style="font-size: 30px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 18px;">
          Faith, Knowledge, and Excellence
        </h2>
        <p style="font-size: 15px; color: var(--summit-muted); line-height: 26px; margin-bottom: 16px;">
          Summit University, Offa, Kwara State was established by the Ansar-Ud-Deen Society of Nigeria (ADSN) with a clear mandate: to provide qualitative, research-driven higher education grounded in sound moral and intellectual values.
        </p>
        <p style="font-size: 15px; color: var(--summit-muted); line-height: 26px; margin-bottom: 25px;">
          Since producing its inaugural graduating set in 2019, the university has nurtured leaders across natural sciences, computing, engineering, management, humanities, and social sciences who are transforming industries across Nigeria and across the globe.
        </p>
        
        <div style="border-left: 4px solid var(--summit-gold); padding-left: 20px; margin-bottom: 25px;">
          <h5 style="color: var(--summit-green-dark); font-weight: 700; margin-bottom: 6px;">The Directorate Mandate</h5>
          <p style="font-size: 14px; color: var(--summit-text); margin: 0; font-style: italic;">
            "To build an authentic, verifiable alumni ecosystem that supports career growth, institutional advancement, mentorship, and national development."
          </p>
        </div>
      </div>

      <div class="col-md-6 col-sm-12" data-aos="fade-left" style="margin-top: 20px;">
        <div style="border-radius: var(--summit-radius-lg); overflow: hidden; box-shadow: var(--summit-shadow-lg);">
          <img src="{{ asset('assets/img/summit/Image of Alumni COnve.jpg') }}" alt="Alumni Convocation" style="width: 100%; height: auto; display: block;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================= Core Strategic Pillars ===================== -->
<section style="background: #f8fafc; padding: 80px 0;">
  <div class="container">
    <div class="text-center" style="margin-bottom: 50px;" data-aos="fade-up">
      <div class="summit-badge-gold" style="margin-bottom: 10px;">
        <i class="fa fa-compass"></i> Strategic Pillars
      </div>
      <h2 style="font-size: 30px; font-weight: 700; color: var(--summit-green-dark);">Our Core Alumni Objectives</h2>
    </div>

    <div class="row">
      <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 30px;">
        <div style="background: #ffffff; border-radius: var(--summit-radius); padding: 30px 20px; box-shadow: var(--summit-shadow); height: 100%; border: 1px solid var(--summit-border);">
          <div style="width: 50px; height: 50px; border-radius: 10px; background: var(--summit-green-light); color: var(--summit-green); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 18px;">
            <i class="fa fa-id-badge"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Identity & Records</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            100% verified registry linked directly to the university's academic archives for tamper-proof alumni credential validation.
          </p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200" style="margin-bottom: 30px;">
        <div style="background: #ffffff; border-radius: var(--summit-radius); padding: 30px 20px; box-shadow: var(--summit-shadow); height: 100%; border: 1px solid var(--summit-border);">
          <div style="width: 50px; height: 50px; border-radius: 10px; background: var(--summit-gold-light); color: var(--summit-gold-dark); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 18px;">
            <i class="fa fa-briefcase"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Career & Placement</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            Direct recruitment and corporate connections across top employers, sharing job postings and collaborative ventures.
          </p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300" style="margin-bottom: 30px;">
        <div style="background: #ffffff; border-radius: var(--summit-radius); padding: 30px 20px; box-shadow: var(--summit-shadow); height: 100%; border: 1px solid var(--summit-border);">
          <div style="width: 50px; height: 50px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 18px;">
            <i class="fa fa-handshake-o"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Student Mentorship</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            Connecting seasoned graduates with current undergraduates for guidance, internship placements, and professional advice.
          </p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400" style="margin-bottom: 30px;">
        <div style="background: #ffffff; border-radius: var(--summit-radius); padding: 30px 20px; box-shadow: var(--summit-shadow); height: 100%; border: 1px solid var(--summit-border);">
          <div style="width: 50px; height: 50px; border-radius: 10px; background: #fef2f2; color: #b91c1c; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 18px;">
            <i class="fa fa-heart"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Endowment & Giving</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            Supporting institutional research chairs, library digitization, laboratory expansion, and student scholarship endowments.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================= Call to Action ===================== -->
<section style="background: linear-gradient(135deg, var(--summit-green-dark) 0%, var(--summit-green) 100%); padding: 65px 0; color: #ffffff; text-align: center;">
  <div class="container" data-aos="zoom-in">
    <h2 style="color: #ffffff; font-size: 30px; font-weight: 800; margin-bottom: 15px;">Become a Recognized Summit Alumnus</h2>
    <p style="color: #e2e8f0; font-size: 15px; max-width: 600px; margin: 0 auto 30px;">
      Claim your verified graduation record and join over six graduating sets shaping the future worldwide.
    </p>
    <a href="{{ url('/register') }}" class="btn-summit-gold" style="padding: 13px 32px; font-size: 15px; border-radius: 8px;">
      <i class="fa fa-check-circle"></i> Verify Matriculation Record
    </a>
  </div>
</section>
@endsection
