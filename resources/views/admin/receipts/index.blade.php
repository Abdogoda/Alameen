<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة الأمين | مراجعة واعتماد إيصالات الدفع اليدوي</title>
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
            <p class="text-[11px] font-bold text-slate-400 px-3 uppercase tracking-wider mb-1">المالية والاشتراكات</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
              <span class="flex-1 text-start">لوحة الإحصائيات</span>
            </a>

            <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              <span class="flex-1 text-start">المستويات والفيديوهات</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-50 text-[#0B2A8F] rounded-md">ديناميك</span>
            </a>

            <!-- إيصالات الدفع (ACTIVE) -->
            <a href="{{ route('admin.receipts.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#012383] text-white font-bold text-sm shadow-xs transition-colors">
              <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span class="flex-1 text-start">إيصالات الدفع</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-amber-400 text-slate-900 rounded-full">١٢ معلق</span>
            </a>

            <a href="{{ route('admin.tasks.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              <span class="flex-1 text-start">تصحيح المهام</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-100 text-[#0B2A8F] rounded-full">٢٨ مهمة</span>
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
          <p class="text-[11px] text-slate-400 text-center font-medium">منصة الأمين © ٢٠٢٦</p>
        </div>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN RECEIPTS CONTENT WORKSPACE             -->
      <!-- ============================================== -->
      <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        
        <!-- Header Bar -->
        <header class="h-20 bg-white border-b border-[#DEE8F7] px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
          <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer">
              <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
              <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] leading-tight">إيصالات الدفع اليدوي والتحويلات</h1>
              <p class="text-xs text-slate-500">فحص صور الإيصالات المرفوعة، التحقق من المبالغ، وتفعيل وصول الطلاب للكورس</p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
              <span class="size-2 rounded-full bg-amber-500 animate-ping"></span>
              ١٢ إيصال معلق بانتظار الاعتماد
            </span>
          </div>
        </header>

        <!-- Body Content -->
        <div class="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto">
          
          <!-- Filters & Search Toolbar -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-4 sm:p-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            
            <!-- Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
              <button type="button" data-filter="all" class="receipt-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-[#012383] text-white">
                جميع الإيصالات (٤٥)
              </button>
              <button type="button" data-filter="pending" class="receipt-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                بانتظار الاعتماد (١٢)
              </button>
              <button type="button" data-filter="approved" class="receipt-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                معتمدة ومفعلة (٣٠)
              </button>
              <button type="button" data-filter="rejected" class="receipt-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                مرفوضة (٣)
              </button>
            </div>

            <!-- Search Input -->
            <div class="relative w-full sm:w-72">
              <input type="text" id="receipts-search-input" placeholder="ابحث باسم الطالب أو رقم الهاتف..." class="w-full h-11 ps-4 pe-10 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] bg-slate-50/50" />
              <svg class="size-4 text-slate-400 absolute end-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

          </div>

          <!-- Receipts Data Table -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
              <table class="w-full text-right border-collapse">
                <thead>
                  <tr class="bg-slate-50/80 border-b border-[#DEE8F7] text-[12px] font-bold text-slate-500">
                    <th class="py-4 px-5">الطالب وبياناته</th>
                    <th class="py-4 px-4">وسيلة التحويل</th>
                    <th class="py-4 px-4">المبلغ المرفوع</th>
                    <th class="py-4 px-4">رقم العملية وتاريخها</th>
                    <th class="py-4 px-4 text-center">الإيصال المرفق</th>
                    <th class="py-4 px-4 text-center">حالة السداد والوصول</th>
                    <th class="py-4 px-5 text-center">الإجراء الإداري</th>
                  </tr>
                </thead>
                <tbody id="receipts-table-body" class="divide-y divide-[#DEE8F7]/70 text-xs">
                  
                  <!-- Row 1 (Pending) -->
                  <tr id="receipt-row-101" data-status="pending" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">سارة خالد المنصور</div>
                      <div class="text-[11px] text-slate-500 font-mono">01023456789 • sara.mansour@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">فودافون كاش</span>
                      <div class="text-[10px] text-slate-400">محفظة إلكترونية</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-[#0B2A8F]">١,٢٠٠ ج.م</span>
                      <span class="text-[10px] text-emerald-600 block">مكتمل 100%</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-slate-700 font-semibold">VF-948271</span>
                      <div class="text-[11px] text-slate-400">منذ ١٥ دقيقة</div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <button type="button" onclick="viewReceiptModal({rowId: 'receipt-row-101', studentName: 'سارة خالد المنصور', studentPhone: '01023456789', amount: '1200', method: 'فودافون كاش (Vodafone Cash)', date: 'منذ ١٥ دقيقة', ref: 'VF-948271', image: '/assets/images/certificate-preview.png'})" class="size-11 mx-auto rounded-xl border border-[#DEE8F7] p-1 bg-white hover:border-[#0B2A8F] cursor-pointer overflow-hidden group shadow-2xs">
                        <img src="/assets/images/certificate-preview.png" alt="إيصال" class="size-full object-cover rounded-md group-hover:scale-105 transition-transform" />
                      </button>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="receipt-status-badge px-3 py-1 rounded-full text-xs font-bold badge-pending inline-flex items-center gap-1.5">
                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                        <span>قيد المراجعة</span>
                      </span>
                    </td>
                    <td class="receipt-actions-cell py-4 px-5 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="viewReceiptModal({rowId: 'receipt-row-101', studentName: 'سارة خالد المنصور', studentPhone: '01023456789', amount: '1200', method: 'فودافون كاش (Vodafone Cash)', date: 'منذ ١٥ دقيقة', ref: 'VF-948271', image: '/assets/images/certificate-preview.png'})" class="px-3.5 py-1.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-2xs transition-colors cursor-pointer">
                          فحص واعتماد
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 2 (Pending) -->
                  <tr id="receipt-row-102" data-status="pending" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">محمود عبد العزيز قنديل</div>
                      <div class="text-[11px] text-slate-500 font-mono">01198765432 • mahmoud.azeez@outlook.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">إنستاباي (InstaPay)</span>
                      <div class="text-[10px] text-slate-400">تحويل لحظي مباشر</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-[#0B2A8F]">١,٢٠٠ ج.م</span>
                      <span class="text-[10px] text-emerald-600 block">مكتمل 100%</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-slate-700 font-semibold">IP-881923</span>
                      <div class="text-[11px] text-slate-400">منذ ٤٠ دقيقة</div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <button type="button" onclick="viewReceiptModal({rowId: 'receipt-row-102', studentName: 'محمود عبد العزيز قنديل', studentPhone: '01198765432', amount: '1200', method: 'إنستاباي (InstaPay)', date: 'منذ ٤٠ دقيقة', ref: 'IP-881923', image: '/assets/images/assignment-banner.png'})" class="size-11 mx-auto rounded-xl border border-[#DEE8F7] p-1 bg-white hover:border-[#0B2A8F] cursor-pointer overflow-hidden group shadow-2xs">
                        <img src="/assets/images/assignment-banner.png" alt="إيصال" class="size-full object-cover rounded-md group-hover:scale-105 transition-transform" />
                      </button>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="receipt-status-badge px-3 py-1 rounded-full text-xs font-bold badge-pending inline-flex items-center gap-1.5">
                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                        <span>قيد المراجعة</span>
                      </span>
                    </td>
                    <td class="receipt-actions-cell py-4 px-5 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="viewReceiptModal({rowId: 'receipt-row-102', studentName: 'محمود عبد العزيز قنديل', studentPhone: '01198765432', amount: '1200', method: 'إنستاباي (InstaPay)', date: 'منذ ٤٠ دقيقة', ref: 'IP-881923', image: '/assets/images/assignment-banner.png'})" class="px-3.5 py-1.5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-2xs transition-colors cursor-pointer">
                          فحص واعتماد
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 3 (Approved) -->
                  <tr id="receipt-row-103" data-status="approved" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">أحمد إبراهيم الحارثي</div>
                      <div class="text-[11px] text-slate-500 font-mono">01011223344 • ahmed.ibrahim@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">تحويل بنكي الأهلي</span>
                      <div class="text-[10px] text-slate-400">حساب بنكي NBE</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-[#0B2A8F]">١,٢٠٠ ج.م</span>
                      <span class="text-[10px] text-emerald-600 block">معتمد</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-slate-700 font-semibold">NBE-772101</span>
                      <div class="text-[11px] text-slate-400">أمس، ٣:٣٠ م</div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <button type="button" onclick="viewReceiptModal({rowId: 'receipt-row-103', studentName: 'أحمد إبراهيم الحارثي', studentPhone: '01011223344', amount: '1200', method: 'تحويل بنكي الأهلي', date: 'أمس، ٣:٣٠ م', ref: 'NBE-772101', image: '/assets/images/tip-1.png'})" class="size-11 mx-auto rounded-xl border border-[#DEE8F7] p-1 bg-white hover:border-[#0B2A8F] cursor-pointer overflow-hidden group shadow-2xs">
                        <img src="/assets/images/tip-1.png" alt="إيصال" class="size-full object-cover rounded-md group-hover:scale-105 transition-transform" />
                      </button>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="receipt-status-badge px-3 py-1 rounded-full text-xs font-bold badge-active inline-flex items-center gap-1.5">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                        <span>معتمد • مفعل</span>
                      </span>
                    </td>
                    <td class="receipt-actions-cell py-4 px-5 text-center">
                      <span class="text-xs text-emerald-700 font-bold bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg">
                        الوصول متاح
                      </span>
                    </td>
                  </tr>

                  <!-- Row 4 (Rejected) -->
                  <tr id="receipt-row-104" data-status="rejected" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">خالد مصطفى الشامي</div>
                      <div class="text-[11px] text-slate-500 font-mono">01556677889 • khaled.shami@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">فودافون كاش</span>
                      <div class="text-[10px] text-slate-400">محفظة إلكترونية</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-rose-600">٦٠٠ ج.م</span>
                      <span class="text-[10px] text-rose-500 block">مبلغ ناقص</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-slate-700 font-semibold">VF-110293</span>
                      <div class="text-[11px] text-slate-400">منذ يومين</div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <button type="button" onclick="viewReceiptModal({rowId: 'receipt-row-104', studentName: 'خالد مصطفى الشامي', studentPhone: '01556677889', amount: '600', method: 'فودافون كاش', date: 'منذ يومين', ref: 'VF-110293', image: '/assets/images/tip-4.png'})" class="size-11 mx-auto rounded-xl border border-[#DEE8F7] p-1 bg-white hover:border-[#0B2A8F] cursor-pointer overflow-hidden group shadow-2xs">
                        <img src="/assets/images/tip-4.png" alt="إيصال" class="size-full object-cover rounded-md group-hover:scale-105 transition-transform" />
                      </button>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="receipt-status-badge px-3 py-1 rounded-full text-xs font-bold badge-rejected inline-flex items-center gap-1.5">
                        <span class="size-1.5 rounded-full bg-rose-500"></span>
                        <span>مرفوض</span>
                      </span>
                    </td>
                    <td class="receipt-actions-cell py-4 px-5 text-center">
                      <button type="button" onclick="showToast('سبب الرفض', 'المبلغ المحول (٦٠٠ ج.م) أقل من سعر الكورس المطلوب (١,٢٠٠ ج.م)', 'error')" class="text-xs text-rose-600 font-bold hover:underline">
                        سبب الرفض
                      </button>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>

            <!-- Table Footer -->
            <div class="p-4 bg-slate-50/70 border-t border-[#DEE8F7] flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3">
              <span>عرض ٤ من إجمالي ٤٥ إيصال مسجل بالنظام</span>
              <div class="flex items-center gap-1">
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] text-slate-400 cursor-not-allowed">السابق</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#012383] text-white font-bold">١</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">٢</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB]">التالي</button>
              </div>
            </div>
          </div>

        </div>

      </main>

    </div>

    <!-- Modal: View Receipt Details -->
    <div id="modal-view-receipt" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 flex flex-col gap-6 shadow-2xl border border-[#DEE8F7] max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div class="flex items-center gap-2">
            <span class="size-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">مراجعة إيصال التحويل اليدوي</h3>
          </div>
          <button type="button" onclick="closeModal('modal-view-receipt')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <div class="w-full h-56 rounded-2xl bg-slate-100 border border-[#DEE8F7] overflow-hidden relative group">
          <img id="receipt-image-preview" src="/assets/images/certificate-preview.png" alt="إيصال الدفع" class="size-full object-contain pointer-events-none" />
        </div>

        <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-4 rounded-2xl border border-[#DEE8F7]/60">
          <div>
            <span class="text-slate-400 block">اسم الطالب المحول:</span>
            <span id="receipt-student-name" class="font-bold text-[#012383] text-sm mt-0.5 block">سارة خالد المنصور</span>
          </div>
          <div>
            <span class="text-slate-400 block">رقم الهاتف / الحساب:</span>
            <span id="receipt-student-phone" class="font-bold text-slate-700 text-sm mt-0.5 block">01023456789</span>
          </div>
          <div>
            <span class="text-slate-400 block">المبلغ المرفوع:</span>
            <span id="receipt-amount" class="font-bold text-emerald-700 text-sm mt-0.5 block">١,٢٠٠ ج.م</span>
          </div>
          <div>
            <span class="text-slate-400 block">طريقة التحويل:</span>
            <span id="receipt-method" class="font-bold text-slate-700 mt-0.5 block">فودافون كاش</span>
          </div>
          <div>
            <span class="text-slate-400 block">رقم العملية / المرجع:</span>
            <span id="receipt-ref" class="font-mono text-slate-600 mt-0.5 block">VF-948271</span>
          </div>
          <div>
            <span class="text-slate-400 block">تاريخ الإرسال:</span>
            <span id="receipt-date" class="font-medium text-slate-600 mt-0.5 block">منذ ١٥ دقيقة</span>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" onclick="openRejectReceiptModal()" class="px-5 h-11 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition-colors cursor-pointer border border-rose-200">
            رفض الإيصال مع سبب
          </button>
          <button type="button" onclick="approveReceipt()" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white text-xs font-bold transition-all shadow-md cursor-pointer flex items-center gap-2">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>اعتماد ومنح الوصول للكورس</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Modal: Reject Reason -->
    <div id="modal-reject-receipt" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 flex flex-col gap-5 shadow-2xl border border-rose-100">
        <div class="flex items-center gap-2.5 text-rose-600">
          <div class="size-9 rounded-xl bg-rose-50 flex items-center justify-center">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          </div>
          <h3 class="font-bold text-lg text-slate-800">سبب رفض إيصال التحويل</h3>
        </div>
        <p class="text-xs text-slate-500">سيصل هذا التوضيح للطالب عبر لوحة تحكمه لإعادة المحاولة.</p>

        <form onsubmit="submitRejectReceipt(event)" class="flex flex-col gap-4">
          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1.5">سبب الرفض:</label>
            <select id="reject-reason-input" class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] bg-white text-xs font-bold text-slate-700 outline-none focus:border-rose-500">
              <option value="صورة الإيصال غير واضحة أو مقطوعة">صورة الإيصال غير واضحة أو مقطوعة</option>
              <option value="المبلغ المحول أقل من سعر الكورس المحدد">المبلغ المحول أقل من سعر الكورس المحدد</option>
              <option value="رقم العملية غير موجود في سجل الحساب">رقم العملية غير موجود في سجل الحساب</option>
              <option value="بيانات المحول غير متطابقة">بيانات المحول غير متطابقة</option>
            </select>
          </div>
          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal('modal-reject-receipt')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إلغاء</button>
            <button type="submit" class="px-5 h-10 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs">تأكيد الرفض</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Module Script -->
    <script type="module">
      import { filterTableRows, filterByPill } from '/assets/js/admin.js';
      filterTableRows('receipts-search-input', 'receipts-table-body');
      filterByPill('.receipt-filter-pill', 'data-status', 'receipts-table-body');
    </script>
    <script type="module" src="/assets/js/admin.js"></script>
      <script type="module" src="./js/animations.js"></script>
  </body>
</html>


