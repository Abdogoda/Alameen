<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة الأمين | دليل الطلاب والتحكم بالصلاحيات</title>
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
            <p class="text-[11px] font-bold text-slate-400 px-3 uppercase tracking-wider mb-1">الطلاب والمستخدمون</p>
            
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

            <a href="{{ route('admin.tasks.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              <span class="flex-1 text-start">تصحيح المهام</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-blue-100 text-[#0B2A8F] rounded-full">٢٨ مهمة</span>
            </a>

            <!-- الطلاب والاشتراكات (ACTIVE) -->
            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#012383] text-white font-bold text-sm shadow-xs transition-colors">
              <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
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
          <p class="text-[11px] text-slate-400 text-center font-medium">التحكم بالوصول • مفتاح الآمان</p>
        </div>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN STUDENTS ROSTER WORKSPACE              -->
      <!-- ============================================== -->
      <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        
        <!-- Header Bar -->
        <header class="h-20 bg-white border-b border-[#DEE8F7] px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
          <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer">
              <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
              <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] leading-tight">دليل الطلاب والتحكم بصلاحيات الوصول</h1>
              <p class="text-xs text-slate-500">إدارة حسابات الدارسين، تتبع ساعات المشاهدة، ومنح أو إلغاء صلاحية الوصول لكورس التخاطب</p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-[#012383] bg-[#EAF1FB] border border-[#DEE8F7] px-3 py-1.5 rounded-xl">
              إجمالي ٣٤٠ طالب مسجل
            </span>
          </div>
        </header>

        <!-- Body Content -->
        <div class="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto">
          
          <!-- Filters & Live Search Toolbar -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-4 sm:p-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
              <button type="button" data-filter="all" class="student-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-[#012383] text-white">
                جميع الطلاب (٣٤٠)
              </button>
              <button type="button" data-filter="active" class="student-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                نشط ومفعل (٣٢٢)
              </button>
              <button type="button" data-filter="revoked" class="student-filter-pill px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-100 hover:bg-[#EAF1FB] text-slate-700">
                ملغي الصلاحية (١٨)
              </button>
            </div>

            <div class="relative w-full sm:w-80">
              <input type="text" id="students-search-input" placeholder="ابحث بالاسم، البريد أو رقم الهاتف..." class="w-full h-11 ps-4 pe-10 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] bg-slate-50/50" />
              <svg class="size-4 text-slate-400 absolute end-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

          </div>

          <!-- Students Roster Table -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
              <table class="w-full text-right border-collapse">
                <thead>
                  <tr class="bg-slate-50/80 border-b border-[#DEE8F7] text-[12px] font-bold text-slate-500">
                    <th class="py-4 px-5">الطالب وبيانات القيد</th>
                    <th class="py-4 px-4">تاريخ التسجيل</th>
                    <th class="py-4 px-4">الساعات المكتملة</th>
                    <th class="py-4 px-4">نسبة مشاهدة الفيديوهات</th>
                    <th class="py-4 px-4 text-center">متوسط التقييم</th>
                    <th class="py-4 px-4 text-center">حالة الصلاحية</th>
                    <th class="py-4 px-5 text-center">التحكم بالوصول</th>
                  </tr>
                </thead>
                <tbody id="students-table-body" class="divide-y divide-[#DEE8F7]/70 text-xs">
                  
                  <!-- Student 1 -->
                  <tr id="student-row-1" data-status="active" data-student-name="أحمد إبراهيم الحارثي" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-[#EAF1FB] flex items-center justify-center font-bold text-sm text-[#0B2A8F] shrink-0">
                          أ
                        </div>
                        <div>
                          <div class="font-bold text-sm text-[#012383]">أحمد إبراهيم الحارثي</div>
                          <div class="text-[11px] text-slate-500 font-mono">01011223344 • ahmed.ibrahim@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">١٢ سبتمبر ٢٠٢٦</span>
                      <div class="text-[10px] text-slate-400">دفعة الخريف</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-[#012383]">٣.٤ ساعة</span>
                      <div class="text-[10px] text-slate-400">من إجمالي ٨ ساعات</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-[#0B2A8F]">٤٢٪</span>
                          <span class="text-slate-400">٨ من ٢٠ فيديو</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-gradient-to-r from-[#0B2A8F] to-[#012383] rounded-full w-[42%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-emerald-700">٨٧ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-active">
                        نشط ومفعل
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-1', 'أحمد إبراهيم الحارثي', 'active')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                        إلغاء الصلاحية
                      </button>
                    </td>
                  </tr>

                  <!-- Student 2 -->
                  <tr id="student-row-2" data-status="active" data-student-name="سارة خالد المنصور" class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-purple-50 flex items-center justify-center font-bold text-sm text-purple-700 shrink-0">
                          س
                        </div>
                        <div>
                          <div class="font-bold text-sm text-[#012383]">سارة خالد المنصور</div>
                          <div class="text-[11px] text-slate-500 font-mono">01023456789 • sara.mansour@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">٢٤ أكتوبر ٢٠٢٦</span>
                      <div class="text-[10px] text-emerald-600 font-bold">تسجيل جديد</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-[#012383]">٠.٦ ساعة</span>
                      <div class="text-[10px] text-slate-400">في البداية</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-[#0B2A8F]">١٠٪</span>
                          <span class="text-slate-400">٢ من ٢٠ فيديو</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-gradient-to-r from-[#0B2A8F] to-[#012383] rounded-full w-[10%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="text-slate-400 font-bold">قيد التقييم</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-active">
                        نشط ومفعل
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-2', 'سارة خالد المنصور', 'active')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                        إلغاء الصلاحية
                      </button>
                    </td>
                  </tr>

                  <!-- Student 3 (Achieved 95% Completion) -->
                  <tr id="student-row-3" data-status="active" data-student-name="مروان خالد العتيبي" class="hover:bg-slate-50/60 transition-colors bg-emerald-50/20">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-sm text-emerald-800 shrink-0">
                          م
                        </div>
                        <div>
                          <div class="font-bold text-sm text-[#012383] flex items-center gap-1.5">
                            <span>مروان خالد العتيبي</span>
                            <span class="text-[10px] text-emerald-700 font-bold bg-emerald-100 px-1.5 py-0.2 rounded">مؤهل للشهادة ✦</span>
                          </div>
                          <div class="text-[11px] text-slate-500 font-mono">marwan.otaibi@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">٥ أغسطس ٢٠٢٦</span>
                      <div class="text-[10px] text-slate-400">دفعة الصيف</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-emerald-700">٧.٦ ساعة</span>
                      <div class="text-[10px] text-emerald-600 font-bold">أتم الكورس</div>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-emerald-600">٩٥٪</span>
                          <span class="text-slate-400">١٩ من ٢٠ فيديو</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-emerald-500 rounded-full w-[95%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-emerald-700">٩٤ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-active">
                        نشط ومفعل
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-3', 'مروان خالد العتيبي', 'active')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                        إلغاء الصلاحية
                      </button>
                    </td>
                  </tr>

                  <!-- Student 4 (Revoked Access) -->
                  <tr id="student-row-4" data-status="revoked" data-student-name="طارق يحيى السعيد" class="hover:bg-slate-50/60 transition-colors opacity-75">
                    <td class="py-4 px-5">
                      <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-sm text-slate-600 shrink-0">
                          ط
                        </div>
                        <div>
                          <div class="font-bold text-sm text-slate-700">طارق يحيى السعيد</div>
                          <div class="text-[11px] text-slate-400 font-mono">tariq.saeed@gmail.com</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-slate-700">١ يوليو ٢٠٢٦</span>
                      <div class="text-[10px] text-rose-600 font-bold">صلاحية مسحوبة</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-bold text-sm text-slate-500">١.٢ ساعة</span>
                    </td>
                    <td class="py-4 px-4">
                      <div class="w-36 flex flex-col gap-1">
                        <div class="flex items-center justify-between text-[11px]">
                          <span class="font-bold text-slate-500">١٥٪</span>
                          <span class="text-slate-400">متوقف</span>
                        </div>
                        <div class="h-2 w-full bg-[#DEE8F7] rounded-full overflow-hidden">
                          <div class="h-full bg-slate-400 rounded-full w-[15%]"></div>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-slate-500">٧٠ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="student-access-badge px-3 py-1 rounded-full text-xs font-bold badge-revoked">
                        ملغي الصلاحية
                      </span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <button type="button" onclick="toggleStudentAccess('student-row-4', 'طارق يحيى السعيد', 'revoked')" class="student-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors cursor-pointer">
                        منح الصلاحية
                      </button>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>

            <div class="p-4 bg-slate-50/70 border-t border-[#DEE8F7] flex items-center justify-between text-xs text-slate-500">
              <span>عرض ٤ من إجمالي ٣٤٠ طالب</span>
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

    <!-- Modal: Toggle Student Access -->
    <div id="modal-toggle-access" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7]">
        <div class="flex items-center gap-2.5 text-[#012383]">
          <div class="size-9 rounded-xl bg-blue-50 flex items-center justify-center">
            <svg class="size-5 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
          </div>
          <h3 id="toggle-access-title" class="font-bold text-lg text-[#012383]">تعديل صلاحية الوصول للطالب</h3>
        </div>
        <p id="toggle-access-desc" class="text-xs text-slate-500 leading-relaxed">
          هل أنت متأكد من تغيير صلاحية الوصول لهذا الطالب؟
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" onclick="closeModal('modal-toggle-access')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs cursor-pointer">إلغاء</button>
          <button type="button" id="toggle-access-confirm-btn" onclick="confirmToggleAccess()" class="px-5 h-10 rounded-xl bg-[#012383] text-white font-bold text-xs shadow-sm cursor-pointer">تأكيد الإجراء</button>
        </div>
      </div>
    </div>

    <!-- Script modules -->
    <script type="module">
      import { filterTableRows, filterByPill } from '/assets/js/admin.js';
      filterTableRows('students-search-input', 'students-table-body');
      filterByPill('.student-filter-pill', 'data-status', 'students-table-body');
    </script>
    <script type="module" src="/assets/js/admin.js"></script>
      <script type="module" src="./js/animations.js"></script>
  </body>
</html>


