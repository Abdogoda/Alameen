<x-admin-layout::admin
    :title="__('إضافة إعلان أو فيديو تعليمي جديد')"
    :description="__('تخصيص المحتوى، المنصات المستهدفة، الجمهور، ونمط الظهور (منبثق، إجباري، شريط علوي، أو مدمج)')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 w-full min-w-0"
>
    <x-slot:actions>
        <a
            href="{{ route('admin.announcements.index') }}"
            class="h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 flex items-center gap-1.5 transition-colors"
        >
            <span>←</span>
            <span>{{ __('العودة للقائمة') }}</span>
        </a>
    </x-slot:actions>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-sm font-semibold flex flex-col gap-2 shadow-xs">
            <span class="font-bold">{{ __('يرجى تصحيح الأخطاء التالية:') }}</span>
            <ul class="list-disc list-inside text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('admin.announcements.store') }}"
        enctype="multipart/form-data"
        x-data="{
            contentType: '{{ old('content_type', 'text') }}',
            videoType: '{{ old('video_type', 'url') }}',
            displayMode: '{{ old('display_mode', 'popup') }}',
            audience: '{{ old('target_audience', 'all') }}',
        }"
        class="flex flex-col gap-6"
    >
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left 2 Columns: Main Content & Media --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- 1. Content & Text Card --}}
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="size-9 rounded-xl bg-blue-50 dark:bg-blue-950 text-[#012383] dark:text-blue-400 flex items-center justify-center font-bold text-sm">1</div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('المحتوى والعناوين') }}</h3>
                            <p class="text-xs text-slate-400">{{ __('العناوين والنصوص باللغتين العربية والإنجليزية') }}</p>
                        </div>
                    </div>

                    {{-- Content Type Selector --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">{{ __('نوع المحتوى') }} <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-3">
                            <label :class="{ 'border-[#012383] bg-blue-50/60 dark:bg-blue-950/40 text-[#012383] dark:text-white': contentType === 'text', 'border-slate-200 dark:border-slate-700': contentType !== 'text' }" class="border rounded-2xl p-3 flex items-center justify-center gap-2 cursor-pointer transition-all">
                                <input type="radio" name="content_type" value="text" x-model="contentType" class="sr-only">
                                <span class="text-base">📝</span>
                                <span class="font-bold text-xs">{{ __('إعلان نصي') }}</span>
                            </label>

                            <label :class="{ 'border-[#012383] bg-blue-50/60 dark:bg-blue-950/40 text-[#012383] dark:text-white': contentType === 'video', 'border-slate-200 dark:border-slate-700': contentType !== 'video' }" class="border rounded-2xl p-3 flex items-center justify-center gap-2 cursor-pointer transition-all">
                                <input type="radio" name="content_type" value="video" x-model="contentType" class="sr-only">
                                <span class="text-base">🎥</span>
                                <span class="font-bold text-xs">{{ __('فيديو فقط') }}</span>
                            </label>

                            <label :class="{ 'border-[#012383] bg-blue-50/60 dark:bg-blue-950/40 text-[#012383] dark:text-white': contentType === 'mixed', 'border-slate-200 dark:border-slate-700': contentType !== 'mixed' }" class="border rounded-2xl p-3 flex items-center justify-center gap-2 cursor-pointer transition-all">
                                <input type="radio" name="content_type" value="mixed" x-model="contentType" class="sr-only">
                                <span class="text-base">🎬</span>
                                <span class="font-bold text-xs">{{ __('فيديو وشرح (مختلط)') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Titles --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('العنوان بالعربية') }} <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="title_ar"
                                value="{{ old('title_ar') }}"
                                required
                                placeholder="{{ __('مثال: فيديو تعريفي بكيفية تسليم المهام السريرية') }}"
                                class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('العنوان بالإنجليزية (اختياري)') }}</label>
                            <input
                                type="text"
                                name="title_en"
                                value="{{ old('title_en') }}"
                                placeholder="{{ __('Example: Clinical Tasks Tutorial Video') }}"
                                class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            />
                        </div>
                    </div>

                    {{-- Summaries --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('الملخص / النبذة (عربي)') }}</label>
                            <textarea
                                name="summary_ar"
                                rows="2"
                                placeholder="{{ __('نبذة مختصرة تظهر كشرح سريع أسفل العنوان...') }}"
                                class="w-full p-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            >{{ old('summary_ar') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('الملخص / النبذة (إنجليزي)') }}</label>
                            <textarea
                                name="summary_en"
                                rows="2"
                                placeholder="{{ __('Short teaser or subtitle...') }}"
                                class="w-full p-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            >{{ old('summary_en') }}</textarea>
                        </div>
                    </div>

                    {{-- Detailed Bodies (for text & mixed) --}}
                    <div x-show="contentType !== 'video'" class="flex flex-col gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('النص التفصيلي (عربي)') }}</label>
                            <textarea
                                name="body_ar"
                                rows="4"
                                placeholder="{{ __('النص الكامل للإعلان أو التعليمات أو التوجيهات...') }}"
                                class="w-full p-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-normal focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            >{{ old('body_ar') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('النص التفصيلي (إنجليزي - اختياري)') }}</label>
                            <textarea
                                name="body_en"
                                rows="3"
                                placeholder="{{ __('Full detailed text content in English...') }}"
                                class="w-full p-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-normal focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            >{{ old('body_en') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 2. Video & Media Card (Shown when video or mixed) --}}
                <div x-show="contentType !== 'text'" class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="size-9 rounded-xl bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-400 flex items-center justify-center font-bold text-sm">2</div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('إعدادات الفيديو التعليمي والوسائط') }}</h3>
                            <p class="text-xs text-slate-400">{{ __('تحديد مصدر الفيديو (رابط يوتيوب، ملف مباشر، أو رفع ملف MP4)') }}</p>
                        </div>
                    </div>

                    {{-- Video Source Type --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">{{ __('مصدر الفيديو') }}</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <label :class="{ 'border-[#012383] bg-blue-50/60 dark:bg-blue-950/40 text-[#012383]': videoType === 'youtube', 'border-slate-200 dark:border-slate-700': videoType !== 'youtube' }" class="border rounded-xl p-2.5 flex items-center justify-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" name="video_type" value="youtube" x-model="videoType" class="sr-only">
                                <span>▶️ YouTube</span>
                            </label>

                            <label :class="{ 'border-[#012383] bg-blue-50/60 dark:bg-blue-950/40 text-[#012383]': videoType === 'url', 'border-slate-200 dark:border-slate-700': videoType !== 'url' }" class="border rounded-xl p-2.5 flex items-center justify-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" name="video_type" value="url" x-model="videoType" class="sr-only">
                                <span>🔗 رابط مباشر</span>
                            </label>

                            <label :class="{ 'border-[#012383] bg-blue-50/60 dark:bg-blue-950/40 text-[#012383]': videoType === 'upload', 'border-slate-200 dark:border-slate-700': videoType !== 'upload' }" class="border rounded-xl p-2.5 flex items-center justify-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" name="video_type" value="upload" x-model="videoType" class="sr-only">
                                <span>📤 رفع ملف فيديو</span>
                            </label>

                            <label :class="{ 'border-[#012383] bg-blue-50/60 dark:bg-blue-950/40 text-[#012383]': videoType === 'mux', 'border-slate-200 dark:border-slate-700': videoType !== 'mux' }" class="border rounded-xl p-2.5 flex items-center justify-center gap-1.5 cursor-pointer text-xs font-bold">
                                <input type="radio" name="video_type" value="mux" x-model="videoType" class="sr-only">
                                <span>⚡ سحابة Mux</span>
                            </label>
                        </div>
                    </div>

                    {{-- URL Input for YouTube / Direct --}}
                    <div x-show="videoType === 'youtube' || videoType === 'url' || videoType === 'vimeo'">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('رابط الفيديو') }}</label>
                        <input
                            type="text"
                            name="video_url"
                            value="{{ old('video_url') }}"
                            placeholder="{{ __('مثال: https://www.youtube.com/watch?v=... أو https://cdn.example.com/video.mp4') }}"
                            class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                        />
                    </div>

                    {{-- File Upload for Local Video --}}
                    <div x-show="videoType === 'upload'">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('اختر ملف الفيديو (MP4 / MOV)') }}</label>
                        <input
                            type="file"
                            name="video_file"
                            accept="video/mp4,video/quicktime,video/webm"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer"
                        />
                    </div>

                    {{-- MUX Playback ID --}}
                    <div x-show="videoType === 'mux'">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('معرف تشغيل Mux (Playback ID)') }}</label>
                        <input
                            type="text"
                            name="video_mux_playback_id"
                            value="{{ old('video_mux_playback_id') }}"
                            placeholder="{{ __('مثال: aBcDeFg123456789') }}"
                            class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                        />
                    </div>

                    {{-- Thumbnail cover image --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('صورة غلاف الفيديو (Thumbnail)') }}</label>
                        <input
                            type="file"
                            name="thumbnail_file"
                            accept="image/*"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-800 dark:file:text-white hover:file:bg-slate-300 cursor-pointer"
                        />
                    </div>
                </div>

                {{-- 3. Call to Action Button --}}
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="size-9 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-sm">3</div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ __('زر الإجراء السريع (Call To Action - اختياري)') }}</h3>
                            <p class="text-xs text-slate-400">{{ __('زر ينقل المستخدم لصفحة أو إجراء معين داخل أو خارج المنصة') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('نص الزر بالعربية') }}</label>
                            <input
                                type="text"
                                name="button_text_ar"
                                value="{{ old('button_text_ar') }}"
                                placeholder="{{ __('مثال: استكشف الكورس الآن، أو التسجيل السريع') }}"
                                class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('نص الزر بالإنجليزية') }}</label>
                            <input
                                type="text"
                                name="button_text_en"
                                value="{{ old('button_text_en') }}"
                                placeholder="{{ __('Example: Explore Course Now') }}"
                                class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('رابط الزر (URL)') }}</label>
                        <input
                            type="text"
                            name="button_url"
                            value="{{ old('button_url') }}"
                            placeholder="{{ __('مثال: /courses/speech-therapy أو https://example.com') }}"
                            class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                        />
                    </div>
                </div>

            </div>

            {{-- Right 1 Column: Targeting, Modes, Frequency & Scheduling --}}
            <div class="flex flex-col gap-6">

                {{-- Targeting Platforms --}}
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-4">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                        <span>🎯</span>
                        <span>{{ __('المنصات المستهدفة') }}</span>
                    </h3>

                    @php $selectedPlatforms = old('target_platforms', ['web', 'mobile', 'admin']); @endphp
                    <div class="flex flex-col gap-2.5">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <input type="checkbox" name="target_platforms[]" value="web" {{ in_array('web', $selectedPlatforms) ? 'checked' : '' }} class="size-4.5 rounded text-[#012383] focus:ring-[#012383]">
                            <div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">🌐 {{ __('الموقع الإلكتروني (Website)') }}</span>
                                <span class="text-[11px] text-slate-400">{{ __('صفحات الموقع وبوابة الطالب') }}</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <input type="checkbox" name="target_platforms[]" value="mobile" {{ in_array('mobile', $selectedPlatforms) ? 'checked' : '' }} class="size-4.5 rounded text-[#012383] focus:ring-[#012383]">
                            <div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">📱 {{ __('تطبيق الهاتف (Mobile App)') }}</span>
                                <span class="text-[11px] text-slate-400">{{ __('تطبيقات Flutter و iOS و Android عبر API') }}</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <input type="checkbox" name="target_platforms[]" value="admin" {{ in_array('admin', $selectedPlatforms) ? 'checked' : '' }} class="size-4.5 rounded text-[#012383] focus:ring-[#012383]">
                            <div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">🛡️ {{ __('لوحة التحكم (Admin Dashboard)') }}</span>
                                <span class="text-[11px] text-slate-400">{{ __('تنبيهات داخلية خاصة بفريق العمل والمشرفين') }}</span>
                            </div>
                        </label>
                    </div>

                    {{-- Audience Selection --}}
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2">{{ __('الجمهور المستهدف') }}</label>
                        <select name="target_audience" x-model="audience" class="w-full h-11 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200">
                            <option value="all">{{ __('الجميع (زوار، طلاب، مشرفين)') }}</option>
                            <option value="students">{{ __('الطلاب والمتدربون فقط (مسجلون دخولهم)') }}</option>
                            <option value="admins">{{ __('طاقم الإدارة والمشرفون فقط') }}</option>
                            <option value="guests">{{ __('الزوار غير المسجلين فقط') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Display Mode & Enforcement --}}
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-4">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                        <span>🗔</span>
                        <span>{{ __('طريقة الظهور وقوة الإلزام') }}</span>
                    </h3>

                    <div class="flex flex-col gap-2.5">
                        {{-- Standard Popup --}}
                        <label :class="{ 'border-purple-600 bg-purple-50/50 dark:bg-purple-950/40': displayMode === 'popup', 'border-slate-200 dark:border-slate-700': displayMode !== 'popup' }" class="border rounded-2xl p-3 flex items-start gap-3 cursor-pointer transition-all">
                            <input type="radio" name="display_mode" value="popup" x-model="displayMode" class="mt-0.5 size-4 text-purple-600">
                            <div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">🗔 {{ __('نافذة منبثقة (Popup Modal)') }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ __('تظهر في منتصف الشاشة مع إمكانية إغلاقها.') }}</span>
                            </div>
                        </label>

                        {{-- Force Blocking Modal --}}
                        <label :class="{ 'border-red-600 bg-red-50/50 dark:bg-red-950/40': displayMode === 'force', 'border-slate-200 dark:border-slate-700': displayMode !== 'force' }" class="border-2 rounded-2xl p-3 flex items-start gap-3 cursor-pointer transition-all">
                            <input type="radio" name="display_mode" value="force" x-model="displayMode" class="mt-0.5 size-4 text-red-600">
                            <div>
                                <span class="font-black text-xs text-red-700 dark:text-red-300 flex items-center gap-1.5">
                                    <span>🔒 {{ __('إجباري مانع (Force / Mandatory)') }}</span>
                                    <span class="px-1.5 py-0.2 bg-red-600 text-white text-[9px] rounded-md font-bold uppercase">{{ __('حاسم') }}</span>
                                </span>
                                <span class="text-[11px] text-red-600/80 dark:text-red-300/80 block mt-0.5 leading-snug">{{ __('يمنع استخدام المنصة كلياً حتى يؤكد المستخدم اطلاعه وموافقته.') }}</span>
                            </div>
                        </label>

                        {{-- Top Banner --}}
                        <label :class="{ 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/40': displayMode === 'banner', 'border-slate-200 dark:border-slate-700': displayMode !== 'banner' }" class="border rounded-2xl p-3 flex items-start gap-3 cursor-pointer transition-all">
                            <input type="radio" name="display_mode" value="banner" x-model="displayMode" class="mt-0.5 size-4 text-blue-600">
                            <div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">📢 {{ __('شريط علوي (Top Banner)') }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ __('شريط أفقي في أعلى الصفحة يحتوي إعلاناً أو إجراءً سريعاً.') }}</span>
                            </div>
                        </label>

                        {{-- Inline Card --}}
                        <label :class="{ 'border-slate-600 bg-slate-100/50 dark:bg-slate-800/40': displayMode === 'inline', 'border-slate-200 dark:border-slate-700': displayMode !== 'inline' }" class="border rounded-2xl p-3 flex items-start gap-3 cursor-pointer transition-all">
                            <input type="radio" name="display_mode" value="inline" x-model="displayMode" class="mt-0.5 size-4 text-slate-600">
                            <div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">🪟 {{ __('بطاقة مدمجة (Inline Card)') }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ __('تظهر كبطاقة تعليمية في مساحة لوحة الطالب أو الكتالوج.') }}</span>
                            </div>
                        </label>
                    </div>

                    {{-- Frequency Rules --}}
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('قاعدة التكرار') }}</label>
                        <select name="frequency" class="w-full h-11 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200">
                            <option value="once" {{ old('frequency') === 'once' ? 'selected' : '' }}>{{ __('مرة واحدة فقط لكل مستخدم (Once)') }}</option>
                            <option value="until_dismissed" {{ old('frequency', 'until_dismissed') === 'until_dismissed' ? 'selected' : '' }}>{{ __('حتى يقوم بالإغلاق أو التأكيد (Until Dismissed)') }}</option>
                            <option value="every_session" {{ old('frequency') === 'every_session' ? 'selected' : '' }}>{{ __('مرة في كل جلسة دخول جديدة (Every Session)') }}</option>
                            <option value="always" {{ old('frequency') === 'always' ? 'selected' : '' }}>{{ __('دائماً وبشكل مستمر (Always)') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Scheduling & Status Card --}}
                <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col gap-4">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                        <span>⚙️</span>
                        <span>{{ __('الجدولة والأولوية والحالة') }}</span>
                    </h3>

                    {{-- Active Toggle --}}
                    <label class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-slate-800 cursor-pointer">
                        <span class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ __('تفعيل الإعلان فور الحفظ') }}</span>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="size-5 text-emerald-600 rounded focus:ring-emerald-500">
                    </label>

                    {{-- Priority --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('درجة الأولوية (Priority)') }}</label>
                        <input
                            type="number"
                            name="priority"
                            value="{{ old('priority', 0) }}"
                            min="0"
                            max="999"
                            class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#012383]"
                        />
                        <span class="text-[10px] text-slate-400 mt-1 block">{{ __('الرقم الأعلى يظهر أولاً في قائمة النوافذ المنبثقة.') }}</span>
                    </div>

                    {{-- Start Date / Time --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('تاريخ بدء الظهور (اختياري)') }}</label>
                        <input
                            type="datetime-local"
                            name="start_at"
                            value="{{ old('start_at') }}"
                            class="w-full h-11 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold focus:outline-none"
                        />
                    </div>

                    {{-- End Date / Time --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">{{ __('تاريخ انتهاء الظهور (اختياري)') }}</label>
                        <input
                            type="datetime-local"
                            name="end_at"
                            value="{{ old('end_at') }}"
                            class="w-full h-11 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold focus:outline-none"
                        />
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="flex-1 h-12 rounded-2xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white font-bold text-sm shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>✓</span>
                        <span>{{ __('حفظ وتطبيق الإعلان') }}</span>
                    </button>
                    <a
                        href="{{ route('admin.announcements.index') }}"
                        class="h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center hover:bg-slate-50 transition-colors"
                    >
                        {{ __('إلغاء') }}
                    </a>
                </div>

            </div>

        </div>
    </form>
</x-admin-layout::admin>
