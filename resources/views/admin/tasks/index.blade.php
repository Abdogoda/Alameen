<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة الأمين | تصحيح المهام والتقييم السريري</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@500;600;700;800&family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/admin.css" />
  </head>
  <body class="bg-[#F8FAFC] text-[#334155] min-h-screen flex flex-col font-['Cairo'] antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F]">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" onclick="toggleAdminSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <div class="flex-1 flex flex-row min-h-screen w-full relative">
      
      <!-- ============================================== -->
      <!-- 1. ADMIN SIDEBAR NAVIGATION                    -->
      <!-- ============================================== -->
      <aside id="admin-sidebar" class="fixed lg:sticky top-0 start-0 z-50 w-[270px] h-screen bg-white border-e border-[#DEE8F7] flex flex-col justify-between p-5 shrink-0 transition-transform duration-300 -translate-x-full lg:translate-x-0 rtl:translate-x-full lg:rtl:translate-x-0 shadow-sm lg:shadow-none">
        
        <div class="flex flex-col gap-6">
          <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 cursor-pointer">
              <div class="size-11 rounded-xl bg-[#EAF1FB] flex items-center justify-center p-2 shrink-0 border border-[#DEE8F7]">
                <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain pointer-events-none" />
              </div>
              <div>
                <span class="font-['Baloo_Bhaijaan_2'] font-extrabold text-xl leading-tight text-[#012383] block">منصة الأمين</span>
                <span class="text-[11px] font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2 py-0.5 rounded-full inline-block mt-0.5">لوحة الإدارة المركزية</span>
              </div>
            </a>
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
              <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>

          <nav class="flex flex-col gap-1.5">
            <p class="text-[11px] font-bold text-slate-400 px-3 uppercase tracking-wider mb-1">التقييم الأكاديمي</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
              <span class="flex-1 text-start">لوحة الإحصائيات</span>
            </a>

            <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              <span class="flex-1 text-start">المستويات والفيديوهات</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-50 text-[#0B2A8F] rounded-md">ديناميك</span>
            </a>

            <a href="{{ route('admin.receipts.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span class="flex-1 text-start">إيصالات الدفع</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-amber-100 text-amber-800 rounded-full">١٢ معلق</span>
            </a>

            <!-- تصحيح المهام (ACTIVE) -->
            <a href="{{ route('admin.tasks.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#012383] text-white font-bold text-sm shadow-xs transition-colors">
              <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              <span class="flex-1 text-start">تصحيح المهام</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-white/20 text-white rounded-full">٢٨ مهمة</span>
            </a>

            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              <span class="flex-1 text-start">الطلاب والاشتراكات</span>
            </a>

            <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <span class="flex-1 text-start">الشهادات المعتمدة</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span class="flex-1 text-start">إعدادات المنصة والدفع</span>
            </a>
          </nav>
        </div>

        <div class="border-t border-[#DEE8F7] pt-4">
          <a href="../pages/grades.html" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#EAF1FB] text-[#012383] text-xs font-bold transition-colors">
            <span>معاينة صفحة درجات الطالب</span>
            <span class="text-[10px] text-slate-400">فتح ↗</span>
          </a>
        </div>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN TASKS GRADING WORKSPACE                -->
      <!-- ============================================== -->
      <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        
        <!-- Header Bar -->
        <header class="h-20 bg-white border-b border-[#DEE8F7] px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
          <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer">
              <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
              <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] leading-tight">مركز تصحيح المهام والتقييم السريري</h1>
              <p class="text-xs text-slate-500">مراجعة حلول الطلاب للمهام الملحقة بالفيديوهات ورصد الدرجات وإرسال الملاحظات</p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
              <span class="size-2 rounded-full bg-purple-500 animate-ping"></span>
              ٢٨ مهمة بانتظار تصحيحك
            </span>
          </div>
        </header>

        <!-- Body Content -->
        <div class="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto">
          
          <!-- Quick Status Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs flex items-center justify-between">
              <div>
                <span class="text-xs font-bold text-slate-400">إجمالي المهام المرفوعة</span>
                <span class="text-2xl font-black text-[#012383] block mt-0.5">٥٦ مهمة</span>
              </div>
              <div class="size-10 rounded-xl bg-blue-50 text-[#0B2A8F] flex items-center justify-center font-bold">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs flex items-center justify-between">
              <div>
                <span class="text-xs font-bold text-slate-400">بانتظار رصد الدرجة</span>
                <span class="text-2xl font-black text-amber-600 block mt-0.5">٢٨ مهمة</span>
              </div>
              <div class="size-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-[#DEE8F7] shadow-xs flex items-center justify-between">
              <div>
                <span class="text-xs font-bold text-slate-400">متوسط درجات الطلاب</span>
                <span class="text-2xl font-black text-emerald-600 block mt-0.5">٨٧.٥٪</span>
              </div>
              <div class="size-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
            </div>
          </div>

          <!-- Filters & Search Toolbar -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-4 sm:p-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
              <button type="button" data-filter="all" class="task-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-[#012383] text-white">
                جميع المهام (٥٦)
              </button>
              <button type="button" data-filter="pending" class="task-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                بانتظار التصحيح (٢٨)
              </button>
              <button type="button" data-filter="graded" class="task-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                مُقيّمة ومُعتمدة (٢٨)
              </button>
            </div>

            <div class="relative w-full sm:w-72">
              <input type="text" id="tasks-search-input" placeholder="ابحث باسم الطالب أو الدرس..." class="w-full h-11 ps-4 pe-10 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] bg-slate-50/50" />
              <svg class="size-4 text-slate-400 absolute end-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

          </div>

          <!-- Tasks Submissions Table -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
              <table class="w-full text-right border-collapse">
                <thead>
                  <tr class="bg-slate-50/80 border-b border-[#DEE8F7] text-[12px] font-bold text-slate-500">
                    <th class="py-4 px-5">الطالب</th>
                    <th class="py-4 px-4">الدرس والمستوى المرتبط</th>
                    <th class="py-4 px-4">تاريخ الرفع</th>
                    <th class="py-4 px-4">ملف الحل المرفق</th>
                    <th class="py-4 px-4 text-center">الدرجة</th>
                    <th class="py-4 px-4 text-center">الحالة</th>
                    <th class="py-4 px-5 text-center">الإجراء</th>
                  </tr>
                </thead>
                <tbody id="tasks-table-body" class="divide-y divide-[#DEE8F7]/70 text-xs">
                  
                  <!-- Task 1 (Pending) -->
                  <tr id="task-row-201" data-status="pending" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">أحمد إبراهيم الحارثي</div>
                      <div class="text-[11px] text-slate-400">ahmed.ibrahim@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="font-bold text-slate-800">الدرس ٨: مهارات التمييز السمعي</div>
                      <div class="text-[11px] text-slate-400">المستوى الثاني • ٦ أزواج صوتية</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">اليوم، ١:١٥ م</span>
                    </td>
                    <td class="py-4 px-4">
                      <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل Auditory_Discrimination_Plan.pdf', 'info')" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB] flex items-center gap-1.5 cursor-pointer">
                        <svg class="size-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        <span>ملف الإجابة PDF</span>
                      </button>
                    </td>
                    <td class="task-score-cell py-4 px-4 text-center">
                      <span class="text-slate-400 font-semibold">—</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="task-status-badge px-3 py-1 rounded-full text-xs font-bold badge-pending inline-block">
                        بانتظار التصحيح
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="openGradingModal({taskId: 'task-row-201', studentName: 'أحمد إبراهيم الحارثي', lessonName: 'الدرس ٨: مهارات التمييز السمعي', submittedDate: 'اليوم، ١:١٥ م', fileName: 'Auditory_Discrimination_Plan.pdf', currentScore: '', currentFeedback: ''})" class="px-4 py-1.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-2xs transition-colors cursor-pointer">
                        تصحيح ورصد
                      </button>
                    </td>
                  </tr>

                  <!-- Task 2 (Pending) -->
                  <tr id="task-row-202" data-status="pending" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">فاطمة الزهراء علي</div>
                      <div class="text-[11px] text-slate-400">fatima.ali@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="font-bold text-slate-800">الدرس ٩: تدريبات الوعي الصوتي</div>
                      <div class="text-[11px] text-slate-400">المستوى الثاني • أنشطة التقطيع</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">اليوم، ١٠:٣٠ ص</span>
                    </td>
                    <td class="py-4 px-4">
                      <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل Phonological_Awareness_Activity.docx', 'info')" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB] flex items-center gap-1.5 cursor-pointer">
                        <svg class="size-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        <span>ملف النشاط DOCX</span>
                      </button>
                    </td>
                    <td class="task-score-cell py-4 px-4 text-center">
                      <span class="text-slate-400 font-semibold">—</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="task-status-badge px-3 py-1 rounded-full text-xs font-bold badge-pending inline-block">
                        بانتظار التصحيح
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="openGradingModal({taskId: 'task-row-202', studentName: 'فاطمة الزهراء علي', lessonName: 'الدرس ٩: تدريبات الوعي الصوتي', submittedDate: 'اليوم، ١٠:٣٠ ص', fileName: 'Phonological_Awareness_Activity.docx', currentScore: '', currentFeedback: ''})" class="px-4 py-1.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-2xs transition-colors cursor-pointer">
                        تصحيح ورصد
                      </button>
                    </td>
                  </tr>

                  <!-- Task 3 (Graded) -->
                  <tr id="task-row-203" data-status="graded" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">مروان خالد العتيبي</div>
                      <div class="text-[11px] text-slate-400">marwan.otaibi@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="font-bold text-slate-800">الدرس ١: أركان ومكونات عملية التواصل</div>
                      <div class="text-[11px] text-slate-400">المستوى الأول • التشويش السريري</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">منذ يومين</span>
                    </td>
                    <td class="py-4 px-4">
                      <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل Communication_Model.pdf', 'info')" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB] flex items-center gap-1.5 cursor-pointer">
                        <svg class="size-3.5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        <span>نموذج الحل PDF</span>
                      </button>
                    </td>
                    <td class="task-score-cell py-4 px-4 text-center">
                      <span class="font-bold text-sm text-[#012383]">٩٤ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="task-status-badge px-3 py-1 rounded-full text-xs font-bold badge-active inline-block">
                        مُقيّمة ومُعتمدة
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="openGradingModal({taskId: 'task-row-203', studentName: 'مروان خالد العتيبي', lessonName: 'الدرس ١: أركان عملية التواصل', submittedDate: 'منذ يومين', fileName: 'Communication_Model.pdf', currentScore: '94', currentFeedback: 'ممتاز جداً في تحديد دور التغذية الراجعة السمعية في تصحيح النطق.'})" class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] text-slate-700 hover:bg-[#EAF1FB] font-bold text-xs transition-colors cursor-pointer">
                        تعديل الدرجة
                      </button>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>

            <div class="p-4 bg-slate-50/70 border-t border-[#DEE8F7] flex items-center justify-between text-xs text-slate-500">
              <span>عرض ٣ من إجمالي ٥٦ تسليم مهمة</span>
              <div class="flex items-center gap-1">
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#012383] text-white font-bold">١</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">٢</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">التالي</button>
              </div>
            </div>
          </div>

        </div>

      </main>

    </div>

    <!-- Modal: Grade Task -->
    <div id="modal-grade-task" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7]">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div class="flex items-center gap-2">
            <div class="size-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center font-bold">
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">تصحيح المهمة السريرية</h3>
          </div>
          <button type="button" onclick="closeModal('modal-grade-task')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl text-xs space-y-1.5 border border-[#DEE8F7]/60">
          <p><span class="text-slate-400">الطالب:</span> <strong id="grade-student-name" class="text-[#012383]">أحمد إبراهيم</strong></p>
          <p><span class="text-slate-400">الدرس:</span> <strong id="grade-lesson-name" class="text-slate-700">الدرس ٨: مهارات التمييز السمعي</strong></p>
          <p><span class="text-slate-400">تاريخ الرفع:</span> <span id="grade-submitted-date" class="text-slate-600">اليوم، ١:١٥ م</span></p>
          <div class="pt-2 flex items-center justify-between border-t border-[#DEE8F7]/50 mt-2">
            <span class="text-slate-500 font-bold" id="grade-file-name">Auditory_Discrimination_Plan.pdf</span>
            <button type="button" onclick="showToast('تنزيل الملف', 'جاري تحميل ملف إجابة الطالب للمراجعة', 'info')" class="px-3 py-1 rounded-lg bg-white border border-[#DEE8F7] text-[#0B2A8F] font-bold text-xs hover:bg-[#EAF1FB]">تحميل الملف ⭳</button>
          </div>
        </div>

        <form onsubmit="submitTaskGrade(event)" class="flex flex-col gap-4">
          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">الدرجة المستحقة (من ١٠٠):</label>
            <input type="number" id="grade-score-input" required min="0" max="100" placeholder="مثال: 90" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383] outline-none focus:border-[#0B2A8F]" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">ملاحظات المحاضر والتوجيهات السريرية:</label>
            <textarea id="grade-feedback-input" rows="3" placeholder="اكتب ملاحظاتك للطالب لتوجيهه في الممارسة العيادية..." class="w-full p-3.5 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] resize-none"></textarea>
          </div>

          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal('modal-grade-task')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إلغاء</button>
            <button type="submit" class="px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">اعتماد ورصد الدرجة</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Script modules -->
    <script type="module">
      import { filterTableRows, filterByPill } from '/assets/js/admin.js';
      filterTableRows('tasks-search-input', 'tasks-table-body');
      filterByPill('.task-filter-pill', 'data-status', 'tasks-table-body');
    </script>
    <script type="module" src="/assets/js/admin.js"></script>
      <script type="module" src="./js/animations.js"></script>
  </body>
</html>


