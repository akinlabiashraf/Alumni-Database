@extends('layouts.app')

@section('title', 'Alumni Directory — Summit University, Offa')
@section('meta_description', 'Search and connect with verified graduates of Summit University Offa across faculties, departments, sets, and global industries.')

@section('content')
<!-- ======================= Header ===================== -->
<section style="background: linear-gradient(135deg, rgba(0, 77, 40, 0.95) 0%, rgba(0, 104, 55, 0.90) 100%), url('{{ asset('assets/img/summit/Summit Image.jpg') }}') center/cover no-repeat; padding: 130px 0 60px; color: #ffffff;">
  <div class="container text-center" data-aos="fade-down">
    <div class="summit-badge-gold" style="margin-bottom: 12px;">
      <i class="fa fa-users"></i> Global Network
    </div>
    <h1 style="color: #ffffff; font-size: 36px; font-weight: 800; margin-bottom: 10px;">Alumni Directory</h1>
    <p style="color: #cbd5e1; font-size: 15px; max-width: 600px; margin: 0 auto;">
      Connect with verified Summit University graduates leading across industries worldwide.
    </p>
  </div>
</section>

<!-- ======================= Main Directory Area ===================== -->
<section style="background: #f8fafc; padding: 50px 0 90px;">
  <div class="container">
    <div class="row">
      
      <!-- Left Sidebar: Faceted Filters -->
      <div class="col-md-3 col-sm-4">
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 25px 20px; box-shadow: var(--summit-shadow); border: 1px solid var(--summit-border); margin-bottom: 30px;">
          
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--summit-border);">
            <h4 style="font-size: 16px; font-weight: 700; color: var(--summit-dark); margin: 0;">
              <i class="fa fa-filter" style="color: var(--summit-green);"></i> Filters
            </h4>
            <button type="button" id="resetFiltersBtn" class="btn btn-xs btn-default" style="font-size: 11px; font-weight: 600;">
              Reset
            </button>
          </div>

          <!-- Graduating Set Filter -->
          <div style="margin-bottom: 20px;">
            <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark); margin-bottom: 8px; display: block;">
              Graduating Set
            </label>
            <select id="filterYear" class="form-control" style="height: 42px; font-size: 13px;">
              <option value="">All Sets (2019–2025)</option>
              <option value="2025">Class of 2025</option>
              <option value="2024">Class of 2024</option>
              <option value="2023">Class of 2023</option>
              <option value="2022">Class of 2022</option>
              <option value="2021">Class of 2021</option>
              <option value="2020">Class of 2020</option>
              <option value="2019">Pioneer Class (2019)</option>
            </select>
          </div>

          <!-- Faculty Filter -->
          <div style="margin-bottom: 20px;">
            <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark); margin-bottom: 8px; display: block;">
              Faculty
            </label>
            <select id="filterFaculty" class="form-control" style="height: 42px; font-size: 13px;">
              <option value="">All Faculties</option>
            </select>
          </div>

          <!-- Department Filter -->
          <div style="margin-bottom: 20px;">
            <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark); margin-bottom: 8px; display: block;">
              Department
            </label>
            <select id="filterDepartment" class="form-control" style="height: 42px; font-size: 13px;">
              <option value="">All Departments</option>
            </select>
          </div>

          <!-- Industry Filter -->
          <div style="margin-bottom: 20px;">
            <label style="font-size: 13px; font-weight: 600; color: var(--summit-dark); margin-bottom: 8px; display: block;">
              Industry
            </label>
            <select id="filterIndustry" class="form-control" style="height: 42px; font-size: 13px;">
              <option value="">All Industries</option>
              <option value="Technology">Technology / Software</option>
              <option value="Banking">Banking & Finance</option>
              <option value="Accounting">Accounting & Audit</option>
              <option value="Healthcare">Healthcare & Life Sciences</option>
              <option value="Media">Media & Communications</option>
              <option value="Engineering">Engineering & Infrastructure</option>
              <option value="Education">Education & Academia</option>
            </select>
          </div>

          <button type="button" id="applyFiltersBtn" class="btn btn-summit-primary" style="width: 100%; height: 42px; justify-content: center; font-size: 13px;">
            <i class="fa fa-check"></i> Apply Filters
          </button>
        </div>
      </div>

      <!-- Right Main Content: Results Grid -->
      <div class="col-md-9 col-sm-8">
        
        <!-- Search & Control Header -->
        <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 18px 25px; box-shadow: var(--summit-shadow); border: 1px solid var(--summit-border); margin-bottom: 25px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px;">
          
          <!-- Search input -->
          <div style="flex: 1; min-width: 250px;">
            <div class="input-group">
              <span class="input-group-addon" style="background: #f8fafc; border-right: none;"><i class="fa fa-search" style="color: var(--summit-green);"></i></span>
              <input type="text" id="directorySearchInput" class="form-control" placeholder="Search by name, skills, company, or degree..." style="height: 44px; font-size: 14px;">
            </div>
          </div>

          <!-- Sort and Counts -->
          <div style="display: flex; align-items: center; gap: 15px;">
            <span id="directoryResultsCount" style="font-size: 13px; font-weight: 600; color: var(--summit-muted);">
              Loading...
            </span>

            <select id="directorySort" class="form-control" style="width: 160px; height: 44px; font-size: 13px;">
              <option value="year_desc">Latest Sets First</option>
              <option value="name_asc">Name (A – Z)</option>
              <option value="name_desc">Name (Z – A)</option>
            </select>
          </div>

        </div>

        <!-- Alumni Cards Container -->
        <div id="alumniCardsContainer" class="row">
          <!-- Dynamically populated via JS -->
        </div>

        <!-- Pagination Controls -->
        <div id="directoryPagination" style="text-align: center; margin-top: 35px; display: none;">
          <!-- Dynamically populated -->
        </div>

      </div>

    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
  let facultiesData = [];
  let currentPage = 1;

  const container = document.getElementById('alumniCardsContainer');
  const countDisplay = document.getElementById('directoryResultsCount');
  const searchInput = document.getElementById('directorySearchInput');
  const yearSelect = document.getElementById('filterYear');
  const facultySelect = document.getElementById('filterFaculty');
  const deptSelect = document.getElementById('filterDepartment');
  const industrySelect = document.getElementById('filterIndustry');
  const sortSelect = document.getElementById('directorySort');
  const applyBtn = document.getElementById('applyFiltersBtn');
  const resetBtn = document.getElementById('resetFiltersBtn');
  const paginationContainer = document.getElementById('directoryPagination');

  // 1. Fetch faculties & departments metadata
  try {
    const acadRes = await SummitAPI.get('/academic-data');
    if (acadRes.success && acadRes.faculties) {
      facultiesData = acadRes.faculties;
      facultiesData.forEach(fac => {
        const opt = document.createElement('option');
        opt.value = fac.id;
        opt.textContent = fac.name;
        facultySelect.appendChild(opt);
      });
    }
  } catch (e) {
    console.error(e);
  }

  // Update department options on faculty change
  facultySelect.addEventListener('change', () => {
    deptSelect.innerHTML = '<option value="">All Departments</option>';
    const selectedFacId = parseInt(facultySelect.value);
    if (!selectedFacId) {
      facultiesData.forEach(fac => {
        fac.departments.forEach(dept => {
          const opt = document.createElement('option');
          opt.value = dept.id;
          opt.textContent = `${dept.name} (${fac.code})`;
          deptSelect.appendChild(opt);
        });
      });
    } else {
      const foundFac = facultiesData.find(f => f.id === selectedFacId);
      if (foundFac) {
        foundFac.departments.forEach(dept => {
          const opt = document.createElement('option');
          opt.value = dept.id;
          opt.textContent = dept.name;
          deptSelect.appendChild(opt);
        });
      }
    }
  });

  // 2. Load Alumni Directory Function
  async function loadDirectory(page = 1) {
    currentPage = page;
    container.innerHTML = `
      <div class="col-xs-12 text-center" style="padding: 60px 0;">
        <i class="fa fa-spinner fa-spin fa-3x" style="color: var(--summit-green); margin-bottom: 15px;"></i>
        <p style="color: var(--summit-muted); font-size: 14px;">Loading verified alumni...</p>
      </div>
    `;

    const params = new URLSearchParams({
      page: page,
      search: searchInput.value.trim(),
      year: yearSelect.value,
      faculty_id: facultySelect.value,
      department_id: deptSelect.value,
      industry: industrySelect.value,
      sort: sortSelect.value,
    });

    const res = await SummitAPI.get(`/alumni?${params.toString()}`);

    if (!res.success || !res.data || res.data.length === 0) {
      countDisplay.textContent = '0 Alumni Found';
      container.innerHTML = `
        <div class="col-xs-12">
          <div style="background: #ffffff; border-radius: 12px; padding: 60px 30px; text-align: center; border: 1px dashed var(--summit-border);">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
              <i class="fa fa-users"></i>
            </div>
            <h4 style="font-weight: 700; color: var(--summit-dark); margin-bottom: 8px;">No Matching Alumni Found</h4>
            <p style="font-size: 14px; color: var(--summit-muted); max-width: 450px; margin: 0 auto 20px;">
              No alumni match your current search criteria. Try broadening your filters or clearing search terms.
            </p>
            <button type="button" class="btn btn-summit-outline" onclick="document.getElementById('resetFiltersBtn').click()">
              Clear All Filters
            </button>
          </div>
        </div>
      `;
      paginationContainer.style.display = 'none';
      return;
    }

    countDisplay.textContent = `Showing ${res.data.length} of ${res.total} Alumni`;

    // Render Alumni Cards
    let html = '';
    res.data.forEach(item => {
      const avatarSrc = item.avatar || '/assets/img/user-profile.png';
      const headline = item.headline || `${item.degree || 'Alumnus'} • Class of ${item.graduation_year || ''}`;
      const location = item.location_display || 'Nigeria';
      const companyInfo = item.current_job_title && item.current_company 
        ? `${item.current_job_title} @ <strong>${item.current_company}</strong>` 
        : (item.industry ? `Industry: ${item.industry}` : 'Summit Graduate');

      html += `
        <div class="col-md-6 col-sm-12" style="margin-bottom: 25px;" data-aos="fade-up">
          <div style="background: #ffffff; border-radius: var(--summit-radius-lg); padding: 25px; box-shadow: var(--summit-shadow); border: 1px solid var(--summit-border); height: 100%; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s ease;">
            
            <div>
              <div style="display: flex; gap: 15px; align-items: flex-start; margin-bottom: 15px;">
                <img src="${avatarSrc}" alt="${item.name}" style="width: 65px; height: 65px; border-radius: 50%; border: 2.5px solid var(--summit-green); object-fit: cover; background: var(--summit-green-light); flex-shrink: 0;">
                
                <div style="flex: 1; overflow: hidden;">
                  <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                    <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: var(--summit-dark);">
                      <a href="/alumni/${item.id}" style="color: var(--summit-green-dark);">${item.name}</a>
                    </h4>
                    <span class="summit-badge-verified" style="font-size: 10px; padding: 2px 7px;">
                      <i class="fa fa-check-circle"></i> Verified
                    </span>
                  </div>

                  <p style="margin: 4px 0 0; font-size: 12px; color: var(--summit-muted); line-height: 18px;">
                    ${headline}
                  </p>
                </div>
              </div>

              <!-- Degree & Dept Meta -->
              <div style="background: #f8fafc; border-radius: 6px; padding: 10px 12px; margin-bottom: 15px; font-size: 12px; line-height: 20px;">
                <div style="color: var(--summit-green-dark); font-weight: 600;">
                  <i class="fa fa-graduation-cap" style="color: var(--summit-gold-dark); margin-right: 5px;"></i>
                  ${item.degree || 'Undergraduate'} &bull; Class of ${item.graduation_year || 'N/A'}
                </div>
                <div style="color: var(--summit-muted);">
                  <i class="fa fa-building-o" style="margin-right: 5px;"></i> ${item.department || ''}
                </div>
              </div>

              <!-- Work and Location -->
              <div style="font-size: 13px; color: var(--summit-text); margin-bottom: 15px; line-height: 20px;">
                <div><i class="fa fa-briefcase" style="color: var(--summit-green); margin-right: 6px;"></i> ${companyInfo}</div>
                <div style="color: var(--summit-muted); font-size: 12px; margin-top: 3px;">
                  <i class="fa fa-map-marker" style="color: var(--summit-gold-dark); margin-right: 6px;"></i> ${location}
                </div>
              </div>

              <!-- Skills Badges -->
              ${item.skills && item.skills.length > 0 ? `
                <div style="display: flex; gap: 5px; flex-wrap: wrap; margin-bottom: 15px;">
                  ${item.skills.slice(0, 3).map(s => `<span class="badge" style="background: #e2e8f0; color: #334155; font-size: 10px; font-weight: 600;">${s}</span>`).join('')}
                  ${item.skills.length > 3 ? `<span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 10px;">+${item.skills.length - 3}</span>` : ''}
                </div>
              ` : ''}
            </div>

            <!-- Card Action Footer -->
            <div style="padding-top: 15px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
              <span class="summit-badge-gold" style="font-size: 10px;">
                Set of ${item.graduation_year || 'N/A'}
              </span>
              <a href="/alumni/${item.id}" class="btn btn-sm btn-summit-primary" style="padding: 6px 14px; font-size: 12px; border-radius: 6px;">
                View Profile <i class="fa fa-arrow-right"></i>
              </a>
            </div>

          </div>
        </div>
      `;
    });

    container.innerHTML = html;

    // Render Pagination
    if (res.last_page > 1) {
      paginationContainer.style.display = 'block';
      let pagHtml = '<ul class="pagination" style="margin: 0;">';
      for (let i = 1; i <= res.last_page; i++) {
        pagHtml += `<li class="${i === res.current_page ? 'active' : ''}"><a href="javascript:void(0)" onclick="window.scrollToDirectoryPage(${i})">${i}</a></li>`;
      }
      pagHtml += '</ul>';
      paginationContainer.innerHTML = pagHtml;
    } else {
      paginationContainer.style.display = 'none';
    }
  }

  window.scrollToDirectoryPage = function(page) {
    loadDirectory(page);
    window.scrollTo({ top: 300, behavior: 'smooth' });
  };

  // Event Listeners
  applyBtn.addEventListener('click', () => loadDirectory(1));
  sortSelect.addEventListener('change', () => loadDirectory(1));
  
  let debounceTimeout;
  searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => loadDirectory(1), 500);
  });

  resetBtn.addEventListener('click', () => {
    searchInput.value = '';
    yearSelect.value = '';
    facultySelect.value = '';
    deptSelect.value = '';
    industrySelect.value = '';
    sortSelect.value = 'year_desc';
    loadDirectory(1);
  });

  // Initial Load
  loadDirectory(1);
});
</script>
@endpush
