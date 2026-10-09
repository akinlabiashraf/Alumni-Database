<!-- ================= footer start ========================= -->
<footer class="summit-footer">
  <div class="container">
    <div class="row">
      <!-- Col 1: University Identity -->
      <div class="col-md-4 col-sm-6">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
          <img src="{{ asset('assets/img/summit/Summit university Logo.webp') }}" alt="Summit University Logo" style="height: 54px; filter: brightness(1.1);">
          <div>
            <h4 style="margin: 0; color: #ffffff; font-size: 17px; line-height: 1.2;">Summit University</h4>
            <span style="color: var(--summit-gold); font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">Offa, Kwara State, Nigeria</span>
          </div>
        </div>
        <p style="color: #94a3b8; font-size: 13px; line-height: 22px;">
          The Official Alumni Portal connecting graduates worldwide. Fostering lifelong collaboration, mentorship, career advancement, and academic excellence.
        </p>
        <div class="summit-ndpa-badge" style="margin-top: 15px; background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.15); color: #cbd5e1;">
          <i class="fa fa-shield" style="color: var(--summit-gold);"></i> NDPA 2023 Compliant Data Platform
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="col-md-2 col-sm-6">
        <h4>Navigation</h4>
        <ul style="list-style: none; padding: 0; margin: 0; line-height: 32px; font-size: 13px;">
          <li><a href="{{ url('/') }}"><i class="fa fa-angle-right"></i> Home</a></li>
          <li><a href="{{ url('/directory') }}"><i class="fa fa-angle-right"></i> Alumni Directory</a></li>
          <li><a href="{{ url('/about') }}"><i class="fa fa-angle-right"></i> About Directorate</a></li>
          <li><a href="{{ url('/features') }}"><i class="fa fa-angle-right"></i> Platform Features</a></li>
          <li><a href="{{ url('/how-it-works') }}"><i class="fa fa-angle-right"></i> How It Works</a></li>
          <li><a href="{{ url('/contact') }}"><i class="fa fa-angle-right"></i> Contact Support</a></li>
        </ul>
      </div>

      <!-- Col 3: Alumni Services -->
      <div class="col-md-3 col-sm-6">
        <h4>Alumni Services</h4>
        <ul style="list-style: none; padding: 0; margin: 0; line-height: 32px; font-size: 13px;">
          <li><a href="{{ url('/register') }}"><i class="fa fa-angle-right"></i> Claim Graduate Record</a></li>
          <li><a href="{{ url('/register#manual') }}"><i class="fa fa-angle-right"></i> Manual Verification Queue</a></li>
          <li><a href="{{ url('/dashboard') }}"><i class="fa fa-angle-right"></i> Member Portal</a></li>
          <li><a href="{{ url('/privacy') }}"><i class="fa fa-angle-right"></i> Privacy Policy & NDPA</a></li>
          <li><a href="{{ url('/terms') }}"><i class="fa fa-angle-right"></i> Terms of Membership</a></li>
        </ul>
      </div>

      <!-- Col 4: Campus Contact -->
      <div class="col-md-3 col-sm-6">
        <h4>Campus Contact</h4>
        <div style="font-size: 13px; line-height: 24px; color: #94a3b8;">
          <p style="margin-bottom: 8px;">
            <i class="fa fa-map-marker" style="color: var(--summit-gold); margin-right: 8px;"></i>
            PMB 4412, Irra Road, Offa, Kwara State, Nigeria
          </p>
          <p style="margin-bottom: 8px;">
            <i class="fa fa-envelope" style="color: var(--summit-gold); margin-right: 8px;"></i>
            <a href="mailto:alumni@summituniversity.edu.ng">alumni@summituniversity.edu.ng</a>
          </p>
          <p style="margin-bottom: 8px;">
            <i class="fa fa-phone" style="color: var(--summit-gold); margin-right: 8px;"></i>
            +234 (0) 814 976 1311
          </p>
          <p style="margin-bottom: 0;">
            <i class="fa fa-globe" style="color: var(--summit-gold); margin-right: 8px;"></i>
            <a href="https://summituniversity.edu.ng" target="_blank" rel="noopener">www.summituniversity.edu.ng</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="summit-footer-bottom text-center">
      <div class="row">
        <div class="col-md-6 text-left text-center-xs">
          &copy; {{ date('Y') }} Summit University, Offa — Directorate of Alumni Relations. All Rights Reserved.
        </div>
        <div class="col-md-6 text-right text-center-xs" style="margin-top: 5px;">
          <span>Licensed by the National Universities Commission (NUC), Federal Republic of Nigeria.</span>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- ================= footer end ========================= -->
