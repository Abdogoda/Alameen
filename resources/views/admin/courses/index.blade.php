<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة الأمين | إدارة المستويات والدروس والتسعير</title>
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
          <!-- Platform Brand -->
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

          <!-- Navigation Links Group -->
          <nav class="flex flex-col gap-1.5">
            <p class="text-[11px] font-bold text-slate-400 px-3 uppercase tracking-wider mb-1">المنهج والتعليم</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
              <span class="flex-1 text-start">لوحة الإحصائيات</span>
            </a>

            <!-- المنهج والمستويات الديناميكية (ACTIVE) -->
            <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#012383] text-white font-bold text-sm shadow-xs transition-colors">
              <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              <span class="flex-1 text-start">المستويات والفيديوهات</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-white/20 text-white rounded-md">ديناميك</span>
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

        <div class="border-t border-[#DEE8F7] pt-4 flex flex-col gap-2">
          <a href="../pages/course-details.html" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#EAF1FB] text-[#012383] text-xs font-bold transition-colors">
            <span>معاينة صفحة الكورس</span>
            <span class="text-[10px] text-slate-400">فتح ↗</span>
          </a>
        </div>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN CURRICULUM WORKSPACE                   -->
      <!-- ============================================== -->
      <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        
        <!-- Header Bar -->
        <header class="h-20 bg-white border-b border-[#DEE8F7] px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
          <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer">
              <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
              <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] leading-tight">إدارة محتوى الكورس والمستويات</h1>
              <p class="text-xs text-slate-500">تحكم كامل في المستويات، رفع الفيديوهات، ربط المهام، وتعديل السعر ديناميكياً</p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <!-- Add Level Primary Button -->
            <button type="button" onclick="openModal('modal-add-level')" class="h-11 px-5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-all flex items-center gap-2 cursor-pointer">
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
              <span>إضافة مستوى جديد</span>
            </button>
          </div>
        </header>

        <!-- Content Body -->
        <div class="p-4 sm:p-8 flex flex-col gap-6 max-w-[1400px] w-full mx-auto">
          
          <!-- Course Card Info Bar with Dynamic Pricing Controller -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-7 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center gap-4">
              <div class="size-16 sm:size-20 rounded-2xl bg-[#012383] overflow-hidden shrink-0 border border-[#DEE8F7]">
                <img src="/assets/images/course-topic-basics.png" alt="كورس التخاطب" class="size-full object-cover" />
              </div>
              <div class="space-y-1 text-right">
                <div class="flex items-center gap-2 flex-wrap">
                  <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-[#0B2A8F] text-xs font-bold font-['Cairo']">الكورس الأساسي النشط</span>
                  <span class="text-xs text-slate-400">•</span>
                  <span class="text-xs text-slate-600 font-medium">٤ مستويات معتمدة</span>
                  <span class="text-xs text-slate-400">•</span>
                  <span class="text-xs text-slate-600 font-medium">٢٠ فيديو و٢٠ مهمة</span>
                </div>
                <h2 class="font-['Cairo'] font-extrabold text-xl sm:text-2xl text-[#012383]">
                  دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة
                </h2>
                <p class="text-xs text-slate-500 max-w-xl">
                  البرنامج التدريبي المتكامل لتأهيل أخصائيي التخاطب وأولياء الأمور مع نظام المهام بعد كل درس وشهادة إتمام عند ٩٥٪.
                </p>
              </div>
            </div>

            <!-- Dynamic Price Badge & Editor Button -->
            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-[#DEE8F7]/80 w-full lg:w-auto justify-between lg:justify-start">
              <div class="text-right">
                <span class="text-[11px] text-slate-400 font-bold block">سعر الكورس المعتمد</span>
                <div class="flex items-baseline gap-2 mt-0.5">
                  <span class="text-2xl font-black text-[#012383] dynamic-course-price">١,٢٠٠ ج.م</span>
                  <span class="text-xs text-slate-400 line-through dynamic-course-old-price">١,٨٠٠ ج.م</span>
                </div>
              </div>
              <button type="button" onclick="openModal('modal-edit-price')" class="h-10 px-4 rounded-xl bg-white border border-[#DEE8F7] hover:bg-[#EAF1FB] text-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs cursor-pointer flex items-center gap-1.5 shrink-0">
                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>تعديل السعر</span>
              </button>
            </div>
          </div>

          <!-- Dynamic Levels Curriculum Container -->
          <div class="flex flex-col gap-5">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-bold text-lg text-[#012383]">هيكل المستويات والدروس (Dynamic Levels Architecture)</h3>
                <p class="text-xs text-slate-500">لكل مستوى: قائمة بالفيديوهات المندرجة تحته مع المهمة السريرية الملحقة به</p>
              </div>
              <span class="text-xs font-bold text-slate-500 bg-white border border-[#DEE8F7] px-3 py-1 rounded-full">
                إجمالي ٤ مستويات
              </span>
            </div>

            <!-- Levels Container -->
            <div id="levels-curriculum-container" class="space-y-4">
              
              <!-- Level 1 -->
              <div id="level-card-1" class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-5 bg-gradient-to-r from-slate-50 via-white to-white flex items-center justify-between border-b border-[#DEE8F7]/60">
                  <div class="flex items-center gap-3.5">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs">
                      ١
                    </span>
                    <div>
                      <div class="flex items-center gap-2">
                        <h4 class="font-bold text-base text-[#012383]">المستوى الأول: مدخل إلى علوم التخاطب والتواصل</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 mt-0.5">أساسيات عملية التواصل، التشريح الصوتي، وتصنيف اضطرابات النطق</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button type="button" onclick="openAddLessonModal('level-card-1', 'المستوى الأول: مدخل إلى علوم التخاطب')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                    <button type="button" onclick="showToast('تعديل المستوى', 'يمكنك تعديل اسم ووصف المستوى الأول', 'info')" class="p-2 text-slate-400 hover:text-[#012383] rounded-lg hover:bg-slate-100 transition-colors" title="تعديل">
                      <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                  </div>
                </div>

                <!-- Lessons List -->
                <div class="lessons-list p-4 sm:p-5 flex flex-col gap-2.5 bg-slate-50/30">
                  
                  <!-- Lesson 1 -->
                  <div class="p-3.5 bg-white border border-[#DEE8F7] rounded-2xl flex items-center justify-between gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
                    <div class="flex items-center gap-3">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] flex items-center justify-center shrink-0 text-[#0B2A8F]">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right">
                        <h5 class="font-bold text-sm text-[#012383]">الدرس ١: مدخل إلى عملية التواصل البشري</h5>
                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 font-medium">مهمة مرتبطة: مخطط أركان التواصل</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                      <button type="button" onclick="showToast('معاينة الفيديو', 'جاري فتح مشغل الفيديو في نافذة المعاينة', 'info')" class="px-2.5 py-1 text-xs font-bold text-[#0B2A8F] hover:bg-[#EAF1FB] rounded-lg">تشغيل ↗</button>
                    </div>
                  </div>

                  <!-- Lesson 2 -->
                  <div class="p-3.5 bg-white border border-[#DEE8F7] rounded-2xl flex items-center justify-between gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
                    <div class="flex items-center gap-3">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] flex items-center justify-center shrink-0 text-[#0B2A8F]">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right">
                        <h5 class="font-bold text-sm text-[#012383]">الدرس ٢: مكوّنات عملية التواصل وعناصر الرسالة</h5>
                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 font-medium">مهمة مرتبطة: تحليل تشويش الرسالة السريرية</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                      <button type="button" onclick="showToast('معاينة الفيديو', 'جاري فتح مشغل الفيديو في نافذة المعاينة', 'info')" class="px-2.5 py-1 text-xs font-bold text-[#0B2A8F] hover:bg-[#EAF1FB] rounded-lg">تشغيل ↗</button>
                    </div>
                  </div>

                  <!-- Lesson 3 -->
                  <div class="p-3.5 bg-white border border-[#DEE8F7] rounded-2xl flex items-center justify-between gap-3 shadow-2xs hover:border-[#0B2A8F]/40 transition-colors">
                    <div class="flex items-center gap-3">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] flex items-center justify-center shrink-0 text-[#0B2A8F]">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right">
                        <h5 class="font-bold text-sm text-[#012383]">الدرس ٣: مراحل النمو اللغوي عند الأطفال</h5>
                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 font-medium">مهمة مرتبطة: جدول مقارنة المعالم اللغوية</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                      <button type="button" onclick="showToast('معاينة الفيديو', 'جاري فتح مشغل الفيديو في نافذة المعاينة', 'info')" class="px-2.5 py-1 text-xs font-bold text-[#0B2A8F] hover:bg-[#EAF1FB] rounded-lg">تشغيل ↗</button>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Level 2 -->
              <div id="level-card-2" class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-5 bg-gradient-to-r from-slate-50 via-white to-white flex items-center justify-between border-b border-[#DEE8F7]/60">
                  <div class="flex items-center gap-3.5">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs">
                      ٢
                    </span>
                    <div>
                      <div class="flex items-center gap-2">
                        <h4 class="font-bold text-base text-[#012383]">المستوى الثاني: مهارات الصوت والاستماع والتمييز</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 mt-0.5">الوعي الصوتي، التمييز السمعي للأصوات، وبناء الأنشطة المنزلية</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button type="button" onclick="openAddLessonModal('level-card-2', 'المستوى الثاني: مهارات الصوت والاستماع')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                  </div>
                </div>

                <div class="lessons-list p-4 sm:p-5 flex flex-col gap-2.5 bg-slate-50/30">
                  <div class="p-3.5 bg-white border border-[#DEE8F7] rounded-2xl flex items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-center gap-3">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] flex items-center justify-center shrink-0 text-[#0B2A8F]">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right">
                        <h5 class="font-bold text-sm text-[#012383]">الدرس ٨: مهارات التمييز السمعي للأصوات المتشابهة</h5>
                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 font-medium">مهمة مرتبطة: ورقة نشاط التمييز الصوتي (٦ أزواج)</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="p-3.5 bg-white border border-[#DEE8F7] rounded-2xl flex items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-center gap-3">
                      <div class="size-8 rounded-lg bg-[#EAF1FB] flex items-center justify-center shrink-0 text-[#0B2A8F]">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                      </div>
                      <div class="text-right">
                        <h5 class="font-bold text-sm text-[#012383]">الدرس ٩: تدريبات الوعي الصوتي والتقطيع</h5>
                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                          <span>٢٠ دقيقة</span>
                          <span>•</span>
                          <span class="text-purple-700 font-medium">مهمة مرتبطة: استراتيجية التقطيع الصوتي للكلمات</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Level 3 -->
              <div id="level-card-3" class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-5 bg-gradient-to-r from-slate-50 via-white to-white flex items-center justify-between border-b border-[#DEE8F7]/60">
                  <div class="flex items-center gap-3.5">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs">
                      ٣
                    </span>
                    <div>
                      <div class="flex items-center gap-2">
                        <h4 class="font-bold text-base text-[#012383]">المستوى الثالث: مخارج الحروف وتصحيح اللدغات والتشويه</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 mt-0.5">تصحيح نطق الراء والسين والكاف والتمارين العضلية المصاحبة</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button type="button" onclick="openAddLessonModal('level-card-3', 'المستوى الثالث: مخارج الحروف وتصحيح اللدغات')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Level 4 -->
              <div id="level-card-4" class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs transition-all">
                <div class="p-5 bg-gradient-to-r from-slate-50 via-white to-white flex items-center justify-between border-b border-[#DEE8F7]/60">
                  <div class="flex items-center gap-3.5">
                    <span class="size-9 rounded-xl bg-[#012383] text-white font-bold text-sm flex items-center justify-center font-['Cairo'] shadow-2xs">
                      ٤
                    </span>
                    <div>
                      <div class="flex items-center gap-2">
                        <h4 class="font-bold text-base text-[#012383]">المستوى الرابع: التطبيقات السريرية ودراسة الحالات والتقييم النهائي</h4>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[11px] font-bold">٥ فيديوهات</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[11px] font-bold">٥ مهام</span>
                      </div>
                      <p class="text-xs text-slate-500 mt-0.5">دراسة حالات واقعية، كتابة الخطة العلاجية الفردية، وتأهيل شهادة الـ ٩٥٪</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button type="button" onclick="openAddLessonModal('level-card-4', 'المستوى الرابع: التطبيقات السريرية')" class="px-3.5 py-1.5 rounded-xl bg-[#0B2A8F] hover:bg-[#012383] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                      <span>إضافة درس</span>
                    </button>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </main>

    </div>

    <!-- Modal: Add New Lesson / Video -->
    <div id="modal-add-lesson" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7]">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">إضافة فيديو ودرس جديد</h3>
            <p class="text-xs text-slate-500 mt-0.5">المستوى المستهدف: <span id="add-lesson-level-label" class="font-bold text-[#0B2A8F]">المستوى الأول</span></p>
          </div>
          <button type="button" onclick="closeModal('modal-add-lesson')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form onsubmit="submitAddLesson(event)" class="flex flex-col gap-4">
          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">عنوان الفيديو / الدرس:</label>
            <input type="text" id="new-lesson-title" required placeholder="مثال: الدرس ٦: تمارين تقوية عضلات النطق" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383] outline-none focus:border-[#0B2A8F]" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-xs font-bold text-slate-700 block mb-1">مدة الفيديو (بالدقائق):</label>
              <input type="number" id="new-lesson-duration" required min="1" max="300" value="20" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-slate-700 outline-none focus:border-[#0B2A8F]" />
            </div>
            <div>
              <label class="text-xs font-bold text-slate-700 block mb-1">ترتيب العرض:</label>
              <input type="number" min="1" value="6" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-slate-700 outline-none focus:border-[#0B2A8F]" />
            </div>
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">رابط الفيديو أو معرّف البث (Vimeo / Bunny / HLS):</label>
            <input type="text" required placeholder="https://player.vimeo.com/video/..." class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-mono text-slate-700 outline-none focus:border-[#0B2A8F]" />
          </div>

          <div class="border-t border-[#DEE8F7] pt-3">
            <label class="text-xs font-bold text-[#012383] block mb-1">المهمة السريرية الملحقة بالفيديو (تفتح بعد إتمام المشاهدة):</label>
            <input type="text" id="new-lesson-task-title" required placeholder="عنوان المهمة (مثال: إعداد خطة تمارين عملية)" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-bold text-slate-700 outline-none focus:border-[#0B2A8F]" />
          </div>

          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal('modal-add-lesson')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إلغاء</button>
            <button type="submit" class="px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">حفظ وإضافة الدرس</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal: Add New Level -->
    <div id="modal-add-level" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7]">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div class="flex items-center gap-2">
            <div class="size-8 rounded-lg bg-blue-50 text-[#0B2A8F] flex items-center justify-center font-bold">
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">إضافة مستوى ديناميكي جديد</h3>
          </div>
          <button type="button" onclick="closeModal('modal-add-level')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form onsubmit="submitAddLevel(event)" class="flex flex-col gap-4">
          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">عنوان المستوى الجديد:</label>
            <input type="text" id="new-level-title" required placeholder="مثال: المستوى الخامس: استراتيجيات التدخل المبكر" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383] outline-none focus:border-[#0B2A8F]" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">وصف موجز للمستوى:</label>
            <textarea id="new-level-desc" rows="2" placeholder="أهداف المستوى وعدد الفيديوهات والمهام..." class="w-full p-3.5 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-700 outline-none focus:border-[#0B2A8F] resize-none"></textarea>
          </div>

          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal('modal-add-level')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إلغاء</button>
            <button type="submit" class="px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">إنشاء المستوى</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal: Edit Price -->
    <div id="modal-edit-price" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7]">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div class="flex items-center gap-2">
            <div class="size-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
              <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">تعديل سعر كورس التخاطب</h3>
          </div>
          <button type="button" onclick="closeModal('modal-edit-price')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form onsubmit="handleUpdatePrice(event)" class="flex flex-col gap-4">
          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">السعر الأصلي (قبل الخصم):</label>
            <input type="number" name="originalPrice" required value="1800" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-slate-600 outline-none focus:border-[#0B2A8F]" />
          </div>

          <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">السعر الفعلي المطلوب دفعه:</label>
            <input type="number" name="currentPrice" required value="1200" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383] outline-none focus:border-[#0B2A8F]" />
          </div>

          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeModal('modal-edit-price')" class="px-4 h-10 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إلغاء</button>
            <button type="submit" class="px-6 h-10 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm">حفظ ونشر السعر</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Scripts -->
    <script type="module" src="/assets/js/admin.js"></script>
      <script type="module" src="./js/animations.js"></script>
  </body>
</html>


