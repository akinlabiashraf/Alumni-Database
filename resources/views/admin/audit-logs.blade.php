@extends('layouts.admin')

@section('title', 'Platform Security & Audit Trail — Summit University, Offa')
@section('page_title', 'System Audit Trail & Security Logs')

@push('styles')
<style>
  .log-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .log-created { background: #dcfce7; color: #166534; }
  .log-updated { background: #e0f2fe; color: #0369a1; }
  .log-deleted { background: #fee2e2; color: #991b1b; }
  .log-imported { background: #ede9fe; color: #6d28d9; }
  .log-auth { background: #fef3c7; color: #b45309; }
</style>
@endpush

@section('content')
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title"><i class="fa fa-shield"></i> Platform Audit Trail (Immutable Events)</h3>
    <span id="auditCountBadge" style="font-size: 13px; color: var(--admin-text-muted);">Loading logs...</span>
  </div>

  <div class="admin-table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Timestamp</th>
          <th>Action Type</th>
          <th>Description</th>
          <th>Initiator (User)</th>
          <th>IP Address</th>
        </tr>
      </thead>
      <tbody id="auditLogsTbody">
        <tr>
          <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p style="margin-top: 10px;">Loading audit events...</p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
  async function loadAuditLogs() {
    const tbody = document.getElementById('auditLogsTbody');
    try {
      const res = await SummitAPI.get('/admin/audit-logs');
      if (!res.success) {
        tbody.innerHTML = `<tr><td colspan="5" style="color:red; text-align:center;">Error: ${res.message}</td></tr>`;
        return;
      }

      document.getElementById('auditCountBadge').textContent = `Showing recent ${res.logs.length} of ${res.pagination.total} events`;

      if (res.logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 40px; color:#94a3b8;">No audit logs recorded yet.</td></tr>';
        return;
      }

      tbody.innerHTML = res.logs.map(log => {
        const time = new Date(log.created_at).toLocaleString('en-GB');
        const user = log.user ? `${log.user.name} (${log.user.role})` : 'System / Guest';

        let badgeClass = 'log-updated';
        if (log.action.includes('created') || log.action.includes('registered')) badgeClass = 'log-created';
        if (log.action.includes('deleted') || log.action.includes('rejected')) badgeClass = 'log-deleted';
        if (log.action.includes('imported')) badgeClass = 'log-imported';
        if (log.action.includes('verified') || log.action.includes('login')) badgeClass = 'log-auth';

        return `
          <tr>
            <td><code style="font-size: 11px;">${time}</code></td>
            <td><span class="log-badge ${badgeClass}">${log.action}</span></td>
            <td><strong>${log.description}</strong></td>
            <td><span style="font-size: 12px; font-weight: 500;">${user}</span></td>
            <td><code style="font-size: 11px;">${log.ip_address || '—'}</code></td>
          </tr>
        `;
      }).join('');
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="5" style="color:red; text-align:center;">Failed to fetch audit logs.</td></tr>';
    }
  }

  document.addEventListener('DOMContentLoaded', loadAuditLogs);
</script>
@endpush
