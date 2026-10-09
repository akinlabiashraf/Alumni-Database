@extends('layouts.admin')

@section('title', 'Master Graduate Records & CSV Uploader — Summit University, Offa')
@section('page_title', 'Master Graduate Records Management')

@push('styles')
<style>
  .filter-bar {
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
  .filter-controls {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    flex-grow: 1;
  }
  .admin-input, .admin-select {
    padding: 9px 14px;
    font-size: 13px;
    border: 1px solid var(--admin-border);
    border-radius: 6px;
    outline: none;
    background: #f8fafc;
    transition: all 0.2s ease;
  }
  .admin-input:focus, .admin-select:focus {
    border-color: var(--admin-primary);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(0, 104, 55, 0.1);
  }
  .pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid var(--admin-border);
  }
  .csv-drop-zone {
    border: 2px dashed #cbd5e1;
    border-radius: 10px;
    padding: 35px 20px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 15px;
  }
  .csv-drop-zone:hover, .csv-drop-zone.dragover {
    border-color: var(--admin-primary);
    background: #f0fdf4;
  }
</style>
@endpush

@section('content')
<!-- Filter and Actions Bar -->
<div class="filter-bar">
  <div class="filter-controls">
    <input type="text" id="recordSearchInput" class="admin-input" placeholder="Search matric or name..." style="min-width: 200px;">
    
    <select id="facultyFilter" class="admin-select">
      <option value="">All Faculties</option>
    </select>

    <select id="departmentFilter" class="admin-select">
      <option value="">All Departments</option>
    </select>

    <select id="yearFilter" class="admin-select">
      <option value="">All Sets</option>
      <option value="2025">Class of 2025</option>
      <option value="2024">Class of 2024</option>
      <option value="2023">Class of 2023</option>
      <option value="2022">Class of 2022</option>
      <option value="2021">Class of 2021</option>
      <option value="2020">Class of 2020</option>
      <option value="2019">Class of 2019 (Pioneers)</option>
    </select>

    <select id="statusFilter" class="admin-select">
      <option value="">All Statuses</option>
      <option value="unclaimed">Unclaimed Only</option>
      <option value="claimed">Claimed Only</option>
    </select>

    <button id="btnApplyFilters" class="btn-admin-outline">
      <i class="fa fa-filter"></i> Filter
    </button>
  </div>

  <div style="display: flex; gap: 10px;">
    <button class="btn-admin-primary" data-toggle="modal" data-target="#bulkImportModal">
      <i class="fa fa-upload"></i> Bulk Import CSV
    </button>
    <button class="btn-admin-gold" data-toggle="modal" data-target="#recordFormModal" onclick="openCreateRecordModal()">
      <i class="fa fa-plus-circle"></i> Add Single Record
    </button>
  </div>
</div>

<!-- Master Records Table Card -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title"><i class="fa fa-graduation-cap"></i> Verified Master Graduate Records</h3>
    <span id="recordsCountBadge" style="font-size: 13px; color: var(--admin-text-muted); font-weight: 500;">Loading...</span>
  </div>

  <div class="admin-table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Matric No.</th>
          <th>Graduate Name</th>
          <th>Faculty & Department</th>
          <th>Degree & Classification</th>
          <th>Set</th>
          <th>Verification Status</th>
          <th style="text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody id="recordsTbody">
        <tr>
          <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p style="margin-top: 10px;">Loading graduate records...</p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination Controls -->
  <div class="pagination-container" id="paginationWrapper" style="display: none;">
    <div style="font-size: 13px; color: var(--admin-text-muted);" id="paginationInfo">Showing 1 to 20</div>
    <div style="display: flex; gap: 8px;">
      <button class="btn-admin-outline" id="btnPrevPage" disabled><i class="fa fa-chevron-left"></i> Previous</button>
      <button class="btn-admin-outline" id="btnNextPage">Next <i class="fa fa-chevron-right"></i></button>
    </div>
  </div>
</div>

<!-- ================= Modal: Bulk CSV Import ================= -->
<div class="modal fade" id="bulkImportModal" tabindex="-1" role="dialog" aria-labelledby="bulkImportModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header" style="background: var(--admin-primary); color: #ffffff; padding: 18px 24px;">
        <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
        <h4 class="modal-title" id="bulkImportModalLabel" style="font-weight: 700; font-size: 16px;">
          <i class="fa fa-file-excel-o"></i> Bulk Import Graduate Records (CSV)
        </h4>
      </div>
      <div class="modal-body" style="padding: 24px;">
        <p style="font-size: 13px; color: #475569; margin-bottom: 16px;">
          Upload the official Summit University graduate roster in CSV format. The system automatically detects duplicate matriculation numbers and matches faculty/department codes.
        </p>

        <div style="margin-bottom: 20px; text-align: right;">
          <a href="/api/admin/template-csv" class="btn-admin-outline" style="font-size: 12px;" target="_blank">
            <i class="fa fa-download"></i> Download CSV Template
          </a>
        </div>

        <form id="csvUploadForm">
          <div class="csv-drop-zone" id="csvDropZone" onclick="document.getElementById('csvFileInput').click();">
            <i class="fa fa-cloud-upload fa-3x" style="color: var(--admin-primary); margin-bottom: 10px;"></i>
            <h5 style="margin: 0 0 5px; font-weight: 600;">Click to select or drag CSV file here</h5>
            <span style="font-size: 12px; color: #94a3b8;" id="selectedFileName">Supports .csv files up to 10MB</span>
            <input type="file" id="csvFileInput" name="file" accept=".csv,text/csv,text/plain" style="display: none;" required>
          </div>

          <div id="importResultBox" style="display: none; margin-top: 15px; padding: 15px; border-radius: 8px;"></div>

          <div style="text-align: right; margin-top: 20px;">
            <button type="button" class="btn-admin-outline" data-dismiss="modal">Close</button>
            <button type="submit" class="btn-admin-primary" id="btnUploadCsv">
              <i class="fa fa-upload"></i> Start Import
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ================= Modal: Single Record Form ================= -->
<div class="modal fade" id="recordFormModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-header" style="background: var(--admin-primary); color: #ffffff; padding: 18px 24px;">
        <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
        <h4 class="modal-title" id="recordModalTitle" style="font-weight: 700; font-size: 16px;">
          <i class="fa fa-graduation-cap"></i> Add Master Graduate Record
        </h4>
      </div>
      <form id="recordForm">
        <div class="modal-body" style="padding: 24px;">
          <input type="hidden" id="recordEditId">

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Matriculation No. *</label>
                <input type="text" id="formMatric" class="form-control" placeholder="22/CONAS/CSC/025" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">First Name *</label>
                <input type="text" id="formFirstName" class="form-control" placeholder="Adebayo" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Middle Name</label>
                <input type="text" id="formMiddleName" class="form-control" placeholder="Kabir">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Last / Surname *</label>
                <input type="text" id="formLastName" class="form-control" placeholder="Lawal" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Faculty *</label>
                <select id="formFacultyId" class="form-control" required></select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Department *</label>
                <select id="formDepartmentId" class="form-control" required></select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Degree Awarded *</label>
                <input type="text" id="formDegree" class="form-control" placeholder="B.Sc. Computer Science" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Class of Degree</label>
                <select id="formClassOfDegree" class="form-control">
                  <option value="">Select Classification</option>
                  <option value="First Class Honours">First Class Honours</option>
                  <option value="Second Class Honours (Upper Division)">Second Class Honours (Upper Division)</option>
                  <option value="Second Class Honours (Lower Division)">Second Class Honours (Lower Division)</option>
                  <option value="Third Class Honours">Third Class Honours</option>
                  <option value="Pass">Pass</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-size: 12px; font-weight: 600;">Graduation Year *</label>
                <input type="number" id="formGradYear" class="form-control" min="2015" max="2035" placeholder="2024" required>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer" style="padding: 16px 24px;">
          <button type="button" class="btn-admin-outline" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin-primary" id="btnSaveRecord">
            <i class="fa fa-save"></i> Save Record
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let currentPage = 1;
  let academicData = null;

  async function loadAcademicMetadata() {
    try {
      const res = await SummitAPI.get('/academic-data');
      if (res.success) {
        academicData = res.data;
        
        // Populate filter dropdowns
        const facFilter = document.getElementById('facultyFilter');
        const deptFilter = document.getElementById('departmentFilter');
        const formFac = document.getElementById('formFacultyId');
        const formDept = document.getElementById('formDepartmentId');

        facFilter.innerHTML = '<option value="">All Faculties</option>';
        deptFilter.innerHTML = '<option value="">All Departments</option>';
        formFac.innerHTML = '<option value="">Select Faculty</option>';
        formDept.innerHTML = '<option value="">Select Department</option>';

        academicData.faculties.forEach(f => {
          facFilter.innerHTML += `<option value="${f.id}">${f.name} (${f.code})</option>`;
          formFac.innerHTML += `<option value="${f.id}">${f.name} (${f.code})</option>`;
        });

        academicData.departments.forEach(d => {
          deptFilter.innerHTML += `<option value="${d.id}">${d.name} (${d.code})</option>`;
          formDept.innerHTML += `<option value="${d.id}">${d.name} (${d.code})</option>`;
        });
      }
    } catch (e) {
      console.error('Error loading academic data:', e);
    }
  }

  async function loadRecords(page = 1) {
    currentPage = page;
    const search = document.getElementById('recordSearchInput').value.trim();
    const facultyId = document.getElementById('facultyFilter').value;
    const deptId = document.getElementById('departmentFilter').value;
    const year = document.getElementById('yearFilter').value;
    const status = document.getElementById('statusFilter').value;

    let query = `?page=${page}`;
    if (search) query += `&search=${encodeURIComponent(search)}`;
    if (facultyId) query += `&faculty_id=${facultyId}`;
    if (deptId) query += `&department_id=${deptId}`;
    if (year) query += `&graduation_year=${year}`;
    if (status) query += `&status=${status}`;

    const tbody = document.getElementById('recordsTbody');
    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px;"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>';

    try {
      const res = await SummitAPI.get(`/admin/records${query}`);
      if (!res.success) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">Error: ${res.message}</td></tr>`;
        return;
      }

      document.getElementById('recordsCountBadge').textContent = `Total: ${res.pagination.total} records`;

      if (res.records.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">No matching graduate records found.</td></tr>';
        document.getElementById('paginationWrapper').style.display = 'none';
        return;
      }

      tbody.innerHTML = res.records.map(r => {
        const facName = r.faculty ? r.faculty.code : '—';
        const deptName = r.department ? r.department.name : '—';
        const isClaimed = !!r.claimed_by_user_id;
        const claimBadge = isClaimed 
          ? `<span class="badge-status badge-claimed"><i class="fa fa-check"></i> Claimed (${r.claimed_by_user ? r.claimed_by_user.name : 'User'})</span>`
          : `<span class="badge-status badge-unclaimed"><i class="fa fa-clock-o"></i> Unclaimed</span>`;

        return `
          <tr>
            <td><code><strong>${r.matric_number}</strong></code></td>
            <td><strong>${r.full_name}</strong></td>
            <td>
              <div style="font-weight: 500;">${deptName}</div>
              <div style="font-size: 11px; color: #64748b;">${facName}</div>
            </td>
            <td>
              <div>${r.degree}</div>
              <div style="font-size: 11px; color: #64748b;">${r.class_of_degree || '—'}</div>
            </td>
            <td><strong>${r.graduation_year}</strong></td>
            <td>${claimBadge}</td>
            <td style="text-align: right; white-space: nowrap;">
              <button class="btn btn-xs btn-default" onclick='openEditRecordModal(${JSON.stringify(r)})' title="Edit">
                <i class="fa fa-pencil"></i>
              </button>
              ${!isClaimed ? `
                <button class="btn btn-xs btn-danger" onclick="deleteRecord(${r.id}, '${r.matric_number}')" title="Delete">
                  <i class="fa fa-trash"></i>
                </button>
              ` : ''}
            </td>
          </tr>
        `;
      }).join('');

      // Setup Pagination
      const p = res.pagination;
      document.getElementById('paginationWrapper').style.display = 'flex';
      document.getElementById('paginationInfo').textContent = `Page ${p.current_page} of ${p.last_page} (${p.total} total records)`;
      document.getElementById('btnPrevPage').disabled = p.current_page <= 1;
      document.getElementById('btnNextPage').disabled = p.current_page >= p.last_page;

    } catch (err) {
      console.error(err);
      tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: red;">Failed to load records.</td></tr>';
    }
  }

  function openCreateRecordModal() {
    document.getElementById('recordEditId').value = '';
    document.getElementById('recordModalTitle').innerHTML = '<i class="fa fa-plus-circle"></i> Add Master Graduate Record';
    document.getElementById('recordForm').reset();
  }

  function openEditRecordModal(record) {
    document.getElementById('recordEditId').value = record.id;
    document.getElementById('recordModalTitle').innerHTML = '<i class="fa fa-pencil"></i> Edit Graduate Record';
    document.getElementById('formMatric').value = record.matric_number;
    document.getElementById('formFirstName').value = record.first_name;
    document.getElementById('formMiddleName').value = record.middle_name || '';
    document.getElementById('formLastName').value = record.last_name;
    document.getElementById('formFacultyId').value = record.faculty_id;
    document.getElementById('formDepartmentId').value = record.department_id;
    document.getElementById('formDegree').value = record.degree;
    document.getElementById('formClassOfDegree').value = record.class_of_degree || '';
    document.getElementById('formGradYear').value = record.graduation_year;
    $('#recordFormModal').modal('show');
  }

  async function deleteRecord(id, matric) {
    if (!confirm(`Are you sure you want to permanently delete record for ${matric}?`)) return;
    try {
      const res = await SummitAPI.request(`/admin/records/${id}`, { method: 'DELETE' });
      if (res.success) {
        alert(res.message);
        loadRecords(currentPage);
      } else {
        alert('Failed: ' + res.message);
      }
    } catch (e) {
      alert('Delete failed: ' + e.message);
    }
  }

  document.addEventListener('DOMContentLoaded', async () => {
    await loadAcademicMetadata();
    await loadRecords(1);

    // Filter events
    document.getElementById('btnApplyFilters').addEventListener('click', () => loadRecords(1));
    document.getElementById('recordSearchInput').addEventListener('keyup', (e) => {
      if (e.key === 'Enter') loadRecords(1);
    });

    document.getElementById('btnPrevPage').addEventListener('click', () => {
      if (currentPage > 1) loadRecords(currentPage - 1);
    });
    document.getElementById('btnNextPage').addEventListener('click', () => {
      loadRecords(currentPage + 1);
    });

    // Check URL parameters for fast action triggering
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'import') {
      $('#bulkImportModal').modal('show');
    } else if (urlParams.get('action') === 'create') {
      openCreateRecordModal();
      $('#recordFormModal').modal('show');
    }

    // CSV File Selection Handler
    const fileInput = document.getElementById('csvFileInput');
    const selectedFileName = document.getElementById('selectedFileName');
    fileInput.addEventListener('change', () => {
      if (fileInput.files.length > 0) {
        selectedFileName.textContent = `Selected: ${fileInput.files[0].name} (${Math.round(fileInput.files[0].size / 1024)} KB)`;
        selectedFileName.style.color = 'var(--admin-primary)';
        selectedFileName.style.fontWeight = 'bold';
      }
    });

    // CSV Upload Submit Handler
    document.getElementById('csvUploadForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      if (!fileInput.files.length) {
        alert('Please choose a CSV file first.');
        return;
      }

      const btn = document.getElementById('btnUploadCsv');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Importing...';

      const formData = new FormData();
      formData.append('file', fileInput.files[0]);

      const resultBox = document.getElementById('importResultBox');
      resultBox.style.display = 'none';

      try {
        const res = await SummitAPI.request('/admin/import', {
          method: 'POST',
          body: formData,
        });

        if (res.success) {
          const s = res.summary;
          resultBox.className = 'alert alert-success';
          resultBox.innerHTML = `
            <h5 style="margin-top:0; font-weight:700;"><i class="fa fa-check-circle"></i> Import Completed Successfully!</h5>
            <ul style="margin-bottom:0; padding-left: 20px;">
              <li><strong>${s.imported_count}</strong> new records created</li>
              <li><strong>${s.duplicate_count}</strong> duplicates skipped</li>
              <li><strong>${s.error_count}</strong> errors encountered</li>
            </ul>
            ${s.errors && s.errors.length ? `<div style="margin-top:10px; font-size:11px; max-height:100px; overflow-y:auto;">${s.errors.join('<br>')}</div>` : ''}
          `;
          resultBox.style.display = 'block';
          loadRecords(1);
        } else {
          resultBox.className = 'alert alert-danger';
          resultBox.innerHTML = `<i class="fa fa-exclamation-triangle"></i> ${res.message}`;
          resultBox.style.display = 'block';
        }
      } catch (err) {
        resultBox.className = 'alert alert-danger';
        resultBox.innerHTML = 'Upload failed: ' + err.message;
        resultBox.style.display = 'block';
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-upload"></i> Start Import';
      }
    });

    // Single Record Form Submit Handler
    document.getElementById('recordForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('btnSaveRecord');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';

      const id = document.getElementById('recordEditId').value;
      const payload = {
        matric_number: document.getElementById('formMatric').value.trim(),
        first_name: document.getElementById('formFirstName').value.trim(),
        middle_name: document.getElementById('formMiddleName').value.trim() || null,
        last_name: document.getElementById('formLastName').value.trim(),
        faculty_id: parseInt(document.getElementById('formFacultyId').value),
        department_id: parseInt(document.getElementById('formDepartmentId').value),
        degree: document.getElementById('formDegree').value.trim(),
        class_of_degree: document.getElementById('formClassOfDegree').value || null,
        graduation_year: parseInt(document.getElementById('formGradYear').value),
      };

      try {
        const endpoint = id ? `/admin/records/${id}` : '/admin/records';
        const method = id ? 'PUT' : 'POST';

        const res = await SummitAPI.request(endpoint, {
          method: method,
          body: payload,
        });

        if (res.success) {
          alert(res.message);
          $('#recordFormModal').modal('hide');
          loadRecords(currentPage);
        } else {
          alert('Error: ' + res.message);
        }
      } catch (err) {
        alert('Failed to save record: ' + err.message);
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-save"></i> Save Record';
      }
    });
  });
</script>
@endpush
