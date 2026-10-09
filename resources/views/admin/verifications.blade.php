@extends('layouts.admin')

@section('title', 'Manual Verification Moderation Queue — Summit University, Offa')
@section('page_title', 'Manual Verifications Moderation Queue')

@push('styles')
<style>
  .verif-filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    border-bottom: 1px solid var(--admin-border);
    padding-bottom: 12px;
  }
  .tab-btn {
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 20px;
    border: 1px solid var(--admin-border);
    background: #ffffff;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .tab-btn.active {
    background: var(--admin-primary);
    color: #ffffff;
    border-color: var(--admin-primary);
  }
  .verif-card-item {
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid var(--admin-border);
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    transition: transform 0.2s ease;
  }
  .verif-card-item:hover {
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
  }
  .verif-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 12px;
    margin-bottom: 14px;
  }
</style>
@endpush

@section('content')
<!-- Filter Tabs -->
<div class="verif-filter-tabs">
  <button class="tab-btn active" data-status="pending" onclick="setFilterStatus('pending')">
    <i class="fa fa-clock-o"></i> Pending Review
    <span class="badge" id="pendingCountTab" style="background: #ef4444; color: #fff;">0</span>
  </button>
  <button class="tab-btn" data-status="approved" onclick="setFilterStatus('approved')">
    <i class="fa fa-check-circle"></i> Approved
  </button>
  <button class="tab-btn" data-status="rejected" onclick="setFilterStatus('rejected')">
    <i class="fa fa-times-circle"></i> Rejected
  </button>
  <button class="tab-btn" data-status="" onclick="setFilterStatus('')">
    <i class="fa fa-list"></i> All Requests
  </button>
</div>

<!-- Queue List Container -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title"><i class="fa fa-id-card-o"></i> Verification Requests</h3>
    <span id="queueCountBadge" style="font-size: 13px; color: var(--admin-text-muted);">Loading...</span>
  </div>

  <div id="verificationsListContainer">
    <div style="text-align: center; padding: 40px; color: #94a3b8;">
      <i class="fa fa-spinner fa-spin fa-2x"></i>
      <p style="margin-top: 10px;">Loading moderation requests...</p>
    </div>
  </div>
</div>

<!-- ================= Modal: Reject Request Reason ================= -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header" style="background: #ef4444; color: #fff; padding: 15px 20px;">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title" style="font-weight: 700; font-size: 15px;">Reject Verification</h4>
      </div>
      <form id="rejectReasonForm">
        <div class="modal-body" style="padding: 20px;">
          <input type="hidden" id="rejectVerificationId">
          <div class="form-group">
            <label style="font-size: 12px; font-weight: 600;">Reason for Rejection *</label>
            <textarea id="rejectReasonInput" class="form-control" rows="4" placeholder="e.g. Matriculation number does not exist in registrar archives or graduation year does not match official records." required></textarea>
          </div>
        </div>
        <div class="modal-footer" style="padding: 12px 20px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" id="btnConfirmReject">Confirm Rejection</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let currentFilterStatus = 'pending';

  function setFilterStatus(status) {
    currentFilterStatus = status;
    document.querySelectorAll('.tab-btn').forEach(btn => {
      if (btn.getAttribute('data-status') === status) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
    loadVerifications();
  }

  async function loadVerifications() {
    const container = document.getElementById('verificationsListContainer');
    container.innerHTML = '<div style="text-align: center; padding: 30px;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>';

    let url = '/admin/verifications';
    if (currentFilterStatus) url += `?status=${currentFilterStatus}`;

    try {
      const res = await SummitAPI.get(url);
      if (!res.success) {
        container.innerHTML = `<div class="alert alert-danger">Error: ${res.message}</div>`;
        return;
      }

      document.getElementById('queueCountBadge').textContent = `Showing ${res.verifications.length} of ${res.pagination.total} records`;
      if (currentFilterStatus === 'pending') {
        document.getElementById('pendingCountTab').textContent = res.pagination.total;
      }

      if (res.verifications.length === 0) {
        container.innerHTML = `
          <div style="text-align: center; padding: 50px 20px; color: #94a3b8;">
            <i class="fa fa-check-circle-o fa-3x" style="color: var(--admin-primary); margin-bottom: 12px;"></i>
            <h4 style="font-weight: 600; color: var(--admin-text-main);">Queue is clear!</h4>
            <p>No verification requests matching the selected filter.</p>
          </div>
        `;
        return;
      }

      container.innerHTML = res.verifications.map(v => {
        const isPending = v.status === 'pending';
        const isApproved = v.status === 'approved';
        const isRejected = v.status === 'rejected';

        let badgeClass = 'badge-pending';
        if (isApproved) badgeClass = 'badge-approved';
        if (isRejected) badgeClass = 'badge-rejected';

        const createdDate = new Date(v.created_at).toLocaleString('en-GB');
        const user = v.user || {};

        return `
          <div class="verif-card-item">
            <div class="verif-card-header">
              <div>
                <h4 style="margin: 0 0 4px; font-weight: 700; color: var(--admin-primary-dark);">
                  ${v.full_name}
                </h4>
                <div style="font-size: 12px; color: #64748b;">
                  User Account: <strong>${user.name || 'Unregistered'}</strong> (${user.email || v.email})
                </div>
              </div>
              <div>
                <span class="badge-status ${badgeClass}" style="text-transform: capitalize;">
                  <i class="fa fa-circle" style="font-size: 8px;"></i> ${v.status}
                </span>
              </div>
            </div>

            <div class="row" style="margin-bottom: 12px;">
              <div class="col-md-3">
                <span style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Claimed Matric No.</span>
                <div style="font-size: 14px; font-weight: 700; color: var(--admin-primary);"><code>${v.matric_number}</code></div>
              </div>
              <div class="col-md-3">
                <span style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Department & Set</span>
                <div style="font-size: 13px; font-weight: 600;">${v.department_name} (Class of ${v.graduation_year})</div>
              </div>
              <div class="col-md-3">
                <span style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Contact Phone</span>
                <div style="font-size: 13px;">${v.phone || 'Not provided'}</div>
              </div>
              <div class="col-md-3">
                <span style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Submitted On</span>
                <div style="font-size: 12px; color: #64748b;">${createdDate}</div>
              </div>
            </div>

            ${v.notes ? `
              <div style="background: #f8fafc; border-left: 3px solid var(--admin-gold); padding: 10px 14px; border-radius: 4px; margin-bottom: 12px; font-size: 12px; color: #334155;">
                <strong>Applicant Notes:</strong> ${v.notes}
              </div>
            ` : ''}

            ${v.rejection_reason ? `
              <div style="background: #fee2e2; border-left: 3px solid #dc2626; padding: 10px 14px; border-radius: 4px; margin-bottom: 12px; font-size: 12px; color: #991b1b;">
                <strong>Rejection Reason:</strong> ${v.rejection_reason}
              </div>
            ` : ''}

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
              <div>
                ${v.id_document_path ? `
                  <a href="${v.id_document_path}" target="_blank" class="btn btn-xs btn-default">
                    <i class="fa fa-file-text-o"></i> View Supporting Document
                  </a>
                ` : '<span style="font-size: 12px; color: #94a3b8;"><i class="fa fa-info-circle"></i> No physical document attached</span>'}
              </div>

              ${isPending ? `
                <div style="display: flex; gap: 8px;">
                  <button class="btn btn-sm btn-success" onclick="approveVerification(${v.id}, '${v.full_name}', '${v.matric_number}')">
                    <i class="fa fa-check"></i> Approve & Verify
                  </button>
                  <button class="btn btn-sm btn-danger" onclick="openRejectModal(${v.id})">
                    <i class="fa fa-times"></i> Reject
                  </button>
                </div>
              ` : `
                <div style="font-size: 12px; color: #64748b;">
                  Reviewed by: <strong>${v.reviewed_by ? v.reviewed_by.name : 'Directorate'}</strong> on ${v.reviewed_at ? new Date(v.reviewed_at).toLocaleDateString('en-GB') : '—'}
                </div>
              `}
            </div>
          </div>
        `;
      }).join('');

    } catch (err) {
      container.innerHTML = '<div class="alert alert-danger">Failed to load verifications queue.</div>';
    }
  }

  async function approveVerification(id, name, matric) {
    if (!confirm(`Are you sure you want to approve manual verification for ${name} (${matric})?\n\nThis will link their account and issue verified alumni status.`)) {
      return;
    }

    try {
      const res = await SummitAPI.request(`/admin/verifications/${id}/approve`, {
        method: 'POST',
      });

      if (res.success) {
        alert(res.message);
        loadVerifications();
      } else {
        alert('Approval failed: ' + res.message);
      }
    } catch (e) {
      alert('Error approving verification: ' + e.message);
    }
  }

  function openRejectModal(id) {
    document.getElementById('rejectVerificationId').value = id;
    document.getElementById('rejectReasonInput').value = '';
    $('#rejectReasonModal').modal('show');
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadVerifications();

    document.getElementById('rejectReasonForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('rejectVerificationId').value;
      const reason = document.getElementById('rejectReasonInput').value.trim();

      const btn = document.getElementById('btnConfirmReject');
      btn.disabled = true;
      btn.textContent = 'Processing...';

      try {
        const res = await SummitAPI.request(`/admin/verifications/${id}/reject`, {
          method: 'POST',
          body: { rejection_reason: reason },
        });

        if (res.success) {
          $('#rejectReasonModal').modal('hide');
          alert(res.message);
          loadVerifications();
        } else {
          alert('Error: ' + res.message);
        }
      } catch (e) {
        alert('Rejection failed: ' + e.message);
      } finally {
        btn.disabled = false;
        btn.textContent = 'Confirm Rejection';
      }
    });
  });
</script>
@endpush
