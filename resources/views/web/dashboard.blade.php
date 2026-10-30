<x-layouts::app :title="__('بوابة الطالب الأكاديمية')">

    <!-- ============================================== -->
    <!-- STUDENT WORKSPACE (بوابة الطالب الأكاديمية)     -->
    <!-- ============================================== -->
    <div class="flex flex-col gap-6 w-full text-start">
        
        @if(auth('admin')->check())
            <div class="bg-gradient-to-r from-[#012383] to-[#0B2A8F] text-white p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm border border-blue-900">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 bg-[#F2AE2E] text-slate-900 font-extrabold text-xs rounded-lg">{{ __('وضع المسؤول') }}</span>
                    <span class="text-xs sm:text-sm font-semibold text-white/90">{{ __('أنت مسجّل كمسؤول في النظام.') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('log-viewer.index') }}" target="_blank" class="px-3.5 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-bold text-white transition-colors flex items-center gap-1.5 border border-white/20">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ __('سجلات النظام (Log Viewer)') }} ↗</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 bg-white text-[#012383] hover:bg-slate-50 rounded-xl text-xs font-bold transition-colors">
                        {{ __('لوحة الإدارة') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Page Introduction -->
        <div class="flex flex-col gap-1 items-start text-start w-full">
            <p class="font-semibold text-xs text-[#0B2A8F]">
                {{ __('مساحة التعلّم / لوحة الطالب') }}
            </p>
            <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383]">
                {{ __('أهلًا بعودتك، :name', ['name' => auth()->user()->name]) }}
            </h1>
            <p class="text-xs sm:text-sm text-[#475569]">
                {{ __('كورساتك ومهامك في مكان واحد. تابع من حيث توقّفت، بخطوة واضحة نحو الإتقان السريري.') }}
            </p>
        </div>

        <!-- Resume Learning Split-Card -->
        <div class="bg-white border border-[#DEE8F7] rounded-2xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-all flex flex-col lg:flex-row items-stretch gap-6 w-full relative overflow-hidden group">
            
            <!-- Video Preview Box -->
            <div class="w-full lg:w-[380px] xl:w-[420px] h-[200px] sm:h-[220px] lg:h-auto rounded-xl overflow-hidden relative shrink-0 shadow-sm bg-slate-100">
                <img src="/assets/images/video-preview.png" alt="تدريبات الوعي الصوتي" class="size-full object-cover pointer-events-none group-hover:scale-105 transition-transform duration-300" />
                
                <!-- Center Play Button -->
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 size-14 rounded-full bg-white/95 border border-white shadow-lg flex items-center justify-center cursor-pointer z-10 group-hover:scale-110 transition-transform">
                    <svg class="size-6 text-[#012383] translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                </div>

                <!-- Video Meta Duration -->
                <div class="absolute top-3 end-3 bg-[#012383]/85 backdrop-blur-md border border-white/20 rounded-full px-3 py-1 flex items-center gap-1.5 text-xs text-white z-10 shadow-sm font-bold">
                    <span>٢٠:٠٠ دقيقة</span>
                </div>
            </div>

            <!-- Content Column -->
            <div class="flex-1 flex flex-col justify-between gap-4 text-start min-w-0">
                
                <div class="flex flex-col gap-2 items-start text-start w-full">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2.5 py-0.5 rounded-full bg-[#EAF1FB] text-[#0B2A8F] text-xs font-bold">
                            {{ __('واصل تعلّمك • آخر موضع محفوظ') }}
                        </span>
                        <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs text-slate-500 font-medium">{{ __('قيد التقدم') }}</span>
                    </div>

                    <h3 class="font-bold text-xl sm:text-2xl text-[#012383] text-start w-full mt-1">
                        {{ __('تدريبات الوعي الصوتي ونطق الحروف') }}
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-500 text-start w-full">
                        {{ __('أساسيات التخاطب  /  المستوى الثاني  /  الدرس ٩') }}
                    </p>
                </div>

                <!-- Progress Track -->
                <div class="flex flex-col gap-2 w-full mt-auto pt-2">
                    <div class="flex items-center justify-between text-xs w-full">
                        <span class="font-bold text-[#0B2A8F]">{{ __('٠٨:٠٠ مكتمل') }}</span>
                        <span class="font-semibold text-slate-500">{{ __('٤٠٪ من الدرس') }}</span>
                    </div>

                    <div class="bg-[#DEE8F7] h-2 rounded-full w-full overflow-hidden relative">
                        <div class="bg-gradient-to-r from-[#0B2A8F] to-[#012383] h-full rounded-full w-[40%]"></div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" class="bg-[#0B2A8F] hover:bg-[#012383] text-white h-11 px-6 rounded-xl flex items-center justify-center gap-2 shadow-sm hover:shadow transition-all font-bold text-xs cursor-pointer">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        <span>{{ __('أكمل المشاهدة الآن') }}</span>
                    </button>
                </div>

            </div>

        </div>

        <!-- Enrolled Courses Grid -->
        <div class="flex flex-col gap-4 w-full">
            <div class="flex items-center justify-between w-full">
                <h2 class="font-bold text-lg sm:text-xl text-[#012383]">
                    {{ __('كورساتي المسجّلة في البرنامج') }}
                </h2>
                <span class="text-xs font-semibold text-slate-500 bg-white border border-[#DEE8F7] px-3 py-1 rounded-full shadow-xs">
                    {{ __('٣ كورسات مسجّلة') }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 w-full">
                
                <!-- Course 1 -->
                <div class="bg-white border border-[#DEE8F7] rounded-2xl p-4 flex flex-col justify-between gap-3 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all">
                    <div class="h-32 rounded-xl overflow-hidden relative w-full bg-slate-100 shrink-0">
                        <img src="/assets/images/course-topic-basics.png" alt="أساسيات التخاطب" class="size-full object-cover pointer-events-none rounded-xl" />
                        <span class="absolute top-2.5 end-2.5 px-2 py-0.5 rounded-full bg-white/90 backdrop-blur-xs text-[11px] font-bold text-[#0B2A8F] shadow-xs">
                            {{ __('قيد المشاهدة') }}
                        </span>
                    </div>
                    
                    <div class="flex flex-col gap-1 text-start w-full">
                        <h4 class="font-bold text-base text-[#012383]">
                            {{ __('أساسيات وتطبيقات التخاطب') }}
                        </h4>
                        <p class="text-xs text-slate-500">
                            {{ __('٨ فيديوهات مكتملة • درس قيد المشاهدة') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1.5 w-full mt-auto pt-2">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>{{ __('تقدّم المشاهدة') }}</span>
                            <span class="font-bold text-[#0B2A8F]">٤٢٪</span>
                        </div>
                        <div class="bg-[#DEE8F7] h-1.5 rounded-full overflow-hidden w-full">
                            <div class="bg-[#0B2A8F] h-full rounded-full w-[42%]"></div>
                        </div>
                    </div>
                </div>

                <!-- Course 2 -->
                <div class="bg-white border border-[#DEE8F7] rounded-2xl p-4 flex flex-col justify-between gap-3 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all">
                    <div class="h-32 rounded-xl overflow-hidden relative w-full bg-slate-100 shrink-0">
                        <img src="/assets/images/course-topic-articulation.png" alt="تصحيح مخارج الحروف" class="size-full object-cover pointer-events-none rounded-xl" />
                        <span class="absolute top-2.5 end-2.5 px-2 py-0.5 rounded-full bg-white/90 backdrop-blur-xs text-[11px] font-bold text-emerald-700 shadow-xs">
                            {{ __('مستوى متقدم') }}
                        </span>
                    </div>
                    
                    <div class="flex flex-col gap-1 text-start w-full">
                        <h4 class="font-bold text-base text-[#012383]">
                            {{ __('تصحيح اللدغات وعيوب النطق') }}
                        </h4>
                        <p class="text-xs text-slate-500">
                            {{ __('١٢ تدريباً سريرياً معتمداً') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1.5 w-full mt-auto pt-2">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>{{ __('تقدّم المشاهدة') }}</span>
                            <span class="font-bold text-[#0B2A8F]">٧٠٪</span>
                        </div>
                        <div class="bg-[#DEE8F7] h-1.5 rounded-full overflow-hidden w-full">
                            <div class="bg-emerald-600 h-full rounded-full w-[70%]"></div>
                        </div>
                    </div>
                </div>

                <!-- Course 3 -->
                <div class="bg-white border border-[#DEE8F7] rounded-2xl p-4 flex flex-col justify-between gap-3 shadow-xs hover:shadow-card hover:-translate-y-0.5 transition-all">
                    <div class="h-32 rounded-xl overflow-hidden relative w-full bg-slate-100 shrink-0">
                        <img src="/assets/images/course-topic-strategies.png" alt="استراتيجيات التلعثم" class="size-full object-cover pointer-events-none rounded-xl" />
                        <span class="absolute top-2.5 end-2.5 px-2 py-0.5 rounded-full bg-white/90 backdrop-blur-xs text-[11px] font-bold text-amber-700 shadow-xs">
                            {{ __('المستوى القادم') }}
                        </span>
                    </div>
                    
                    <div class="flex flex-col gap-1 text-start w-full">
                        <h4 class="font-bold text-base text-[#012383]">
                            {{ __('استراتيجيات علاج التلعثم والطلاقة') }}
                        </h4>
                        <p class="text-xs text-slate-500">
                            {{ __('يتطلب اجتياز المستوى الثاني بنسبة ٩٥٪') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1.5 w-full mt-auto pt-2">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>{{ __('تقدّم المشاهدة') }}</span>
                            <span class="font-bold text-slate-400">٠٪</span>
                        </div>
                        <div class="bg-[#DEE8F7] h-1.5 rounded-full overflow-hidden w-full">
                            <div class="bg-slate-300 h-full rounded-full w-[0%]"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</x-layouts::app>
