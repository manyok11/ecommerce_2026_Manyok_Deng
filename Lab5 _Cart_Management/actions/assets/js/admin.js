// =============================================================
// assets/js/admin.js  —  Shoppn Admin UI behaviour
// Vanilla JS — no framework. All functions are named and used
// via HTML onclick/oninput attributes or event listeners.
// =============================================================

// ── Sidebar toggle (mobile) ──────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
  const sidebar  = document.getElementById('sidebar');
  const toggle   = document.getElementById('sidebarToggle');
  const overlay  = document.getElementById('sidebarOverlay');

  function openSidebar()  {
    sidebar && sidebar.classList.add('open');
    overlay && (overlay.style.display = 'block');
  }
  function closeSidebar() {
    sidebar && sidebar.classList.remove('open');
    overlay && (overlay.style.display = 'none');
  }

  toggle  && toggle.addEventListener('click', openSidebar);
  overlay && overlay.addEventListener('click', closeSidebar);

  // ── Topbar user dropdown ──────────────────────────────────
  const avatarBtn  = document.getElementById('topbarAvatarBtn');
  const dropdown   = document.getElementById('topbarDropdown');

  if (avatarBtn && dropdown) {
    avatarBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
    });
    document.addEventListener('click', function () {
      dropdown.classList.remove('open');
    });
  }

  // ── Toast auto-dismiss ────────────────────────────────────
  document.querySelectorAll('.toast').forEach(function (toast) {
    setTimeout(function () { dismissToast(toast); }, 4500);
  });

  // ── Animate stat cards ────────────────────────────────────
  document.querySelectorAll('.anim-fade-up').forEach(function (el) {
    el.style.opacity = ''; // let CSS animation take over
  });
});

// ── Toast helpers ────────────────────────────────────────────
function dismissToast(toast) {
  if (!toast) return;
  toast.style.transition = 'opacity .25s, transform .25s';
  toast.style.opacity = '0';
  toast.style.transform = 'translateX(20px)';
  setTimeout(function () { toast.remove(); }, 280);
}

function showToast(message, type) {
  // type: 'success' | 'error' | 'warn'
  const stack = document.getElementById('toastStack');
  if (!stack) return;
  const icons = {
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>',
    error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
    warn:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>'
  };
  const t = type || 'success';
  const div = document.createElement('div');
  div.className = 'toast toast--' + t;
  div.innerHTML = (icons[t] || '') +
    '<span>' + message + '</span>' +
    '<button class="toast-close" onclick="dismissToast(this.parentElement)" aria-label="Dismiss">' +
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>';
  stack.appendChild(div);
  setTimeout(function () { dismissToast(div); }, 4500);
}

// ── Live table filter ────────────────────────────────────────
function filterTable(inputId, tableId) {
  const filter   = document.getElementById(inputId).value.toLowerCase();
  const table    = document.getElementById(tableId);
  const emptyMsg = document.getElementById(tableId + '-empty');
  if (!table) return;

  const rows    = table.querySelectorAll('tbody tr');
  let   visible = 0;

  rows.forEach(function (row) {
    const match = row.textContent.toLowerCase().includes(filter);
    row.style.display = match ? '' : 'none';
    if (match) visible++;
  });

  if (emptyMsg) {
    emptyMsg.style.display = (visible === 0 && filter !== '') ? 'block' : 'none';
  }
}

// ── Character counter ────────────────────────────────────────
function charCount(inputId, counterId, maxLen) {
  const input   = document.getElementById(inputId);
  const counter = document.getElementById(counterId);
  if (!input || !counter) return;

  const used = input.value.length;
  counter.textContent = used + ' / ' + maxLen;
  counter.style.color =
    used > maxLen - 10 ? '#F43F5E' :
    used > maxLen - 25 ? '#F59E0B' : '#94A3B8';
}

// ── Profile picture preview ──────────────────────────────────
function previewAvatar(input) {
  if (!input.files || !input.files[0]) return;
  const file    = input.files[0];
  const allowed = ['image/jpeg', 'image/png', 'image/webp'];

  if (!allowed.includes(file.type)) {
    showToast('Only JPG, PNG, and WEBP images are allowed.', 'error');
    input.value = '';
    return;
  }
  if (file.size > 2 * 1024 * 1024) {
    showToast('Image must be under 2 MB.', 'error');
    input.value = '';
    return;
  }

  const reader = new FileReader();
  reader.onload = function (e) {
    const preview = document.getElementById('avatar-preview');
    if (preview) {
      // Replace initials div with img
      preview.style.backgroundImage = 'url(' + e.target.result + ')';
      preview.style.backgroundSize  = 'cover';
      preview.style.backgroundPosition = 'center';
      preview.textContent = '';
    }
  };
  reader.readAsDataURL(file);
}

// ── Password visibility toggle ───────────────────────────────
function togglePassword(btnId, inputId) {
  const btn   = document.getElementById(btnId);
  const input = document.getElementById(inputId);
  if (!btn || !input) return;

  const isPass = input.type === 'password';
  input.type   = isPass ? 'text' : 'password';

  // Swap icon SVG
  btn.innerHTML = isPass
    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>';
}
