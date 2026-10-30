<x-admin-layout::admin
    :title="__('تعديل الكورس التدريبي: :title', ['title' => $course->title_ar])"
    :description="__('تعديل تفاصيل الكورس، السعر، الصورة البارزة، وإعدادات الفيديو التعريفي عبر MUX أو رابط خارجي')"
    contentClass="p-3.5 sm:p-6 lg:p-8 flex flex-col gap-6 max-w-[1200px] w-full mx-auto min-w-0"
>
    <x-slot:actions>
        <div class="flex items-center gap-2 flex-wrap">
            <a 
                href="{{ route('admin.courses.index') }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-4 rounded-xl bg-white dark:bg-slate-800 border border-[#DEE8F7] dark:border-slate-700 hover:border-[#0B2A8F] text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-1.5 transition-all shadow-2xs"
            >
                <svg class="size-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('دليل الكورسات') }}</span>
            </a>

            <a 
                href="{{ route('admin.courses.show', $course) }}" 
                class="h-9 sm:h-11 px-3.5 sm:px-5 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-xs hover:shadow-md"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ __('عرض المنهج والمستويات (View Page)') }}</span>
            </a>
        </div>
    </x-slot:actions>

    {{-- Session Flash Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="size-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold space-y-1 shadow-xs">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        {{-- Card 1: Main Course Metadata --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('البيانات الأساسية للكورس') }}</h3>
                    <p class="text-xs text-slate-400">{{ __('العناوين، الاسم المعرّف (Slug)، ونبذة المنهج') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('حالة النشر:') }}</label>
                    <select name="is_published" class="h-9 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-[#012383] dark:text-white outline-none">
                        <option value="1" {{ $course->is_published ? 'selected' : '' }}>{{ __('منشور ونشط للطلاب') }}</option>
                        <option value="0" {{ ! $course->is_published ? 'selected' : '' }}>{{ __('مسودة غير منشورة') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('عنوان الكورس بالعربية:') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title_ar" 
                        required 
                        value="{{ old('title_ar', $course->title_ar) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-white outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('عنوان الكورس بالإنجليزية:') }}
                    </label>
                    <input 
                        type="text" 
                        name="title_en" 
                        value="{{ old('title_en', $course->title_en) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                    {{ __('المعرّف الرابطي (Slug):') }} <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="slug" 
                    required 
                    value="{{ old('slug', $course->slug) }}" 
                    class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('الوصف بالعربية:') }}</label>
                    <textarea name="description_ar" rows="3" class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50">{{ old('description_ar', $course->description_ar) }}</textarea>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ __('الوصف بالإنجليزية:') }}</label>
                    <textarea name="description_en" rows="3" class="w-full p-3.5 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] resize-none bg-slate-50/50 dark:bg-slate-800/50">{{ old('description_en', $course->description_en) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Card 2: Pricing & Access Duration --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('التسعير وصلاحية الوصول') }}</h3>
                <p class="text-xs text-slate-400">{{ __('السعر الفعلي المطلوب وسعر الشطب قبل الخصم، ومدة اشتراك الطالب') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('السعر الفعلي (ج.م):') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="price" 
                        required 
                        min="0" 
                        value="{{ old('price', (int)$course->price) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-[#012383] dark:text-blue-400 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('السعر الأصلي قبل الخصم (ج.م):') }}
                    </label>
                    <input 
                        type="number" 
                        name="original_price" 
                        min="0" 
                        value="{{ old('original_price', (int)$course->original_price) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-600 dark:text-slate-300 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        {{ __('مدة الصلاحية (أيام):') }} <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="access_duration_days" 
                        required 
                        min="1" 
                        value="{{ old('access_duration_days', $course->access_duration_days) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>
            </div>
        </div>

        {{-- Card 3: Promo / Preview Video (MUX vs Link + Live Player Preview) --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-5" x-data="{ videoOption: '{{ !empty($course->mux_playback_id) ? 'mux_link' : 'mux_link' }}' }">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white flex items-center gap-2">
                        <svg class="size-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>{{ __('الفيديو التعريفي للكورس (MUX Platform / Link)') }}</span>
                    </h3>
                    <p class="text-xs text-slate-400">{{ __('يتم رفع الفيديو عبر المنصة إلى MUX أو تضمينه عبر رابط خارجي (YouTube, Vimeo, HLS, MP4) مع إمكانية المعاينة الفورية') }}</p>
                </div>

                {{-- Source Switcher Tabs --}}
                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                    <button 
                        type="button" 
                        @click="videoOption = 'upload'" 
                        :class="videoOption === 'upload' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer"
                    >
                        {{ __('رفع ملف فيديو إلى MUX') }}
                    </button>
                    <button 
                        type="button" 
                        @click="videoOption = 'mux_link'" 
                        :class="videoOption === 'mux_link' ? 'bg-[#012383] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-[#012383]'" 
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer"
                    >
                        {{ __('رابط خارجي أو Mux ID') }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    {{-- Option 1: File Upload to MUX --}}
                    <div x-show="videoOption === 'upload'" class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block">
                            {{ __('رفع ملف فيديو جديد:') }}
                        </label>
                        <input 
                            type="file" 
                            name="promo_video_file" 
                            accept="video/mp4,video/quicktime,video/webm" 
                            class="w-full text-xs text-slate-500 file:mr-2 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#012383] file:text-white hover:file:bg-[#0B2A8F] cursor-pointer" 
                        />
                        <p class="text-[11px] text-slate-400">
                            {{ __('صيغ مقبولة: MP4, MOV, WebM. يتم إرسال الفيديو ومعالجته على منصة MUX السحابية مباشرة.') }}
                        </p>
                    </div>

                    {{-- Option 2: Link / Mux Playback ID --}}
                    <div x-show="videoOption === 'mux_link'" class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block">
                            {{ __('رابط الفيديو أو معرّف بث MUX:') }}
                        </label>
                        <input 
                            type="text" 
                            id="promo-url-input"
                            name="promo_video_url" 
                            value="{{ old('promo_video_url', $course->mux_playback_id ?: $course->promo_video_url) }}" 
                            placeholder="https://stream.mux.com/XXXXX.m3u8 أو معرّف MUX أو رابط YouTube / Vimeo" 
                            class="w-full h-11 px-4 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                        />
                        <p class="text-[11px] text-slate-400">
                            {{ __('يدعم: روابط بث MUX (.m3u8)، معرّفات Mux Playback ID، روابط YouTube، روابط Vimeo، وملفات MP4 المباشرة.') }}
                        </p>
                    </div>

                    {{-- Current Mux Details if present --}}
                    @if(!empty($course->mux_playback_id))
                        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 space-y-1">
                            <span class="font-bold flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                {{ __('مرتبط حالياً بمنصة MUX') }}
                            </span>
                            <p class="font-mono text-[11px] truncate">Playback ID: {{ $course->mux_playback_id }}</p>
                        </div>
                    @endif
                </div>

                {{-- Live Promo Video Preview Box --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-[#012383] dark:text-blue-400 block">
                            {{ __('معاينة الفيديو مباشرة (Live Player Preview):') }}
                        </label>
                        <button 
                            type="button" 
                            onclick="reloadPromoPreview()" 
                            class="text-xs font-bold text-[#0B2A8F] hover:underline cursor-pointer"
                        >
                            {{ __('تحديث المعاينة 🔄') }}
                        </button>
                    </div>

                    <div id="promo-preview-player" class="w-full bg-slate-950 rounded-2xl overflow-hidden aspect-video border border-slate-800 flex items-center justify-center relative shadow-md">
                        @if(!empty($course->mux_playback_id))
                            <mux-player 
                                playback-id="{{ $course->mux_playback_id }}" 
                                stream-type="on-demand" 
                                controls 
                                class="w-full h-full"
                            ></mux-player>
                        @elseif(!empty($course->effective_promo_video_url))
                            <video src="{{ $course->effective_promo_video_url }}" controls class="w-full h-full"></video>
                        @else
                            <div class="text-center p-6 text-slate-500 text-xs">
                                <svg class="size-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>{{ __('أدخل رابط الفيديو أو ارفع ملف لمعاينته هنا.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Thumbnail --}}
        <div class="bg-white dark:bg-slate-900 border border-[#DEE8F7] dark:border-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
            <div class="border-b border-[#DEE8F7]/80 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-base sm:text-lg text-[#012383] dark:text-white">{{ __('صورة الغلاف (Thumbnail)') }}</h3>
                <p class="text-xs text-slate-400">{{ __('صورة العرض في دليل الدورات وصفحة تفاصيل الكورس') }}</p>
            </div>

            <div class="flex items-center gap-5 flex-wrap sm:flex-nowrap">
                <div class="size-24 sm:size-28 rounded-2xl bg-[#012383] overflow-hidden border border-[#DEE8F7] dark:border-slate-700 shrink-0 shadow-2xs">
                    <img id="thumbnail-preview" src="{{ $course->thumbnail ?: '/assets/images/course-topic-basics.png' }}" alt="{{ $course->title_ar }}" class="size-full object-cover" />
                </div>

                <div class="space-y-2 flex-1">
                    <input 
                        type="file" 
                        name="thumbnail_file" 
                        accept="image/*" 
                        onchange="previewThumbnailFile(this)"
                        class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF1FB] file:text-[#012383] hover:file:bg-[#DEE8F7] cursor-pointer" 
                    />
                    <div class="text-xs text-slate-400">
                        {{ __('أو حدد مسار الصورة المباشر:') }}
                    </div>
                    <input 
                        type="text" 
                        name="thumbnail" 
                        value="{{ old('thumbnail', $course->thumbnail) }}" 
                        placeholder="/assets/images/..." 
                        class="w-full h-10 px-3 rounded-xl border border-[#DEE8F7] dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 outline-none focus:border-[#0B2A8F] bg-slate-50/50 dark:bg-slate-800/50" 
                    />
                </div>
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-2">
            {{-- Delete Course Form Button --}}
            <div>
                <button 
                    type="button" 
                    onclick="if(confirm('{{ __('هل أنت متأكد من رغبتك في حذف هذا الكورس نهائياً؟') }}')) document.getElementById('delete-course-form').submit();" 
                    class="px-4 py-2.5 rounded-xl border border-rose-300 dark:border-rose-900 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-bold transition-all cursor-pointer"
                >
                    {{ __('حذف الكورس التدريبي نهائياً') }}
                </button>
            </div>

            {{-- Save & Cancel Buttons --}}
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a 
                    href="{{ route('admin.courses.show', $course) }}" 
                    class="w-full sm:w-auto px-5 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center cursor-pointer"
                >
                    {{ __('إلغاء') }}
                </a>

                <button 
                    type="submit" 
                    class="w-full sm:w-auto px-8 h-11 rounded-xl bg-[#012383] hover:bg-[#0B2A8F] text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-lg hover:shadow-blue-900/30 active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-2"
                >
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('حفظ التعديلات ونشر الكورس') }}</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Hidden Delete Course Form --}}
    <form id="delete-course-form" action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function previewThumbnailFile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('thumbnail-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function reloadPromoPreview() {
            const urlInput = document.getElementById('promo-url-input').value.trim();
            const playerContainer = document.getElementById('promo-preview-player');

            if (!urlInput) {
                playerContainer.innerHTML = '<div class="text-center p-6 text-slate-500 text-xs">يرجى كتابة رابط الفيديو للمعاينة.</div>';
                return;
            }

            playerContainer.innerHTML = '';

            // 1. Mux
            if (urlInput.includes('stream.mux.com') || /^[a-zA-Z0-9]{16,64}$/.test(urlInput)) {
                let playbackId = urlInput;
                const match = urlInput.match(/stream\.mux\.com\/([a-zA-Z0-9_\-]+)/);
                if (match) playbackId = match[1];

                if (window.customElements && customElements.get('mux-player')) {
                    const muxPlayer = document.createElement('mux-player');
                    muxPlayer.setAttribute('playback-id', playbackId);
                    muxPlayer.setAttribute('stream-type', 'on-demand');
                    muxPlayer.setAttribute('controls', 'true');
                    muxPlayer.className = 'w-full h-full';
                    playerContainer.appendChild(muxPlayer);
                } else {
                    const video = document.createElement('video');
                    video.controls = true;
                    video.className = 'w-full h-full';
                    const hlsSrc = `https://stream.mux.com/${playbackId}.m3u8`;
                    if (window.Hls && Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(hlsSrc);
                        hls.attachMedia(video);
                    } else {
                        video.src = hlsSrc;
                    }
                    playerContainer.appendChild(video);
                }
            }
            // 2. YouTube
            else if (urlInput.includes('youtube.com') || urlInput.includes('youtu.be')) {
                let ytId = '';
                const m = urlInput.match(/(?:embed\/|watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]+)/);
                if (m) ytId = m[1];
                playerContainer.innerHTML = `<iframe src="https://www.youtube.com/embed/${ytId}" class="w-full h-full border-0" allowfullscreen></iframe>`;
            }
            // 3. Vimeo
            else if (urlInput.includes('vimeo.com')) {
                let vmId = '';
                const m = urlInput.match(/vimeo\.com\/(?:video\/)?([0-9]+)/);
                if (m) vmId = m[1];
                playerContainer.innerHTML = `<iframe src="https://player.vimeo.com/video/${vmId}" class="w-full h-full border-0" allowfullscreen></iframe>`;
            }
            // 4. MP4 / Direct link
            else {
                const video = document.createElement('video');
                video.controls = true;
                video.className = 'w-full h-full';
                video.src = urlInput;
                playerContainer.appendChild(video);
            }
        }
    </script>
</x-admin-layout::admin>
