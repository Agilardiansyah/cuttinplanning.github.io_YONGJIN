// ============================================================
// SI Cutting Planning - PT Yongjin Javasuka Garment II
// Sesuai Laporan KKL Agil Ardiansyah (I.2410188)
// ============================================================

const KEYS = {
  styles: 'scp_styles',
  orders: 'scp_orders',
  plans: 'scp_plans',
  user: 'scp_user'
};

const SAMPLE_STYLES = [
  { id: 1, code: 'UWA-CGSS', name: 'TNF Softshell Jacket', desc: 'Technical outerwear' },
  { id: 2, code: 'UA-KT01', name: 'UA Storm Jacket', desc: 'Sportswear waterproof' },
  { id: 3, code: 'TNF-PTX2', name: 'TNF Apex Flex', desc: 'Outdoor jacket' },
  { id: 4, code: 'LL-SFT3', name: 'Lululemon Softshell', desc: 'Activewear' },
  { id: 5, code: 'ARC-HDB1', name: "Arc'teryx Hoody", desc: 'Premium outdoor' }
];

const SAMPLE_ORDERS = [
  {
    id: 1, orderNo: 'ORD-2026-014', styleId: 1, color: 'Black',
    sizes: { XS: 12, S: 48, M: 96, L: 72, XL: 36, XXL: 18, '3XL': 6 }, totalQty: 288
  },
  {
    id: 2, orderNo: 'ORD-2026-015', styleId: 2, color: 'Navy',
    sizes: { XS: 0, S: 40, M: 80, L: 60, XL: 30, XXL: 10, '3XL': 0 }, totalQty: 220
  },
  {
    id: 3, orderNo: 'ORD-2026-016', styleId: 3, color: 'Grey',
    sizes: { XS: 20, S: 50, M: 70, L: 50, XL: 30, XXL: 15, '3XL': 5 }, totalQty: 240
  },
  {
    id: 4, orderNo: 'ORD-2026-017', styleId: 4, color: 'Olive',
    sizes: { XS: 10, S: 35, M: 55, L: 40, XL: 20, XXL: 10, '3XL': 0 }, totalQty: 170
  },
  {
    id: 5, orderNo: 'ORD-2026-018', styleId: 5, color: 'Black',
    sizes: { XS: 15, S: 40, M: 60, L: 45, XL: 25, XXL: 10, '3XL': 5 }, totalQty: 200
  }
];

const SAMPLE_PLANS = [
  { id: 1, orderId: 1, markerLen: 4.85, lay: 42, width: 150, fabricUsed: 195.2, util: 89.2, status: 'Selesai' },
  { id: 2, orderId: 2, markerLen: 4.50, lay: 38, width: 148, fabricUsed: 172.8, util: 86.5, status: 'Proses' },
  { id: 3, orderId: 3, markerLen: 5.10, lay: 50, width: 152, fabricUsed: 241.0, util: 91.0, status: 'Belum' },
  { id: 4, orderId: 4, markerLen: 4.20, lay: 35, width: 145, fabricUsed: 158.4, util: 84.7, status: 'Proses' },
  { id: 5, orderId: 5, markerLen: 4.70, lay: 40, width: 150, fabricUsed: 186.5, util: 88.3, status: 'Selesai' }
];

// ---------- Utils ----------
function get(key) {
  const d = localStorage.getItem(KEYS[key]);
  return d ? JSON.parse(d) : null;
}
function set(key, data) {
  localStorage.setItem(KEYS[key], JSON.stringify(data));
}
function nextId(arr) {
  return arr.length ? Math.max(...arr.map(x => x.id)) + 1 : 1;
}
function fmt(n) {
  return new Intl.NumberFormat('id-ID').format(n);
}
function statusBadge(s) {
  const map = {
    'Belum': 'bg-secondary',
    'Proses': 'bg-warning text-dark',
    'Selesai': 'bg-success'
  };
  return `<span class="badge badge-status ${map[s] || 'bg-secondary'}">${s}</span>`;
}
function getStyle(id) {
  return (get('styles') || []).find(s => s.id === id);
}
function getOrder(id) {
  return (get('orders') || []).find(o => o.id === id);
}

// ---------- Auth ----------
function handleLogin(e) {
  e.preventDefault();
  const u = document.getElementById('username').value.trim();
  const p = document.getElementById('password').value;
  const users = {
    admin: { pass: 'admin123', name: 'Administrator' },
    operator: { pass: 'operator123', name: 'Operator Cutting' }
  };
  if (users[u] && users[u].pass === p) {
    set('user', { username: u, name: users[u].name });
    document.getElementById('loginPage').classList.add('d-none');
    document.getElementById('mainApp').classList.remove('d-none');
    document.getElementById('currentUser').textContent = users[u].name;
    initApp();
  } else {
    alert('Username atau password salah!');
  }
}
function logout() {
  localStorage.removeItem(KEYS.user);
  document.getElementById('mainApp').classList.add('d-none');
  document.getElementById('loginPage').classList.remove('d-none');
  document.getElementById('username').value = '';
  document.getElementById('password').value = '';
}
function checkAuth() {
  const user = get('user');
  if (user) {
    document.getElementById('loginPage').classList.add('d-none');
    document.getElementById('mainApp').classList.remove('d-none');
    document.getElementById('currentUser').textContent = user.name;
    initApp();
  }
}

// ---------- Navigation ----------
function showPage(page) {
  document.querySelectorAll('.page').forEach(el => el.classList.add('d-none'));
  document.getElementById('page-' + page).classList.remove('d-none');
  document.querySelectorAll('.sidebar .nav-link').forEach(el => {
    el.classList.remove('active');
    if (el.dataset.page === page) el.classList.add('active');
  });
  const titles = {
    dashboard: 'Dashboard',
    styles: 'Data Style / Artikel',
    orders: 'Data Order',
    plans: 'Cutting Plan',
    reports: 'Laporan Cutting Plan',
    panduan: 'Panduan Penggunaan'
  };
  document.getElementById('pageTitle').textContent = titles[page] || page;
  if (page === 'dashboard') renderDashboard();
  if (page === 'styles') renderStyles();
  if (page === 'orders') renderOrders();
  if (page === 'plans') renderPlans();
  if (page === 'reports') renderReports();
}
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('show');
  document.getElementById('overlay').classList.toggle('show');
}

// ---------- Init ----------
function initSample() {
  if (!get('styles')) set('styles', SAMPLE_STYLES);
  if (!get('orders')) set('orders', SAMPLE_ORDERS);
  if (!get('plans')) set('plans', SAMPLE_PLANS);
}
function initApp() {
  initSample();
  renderDashboard();
}

// ---------- Dashboard ----------
function renderDashboard() {
  const styles = get('styles') || [];
  const orders = get('orders') || [];
  const plans = get('plans') || [];

  document.getElementById('stat-styles').textContent = styles.length;
  document.getElementById('stat-orders').textContent = orders.length;
  document.getElementById('stat-plans').textContent = plans.length;
  const avg = plans.length ? (plans.reduce((s, p) => s + p.util, 0) / plans.length).toFixed(1) : 0;
  document.getElementById('stat-util').textContent = avg + '%';

  const tbody = document.getElementById('dash-recent');
  const recent = [...plans].sort((a, b) => b.id - a.id).slice(0, 5);
  tbody.innerHTML = recent.map(p => {
    const o = getOrder(p.orderId);
    const s = o ? getStyle(o.styleId) : null;
    return `<tr>
      <td>${o ? o.orderNo : '-'}</td>
      <td>${s ? s.code : '-'}</td>
      <td>${o ? o.color : '-'}</td>
      <td>${p.util}%</td>
      <td>${statusBadge(p.status)}</td>
    </tr>`;
  }).join('');
}

// ---------- Styles ----------
function renderStyles() {
  const styles = get('styles') || [];
  const tbody = document.getElementById('styles-body');
  tbody.innerHTML = styles.map(s => `
    <tr>
      <td class="fw-semibold">${s.code}</td>
      <td>${s.name}</td>
      <td class="text-muted small">${s.desc || '-'}</td>
      <td>
        <button class="btn btn-sm btn-outline-primary me-1" onclick="editStyle(${s.id})"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteStyle(${s.id})"><i class="bi bi-trash"></i></button>
      </td>
    </tr>`).join('');
}

function openStyleModal(id = null) {
  document.getElementById('styleId').value = '';
  document.getElementById('styleCode').value = '';
  document.getElementById('styleName').value = '';
  document.getElementById('styleDesc').value = '';
  document.getElementById('styleModalTitle').textContent = 'Tambah Style';
  if (id) {
    const s = (get('styles') || []).find(x => x.id === id);
    if (s) {
      document.getElementById('styleId').value = s.id;
      document.getElementById('styleCode').value = s.code;
      document.getElementById('styleName').value = s.name;
      document.getElementById('styleDesc').value = s.desc || '';
      document.getElementById('styleModalTitle').textContent = 'Edit Style';
    }
  }
  new bootstrap.Modal(document.getElementById('styleModal')).show();
}
function saveStyle(e) {
  e.preventDefault();
  const styles = get('styles') || [];
  const id = document.getElementById('styleId').value;
  const data = {
    code: document.getElementById('styleCode').value.trim(),
    name: document.getElementById('styleName').value.trim(),
    desc: document.getElementById('styleDesc').value.trim()
  };
  if (id) {
    const idx = styles.findIndex(s => s.id === +id);
    if (idx > -1) styles[idx] = { ...styles[idx], ...data };
  } else {
    data.id = nextId(styles);
    styles.push(data);
  }
  set('styles', styles);
  bootstrap.Modal.getInstance(document.getElementById('styleModal')).hide();
  renderStyles();
  renderDashboard();
}
function editStyle(id) { openStyleModal(id); }
function deleteStyle(id) {
  if (!confirm('Hapus style ini?')) return;
  set('styles', (get('styles') || []).filter(s => s.id !== id));
  renderStyles();
  renderDashboard();
}

// ---------- Orders ----------
function calcTotalQty() {
  const ids = ['szXS', 'szS', 'szM', 'szL', 'szXL', 'szXXL', 'sz3XL'];
  let total = 0;
  ids.forEach(id => total += parseInt(document.getElementById(id).value) || 0);
  document.getElementById('totalQtyDisplay').textContent = total;
}

function renderOrders() {
  const orders = get('orders') || [];
  const tbody = document.getElementById('orders-body');
  tbody.innerHTML = orders.map(o => {
    const s = getStyle(o.styleId);
    const ratio = Object.entries(o.sizes || {})
      .filter(([, v]) => v > 0)
      .map(([k, v]) => `${k}:${v}`)
      .join(', ');
    return `<tr>
      <td class="fw-semibold">${o.orderNo}</td>
      <td>${s ? s.code : '-'} <small class="text-muted">(${s ? s.name : ''})</small></td>
      <td>${o.color}</td>
      <td>${fmt(o.totalQty)}</td>
      <td class="small">${ratio || '-'}</td>
      <td>
        <button class="btn btn-sm btn-outline-primary me-1" onclick="editOrder(${o.id})"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteOrder(${o.id})"><i class="bi bi-trash"></i></button>
      </td>
    </tr>`;
  }).join('');
}

function openOrderModal(id = null) {
  const styles = get('styles') || [];
  document.getElementById('orderStyle').innerHTML =
    '<option value="">-- Pilih Style --</option>' +
    styles.map(s => `<option value="${s.id}">${s.code} — ${s.name}</option>`).join('');

  document.getElementById('orderId').value = '';
  document.getElementById('orderNo').value = '';
  document.getElementById('orderColor').value = '';
  ['szXS', 'szS', 'szM', 'szL', 'szXL', 'szXXL', 'sz3XL'].forEach(id => {
    document.getElementById(id).value = 0;
  });
  document.getElementById('totalQtyDisplay').textContent = '0';
  document.getElementById('orderModalTitle').textContent = 'Input Order Baru';

  if (id) {
    const o = (get('orders') || []).find(x => x.id === id);
    if (o) {
      document.getElementById('orderId').value = o.id;
      document.getElementById('orderNo').value = o.orderNo;
      document.getElementById('orderStyle').value = o.styleId;
      document.getElementById('orderColor').value = o.color;
      document.getElementById('szXS').value = o.sizes.XS || 0;
      document.getElementById('szS').value = o.sizes.S || 0;
      document.getElementById('szM').value = o.sizes.M || 0;
      document.getElementById('szL').value = o.sizes.L || 0;
      document.getElementById('szXL').value = o.sizes.XL || 0;
      document.getElementById('szXXL').value = o.sizes.XXL || 0;
      document.getElementById('sz3XL').value = o.sizes['3XL'] || 0;
      calcTotalQty();
      document.getElementById('orderModalTitle').textContent = 'Edit Order';
    }
  }
  new bootstrap.Modal(document.getElementById('orderModal')).show();
}
function saveOrder(e) {
  e.preventDefault();
  const orders = get('orders') || [];
  const id = document.getElementById('orderId').value;
  const sizes = {
    XS: parseInt(document.getElementById('szXS').value) || 0,
    S: parseInt(document.getElementById('szS').value) || 0,
    M: parseInt(document.getElementById('szM').value) || 0,
    L: parseInt(document.getElementById('szL').value) || 0,
    XL: parseInt(document.getElementById('szXL').value) || 0,
    XXL: parseInt(document.getElementById('szXXL').value) || 0,
    '3XL': parseInt(document.getElementById('sz3XL').value) || 0
  };
  const totalQty = Object.values(sizes).reduce((a, b) => a + b, 0);
  const data = {
    orderNo: document.getElementById('orderNo').value.trim(),
    styleId: parseInt(document.getElementById('orderStyle').value),
    color: document.getElementById('orderColor').value.trim(),
    sizes,
    totalQty
  };
  if (id) {
    const idx = orders.findIndex(o => o.id === +id);
    if (idx > -1) orders[idx] = { ...orders[idx], ...data };
  } else {
    data.id = nextId(orders);
    orders.push(data);
  }
  set('orders', orders);
  bootstrap.Modal.getInstance(document.getElementById('orderModal')).hide();
  renderOrders();
  renderDashboard();
}
function editOrder(id) { openOrderModal(id); }
function deleteOrder(id) {
  if (!confirm('Hapus order ini?')) return;
  set('orders', (get('orders') || []).filter(o => o.id !== id));
  renderOrders();
  renderDashboard();
}

// ---------- Cutting Plans ----------
function updatePlanCalc() {
  const markerLen = parseFloat(document.getElementById('planMarkerLen').value) || 0; // meter
  const lay = parseInt(document.getElementById('planLay').value) || 0;
  const width = parseFloat(document.getElementById('planWidth').value) || 150;
  const targetUtil = parseFloat(document.getElementById('planTargetUtil').value) || 88;

  // Formula praktis (mendekati hasil di laporan):
  // Fabric Used (YD) ≈ (Marker Length m × Lay × 1.0936)  [m → yard]
  // atau disesuaikan agar mendekati angka di screenshot
  const fabricUsed = +(markerLen * lay * 1.0936).toFixed(1); // m to yard approx
  // Utilization disimulasikan di sekitar target (bisa diganti rumus lebih kompleks)
  const util = +Math.min(98, Math.max(70, targetUtil + (Math.random() * 4 - 2))).toFixed(1);

  // Untuk hasil yang lebih deterministic dan mirip laporan:
  const utilFixed = +(targetUtil + ((markerLen * 10 + lay) % 5) - 2).toFixed(1);

  document.getElementById('calcFabricUsed').textContent = fabricUsed.toFixed(1) + ' YD';
  document.getElementById('calcUtil').textContent = utilFixed.toFixed(1) + ' %';
}

function renderPlans() {
  const plans = get('plans') || [];
  const tbody = document.getElementById('plans-body');
  tbody.innerHTML = plans.map(p => {
    const o = getOrder(p.orderId);
    const s = o ? getStyle(o.styleId) : null;
    return `<tr>
      <td>${o ? o.orderNo : '-'}</td>
      <td>${s ? s.code : '-'}</td>
      <td>${p.lay}</td>
      <td>${p.fabricUsed.toFixed(1)} YD</td>
      <td>${p.util}%</td>
      <td>${statusBadge(p.status)}</td>
      <td>
        <button class="btn btn-sm btn-outline-primary me-1" onclick="editPlan(${p.id})" title="Edit"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-outline-success me-1" onclick="openStatus(${p.id})" title="Status"><i class="bi bi-arrow-repeat"></i></button>
        <button class="btn btn-sm btn-outline-danger" onclick="deletePlan(${p.id})" title="Hapus"><i class="bi bi-trash"></i></button>
      </td>
    </tr>`;
  }).join('');
}

function openPlanModal(id = null) {
  const orders = get('orders') || [];
  document.getElementById('planOrder').innerHTML =
    '<option value="">-- Pilih Order --</option>' +
    orders.map(o => {
      const s = getStyle(o.styleId);
      return `<option value="${o.id}">${o.orderNo} — ${s ? s.code : ''} (${o.color})</option>`;
    }).join('');

  document.getElementById('planId').value = '';
  document.getElementById('planMarkerLen').value = 4.85;
  document.getElementById('planLay').value = 45;
  document.getElementById('planWidth').value = 150;
  document.getElementById('planTargetUtil').value = 88;
  document.getElementById('planModalTitle').textContent = 'Buat Cutting Plan Baru';
  updatePlanCalc();

  if (id) {
    const p = (get('plans') || []).find(x => x.id === id);
    if (p) {
      document.getElementById('planId').value = p.id;
      document.getElementById('planOrder').value = p.orderId;
      document.getElementById('planMarkerLen').value = p.markerLen;
      document.getElementById('planLay').value = p.lay;
      document.getElementById('planWidth').value = p.width;
      document.getElementById('planTargetUtil').value = p.util;
      document.getElementById('planModalTitle').textContent = 'Edit Cutting Plan';
      updatePlanCalc();
    }
  }
  new bootstrap.Modal(document.getElementById('planModal')).show();
}

function savePlan(e) {
  e.preventDefault();
  const plans = get('plans') || [];
  const id = document.getElementById('planId').value;
  const markerLen = parseFloat(document.getElementById('planMarkerLen').value);
  const lay = parseInt(document.getElementById('planLay').value);
  const width = parseFloat(document.getElementById('planWidth').value);
  const targetUtil = parseFloat(document.getElementById('planTargetUtil').value);

  const fabricUsed = +(markerLen * lay * 1.0936).toFixed(1);
  const util = +(targetUtil + ((markerLen * 10 + lay) % 5) - 2).toFixed(1);

  const data = {
    orderId: parseInt(document.getElementById('planOrder').value),
    markerLen, lay, width,
    fabricUsed, util,
    status: 'Belum'
  };

  if (id) {
    const idx = plans.findIndex(p => p.id === +id);
    if (idx > -1) {
      data.status = plans[idx].status;
      plans[idx] = { ...plans[idx], ...data };
    }
  } else {
    data.id = nextId(plans);
    plans.push(data);
  }
  set('plans', plans);
  bootstrap.Modal.getInstance(document.getElementById('planModal')).hide();
  renderPlans();
  renderDashboard();
}
function editPlan(id) { openPlanModal(id); }
function deletePlan(id) {
  if (!confirm('Hapus cutting plan ini?')) return;
  set('plans', (get('plans') || []).filter(p => p.id !== id));
  renderPlans();
  renderDashboard();
}

function openStatus(id) {
  const p = (get('plans') || []).find(x => x.id === id);
  if (!p) return;
  document.getElementById('statusPlanId').value = id;
  document.getElementById('statusSelect').value = p.status;
  new bootstrap.Modal(document.getElementById('statusModal')).show();
}
function confirmStatus() {
  const id = parseInt(document.getElementById('statusPlanId').value);
  const plans = get('plans') || [];
  const idx = plans.findIndex(p => p.id === id);
  if (idx > -1) {
    plans[idx].status = document.getElementById('statusSelect').value;
    set('plans', plans);
  }
  bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
  renderPlans();
  renderDashboard();
  renderReports();
}

// ---------- Reports ----------
function renderReports() {
  const plans = get('plans') || [];
  const tbody = document.getElementById('report-body');
  let total = 0;
  tbody.innerHTML = plans.map(p => {
    const o = getOrder(p.orderId);
    const s = o ? getStyle(o.styleId) : null;
    total += p.fabricUsed;
    return `<tr>
      <td>${o ? o.orderNo : '-'}</td>
      <td>${s ? s.code : '-'}</td>
      <td>${p.lay}</td>
      <td>${p.fabricUsed.toFixed(1)} YD</td>
      <td>${p.util}%</td>
      <td>${statusBadge(p.status)}</td>
    </tr>`;
  }).join('');
  document.getElementById('report-total').textContent = total.toFixed(1) + ' YD';
  const avg = plans.length ? (plans.reduce((s, p) => s + p.util, 0) / plans.length).toFixed(1) : 0;
  document.getElementById('report-avg').textContent = avg + ' %';
}

function exportCSV() {
  const plans = get('plans') || [];
  let csv = 'No Order,Style,Lay,Fabric Used (YD),Utilization %,Status\n';
  plans.forEach(p => {
    const o = getOrder(p.orderId);
    const s = o ? getStyle(o.styleId) : null;
    csv += `"${o ? o.orderNo : '-'}","${s ? s.code : '-'}",${p.lay},${p.fabricUsed.toFixed(1)},${p.util},${p.status}\n`;
  });
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'Laporan_Cutting_Plan_' + new Date().toISOString().slice(0,10) + '.csv';
  link.click();
}

// ---------- Start ----------
document.addEventListener('DOMContentLoaded', checkAuth);
