/**
 * Al-Ameen Admin Dashboard - Core Interactive Logic
 * Handles modals, dynamic levels/lessons, pricing, receipts approval, task grading, and access control.
 */

// ==========================================
// 1. TOAST NOTIFICATIONS SYSTEM
// ==========================================
function initToastContainer() {
  if (!document.getElementById('toast-container')) {
    const container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }
}

export function showToast(title, message, type = 'success') {
  initToastContainer();
  const container = document.getElementById('toast-container');
  
  const toast = document.createElement('div');
  toast.className = `toast flex items-start gap-3 p-4 rounded-xl border shadow-lg bg-white ${
    type === 'success' ? 'border-emerald-200 text-emerald-900' :
    type === 'error' ? 'border-rose-200 text-rose-900' :
    'border-blue-200 text-blue-900'
  }`;

  const iconSvg = type === 'success' 
    ? `<div class="size-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600 font-bold"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>`
    : type === 'error'
    ? `<div class="size-6 rounded-full bg-rose-100 flex items-center justify-center shrink-0 text-rose-600 font-bold"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></div>`
    : `<div class="size-6 rounded-full bg-blue-100 flex items-center justify-center shrink-0 text-blue-600 font-bold"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>`;

  toast.innerHTML = `
    ${iconSvg}
    <div class="flex-1 text-right">
      <h4 class="font-bold text-sm font-['Cairo'] text-slate-800">${title}</h4>
      <p class="text-xs text-slate-600 font-['Cairo'] mt-0.5">${message}</p>
    </div>
    <button type="button" class="text-slate-400 hover:text-slate-600 cursor-pointer p-0.5" onclick="this.closest('.toast').remove()">
      <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.classList.add('toast-exit');
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

// ==========================================
// 2. MODAL DIALOGS CONTROLLER
// ==========================================
export function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.remove('modal-hidden');
  document.documentElement.classList.add('modal-open');
  document.body.classList.add('modal-open');
  document.body.style.overflow = 'hidden';
  document.documentElement.style.overflow = 'hidden';

  // Ensure modal content starts scrolled to top so header is never cut off
  const content = modal.querySelector('.modal-content') || modal;
  if (content && typeof content.scrollTop !== 'undefined') {
    content.scrollTop = 0;
  }
}

export function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.add('modal-hidden');

  const anyOpen = document.querySelector('.modal-backdrop:not(.modal-hidden)');
  if (!anyOpen) {
    document.documentElement.classList.remove('modal-open');
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
  }
}

// Global window attachment for inline HTML callbacks
if (typeof window !== 'undefined') {
  window.openModal = openModal;
  window.closeModal = closeModal;
  window.showToast = showToast;
}

// Close modal on escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-backdrop:not(.modal-hidden)').forEach((modal) => {
      closeModal(modal.id);
    });
  }
});

// Close modal on clicking backdrop
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('modal-backdrop')) {
    closeModal(e.target.id);
  }
});

// Prevent outside scroll leak when modal is open
document.addEventListener('wheel', (e) => {
  const activeModal = document.querySelector('.modal-backdrop:not(.modal-hidden)');
  if (!activeModal) return;
  if (!e.target.closest('.modal-content')) {
    e.preventDefault();
  }
}, { passive: false });

document.addEventListener('touchmove', (e) => {
  const activeModal = document.querySelector('.modal-backdrop:not(.modal-hidden)');
  if (!activeModal) return;
  if (!e.target.closest('.modal-content')) {
    e.preventDefault();
  }
}, { passive: false });

// ==========================================
// 3. SIDEBAR MOBILE TOGGLE
// ==========================================
export function toggleAdminSidebar() {
  const sidebar = document.getElementById('admin-sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if (!sidebar) return;
  
  if (sidebar.classList.contains('-translate-x-full') || sidebar.classList.contains('translate-x-full')) {
    sidebar.classList.remove('-translate-x-full', 'translate-x-full');
    overlay?.classList.remove('hidden');
  } else {
    // RTL Check
    const isRtl = document.documentElement.dir === 'rtl';
    if (isRtl) {
      sidebar.classList.add('translate-x-full');
    } else {
      sidebar.classList.add('-translate-x-full');
    }
    overlay?.classList.add('hidden');
  }
}

if (typeof window !== 'undefined') {
  window.toggleAdminSidebar = toggleAdminSidebar;
}

// ==========================================
// 4. DYNAMIC PRICING CONTROLLER
// ==========================================
export function handleUpdatePrice(event) {
  event.preventDefault();
  const form = event.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  if (window.AlameenButtonLoader && submitBtn) {
    window.AlameenButtonLoader.setButtonLoading(submitBtn, true, 'جاري تحديث السعر...');
  }

  setTimeout(() => {
    const originalPrice = form.originalPrice?.value;
    const currentPrice = form.currentPrice?.value;

    // Update elements across page
    document.querySelectorAll('.dynamic-course-price').forEach(el => {
      el.textContent = `${Number(currentPrice).toLocaleString('ar-EG')} ج.م`;
    });
    document.querySelectorAll('.dynamic-course-old-price').forEach(el => {
      el.textContent = `${Number(originalPrice).toLocaleString('ar-EG')} ج.م`;
    });

    closeModal('modal-edit-price');
    if (window.AlameenButtonLoader && submitBtn) {
      window.AlameenButtonLoader.setButtonLoading(submitBtn, false);
    }
    showToast('تم تحديث السعر بنجاح', `تم ضبط سعر الكورس إلى ${currentPrice} ج.م مع إمكانية الشراء المباشر.`);
  }, 400);
}

if (typeof window !== 'undefined') {
  window.handleUpdatePrice = handleUpdatePrice;
}

// ==========================================
// 5. PAYMENT RECEIPTS APPROVAL & ACCESS GRANT
// ==========================================
export function viewReceiptModal(data) {
  const modal = document.getElementById('modal-view-receipt');
  if (!modal) return;

  document.getElementById('receipt-student-name').textContent = data.studentName;
  document.getElementById('receipt-student-phone').textContent = data.studentPhone;
  document.getElementById('receipt-amount').textContent = `${data.amount} ج.م`;
  document.getElementById('receipt-method').textContent = data.method;
  document.getElementById('receipt-date').textContent = data.date;
  document.getElementById('receipt-ref').textContent = data.ref;
  
  const imgEl = document.getElementById('receipt-image-preview');
  if (imgEl && data.image) {
    imgEl.src = data.image;
  }

  // Store current active row ID for action buttons
  modal.dataset.rowId = data.rowId;
  openModal('modal-view-receipt');
}

export function approveReceipt() {
  const modal = document.getElementById('modal-view-receipt');
  const confirmBtn = document.querySelector('#modal-view-receipt button[onclick*="approveReceipt"]');
  if (window.AlameenButtonLoader && confirmBtn) {
    window.AlameenButtonLoader.setButtonLoading(confirmBtn, true, 'جاري الاعتماد ومنح الوصول...');
  }

  setTimeout(() => {
    const rowId = modal.dataset.rowId;
    const row = document.getElementById(rowId);

    if (row) {
      const badge = row.querySelector('.receipt-status-badge');
      if (badge) {
        badge.className = 'receipt-status-badge px-3 py-1 rounded-full text-xs font-bold badge-active flex items-center gap-1.5 justify-center';
        badge.innerHTML = `<span class="size-1.5 rounded-full bg-emerald-500"></span> <span>معتمد • مفعل</span>`;
      }
      const actionCell = row.querySelector('.receipt-actions-cell');
      if (actionCell) {
        actionCell.innerHTML = `<span class="text-xs text-emerald-700 font-bold bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg">تم منح الوصول</span>`;
      }
    }

    closeModal('modal-view-receipt');
    if (window.AlameenButtonLoader && confirmBtn) {
      window.AlameenButtonLoader.setButtonLoading(confirmBtn, false);
    }
    showToast('تم اعتماد الإيصال', 'تم تأكيد السداد وتفعيل صلاحية وصول الطالب إلى الكورس تلقائيًا.');
  }, 400);
}

export function openRejectReceiptModal() {
  closeModal('modal-view-receipt');
  openModal('modal-reject-receipt');
}

export function submitRejectReceipt(event) {
  event.preventDefault();
  const form = event.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  if (window.AlameenButtonLoader && submitBtn) {
    window.AlameenButtonLoader.setButtonLoading(submitBtn, true, 'جاري تأكيد الرفض...');
  }

  setTimeout(() => {
    const modal = document.getElementById('modal-view-receipt');
    const rowId = modal?.dataset?.rowId;
    const reason = document.getElementById('reject-reason-input')?.value || 'بيانات التحويل غير مطابقة';

    if (rowId) {
      const row = document.getElementById(rowId);
      if (row) {
        const badge = row.querySelector('.receipt-status-badge');
        if (badge) {
          badge.className = 'receipt-status-badge px-3 py-1 rounded-full text-xs font-bold badge-rejected flex items-center gap-1.5 justify-center';
          badge.innerHTML = `<span class="size-1.5 rounded-full bg-rose-500"></span> <span>مرفوض</span>`;
        }
        const actionCell = row.querySelector('.receipt-actions-cell');
        if (actionCell) {
          actionCell.innerHTML = `<button type="button" onclick="showToast('سبب الرفض', '${reason}', 'info')" class="text-xs text-rose-700 hover:underline">عرض السبب</button>`;
        }
      }
    }

    closeModal('modal-reject-receipt');
    if (window.AlameenButtonLoader && submitBtn) {
      window.AlameenButtonLoader.setButtonLoading(submitBtn, false);
    }
    showToast('تم رفض الإيصال', `تم رفض الطلب مع إشعار الطالب بالسبب: ${reason}`, 'error');
  }, 400);
}

if (typeof window !== 'undefined') {
  window.viewReceiptModal = viewReceiptModal;
  window.approveReceipt = approveReceipt;
  window.openRejectReceiptModal = openRejectReceiptModal;
  window.submitRejectReceipt = submitRejectReceipt;
}

// ==========================================
// 6. STUDENT ACCESS CONTROL (GRANT / REVOKE)
// ==========================================
export function toggleStudentAccess(studentId, studentName, currentStatus) {
  const modal = document.getElementById('modal-toggle-access');
  if (!modal) return;

  modal.dataset.studentId = studentId;
  modal.dataset.currentStatus = currentStatus;

  const titleEl = document.getElementById('toggle-access-title');
  const descEl = document.getElementById('toggle-access-desc');
  const confirmBtn = document.getElementById('toggle-access-confirm-btn');

  if (currentStatus === 'active') {
    titleEl.textContent = `إلغاء صلاحية الوصول للطالب: ${studentName}`;
    descEl.textContent = `هل أنت متأكد من رغبتك في سحب صلاحية الطالب عن كورس التخاطب؟ لن يتمكن من مشاهدة الفيديوهات أو تسليم المهام بعد الآن.`;
    confirmBtn.textContent = 'نعم، قم بإلغاء الصلاحية';
    confirmBtn.className = 'h-11 px-6 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm transition-colors cursor-pointer shadow-sm';
  } else {
    titleEl.textContent = `منح وتفعيل الوصول للطالب: ${studentName}`;
    descEl.textContent = `سيتم تفعيل وصول الطالب فورًا لكامل مستويات الكورس وفيديوهاته وإتاحة تسليم المهام.`;
    confirmBtn.textContent = 'نعم، امنح الصلاحية الآن';
    confirmBtn.className = 'h-11 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition-colors cursor-pointer shadow-sm';
  }

  openModal('modal-toggle-access');
}

export function confirmToggleAccess() {
  const modal = document.getElementById('modal-toggle-access');
  const studentId = modal.dataset.studentId;
  const currentStatus = modal.dataset.currentStatus;
  const confirmBtn = document.getElementById('toggle-access-confirm-btn');
  const processingText = currentStatus === 'active' ? 'جاري إلغاء الصلاحية...' : 'جاري تفعيل الصلاحية...';

  if (window.AlameenButtonLoader && confirmBtn) {
    window.AlameenButtonLoader.setButtonLoading(confirmBtn, true, processingText);
  }

  setTimeout(() => {
    const row = document.getElementById(studentId);

    if (row) {
      const badge = row.querySelector('.student-access-badge');
      const toggleBtn = row.querySelector('.student-toggle-btn');

      if (currentStatus === 'active') {
        if (badge) {
          badge.className = 'student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-revoked';
          badge.textContent = 'ملغي الصلاحية';
        }
        if (toggleBtn) {
          toggleBtn.textContent = 'منح الصلاحية';
          toggleBtn.className = 'student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors';
          toggleBtn.setAttribute('onclick', `toggleStudentAccess('${studentId}', '${row.dataset.studentName}', 'revoked')`);
        }
        showToast('تم سحب الصلاحية', 'تم إلغاء وصول الطالب للكورس بنجاح.', 'info');
      } else {
        if (badge) {
          badge.className = 'student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-active';
          badge.textContent = 'نشط ومفعل';
        }
        if (toggleBtn) {
          toggleBtn.textContent = 'إلغاء الصلاحية';
          toggleBtn.className = 'student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors';
          toggleBtn.setAttribute('onclick', `toggleStudentAccess('${studentId}', '${row.dataset.studentName}', 'active')`);
        }
        showToast('تم تفعيل الصلاحية', 'تم منح الطالب صلاحية الوصول الكامل لمحتوى الكورس.');
      }
    }

    closeModal('modal-toggle-access');
    if (window.AlameenButtonLoader && confirmBtn) {
      window.AlameenButtonLoader.setButtonLoading(confirmBtn, false);
    }
  }, 400);
}

if (typeof window !== 'undefined') {
  window.toggleStudentAccess = toggleStudentAccess;
  window.confirmToggleAccess = confirmToggleAccess;
}

// ==========================================
// 7. TASK GRADING & CLINICAL EVALUATION
// ==========================================
export function openGradingModal(data) {
  const modal = document.getElementById('modal-grade-task');
  if (!modal) return;

  modal.dataset.taskId = data.taskId;
  document.getElementById('grade-student-name').textContent = data.studentName;
  document.getElementById('grade-lesson-name').textContent = data.lessonName;
  document.getElementById('grade-submitted-date').textContent = data.submittedDate;
  document.getElementById('grade-file-name').textContent = data.fileName;
  
  const scoreInput = document.getElementById('grade-score-input');
  if (scoreInput) scoreInput.value = data.currentScore || '';
  
  const feedbackInput = document.getElementById('grade-feedback-input');
  if (feedbackInput) feedbackInput.value = data.currentFeedback || '';

  openModal('modal-grade-task');
}

export function submitTaskGrade(event) {
  event.preventDefault();
  const form = event.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  if (window.AlameenButtonLoader && submitBtn) {
    window.AlameenButtonLoader.setButtonLoading(submitBtn, true, 'جاري حفظ التقييم...');
  }

  setTimeout(() => {
    const modal = document.getElementById('modal-grade-task');
    const taskId = modal?.dataset?.taskId;
    const score = document.getElementById('grade-score-input')?.value;
    const feedback = document.getElementById('grade-feedback-input')?.value;

    const row = taskId ? document.getElementById(taskId) : null;
    if (row) {
      const scoreCell = row.querySelector('.task-score-cell');
      if (scoreCell) {
        scoreCell.innerHTML = `<span class="font-bold text-sm text-[#012383]">${score} / ١٠٠</span>`;
      }
      const statusCell = row.querySelector('.task-status-badge');
      if (statusCell) {
        statusCell.className = 'task-status-badge px-3 py-1 rounded-full text-xs font-bold badge-active';
        statusCell.textContent = 'مُقيّمة ومُعتمدة';
      }
    }

    closeModal('modal-grade-task');
    if (window.AlameenButtonLoader && submitBtn) {
      window.AlameenButtonLoader.setButtonLoading(submitBtn, false);
    }
    showToast('تم حفظ التقييم', `تم تسجيل الدرجة (${score} من 100) وإرسال الملاحظات التوجيهية لملف الطالب.`);
  }, 400);
}

if (typeof window !== 'undefined') {
  window.openGradingModal = openGradingModal;
  window.submitTaskGrade = submitTaskGrade;
}

// ==========================================
// 8. DYNAMIC LEVELS & LESSONS CRUD SIMULATOR
// ==========================================
export function submitAddLevel(event) {
  event.preventDefault();
  const form = event.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  const title = document.getElementById('new-level-title')?.value;
  const desc = document.getElementById('new-level-desc')?.value;
  const container = document.getElementById('levels-curriculum-container');

  if (!title || !container) return;

  if (window.AlameenButtonLoader && submitBtn) {
    window.AlameenButtonLoader.setButtonLoading(submitBtn, true, 'جاري إضافة المستوى...');
  }

  setTimeout(() => {
    const levelCount = container.children.length + 1;
    const levelId = `level-dynamic-${Date.now()}`;

    const levelHtml = `
      <div id="${levelId}" class="bg-white border border-[#DEE8F7] rounded-2xl overflow-hidden shadow-xs transition-all mb-4">
        <div class="p-5 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between border-b border-[#DEE8F7]/60">
          <div class="flex items-center gap-3">
            <span class="size-8 rounded-xl bg-[#EAF1FB] text-[#0B2A8F] font-bold text-sm flex items-center justify-center font-['Cairo']">
              ${levelCount}
            </span>
            <div>
              <h3 class="font-bold text-base text-[#012383] font-['Cairo']">${title}</h3>
              <p class="text-xs text-[#64748B] font-['Cairo']">${desc || 'مستوى ديناميكي جديد • ٠ فيديوهات'}</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <button type="button" onclick="openAddLessonModal('${levelId}', '${title}')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
              <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
              <span>إضافة درس</span>
            </button>
            <button type="button" onclick="document.getElementById('${levelId}').remove(); showToast('تم الحذف', 'تم حذف المستوى بنجاح', 'info');" class="p-2 text-slate-400 hover:text-rose-600 transition-colors cursor-pointer rounded-lg hover:bg-rose-50" title="حذف المستوى">
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </div>
        </div>
        <div class="lessons-list p-4 flex flex-col gap-2.5 bg-slate-50/40 min-h-[70px] text-center text-xs text-slate-400 font-['Cairo'] py-6">
          لا توجد فيديوهات في هذا المستوى حتى الآن. اضغط على زر "إضافة درس" لإضافة فيديو ومهمته.
        </div>
      </div>
    `;

    container.insertAdjacentHTML('beforeend', levelHtml);
    closeModal('modal-add-level');
    if (window.AlameenButtonLoader && submitBtn) {
      window.AlameenButtonLoader.setButtonLoading(submitBtn, false);
    }
    showToast('تمت إضافة المستوى', `تم إنشاء "${title}" بنجاح ضمن تسلسل منهج الكورس.`);
    form.reset();
  }, 400);
}

export function openAddLessonModal(levelId, levelTitle) {
  const modal = document.getElementById('modal-add-lesson');
  if (!modal) return;
  modal.dataset.targetLevelId = levelId;
  const label = document.getElementById('add-lesson-level-label');
  if (label) label.textContent = levelTitle;
  openModal('modal-add-lesson');
}

export function submitAddLesson(event) {
  event.preventDefault();
  const form = event.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  const modal = document.getElementById('modal-add-lesson');
  const levelId = modal.dataset.targetLevelId;
  const levelEl = document.getElementById(levelId);
  if (!levelEl) return;

  const title = document.getElementById('new-lesson-title')?.value;
  if (!title) return;

  if (window.AlameenButtonLoader && submitBtn) {
    window.AlameenButtonLoader.setButtonLoading(submitBtn, true, 'جاري إضافة الدرس...');
  }

  setTimeout(() => {
    const listContainer = levelEl.querySelector('.lessons-list');
    const duration = document.getElementById('new-lesson-duration')?.value || '20';
    const taskTitle = document.getElementById('new-lesson-task-title')?.value || `مهمة: ${title}`;

    if (listContainer.innerHTML.includes('لا توجد فيديوهات')) {
      listContainer.innerHTML = '';
    }

    const lessonId = `lesson-dynamic-${Date.now()}`;
    const lessonHtml = `
      <div id="${lessonId}" class="p-3.5 bg-white border border-[#DEE8F7] rounded-xl flex items-center justify-between gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
        <div class="flex items-center gap-3">
          <div class="size-8 rounded-lg bg-[#EAF1FB] flex items-center justify-center shrink-0 text-[#0B2A8F]">
            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
          </div>
          <div class="text-right">
            <h4 class="font-bold text-sm text-[#012383] font-['Cairo']">${title}</h4>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-['Cairo'] mt-0.5">
              <span>${duration} دقيقة</span>
              <span>•</span>
              <span class="text-emerald-700 font-medium">مهمة مرتبطة: ${taskTitle}</span>
            </div>
          </div>
        </div>
        <button type="button" onclick="document.getElementById('${lessonId}').remove(); showToast('تم الحذف', 'تم حذف الدرس ومهمته', 'info');" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors cursor-pointer rounded-lg hover:bg-rose-50" title="حذف الدرس">
          <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
      </div>
    `;

    listContainer.insertAdjacentHTML('beforeend', lessonHtml);
    closeModal('modal-add-lesson');
    if (window.AlameenButtonLoader && submitBtn) {
      window.AlameenButtonLoader.setButtonLoading(submitBtn, false);
    }
    showToast('تمت إضافة الدرس', `تمت إضافة فيديو "${title}" مع مهمته بنجاح.`);
    form.reset();
  }, 400);
}

if (typeof window !== 'undefined') {
  window.submitAddLevel = submitAddLevel;
  window.openAddLessonModal = openAddLessonModal;
  window.submitAddLesson = submitAddLesson;
}

// ==========================================
// 9. SEARCH & FILTER TABLE HELPER
// ==========================================
export function filterTableRows(inputId, tableBodyId, columnIndex = 0) {
  const input = document.getElementById(inputId);
  const tbody = document.getElementById(tableBodyId);
  if (!input || !tbody) return;

  input.addEventListener('input', () => {
    const query = input.value.trim().toLowerCase();
    const rows = tbody.querySelectorAll('tr');

    rows.forEach(row => {
      const cellText = row.innerText.toLowerCase();
      if (cellText.includes(query)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
}

export function filterByPill(pillSelector, targetAttribute, tableBodyId) {
  const pills = document.querySelectorAll(pillSelector);
  const tbody = document.getElementById(tableBodyId);
  if (!pills.length || !tbody) return;

  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      pills.forEach(p => p.classList.remove('bg-[#012383]', 'text-white'));
      pill.classList.add('bg-[#012383]', 'text-white');

      const filterValue = pill.dataset.filter;
      const rows = tbody.querySelectorAll('tr');

      rows.forEach(row => {
        const val = row.getAttribute(targetAttribute);
        if (filterValue === 'all' || val === filterValue) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });
}

if (typeof window !== 'undefined') {
  window.filterTableRows = filterTableRows;
  window.filterByPill = filterByPill;
  if (!window.setTheme) {
    window.setTheme = (theme) => {
      if (window.AlameenTheme) {
        window.AlameenTheme.setTheme(theme);
      } else {
        localStorage.setItem('alameen_theme', theme);
        const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', isDark);
        document.documentElement.setAttribute('data-theme', theme);
      }
    };
  }
}

