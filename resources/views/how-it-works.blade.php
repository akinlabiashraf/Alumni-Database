@extends('layouts.app')

@section('title', 'How It Works — Step-by-Step Alumni Verification Guide')
@section('meta_description', 'A step-by-step guide explaining how Summit University graduates verify their matriculation numbers, claim their records, and set privacy controls.')

@section('content')
<!-- ======================= Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit Image.jpg') }}') center/cover no-repeat; padding: 140px 0 70px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 15px;">
      <i class="fa fa-info-circle"></i> Onboarding Guide
    </div>
    <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px;">How Verification Works</h1>
    <p style="color: #cbd5e1; font-size: 16px; max-width: 650px; margin: 0 auto;">
      A 60-second walkthrough to verify your graduate record and claim your official Summit Alumni account.
    </p>
  </div>
</section>

<!-- ======================= 4-Step Process Walkthrough ===================== -->
<section style="background: #ffffff; padding: 80px 0;">
  <div class="container">
    <div class="text-center" style="margin-bottom: 60px;" data-aos="fade-up">
      <h2 style="font-size: 30px; font-weight: 700; color: var(--summit-green-dark);">4 Simple Steps to Join</h2>
      <p style="font-size: 15px; color: var(--summit-muted);">Follow our streamlined, tamper-proof onboarding process.</p>
    </div>

    <div class="row">
      <!-- Step 1 -->
      <div class="col-md-3 col-sm-6 text-center" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 30px;">
        <div style="background: #f8fafc; border-radius: var(--summit-radius-lg); padding: 35px 20px; border: 1px solid var(--summit-border); height: 100%; position: relative;">
          <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--summit-green); color: #fff; width: 32px; height: 32px; border-radius: 50%; font-weight: 700; line-height: 32px; font-size: 14px;">1</div>
          <div style="font-size: 36px; color: var(--summit-green); margin-top: 10px; margin-bottom: 15px;">
            <i class="fa fa-search"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Enter Matric No.</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            Provide your official matriculation number (e.g. <code>22/CONAS/CSC/025</code>) to check the university graduate database.
          </p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="col-md-3 col-sm-6 text-center" data-aos="fade-up" data-aos-delay="200" style="margin-bottom: 30px;">
        <div style="background: #f8fafc; border-radius: var(--summit-radius-lg); padding: 35px 20px; border: 1px solid var(--summit-border); height: 100%; position: relative;">
          <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--summit-gold-dark); color: #fff; width: 32px; height: 32px; border-radius: 50%; font-weight: 700; line-height: 32px; font-size: 14px;">2</div>
          <div style="font-size: 36px; color: var(--summit-gold-dark); margin-top: 10px; margin-bottom: 15px;">
            <i class="fa fa-check-square-o"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Preview Match</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            Review your masked identity, department, degree, and graduation year to confirm that the matched record belongs to you.
          </p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="col-md-3 col-sm-6 text-center" data-aos="fade-up" data-aos-delay="300" style="margin-bottom: 30px;">
        <div style="background: #f8fafc; border-radius: var(--summit-radius-lg); padding: 35px 20px; border: 1px solid var(--summit-border); height: 100%; position: relative;">
          <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--summit-green); color: #fff; width: 32px; height: 32px; border-radius: 50%; font-weight: 700; line-height: 32px; font-size: 14px;">3</div>
          <div style="font-size: 36px; color: var(--summit-green); margin-top: 10px; margin-bottom: 15px;">
            <i class="fa fa-lock"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Set Password</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            Provide your current contact email, phone number, choose a secure password, and consent to NDPA 2023 terms.
          </p>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="col-md-3 col-sm-6 text-center" data-aos="fade-up" data-aos-delay="400" style="margin-bottom: 30px;">
        <div style="background: #f8fafc; border-radius: var(--summit-radius-lg); padding: 35px 20px; border: 1px solid var(--summit-border); height: 100%; position: relative;">
          <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--summit-gold-dark); color: #fff; width: 32px; height: 32px; border-radius: 50%; font-weight: 700; line-height: 32px; font-size: 14px;">4</div>
          <div style="font-size: 36px; color: var(--summit-gold-dark); margin-top: 10px; margin-bottom: 15px;">
            <i class="fa fa-users"></i>
          </div>
          <h4 style="font-size: 17px; font-weight: 700; color: var(--summit-dark); margin-bottom: 10px;">Connect & Share</h4>
          <p style="font-size: 13px; color: var(--summit-muted); line-height: 22px; margin: 0;">
            Your record is instantly marked as claimed. Enter your dashboard, update your career bio, and connect with your classmates.
          </p>
        </div>
      </div>
    </div>

    <div class="text-center" style="margin-top: 40px;">
      <a href="{{ url('/register') }}" class="btn-summit-primary" style="padding: 13px 32px; font-size: 15px;">
        <i class="fa fa-arrow-right"></i> Begin Verification Step 1
      </a>
    </div>
  </div>
</section>

<!-- ======================= FAQ Section ===================== -->
<section style="background: #f8fafc; padding: 80px 0;">
  <div class="container">
    <div class="text-center" style="margin-bottom: 50px;" data-aos="fade-up">
      <div class="summit-badge-verified" style="margin-bottom: 10px;">
        <i class="fa fa-question-circle"></i> Answers
      </div>
      <h2 style="font-size: 30px; font-weight: 700; color: var(--summit-green-dark);">Frequently Asked Questions</h2>
    </div>

    <div class="row">
      <div class="col-md-8 col-md-offset-2 col-sm-12">
        <div class="panel-group" id="faqAccordion" role="tablist" aria-multiselectable="true">
          
          <!-- Q1 -->
          <div class="panel panel-default" style="border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--summit-border);">
            <div class="panel-heading" role="tab" id="headingOne" style="background: #ffffff; padding: 18px 20px;">
              <h4 class="panel-title" style="font-size: 16px; font-weight: 600;">
                <a role="button" data-toggle="collapse" data-parent="#faqAccordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: var(--summit-green-dark); display: flex; justify-content: space-between; align-items: center;">
                  What should I do if my matriculation number is not found?
                  <i class="fa fa-angle-down"></i>
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body" style="color: var(--summit-muted); font-size: 14px; line-height: 24px; padding: 20px;">
                If your record is not returned automatically (for example, if you are from an earlier pioneer set or have a spelling variance), our system allows you to submit a <strong>Manual Verification Request</strong>. You can upload your Statement of Result or University ID card, and an administrator will review and verify your account within 24–48 hours.
              </div>
            </div>
          </div>

          <!-- Q2 -->
          <div class="panel panel-default" style="border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--summit-border);">
            <div class="panel-heading" role="tab" id="headingTwo" style="background: #ffffff; padding: 18px 20px;">
              <h4 class="panel-title" style="font-size: 16px; font-weight: 600;">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#faqAccordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="color: var(--summit-green-dark); display: flex; justify-content: space-between; align-items: center;">
                  What if my matriculation number has already been claimed by someone else?
                  <i class="fa fa-angle-down"></i>
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body" style="color: var(--summit-muted); font-size: 14px; line-height: 24px; padding: 20px;">
                Each matriculation record can only be claimed once. If you receive an "Already Claimed" alert and believe this was done in error or without your authorization, please contact the Directorate immediately at <a href="mailto:alumni@summituniversity.edu.ng" style="color: var(--summit-green); font-weight: 600;">alumni@summituniversity.edu.ng</a>. The team will freeze the record and initiate identity confirmation.
              </div>
            </div>
          </div>

          <!-- Q3 -->
          <div class="panel panel-default" style="border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--summit-border);">
            <div class="panel-heading" role="tab" id="headingThree" style="background: #ffffff; padding: 18px 20px;">
              <h4 class="panel-title" style="font-size: 16px; font-weight: 600;">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#faqAccordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="color: var(--summit-green-dark); display: flex; justify-content: space-between; align-items: center;">
                  Who can see my phone number and email address?
                  <i class="fa fa-angle-down"></i>
                </a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body" style="color: var(--summit-muted); font-size: 14px; line-height: 24px; padding: 20px;">
                Under our NDPA 2023 compliance architecture, <strong>you control your privacy</strong>. By default, phone numbers are marked <code>Private</code> (visible only to you and official university administrators), and emails are set to <code>Alumni Only</code>. You can customize these settings at any time in your Member Portal.
              </div>
            </div>
          </div>

          <!-- Q4 -->
          <div class="panel panel-default" style="border-radius: 8px; margin-bottom: 15px; border: 1px solid var(--summit-border);">
            <div class="panel-heading" role="tab" id="headingFour" style="background: #ffffff; padding: 18px 20px;">
              <h4 class="panel-title" style="font-size: 16px; font-weight: 600;">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#faqAccordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="color: var(--summit-green-dark); display: flex; justify-content: space-between; align-items: center;">
                  Can I change my registered email or phone number later?
                  <i class="fa fa-angle-down"></i>
                </a>
              </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
              <div class="panel-body" style="color: var(--summit-muted); font-size: 14px; line-height: 24px; padding: 20px;">
                Yes! Your graduation details (matric number, degree, and set) remain immutable to preserve academic integrity, but your personal contact information, employer, current location, and career milestones can be updated whenever you like.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
@endsection
