@extends('layouts.admin')

@section('title', 'User Accounts & Roles Management — Summit University, Offa')
@section('page_title', 'User Accounts & Access Control')

@push('styles')
<style>
  .user-filter-bar {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid var(--admin-border);
    padding: 18px 20px;
    margin-bottom: 25px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    justify-content: space-between;
  }
  .user-avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--admin-primary);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    margin-right: 8px;
  }
</style>
@endpush

@section('content')
<!-- Filter Bar -->
<div class="user-filter-bar">
  <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; flex-grow: 1;">
    <input type="text" id="userSearchInput" class="admin-input" placeholder="Search name, email, matric..." style="min-width: 220px;">
    
    <select id="userRoleFilter" class="admin-select">
      <option value="">All Roles</option>
      <option value="admin">Administrators</option>
      <option value="moderator">Moderators</option>
      <option value="alumnus">Alumni</option>
      <option value="student">Students</option>
    </select>

    <select id="userStatusFilter" class="admin-select">
      <option value="">All Statuses</option>
      <option value="active">Active</option>
      <option value="suspended">Suspended</option>
      <option value="pending">Pending</option>
    </select>

    <button id="btnFilterUsers" class="btn-admin-outline">
      <i class="fa fa-filter"></i> Apply
    </button>
  </div>
</div>

<!-- Users Table Card -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title"><i class="fa fa-users"></i> Platform User Directory</h3>
    <span id="userCountBadge" style="font-size: 13px; color: var(--admin-text-muted);">Loading...</span>
  </div>

  <div class="admin-table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>User</th>
          <th>Matriculation No.</th>
          <th>Role</th>
          <th>Account Status</th>
          <th>Academic Record</th>
          <th>Joined Date</th>
          <th style="text-align: right;">Access Control</th>
        </tr>
      </thead>
      <tbody id="usersTbody">
        <tr>
          <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p style="margin-top: 10px;">Loading registered users...</p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- ================= Modal: Change User Role ================= -->
<div class="modal fade" id="roleChangeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header" style="background: var(--admin-primary); color: #fff; padding: 15px 20px;">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title" style="font-weight: 700; font-size: 15px;">Modify User Role</h4>
      </div>
      <form id="roleChangeForm">
        <div class="modal-body" style="padding: 20px;">
          <input type="hidden" id="roleUserId">
          <p id="roleUserTargetName" style="font-weight: 600; font-size: 13px; margin-bottom: 12px;"></p>
          <div class="form-group">
            <label style="font-size: 12px; font-weight: 600;">Select New Role *</label>
            <select id="roleSelectInput" class="form-control" required>
              <option value="alumnus">Alumnus (Verified Graduate)</option>
              <option value="moderator">Moderator (Verification Staff)</option>
              <option value="admin">Super Administrator</option>
              <option value="student">Student</option>
            </select>
          </div>
        </div>
        <div class="modal-footer" style="padding: 12px 20px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-admin-primary">Update Role</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let currentUsers = [];

  async function loadUsers() {
    const search = document.getElementById('userSearchInput').value.trim();
    const role = document.getElementById('userRoleFilter').value;
    const status = document.getElementById('userStatusFilter').value;

    let query = '?page=1';
    if (search) query += `&search=${encodeURIComponent(search)}`;
    if (role) query += `&role=${role}`;
    if (status) query += `&status=${status}`;

    const tbody = document.getElementById('usersTbody');
    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px;"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>';

    try {
      const res = await SummitAPI.get(`/admin/users${query}`);
      if (!res.success) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">Error: ${res.message}</td></tr>`;
        return;
      }

      currentUsers = res.users;
      document.getElementById('userCountBadge').textContent = `Total: ${res.pagination.total} registered accounts`;

      if (res.users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">No matching users found.</td></tr>';
        return;
      }

      tbody.innerHTML = res.users.map(u => {
        let roleBadge = `badge-role-${u.role}`;
        let statusBadge = u.status === 'active' ? 'badge-active' : (u.status === 'suspended' ? 'badge-suspended' : 'badge-pending');
        const initial = u.name ? u.name.charAt(0).toUpperCase() : 'U';
        const joinedDate = new Date(u.created_at).toLocaleDateString('en-GB');

        const grad = u.graduate_record 
          ? `<div style="font-size: 12px; font-weight: 600;">${u.graduate_record.degree}</div><div style="font-size: 11px; color: #64748b;">Class of ${u.graduate_record.graduation_year}</div>`
          : '<span style="font-size: 11px; color: #94a3b8;">Unlinked / Staff</span>';

        return `
          <tr>
            <td>
              <div style="display: flex; align-items: center;">
                <div class="user-avatar-small">${initial}</div>
                <div>
                  <div style="font-weight: 700; color: #0f172a;">${u.name}</div>
                  <div style="font-size: 11px; color: #64748b;">${u.email}</div>
                </div>
              </div>
            </td>
            <td><code>${u.matric_number || '—'}</code></td>
            <td>
              <span class="badge-status ${roleBadge}" style="text-transform: capitalize;">
                ${u.role}
              </span>
            </td>
            <td>
              <span class="badge-status ${statusBadge}" style="text-transform: capitalize;">
                ${u.status}
              </span>
            </td>
            <td>${grad}</td>
            <td><span style="font-size: 12px; color: #64748b;">${joinedDate}</span></td>
            <td style="text-align: right; white-space: nowrap;">
              <button class="btn btn-xs btn-default" onclick="openRoleModal(${u.id}, '${u.name}', '${u.role}')" title="Modify Role">
                <i class="fa fa-user-secret"></i> Role
              </button>
              ${u.status === 'active' ? `
                <button class="btn btn-xs btn-warning" onclick="toggleStatus(${u.id}, 'suspended')" title="Suspend Access">
                  <i class="fa fa-ban"></i>
                </button>
              ` : `
                <button class="btn btn-xs btn-success" onclick="toggleStatus(${u.id}, 'active')" title="Activate Account">
                  <i class="fa fa-check"></i>
                </button>
              `}
            </td>
          </tr>
        `;
      }).join('');

    } catch (err) {
      console.error(err);
      tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: red;">Failed to load users.</td></tr>';
    }
  }

  function openRoleModal(id, name, currentRole) {
    document.getElementById('roleUserId').value = id;
    document.getElementById('roleUserTargetName').textContent = `User: ${name} (Current: ${currentRole})`;
    document.getElementById('roleSelectInput').value = currentRole;
    $('#roleChangeModal').modal('show');
  }

  async function toggleStatus(id, newStatus) {
    if (!confirm(`Are you sure you want to change user status to '${newStatus}'?`)) return;

    try {
      const res = await SummitAPI.request(`/admin/users/${id}/status`, {
        method: 'PUT',
        body: { status: newStatus },
      });

      if (res.success) {
        alert(res.message);
        loadUsers();
      } else {
        alert('Action failed: ' + res.message);
      }
    } catch (e) {
      alert('Error updating status: ' + e.message);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadUsers();

    document.getElementById('btnFilterUsers').addEventListener('click', loadUsers);
    document.getElementById('userSearchInput').addEventListener('keyup', (e) => {
      if (e.key === 'Enter') loadUsers();
    });

    document.getElementById('roleChangeForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('roleUserId').value;
      const newRole = document.getElementById('roleSelectInput').value;

      try {
        const res = await SummitAPI.request(`/admin/users/${id}/role`, {
          method: 'PUT',
          body: { role: newRole },
        });

        if (res.success) {
          alert(res.message);
          $('#roleChangeModal').modal('hide');
          loadUsers();
        } else {
          alert('Failed: ' + res.message);
        }
      } catch (e) {
        alert('Error: ' + e.message);
      }
    });
  });
</script>
@endpush
