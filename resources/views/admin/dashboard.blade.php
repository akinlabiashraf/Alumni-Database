@extends('layouts.admin')

@section('title', 'Admin Overview Dashboard — Summit University, Offa')
@section('page_title', 'Directorate Operations Dashboard')

@push('styles')
<style>
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
  }
  .kpi-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid var(--admin-border);
    padding: 22px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
  }
  .kpi-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
  }
  .kpi-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }
  .kpi-icon-green { background: #dcfce7; color: #166534; }
  .kpi-icon-gold { background: #fef3c7; color: #b45309; }
  .kpi-icon-blue { background: #e0f2fe; color: #0369a1; }
  .kpi-icon-purple { background: #ede9fe; color: #6d28d9; }

  .kpi-title {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--admin-text-muted);
    margin: 0;
  }
  .kpi-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--admin-text-main);
    margin: 4px 0;
    line-height: 1.1;
  }
  .kpi-subtext {
    font-size: 12px;
    color: var(--admin-text-muted);
    margin: 0;
  }
  .progress-thin {
    height: 6px;
    border-radius: 4px;
    background: #e2e8f0;
    margin-top: 10px;
    overflow: hidden;
  }
  .progress-bar-green {
    background: linear-gradient(90deg, #10b981 0%, #006837 100%);
  }

  /* Quick Actions Bar */
  .quick-actions-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 25px;
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid var(--admin-border);
    padding: 16px 20px;
    align-items: center;
    justify-content: space-between;
  }
  .quick-actions-left {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: 13px;
    color: var(--admin-primary-dark);
  }

  /* Distribution Bars */
  .dist-item {
    margin-bottom: 16px;
  }
  .dist-item-header {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
  }
</style>
@endpush

@section('content')
<!-- Quick Action Bar -->
<div class="quick-actions-bar">
  <div class="quick-actions-left">
    <i class="fa fa-bolt" style="color: var(--admin-gold); font-size: 16px;"></i>
    <span>Directorate Fast Actions:</span>
  </div>
  <div style="display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="{{ url('/admin/records') }}?action=import" class="btn-admin-primary">
      <i class="fa fa-upload"></i> Bulk Import CSV
    </a>
    <a href="{{ url('/admin/records') }}?action=create" class="btn-admin-gold">
      <i class="fa fa-plus-circle"></i> Add Single Record
    </a>
    <a href="{{ url('/admin/verifications') }}" class="btn-admin-outline">
      <i class="fa fa-shield"></i> Review Pending Queue
    </a>
    <a href="/api/admin/template-csv" class="btn-admin-outline" target="_blank">
      <i class="fa fa-download"></i> Sample CSV Template
    </a>
  </div>
</div>

<!-- Primary KPI Grid -->
<div class="kpi-grid">
  <!-- Total Records -->
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-title">Master Records</span>
      <div class="kpi-icon-wrap kpi-icon-green"><i class="fa fa-graduation-cap"></i></div>
    </div>
    <div class="kpi-value" id="kpiTotalRecords">—</div>
    <p class="kpi-subtext"><span id="kpiClaimedRecords">—</span> claimed | <span id="kpiUnclaimedRecords">—</span> unclaimed</p>
  </div>

  <!-- Overall Claim Rate -->
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-title">Platform Claim Rate</span>
      <div class="kpi-icon-wrap kpi-icon-gold"><i class="fa fa-pie-chart"></i></div>
    </div>
    <div class="kpi-value" id="kpiClaimRate">—%</div>
    <div class="progress-thin">
      <div class="progress-bar progress-bar-green" id="kpiClaimProgressBar" style="width: 0%;"></div>
    </div>
  </div>

  <!-- Registered Alumni -->
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-title">Active Alumni Users</span>
      <div class="kpi-icon-wrap kpi-icon-blue"><i class="fa fa-users"></i></div>
    </div>
    <div class="kpi-value" id="kpiAlumniUsers">—</div>
    <p class="kpi-subtext"><span id="kpiTotalUsers">—</span> total registered platform accounts</p>
  </div>

  <!-- Pending Verifications -->
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-title">Pending Moderation</span>
      <div class="kpi-icon-wrap kpi-icon-purple"><i class="fa fa-clock-o"></i></div>
    </div>
    <div class="kpi-value" id="kpiPendingVerifications">—</div>
    <p class="kpi-subtext"><a href="{{ url('/admin/verifications') }}" style="color: var(--admin-primary); font-weight: 600;">View verification queue &rarr;</a></p>
  </div>
</div>

<!-- Distribution Rows -->
<div class="row">
  <!-- Set Claim Breakdown -->
  <div class="col-md-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><i class="fa fa-bar-chart"></i> Claims by Graduating Set</h3>
        <span style="font-size: 11px; color: var(--admin-text-muted);">Pioneers to Recent</span>
      </div>
      <div id="setDistributionContainer">
        <div style="text-align: center; padding: 30px; color: #94a3b8;">
          <i class="fa fa-spinner fa-spin fa-2x"></i>
          <p style="margin-top: 10px;">Loading graduating sets...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Faculty Breakdown -->
  <div class="col-md-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><i class="fa fa-university"></i> Records by Faculty</h3>
        <span style="font-size: 11px; color: var(--admin-text-muted);">All Accredited Faculties</span>
      </div>
      <div id="facultyDistributionContainer">
        <div style="text-align: center; padding: 30px; color: #94a3b8;">
          <i class="fa fa-spinner fa-spin fa-2x"></i>
          <p style="margin-top: 10px;">Loading faculties...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Activities Table -->
<div class="row">
  <!-- Recent Verified Claims -->
  <div class="col-md-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><i class="fa fa-check-circle"></i> Recent Verified Claims</h3>
        <a href="{{ url('/admin/records?status=claimed') }}" style="font-size: 12px; font-weight: 600; color: var(--admin-primary);">View All &rarr;</a>
      </div>
      <div class="admin-table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Alumnus</th>
              <th>Matric No.</th>
              <th>Dept / Degree</th>
              <th>Claimed At</th>
            </tr>
          </thead>
          <tbody id="recentClaimsTbody">
            <tr><td colspan="4" style="text-align: center; color: #94a3b8;">Loading recent claims...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent Audit Logs -->
  <div class="col-md-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><i class="fa fa-shield"></i> Directorate Activity Trail</h3>
        <a href="{{ url('/admin/audit-logs') }}" style="font-size: 12px; font-weight: 600; color: var(--admin-primary);">All Logs &rarr;</a>
      </div>
      <div class="admin-table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Action</th>
              <th>Description</th>
              <th>Operator</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody id="recentAuditTbody">
            <tr><td colspan="4" style="text-align: center; color: #94a3b8;">Loading audit activity...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', async () => {
    try {
      const res = await SummitAPI.get('/admin/dashboard');
      if (!res.success) {
        alert('Failed to load dashboard metrics: ' + (res.message || 'Error'));
        return;
      }

      const m = res.metrics;
      document.getElementById('kpiTotalRecords').textContent = m.total_records.toLocaleString();
      document.getElementById('kpiClaimedRecords').textContent = m.claimed_records.toLocaleString();
      document.getElementById('kpiUnclaimedRecords').textContent = m.unclaimed_records.toLocaleString();
      document.getElementById('kpiClaimRate').textContent = m.claim_rate + '%';
      document.getElementById('kpiClaimProgressBar').style.width = m.claim_rate + '%';
      document.getElementById('kpiAlumniUsers').textContent = m.alumni_users.toLocaleString();
      document.getElementById('kpiTotalUsers').textContent = m.total_users.toLocaleString();
      document.getElementById('kpiPendingVerifications').textContent = m.pending_verifications;

      // Render Graduating Sets Distribution
      const setBox = document.getElementById('setDistributionContainer');
      if (res.set_distribution && res.set_distribution.length > 0) {
        setBox.innerHTML = res.set_distribution.map(item => `
          <div class="dist-item">
            <div class="dist-item-header">
              <span>Class of ${item.year}</span>
              <span><strong>${item.claimed}</strong> / ${item.total} claimed (${item.claim_rate}%)</span>
            </div>
            <div class="progress-thin">
              <div class="progress-bar progress-bar-green" style="width: ${item.claim_rate}%;"></div>
            </div>
          </div>
        `).join('');
      } else {
        setBox.innerHTML = '<p style="color: #94a3b8; text-align: center;">No graduate sets found.</p>';
      }

      // Render Faculty Distribution
      const facBox = document.getElementById('facultyDistributionContainer');
      if (res.faculty_distribution && res.faculty_distribution.length > 0) {
        facBox.innerHTML = res.faculty_distribution.map(item => `
          <div class="dist-item">
            <div class="dist-item-header">
              <span>${item.name} (${item.code})</span>
              <span><strong>${item.claimed}</strong> / ${item.total} claimed (${item.claim_rate}%)</span>
            </div>
            <div class="progress-thin">
              <div class="progress-bar progress-bar-green" style="width: ${item.claim_rate}%;"></div>
            </div>
          </div>
        `).join('');
      } else {
        facBox.innerHTML = '<p style="color: #94a3b8; text-align: center;">No faculty data available.</p>';
      }

      // Render Recent Claims
      const claimsTbody = document.getElementById('recentClaimsTbody');
      if (res.recent_claims && res.recent_claims.length > 0) {
        claimsTbody.innerHTML = res.recent_claims.map(claim => {
          const user = claim.claimed_by_user || {};
          const dept = claim.department ? claim.department.name : claim.degree;
          const dateStr = claim.claimed_at ? new Date(claim.claimed_at).toLocaleDateString('en-GB') : '—';
          return `
            <tr>
              <td><strong>${user.name || claim.full_name}</strong></td>
              <td><code>${claim.matric_number}</code></td>
              <td><span style="font-size: 12px; color: #475569;">${dept}</span></td>
              <td><span style="font-size: 11px; color: #64748b;">${dateStr}</span></td>
            </tr>
          `;
        }).join('');
      } else {
        claimsTbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #94a3b8;">No claims recorded yet.</td></tr>';
      }

      // Render Recent Audit Logs
      const auditTbody = document.getElementById('recentAuditTbody');
      if (res.recent_audit_logs && res.recent_audit_logs.length > 0) {
        auditTbody.innerHTML = res.recent_audit_logs.map(log => {
          const user = log.user ? log.user.name : 'System';
          const timeStr = log.created_at ? new Date(log.created_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) : '—';
          return `
            <tr>
              <td><span class="badge-status badge-role-admin" style="font-size: 10px;">${log.action}</span></td>
              <td style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${log.description}">${log.description}</td>
              <td><span style="font-size: 12px; font-weight: 500;">${user}</span></td>
              <td><span style="font-size: 11px; color: #64748b;">${timeStr}</span></td>
            </tr>
          `;
        }).join('');
      } else {
        auditTbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #94a3b8;">No audit trail recorded.</td></tr>';
      }

    } catch (err) {
      console.error('Error fetching admin dashboard:', err);
    }
  });
</script>
@endpush
