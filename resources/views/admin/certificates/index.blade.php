<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة الأمين | متابعة وإصدار الشهادات ومعيار الـ 95%</title>
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
            <p class="text-[11px] font-bold text-slate-400 px-3 uppercase tracking-wider mb-1">الشهادات والاعتمادات</p>
            
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

            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              <span class="flex-1 text-start">الطلاب والاشتراكات</span>
            </a>

            <!-- الشهادات (ACTIVE) -->
            <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#012383] text-white font-bold text-sm shadow-xs transition-colors">
              <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <span class="flex-1 text-start">الشهادات المعتمدة</span>
              <span class="px-2 py-0.5 text-[11px] font-bold bg-emerald-500 text-white rounded-full">٩٥٪</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span class="flex-1 text-start">إعدادات المنصة والدفع</span>
            </a>
          </nav>
        </div>

        <div class="border-t border-[#DEE8F7] pt-4">
          <a href="../pages/certificates.html" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-[#EAF1FB] text-[#012383] text-xs font-bold transition-colors">
            <span>معاينة شهادات الطلاب</span>
            <span class="text-[10px] text-slate-400">فتح ↗</span>
          </a>
        </div>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN CERTIFICATES WORKSPACE                 -->
      <!-- ============================================== -->
      <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        
        <header class="h-20 bg-white border-b border-[#DEE8F7] px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
          <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer">
              <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
              <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] leading-tight">سجل الشهادات المعتمدة ومعيار الـ 95%</h1>
              <p class="text-xs text-slate-500">إدارة إصدار الشهادات الرقمية المؤتمتة وفق شرط إكمال مشاهدة ٩٥٪ من فيديوهات الكورس</p>
            </div>
          </div>
        </header>

        <div class="p-4 sm:p-8 flex flex-col gap-6 max-w-[1500px] w-full mx-auto">
          
          <!-- Strict 95% Rule Banner -->
          <div class="bg-gradient-to-r from-emerald-900 via-[#0B2A8F] to-[#012383] rounded-3xl p-6 sm:p-7 text-white shadow-card flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="space-y-1 text-right">
              <div class="flex items-center gap-2">
                <span class="px-3 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold">
                  قاعدة الأمان الأكاديمي الصارمة
                </span>
                <span class="text-xs text-white/70">• إكمال ٩٥٪ كحد أدنى</span>
              </div>
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-white">
                شرط إتمام المشاهدة الإلزامي (Strict 95% Video Completion Gate)
              </h3>
              <p class="text-xs sm:text-sm text-white/80 max-w-2xl leading-relaxed">
                المنصة مبرمجة برمجياً بحيث يُقفل رابط إصدار الشهادة ولا يستطيع أي طالب استخراجها إلا إذا بلغت نسبة مشاهدته الفعلية المسجلة في قاعدة البيانات ٩٥٪ فما فوق (١٩ من ٢٠ فيديو على الأقل).
              </p>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center shrink-0 w-full sm:w-auto">
              <span class="text-xs text-white/70 block">الشهادات المصدرة حتى الآن</span>
              <span class="text-3xl font-black text-white block mt-0.5">٢٤ شهادة</span>
              <span class="text-[11px] text-emerald-300 block">جميعها تحققت برمجياً ✦</span>
            </div>
          </div>

          <!-- Certificates Table -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl overflow-hidden shadow-xs">
            <div class="p-5 border-b border-[#DEE8F7] flex items-center justify-between">
              <div>
                <h4 class="font-bold text-base text-[#012383]">سجل الطلاب المؤهلين والحاصلين على الشهادة</h4>
                <p class="text-xs text-slate-400">تحقق من بيانات الاعتماد، كود التوثيق، وتاريخ التخرج</p>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-right border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-50/80 border-b border-[#DEE8F7] text-[12px] font-bold text-slate-500">
                    <th class="py-4 px-5">اسم الخريج / الطالب</th>
                    <th class="py-4 px-4">رقم الاعتماد التسلسلي</th>
                    <th class="py-4 px-4 text-center">نسبة المشاهدة الفعلية</th>
                    <th class="py-4 px-4 text-center">معدل المهام السريرية</th>
                    <th class="py-4 px-4">تاريخ التخرج</th>
                    <th class="py-4 px-5 text-center">إجراءات الشهادة</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-[#DEE8F7]/70">
                  
                  <!-- Student 1 -->
                  <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">مروان خالد العتيبي</div>
                      <div class="text-[11px] text-slate-400">marwan.otaibi@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-1 rounded-md">ALM-2026-SP-0489</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ٩٥٪ (١٩ فيديو)
                      </span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-[#012383]">٩٤ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">٢٠ أكتوبر ٢٠٢٦</span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="openModal('modal-preview-cert')" class="px-3 py-1.5 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs">
                          معاينة الشهادة
                        </button>
                        <button type="button" onclick="showToast('طباعة الشهادة', 'جاري إعداد نسخة PDF عالية الجودة للطباعة', 'info')" class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] text-[#0B2A8F] hover:bg-[#EAF1FB] font-bold text-xs transition-colors">
                          تنزيل PDF ⭳
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Student 2 -->
                  <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-5">
                      <div class="font-bold text-sm text-[#012383]">نورة سعد الغامدي</div>
                      <div class="text-[11px] text-slate-400">noura.ghamdi@gmail.com</div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="font-mono text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-1 rounded-md">ALM-2026-SP-0490</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ١٠٠٪ (٢٠ فيديو)
                      </span>
                    </td>
                    <td class="py-4 px-4 text-center">
                      <span class="font-bold text-sm text-[#012383]">٩٨ / ١٠٠</span>
                    </td>
                    <td class="py-4 px-4">
                      <span class="text-slate-600 font-medium">١٨ أكتوبر ٢٠٢٦</span>
                    </td>
                    <td class="py-4 px-5 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="openModal('modal-preview-cert')" class="px-3 py-1.5 rounded-xl bg-[#012383] text-white hover:bg-[#0B2A8F] font-bold text-xs transition-colors shadow-2xs">
                          معاينة الشهادة
                        </button>
                        <button type="button" onclick="showToast('طباعة الشهادة', 'جاري إعداد نسخة PDF عالية الجودة للطباعة', 'info')" class="px-3 py-1.5 rounded-xl border border-[#DEE8F7] text-[#0B2A8F] hover:bg-[#EAF1FB] font-bold text-xs transition-colors">
                          تنزيل PDF ⭳
                        </button>
                      </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>

        </div>

      </main>

    </div>

    <!-- Modal: Preview Certificate -->
    <div id="modal-preview-cert" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 modal-hidden">
      <div class="modal-content bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 flex flex-col gap-6 shadow-2xl border border-[#DEE8F7] max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">معاينة شهادة الإتمام المعتمدة</h3>
            <p class="text-xs text-slate-500">تم التحقق من معيار المشاهدة ٩٥٪ والكود الأكاديمي الموحد</p>
          </div>
          <button type="button" onclick="closeModal('modal-preview-cert')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Certificate Card View -->
        <div class="p-8 rounded-2xl bg-gradient-to-br from-white via-slate-50 to-[#EAF1FB]/40 border-2 border-[#DEE8F7] relative text-center flex flex-col items-center gap-4 shadow-sm">
          <div class="size-16 rounded-full bg-[#EAF1FB] p-2 flex items-center justify-center border border-[#DEE8F7]">
            <img src="/assets/images/logo.png" alt="منصة الأمين" class="size-full object-contain" />
          </div>
          
          <h2 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl text-[#012383]">شهادة إتمام برنامج تدريبي معتمد</h2>
          <p class="text-xs text-slate-500">تشهد منصة الأمين لتأهيل علوم التخاطب بأن الدارس / الدارسة:</p>
          
          <p class="font-['Baloo_Bhaijaan_2'] font-black text-2xl text-[#0B2A8F] border-b border-[#DEE8F7] pb-2 px-8">
            مروان خالد العتيبي
          </p>

          <p class="text-xs text-slate-600 max-w-md leading-relaxed">
            قد أكمل بنجاح متطلبات <strong>دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة</strong> بعد إتمام أكثر من ٩٥٪ من ساعات المشاهدة التدريبية واجتياز المهام السريرية بنسبة <strong>٩٤٪</strong>.
          </p>

          <div class="w-full flex items-center justify-between border-t border-[#DEE8F7] pt-4 mt-2 text-xs">
            <div class="text-right">
              <span class="text-slate-400 block text-[10px]">كود التحقق الرقمي:</span>
              <span class="font-mono font-bold text-[#012383]">ALM-2026-SP-0489</span>
            </div>
            <div class="text-center">
              <span class="text-slate-400 block text-[10px]">ختم الاعتماد:</span>
              <span class="text-emerald-700 font-bold">معتمد سريرياً ✦</span>
            </div>
            <div class="text-left">
              <span class="text-slate-400 block text-[10px]">تاريخ المنح:</span>
              <span class="font-bold text-slate-700">٢٠ أكتوبر ٢٠٢٦</span>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" onclick="closeModal('modal-preview-cert')" class="px-5 h-11 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs">إغلاق</button>
          <button type="button" onclick="window.print()" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-2 shadow-sm">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>طباعة وتنزيل PDF</span>
          </button>
        </div>
      </div>
    </div>

    <script type="module" src="/assets/js/admin.js"></script>
      <script type="module" src="./js/animations.js"></script>
  </body>
</html>


