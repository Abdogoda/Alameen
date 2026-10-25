<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة الأمين | إعدادات المنصة وطرق الدفع اليدوي</title>
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
            <p class="text-[11px] font-bold text-slate-400 px-3 uppercase tracking-wider mb-1">الإدارة والتكوين</p>
            
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

            <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-[#EAF1FB]/60 hover:text-[#012383] font-semibold text-sm transition-colors">
              <svg class="size-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <span class="flex-1 text-start">الشهادات المعتمدة</span>
            </a>

            <!-- الإعدادات (ACTIVE) -->
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#012383] text-white font-bold text-sm shadow-xs transition-colors">
              <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span class="flex-1 text-start">إعدادات المنصة والدفع</span>
            </a>
          </nav>
        </div>

        <div class="border-t border-[#DEE8F7] pt-4">
          <p class="text-[11px] text-slate-400 text-center font-medium">إصدار النظام v2.4.0</p>
        </div>

      </aside>

      <!-- ============================================== -->
      <!-- 2. MAIN SETTINGS WORKSPACE                     -->
      <!-- ============================================== -->
      <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        
        <header class="h-20 bg-white border-b border-[#DEE8F7] px-4 sm:px-8 flex items-center justify-between gap-4 sticky top-0 z-30">
          <div class="flex items-center gap-3">
            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer">
              <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
              <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl sm:text-2xl text-[#012383] leading-tight">إعدادات قنوات الدفع اليدوي والمنصة</h1>
              <p class="text-xs text-slate-500">تحديد أرقام المحافظ البنكية، شروط إصدار الشهادات، وتفاصيل إدارة النظام</p>
            </div>
          </div>
        </header>

        <div class="p-4 sm:p-8 flex flex-col gap-6 max-w-[1200px] w-full mx-auto">
          
          <!-- Section 1: Manual Payment Channels -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-6">
            <div class="border-b border-[#DEE8F7] pb-4">
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">بيانات استلام التحويلات اليدوية (تظهر للطلاب عند التسجيل)</h3>
              <p class="text-xs text-slate-500">سيقوم الطالب بتحويل المبلغ المطلوب إلى هذه الحسابات ثم رفع صورة الإيصال</p>
            </div>

            <form onsubmit="event.preventDefault(); showToast('تم حفظ الإعدادات', 'تم تحديث بيانات قنوات الدفع بنجاح.');" class="grid grid-cols-1 md:grid-cols-2 gap-5">
              
              <!-- Vodafone Cash -->
              <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-rose-700 font-bold text-sm">
                  <span class="size-2.5 rounded-full bg-rose-600"></span>
                  <span>محفظة فودافون كاش (Vodafone Cash)</span>
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">رقم المحفظة المعتمد:</label>
                  <input type="text" value="01023456789" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-slate-800 bg-white" />
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">اسم صاحب المحفظة:</label>
                  <input type="text" value="مركز الأمين للتخاطب" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-800 bg-white" />
                </div>
              </div>

              <!-- InstaPay -->
              <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-purple-700 font-bold text-sm">
                  <span class="size-2.5 rounded-full bg-purple-600"></span>
                  <span>حساب إنستاباي (InstaPay Address)</span>
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">عنوان الدفع اللحظي (IPA):</label>
                  <input type="text" value="alameen@instapay" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold font-mono text-slate-800 bg-white" />
                </div>
                <div>
                  <label class="text-xs font-bold text-slate-600 block mb-1">اسم الحساب المسجل:</label>
                  <input type="text" value="Al-Ameen Speech Center" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-medium text-slate-800 bg-white" />
                </div>
              </div>

              <!-- Bank Account -->
              <div class="p-5 rounded-2xl bg-slate-50/70 border border-[#DEE8F7]/80 flex flex-col gap-3 md:col-span-2">
                <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
                  <span class="size-2.5 rounded-full bg-[#0B2A8F]"></span>
                  <span>التحويل البنكي المباشر (National Bank / CIB)</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">اسم البنك:</label>
                    <input type="text" value="البنك الأهلي المصري (NBE)" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-bold text-slate-800 bg-white" />
                  </div>
                  <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">رقم الحساب:</label>
                    <input type="text" value="1234567890123456" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-mono font-bold text-slate-800 bg-white" />
                  </div>
                  <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">رقم الآيبان (IBAN):</label>
                    <input type="text" value="EG380002000100000012345678901" class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-xs font-mono font-bold text-slate-800 bg-white" />
                  </div>
                </div>
              </div>

              <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-6 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer">
                  حفظ قنوات الدفع
                </button>
              </div>

            </form>
          </div>

          <!-- Section 2: Academic & Certificate Rules -->
          <div class="bg-white border border-[#DEE8F7] rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col gap-6">
            <div class="border-b border-[#DEE8F7] pb-4">
              <h3 class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-[#012383]">معايير إتمام الكورس والشهادات الرقمية</h3>
              <p class="text-xs text-slate-500">الضوابط التي يُصدر النظام بناءً عليها الشهادة أو يقفلها</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">نسبة المشاهدة الإلزامية لإصدار الشهادة:</label>
                <div class="flex items-center gap-2">
                  <input type="number" value="95" min="50" max="100" class="w-32 h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383]" />
                  <span class="text-sm font-bold text-slate-500">٪ من مجموع فيديوهات الكورس</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">الوضع الموصى به من العميل: ٩٥٪ (١٩ من ٢٠ فيديو).</p>
              </div>

              <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">الدرجة الصغرى لاجتياز المهام السريرية:</label>
                <div class="flex items-center gap-2">
                  <input type="number" value="60" min="50" max="100" class="w-32 h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm font-bold text-[#012383]" />
                  <span class="text-sm font-bold text-slate-500">من ١٠٠ كمتوسط تراكمي</span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </main>

    </div>

    <script type="module" src="/assets/js/admin.js"></script>
      <script type="module" src="./js/animations.js"></script>
  </body>
</html>


