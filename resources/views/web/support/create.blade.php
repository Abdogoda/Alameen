<x-layouts::app :title="__('فتح تذكرة دعم جديدة')">
    <div class="flex flex-col gap-6 w-full text-start max-w-4xl mx-auto">

        <!-- Top breadcrumb & back button -->
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('support.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-[#0B2A8F] hover:text-[#012383] transition-colors">
                <svg class="size-4 transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>{{ __('العودة إلى مركز الدعم والرسائل') }}</span>
            </a>
            <span class="text-xs text-slate-400 font-medium">{{ __('تذكرة استفسار أو بلاغ') }}</span>
        </div>

        <!-- Page Intro Header -->
        <div class="flex flex-col gap-1 items-start text-start w-full">
            <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl sm:text-3xl text-[#012383]">
                {{ __('فتح تذكرة دعم واستفسار جديدة') }}
            </h1>
            <p class="text-xs sm:text-sm text-[#475569]">
                {{ __('املأ التفاصيل التالية بعناية، وسيقوم المشرف السريري أو فريق الدعم الفني بالرد المباشر عليك.') }}
            </p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold">
                <p class="font-bold mb-1">{{ __('يرجى تصحيح الأخطاء التالية قبل المتابعة:') }}</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <form
            action="{{ route('support.store') }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{
                files: [],
                handleFileSelect(event) {
                    this.files = Array.from(event.target.files);
                },
                removeFile(index) {
                    const dt = new DataTransfer();
                    const input = this.$refs.fileInput;
                    this.files.splice(index, 1);
                    this.files.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                }
            }"
            class="bg-white border border-[#DEE8F7] rounded-2xl p-5 sm:p-8 shadow-sm flex flex-col gap-6"
        >
            @csrf

            <!-- Subject -->
            <div class="flex flex-col gap-2">
                <label for="subject" class="font-bold text-xs sm:text-sm text-slate-800">
                    {{ __('عنوان التذكرة / موضوع الاستفسار') }} <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    id="subject"
                    name="subject"
                    value="{{ old('subject') }}"
                    required
                    placeholder="{{ __('مثال: استفسار حول المهمة السريرية للدرس الرابع، أو مشكلة في تشغيل الفيديو...') }}"
                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] text-sm focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium"
                />
            </div>

            <!-- Category & Priority Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Category -->
                <div class="flex flex-col gap-2">
                    <label for="category" class="font-bold text-xs sm:text-sm text-slate-800">
                        {{ __('تصنيف الاستفسار') }} <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="category"
                        name="category"
                        required
                        class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] text-sm focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium bg-white"
                    >
                        <option value="general" @selected(old('category') === 'general')>{{ __('استفسار عام') }}</option>
                        <option value="academic" @selected(old('category') === 'academic')>{{ __('استفسار سريري وأكاديمي (تصحيح/مهام)') }}</option>
                        <option value="technical" @selected(old('category') === 'technical')>{{ __('دعم فني وتقني (المنصة/الفيديو/الحساب)') }}</option>
                        <option value="billing" @selected(old('category') === 'billing')>{{ __('فواتير ومدفوعات وإيصالات') }}</option>
                        <option value="courses" @selected(old('category') === 'courses')>{{ __('محتوى الدورات والدروس') }}</option>
                        <option value="certificates" @selected(old('category') === 'certificates')>{{ __('الشهادات والاعتمادات') }}</option>
                    </select>
                </div>

                <!-- Priority -->
                <div class="flex flex-col gap-2">
                    <label for="priority" class="font-bold text-xs sm:text-sm text-slate-800">
                        {{ __('درجة الأهمية') }} <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="priority"
                        name="priority"
                        required
                        class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] text-sm focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium bg-white"
                    >
                        <option value="low" @selected(old('priority') === 'low')>{{ __('منخفضة - استفسار غير عاجل') }}</option>
                        <option value="medium" @selected(old('priority', 'medium') === 'medium')>{{ __('متوسطة - عادي (الموصى به)') }}</option>
                        <option value="high" @selected(old('priority') === 'high')>{{ __('عالية - تعيق تقدمي في التعلم') }}</option>
                        <option value="urgent" @selected(old('priority') === 'urgent')>{{ __('عاجلة جداً - طارئة') }}</option>
                    </select>
                </div>
            </div>

            <!-- Optional Course Link -->
            @if($enrolledCourses->count() > 0)
                <div class="flex flex-col gap-2">
                    <label for="course_id" class="font-bold text-xs sm:text-sm text-slate-800 flex items-center justify-between">
                        <span>{{ __('الكورس ذو الصلة (اختياري)') }}</span>
                        <span class="text-[11px] font-normal text-slate-400">{{ __('يساعد المشرف على فهم السياق مباشرة') }}</span>
                    </label>
                    <select
                        id="course_id"
                        name="course_id"
                        class="w-full h-11 px-3.5 rounded-xl border border-[#DEE8F7] text-sm focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium bg-white"
                    >
                        <option value="">{{ __('--- غير مرتبط بكورس محدد ---') }}</option>
                        @foreach($enrolledCourses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                                {{ $course->title_ar ?? $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Message Body -->
            <div class="flex flex-col gap-2">
                <label for="message" class="font-bold text-xs sm:text-sm text-slate-800">
                    {{ __('تفاصيل المشكلة أو الاستفسار') }} <span class="text-rose-500">*</span>
                </label>
                <textarea
                    id="message"
                    name="message"
                    rows="6"
                    required
                    placeholder="{{ __('اشرح تفاصيل المشكلة أو الاستفسار بوضوح، مع ذكر أي خطوات تم اتخاذها...') }}"
                    class="w-full p-4 rounded-xl border border-[#DEE8F7] text-sm focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all outline-hidden font-medium"
                >{{ old('message') }}</textarea>
            </div>

            <!-- Attachments Upload -->
            <div class="flex flex-col gap-2">
                <label class="font-bold text-xs sm:text-sm text-slate-800 flex items-center justify-between">
                    <span>{{ __('المرفقات (صور، مستندات، لقطات شاشة، تسجيل صوتي)') }}</span>
                    <span class="text-[11px] font-normal text-slate-400">{{ __('حتى 5 ملفات (بحد أقصى 20MB للملف)') }}</span>
                </label>

                <div class="border-2 border-dashed border-[#DEE8F7] hover:border-[#0B2A8F]/50 rounded-2xl p-6 text-center bg-slate-50/50 hover:bg-slate-50 transition-colors flex flex-col items-center justify-center gap-2 cursor-pointer relative">
                    <input
                        type="file"
                        id="attachments"
                        name="attachments[]"
                        multiple
                        x-ref="fileInput"
                        @change="handleFileSelect($event)"
                        accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip,.mp3,.wav,.ogg,.m4a"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                    />
                    <div class="size-12 rounded-xl bg-[#EAF1FB] flex items-center justify-center text-[#012383]">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs sm:text-sm font-bold text-[#012383]">{{ __('انقر لاختيار الملفات أو اسحبها هنا') }}</span>
                        <span class="text-[11px] text-slate-400">{{ __('PNG, JPG, PDF, DOCX, MP3, ZIP') }}</span>
                    </div>
                </div>

                <!-- Selected Files List -->
                <template x-if="files.length > 0">
                    <div class="flex flex-col gap-2 mt-2">
                        <template x-for="(file, index) in files" :key="index">
                            <div class="flex items-center justify-between p-2.5 bg-slate-100 rounded-xl border border-slate-200 text-xs">
                                <div class="flex items-center gap-2 truncate">
                                    <svg class="size-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="font-bold text-slate-800 truncate" x-text="file.name"></span>
                                    <span class="text-slate-400 text-[10px]" x-text="'(' + (file.size / 1024).toFixed(1) + ' KB)'"></span>
                                </div>
                                <button type="button" @click="removeFile(index)" class="text-rose-500 hover:text-rose-700 p-1 font-bold">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[#DEE8F7]">
                <a href="{{ route('support.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-[#DEE8F7] text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-bold text-center">
                    {{ __('إلغاء') }}
                </a>
                <button
                    type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#012383] to-[#0B2A8F] hover:from-[#0B2A8F] hover:to-[#012383] text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>{{ __('إرسال التذكرة وبدء المحادثة') }}</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>
