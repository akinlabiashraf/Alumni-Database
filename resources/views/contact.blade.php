@extends('layouts.app')

@section('title', 'Contact the Directorate — Summit University, Offa')
@section('meta_description', 'Get in touch with the Summit University Alumni & Advancement Directorate in Offa, Kwara State. Submit inquiries or request assistance.')

@section('content')
<!-- ======================= Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit University Entrance Gate.jpg') }}') center/cover no-repeat; padding: 140px 0 70px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 15px;">
      <i class="fa fa-envelope"></i> We are here to support you
    </div>
    <h1 style="color: #ffffff; font-size: 38px; font-weight: 800; margin-bottom: 12px;">Contact the Alumni Directorate</h1>
    <p style="color: #cbd5e1; font-size: 16px; max-width: 650px; margin: 0 auto;">
      Have a question regarding record claims, transcripts, chapters, or events? Reach out to our campus liaison team.
    </p>
  </div>
</section>

<!-- ======================= Contact Form & Campus Information ===================== -->
<section style="background: #ffffff; padding: 80px 0;">
  <div class="container">
    <div class="row">
      <!-- Contact Details -->
      <div class="col-md-5 col-sm-12" data-aos="fade-right">
        <div style="background: #f8fafc; border-radius: var(--summit-radius-lg); padding: 35px 30px; border: 1px solid var(--summit-border); height: 100%;">
          <div class="summit-badge-verified" style="margin-bottom: 15px;">
            <i class="fa fa-map-marker"></i> Campus Directorate
          </div>
          <h3 style="font-size: 24px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 15px;">
            Directorate of Alumni Relations
          </h3>
          <p style="font-size: 14px; color: var(--summit-muted); line-height: 24px; margin-bottom: 25px;">
            Administrative Senate Building, Summit University, PMB 4412, Irra Road, Offa, Kwara State, Nigeria.
          </p>

          <div style="margin-bottom: 20px; display: flex; align-items: flex-start; gap: 15px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--summit-green-light); color: var(--summit-green); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
              <i class="fa fa-envelope"></i>
            </div>
            <div>
              <h5 style="margin: 0 0 3px; font-weight: 700; color: var(--summit-dark); font-size: 14px;">Direct Email</h5>
              <a href="mailto:alumni@summituniversity.edu.ng" style="color: var(--summit-green); font-size: 13px; font-weight: 600;">alumni@summituniversity.edu.ng</a>
            </div>
          </div>

          <div style="margin-bottom: 20px; display: flex; align-items: flex-start; gap: 15px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--summit-gold-light); color: var(--summit-gold-dark); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
              <i class="fa fa-phone"></i>
            </div>
            <div>
              <h5 style="margin: 0 0 3px; font-weight: 700; color: var(--summit-dark); font-size: 14px;">Phone & WhatsApp</h5>
              <span style="color: var(--summit-text); font-size: 13px;">+234 (0) 814 976 1311</span><br>
              <span style="color: var(--summit-muted); font-size: 12px;">+234 (0) 803 123 4567</span>
            </div>
          </div>

          <div style="margin-bottom: 25px; display: flex; align-items: flex-start; gap: 15px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
              <i class="fa fa-clock-o"></i>
            </div>
            <div>
              <h5 style="margin: 0 0 3px; font-weight: 700; color: var(--summit-dark); font-size: 14px;">Office Working Hours</h5>
              <span style="color: var(--summit-muted); font-size: 13px;">Monday – Friday: 8:00 AM – 4:00 PM (WAT)</span>
            </div>
          </div>

          <div style="border-top: 1px solid var(--summit-border); padding-top: 20px; font-size: 12px; color: var(--summit-muted);">
            <i class="fa fa-shield" style="color: var(--summit-green);"></i> Messages sent via this form are encrypted and reviewed by the Directorate within 24 hours.
          </div>
        </div>
      </div>

      <!-- Contact Inquiry Form -->
      <div class="col-md-7 col-sm-12" data-aos="fade-left" style="margin-top: 20px;">
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 35px 30px; box-shadow: var(--summit-shadow-lg); border: 1px solid var(--summit-border);">
          <h3 style="font-size: 22px; font-weight: 700; color: var(--summit-green-dark); margin-bottom: 8px;">
            Send an Inquiry
          </h3>
          <p style="font-size: 14px; color: var(--summit-muted); margin-bottom: 25px;">
            Fill out the form below and an officer will get in touch with you.
          </p>

          <div id="contactFormAlert" style="display: none; padding: 12px 15px; border-radius: 6px; font-size: 14px; margin-bottom: 20px;"></div>

          <form id="publicContactForm">
            <div class="row">
              <div class="col-md-6 col-sm-12 form-group">
                <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Your Full Name *</label>
                <input type="text" id="contactName" class="form-control" placeholder="e.g. Amina Bello" required style="height: 45px;">
              </div>
              <div class="col-md-6 col-sm-12 form-group">
                <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Email Address *</label>
                <input type="email" id="contactEmail" class="form-control" placeholder="e.g. amina@example.com" required style="height: 45px;">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-sm-12 form-group">
                <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Phone Number (Optional)</label>
                <input type="tel" id="contactPhone" class="form-control" placeholder="e.g. +234 803 123 4567" style="height: 45px;">
              </div>
              <div class="col-md-6 col-sm-12 form-group">
                <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Inquiry Subject *</label>
                <select id="contactSubject" class="form-control" required style="height: 45px;">
                  <option value="">Select Category</option>
                  <option value="Matriculation Verification Help">Matriculation Verification Help</option>
                  <option value="Dispute an Unauthorized Claim">Dispute an Unauthorized Claim</option>
                  <option value="Transcript & Academic Reference">Transcript & Academic Reference</option>
                  <option value="Alumni Chapter & Reunion">Alumni Chapter & Reunion</option>
                  <option value="General Directorate Inquiry">General Directorate Inquiry</option>
                </select>
              </div>
            </div>

            <div class="form-group" style="margin-top: 10px;">
              <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark);">Your Message *</label>
              <textarea id="contactMessage" class="form-control" rows="5" placeholder="Please describe how we can assist you..." required></textarea>
            </div>

            <div class="form-group" style="margin-top: 25px;">
              <button type="submit" id="contactSubmitBtn" class="btn btn-summit-primary" style="padding: 12px 28px; font-size: 15px;">
                <span>Send Message</span> <i class="fa fa-paper-plane"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('publicContactForm');
  const alertBox = document.getElementById('contactFormAlert');
  const btn = document.getElementById('contactSubmitBtn');

  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.style.display = 'none';

      const payload = {
        name: document.getElementById('contactName').value.trim(),
        email: document.getElementById('contactEmail').value.trim(),
        phone: document.getElementById('contactPhone').value.trim(),
        subject: document.getElementById('contactSubject').value,
        message: document.getElementById('contactMessage').value.trim(),
      };

      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting...';

      const res = await SummitAPI.post('/contact', payload);

      btn.disabled = false;
      btn.innerHTML = '<span>Send Message</span> <i class="fa fa-paper-plane"></i>';

      if (res.success) {
        alertBox.className = 'alert alert-success';
        alertBox.innerHTML = `<strong><i class="fa fa-check-circle"></i> Message Delivered!</strong><br>${res.message}`;
        alertBox.style.display = 'block';
        form.reset();
      } else {
        alertBox.className = 'alert alert-danger';
        alertBox.innerHTML = `<strong><i class="fa fa-exclamation-circle"></i> Submission Failed:</strong> ${res.message}`;
        alertBox.style.display = 'block';
      }
    });
  }
});
</script>
@endpush
