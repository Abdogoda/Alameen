<x-layouts::marketing :title="__('تقرير تدقيق الهوية البصرية وجودة الكود')">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 flex flex-col gap-8">
<!-- Hero Header Banner -->
      <section class="bg-gradient-to-br from-[#012383] via-[#0B2A8F] to-[#153B9D] rounded-[24px] p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -top-12 -left-12 size-56 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-16 -right-16 size-72 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-3">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-white text-xs font-bold self-start border border-white/20">
            <span>تقرير التدقيق الاستراتيجي الشامل</span>
            <span>•</span>
            <span>أكتوبر ٢٠٢٦</span>
          </div>

          <h1 class="font-['Baloo_Bhaijaan_2'] font-extrabold text-2xl sm:text-4xl leading-tight">
            تدقيق وتقييم الهوية البصرية وجودة الكود البرمجي
          </h1>

          <p class="text-white/90 text-sm sm:text-base max-w-3xl leading-relaxed font-medium">
            تقييم نقدي شامل لتصاميم منصة مركز الأمين المولدة بالذكاء الاصطناعي (AI-Generated Figma Designs)، وتفكيك الأنماط العامة المكررة، مع وضع بدائل معمارية مميزة ونظام توكنز صارم قبل الشروع في أي تعديل تنفيذي.
          </p>

          <div class="pt-4 mt-2 border-t border-white/15 flex flex-wrap items-center gap-6 text-xs text-white/80 font-medium">
            <div class="flex items-center gap-2">
              <span class="size-2 rounded-full bg-emerald-400"></span>
              <span>نطاق العمل: تدقيق وتقييم استراتيجي فقط (Zero Execution)</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="size-2 rounded-full bg-amber-400"></span>
              <span>الملفات الممسوحة: 14 صفحة HTML + محرك CSS و i18n</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Section Navigation Pills (Quick Scroll) -->
      <nav class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-2xl border border-[#DEE8F7] shadow-xs no-print sticky top-20 z-30">
        <a href="#section-a" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#012383] hover:bg-[#EAF1FB] transition-colors">A. تدقيق الهوية</a>
        <a href="#section-b" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#012383] hover:bg-[#EAF1FB] transition-colors">B. جودة الكود</a>
        <a href="#section-c" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#012383] hover:bg-[#EAF1FB] transition-colors">C. مصفوفة التصنيف</a>
        <a href="#section-d" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#012383] hover:bg-[#EAF1FB] transition-colors">D. البدائل المقترحة</a>
        <a href="#section-e" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#012383] hover:bg-[#EAF1FB] transition-colors">E. نظام التوكنز</a>
        <a href="#section-f" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#012383] hover:bg-[#EAF1FB] transition-colors">F. مسارات العمل</a>
        <a href="#section-g" class="px-4 py-1.5 rounded-xl text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all ms-auto">G. مصفوفة الاعتماد</a>
      </nav>

      <!-- ============================================== -->
      <!-- SECTION A: VISUAL IDENTITY AUDIT               -->
      <!-- ============================================== -->
      <section id="section-a" class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <span class="text-xs font-bold text-[#0B2A8F]">القسم الأول</span>
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]">A. تدقيق الهوية البصرية (Visual Identity Audit)</h2>
          </div>
          <span class="px-3 py-1 rounded-full bg-[#EAF1FB] text-[#012383] text-xs font-bold">10 نتائج مرصودة</span>
        </div>

        <p class="text-sm leading-relaxed text-[#475569]">
          تم فحص كافة العناصر البصرية المولدة عبر أدوات الذكاء الاصطناعي وتصنيفها وفق المقياس الخماسي المعتمد لتحديد ما إذا كانت خياراً مفيداً، أم قاعدة اتساق لازمة، أم قالباً عاماً يجب تغييره:
        </p>

        <!-- Findings Table -->
        <div class="overflow-x-auto border border-[#DEE8F7] rounded-xl">
          <table class="w-full text-right text-xs">
            <thead class="bg-[#FAFCFF] border-b border-[#DEE8F7] text-[#012383] font-bold">
              <tr>
                <th class="p-3.5">#</th>
                <th class="p-3.5">العنصر / النمط المرصود</th>
                <th class="p-3.5">التحليل النقدي والدلالة الوظيفية</th>
                <th class="p-3.5 whitespace-nowrap">التصنيف</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#DEE8F7]">
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A1</td>
                <td class="p-3.5 font-bold text-[#012383]">تشتت الحواف الدائرية (Radius Fragmentation)</td>
                <td class="p-3.5 text-[#475569]">تباين عشوائي غير مبرر: 131px في صور الهيرو، و 24px للحاويات، و 20px للبطاقات، و 16px لبطاقات الدورات، و 10px للأزرار. ناتج عن توليد عشوائي بأدوات الذكاء الاصطناعي دون مقياس هندسي موحد.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold whitespace-nowrap">(3) نمط AI عام يستوجب التحسين</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A2</td>
                <td class="p-3.5 font-bold text-[#012383]">متلازمة البطاقات المتداخلة (Russian-Doll Cards)</td>
                <td class="p-3.5 text-[#475569]">حشر كل عنصر داخل بطاقة بيضاء بإطار رمادي: الشاشة تحوي بطاقة، بداخلها بطاقة فيديو، بداخلها بطاقة شريط، بداخلها بطاقة تفاصيل. يفقد الصفحة عمقها الحقيقي ويخلق إرهاقاً بصرياً.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold whitespace-nowrap">(3) نمط AI عام يستوجب التحسين</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A3</td>
                <td class="p-3.5 font-bold text-[#012383]">البانر الكحلي القاتم (Dark Gradient Banner)</td>
                <td class="p-3.5 text-[#475569]">بطاقات داكنة بتدرج لوني كحلي مسود في لوحة التحكم والشهادات. نمط مستهلك في قوالب لوحات التحكم البرمجية ومظلم جداً على منصة تعليمية وتأهيلية موجهة للأطفال وأولياء الأمور.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold whitespace-nowrap">(3) نمط AI عام يستوجب التحسين</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A4</td>
                <td class="p-3.5 font-bold text-[#012383]">إغراق الواجهة بالأقراص والشارات (Pill Overuse)</td>
                <td class="p-3.5 text-[#475569]">تحويل كل سطر إلى قرص بيضاوي ملون (مدة الدرس، نوع الحساب، رقم القيد، الحالة، التخصص، اللغة). عندما تصبح كل معلومة شارة، تفقد الشارات قدرتها على لفت انتباه المستخدم لما هو عاجل.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold whitespace-nowrap">(3) نمط AI عام يستوجب التحسين</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A5</td>
                <td class="p-3.5 font-bold text-[#012383]">هيمنة Baloo Bhaijaan على السياق العيادي</td>
                <td class="p-3.5 text-[#475569]">الخط ممتاز وجذاب في العناوين الإعلانية، لكن استخدامه في الشهادات المعتمدة، وسجل الدرجات، والتفاصيل الطبية يضفي طابعاً كرتونياً غير موثوق يضر بالرصانة الأكاديمية المطلوبة.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold whitespace-nowrap">(3) نمط AI عام يستوجب التحسين</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A6</td>
                <td class="p-3.5 font-bold text-[#012383]">كارت التذكير الجانبي الجامد (Static Reminder)</td>
                <td class="p-3.5 text-[#475569]">كارت بنص ثابت غير متغير مكرر في شريط التنقل لـ 8 صفحات داخلية، يستهلك مساحة عمودية هامة بلا فائدة تفاعلية للمتعلم.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold whitespace-nowrap">(3) نمط AI عام يستوجب التحسين</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A7</td>
                <td class="p-3.5 font-bold text-[#012383]">شبكة صور الهيرو الرباعية (2x2 Quadrant)</td>
                <td class="p-3.5 text-[#475569]">تكوين مميز يجمع بين الطفل والأخصائي بأشكال غير متماثلة. فكرة قوية تحتاج لتهذيب نسب التقويس وأوراق الشجر لتبدو أكثر فخامة وإتقاناً.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-[11px] font-bold whitespace-nowrap">(5) حالة تتطلب قرار المستخدم</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A8</td>
                <td class="p-3.5 font-bold text-[#012383]">شريط التنقل الجانبي الأيمن (Sidebar RTL)</td>
                <td class="p-3.5 text-[#475569]">تثبيت القائمة على اليمين بعرض 248px مع تباعد متزن يخدم بنية القراءة العربية بكفاءة عالية، ويوفر بيئة عمل تعليمية مريحة جداً.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold whitespace-nowrap">(2) قاعدة اتساق لازمة ومفيدة</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A9</td>
                <td class="p-3.5 font-bold text-[#012383]">ألوان الهوية الزرقاء الملكية (#012383 و #0B2A8F)</td>
                <td class="p-3.5 text-[#475569]">التناغم اللوني بين الكحلي الداكن والأزرق الملكي يمنح المنصة هيبة أكاديمية واستقراراً موثوقاً لجمهور أولياء الأمور والأخصائيين.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold whitespace-nowrap">(1) نمط تصميمي مقصود ومفيد</span></td>
              </tr>
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="p-3.5 font-bold text-[#0B2A8F]">A10</td>
                <td class="p-3.5 font-bold text-[#012383]">أخطاء محاذاة الهيدر العلوي بين الصفحات</td>
                <td class="p-3.5 text-[#475569]">اختلاف ترتيب حقل البحث وأيقونة الإشعارات وزر اللغة بين الشاشات يسبب قفزات بصرية مشوشة للمستخدم عند الانتقال بين الصفحات.</td>
                <td class="p-3.5"><span class="px-2.5 py-1 rounded-full bg-red-50 text-red-800 border border-red-200 text-[11px] font-bold whitespace-nowrap">(4) عدم اتساق / عيب تنفيذي</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- ============================================== -->
      <!-- SECTION B: CODE QUALITY & REPETITION AUDIT    -->
      <!-- ============================================== -->
      <section id="section-b" class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <span class="text-xs font-bold text-[#0B2A8F]">القسم الثاني</span>
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]">B. تدقيق جودة الكود والتكرار (Code Quality & Repetition Audit)</h2>
          </div>
          <span class="px-3 py-1 rounded-full bg-red-50 text-red-800 border border-red-200 text-xs font-bold">نقاط حرجة للصيانة</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="p-5 rounded-2xl bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-2">
            <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
              <span class="size-6 rounded-lg bg-[#EAF1FB] flex items-center justify-center text-xs">1</span>
              <span>القيم الاعتباطية المباشرة (Hardcoded Values)</span>
            </div>
            <p class="text-xs text-[#475569] leading-relaxed">
              تكرار قيم صلبة مثل <code class="px-1.5 py-0.5 rounded bg-slate-100 text-[#012383]">bg-[#012383]</code> (أكثر من 60 مرة) و <code class="px-1.5 py-0.5 rounded bg-slate-100 text-[#012383]">border-[#DEE8F7]</code> (أكثر من 85 مرة) في HTML بدلاً من ربطها بـ Design Tokens في Tailwind. أي تعديل في درجة اللون يتطلب فحص وتعديل 14 ملفاً منفصلاً.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-2">
            <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
              <span class="size-6 rounded-lg bg-[#EAF1FB] flex items-center justify-center text-xs">2</span>
              <span>التكرار الهيكلي للمكونات الثابتة (Component Duplication)</span>
            </div>
            <p class="text-xs text-[#475569] leading-relaxed">
              شريط التنقل الجانبي (110 أسطر) والهيدر العلوي (70 سطراً) مكرران حرفياً بنظام Copy-Paste في 8 صفحات مستقلة. تغيير رابط أو أيقونة واحدة يستوجب التعديل في 8 ملفات، مما يرفع احتمالية الخطأ والتضارب.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-2">
            <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
              <span class="size-6 rounded-lg bg-[#EAF1FB] flex items-center justify-center text-xs">3</span>
              <span>تضارب فئات التحويم والتفعيل (Hover Collisions)</span>
            </div>
            <p class="text-xs text-[#475569] leading-relaxed">
              وجود فئات Tailwind ذات خصوصية متساوية في نفس الزر مثل <code class="px-1.5 py-0.5 rounded bg-slate-100">hover:bg-[#EAF1FB]</code> عند تفعيله ديناميكياً بـ <code class="px-1.5 py-0.5 rounded bg-slate-100">bg-[#012383]</code> كان يتسبب في بهتان العنصر النشط عند مرور مؤشر الفأرة فوقه.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-2">
            <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
              <span class="size-6 rounded-lg bg-[#EAF1FB] flex items-center justify-center text-xs">4</span>
              <span>اكتمال مفاتيح الترجمة (i18n Coverage)</span>
            </div>
            <p class="text-xs text-[#475569] leading-relaxed">
              محرك الترجمة ممتاز، لكن هناك نصوص عربية ثابتة صلبة في صفحات مثل <code class="px-1.5 py-0.5 rounded bg-slate-100">reviews.html</code> و <code class="px-1.5 py-0.5 rounded bg-slate-100">contact.html</code> تفتقر لسمات <code class="px-1.5 py-0.5 rounded bg-slate-100">data-i18n</code> مما يتركها بالعربية حتى عند التبديل للإنجليزية.
            </p>
          </div>
        </div>
      </section>

      <!-- ============================================== -->
      <!-- SECTION C: PRESERVE, REFINE, OR REDESIGN       -->
      <!-- ============================================== -->
      <section id="section-c" class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <span class="text-xs font-bold text-[#0B2A8F]">القسم الثالث</span>
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]">C. الحفاظ، التهذيب، أم إعادة التصميم (Preserve, Refine, or Redesign)</h2>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Column 1: Preserve -->
          <div class="rounded-2xl border-2 border-emerald-200 bg-emerald-50/30 p-5 flex flex-col gap-4">
            <div class="flex items-center gap-2">
              <span class="size-3 rounded-full bg-emerald-500"></span>
              <h3 class="font-bold text-[#012383] text-base">PRESERVE (الحفاظ والأصالة)</h3>
            </div>
            <p class="text-xs text-[#475569]">عناصر أصيلة وناجحة تمثل روح المنصة ويجب التمسك بها:</p>
            <ul class="text-xs space-y-2.5 text-[#012383] font-semibold">
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold">✔</span>
                <span>لوحة الألوان الملكية والكحلية (#012383 / #0B2A8F) للثقة والوقار.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold">✔</span>
                <span>شريط التنقل الجانبي الأيمن المخصص لبيئة RTL والخط العربي.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold">✔</span>
                <span>تكوين شبكة صور الهيرو الرباعية كعنصر إنساني جذاب.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold">✔</span>
                <span>محرك الترجمة i18n المعتمد على سمات البيانات.</span>
              </li>
            </ul>
          </div>

          <!-- Column 2: Refine -->
          <div class="rounded-2xl border-2 border-blue-200 bg-blue-50/30 p-5 flex flex-col gap-4">
            <div class="flex items-center gap-2">
              <span class="size-3 rounded-full bg-blue-500"></span>
              <h3 class="font-bold text-[#012383] text-base">REFINE (التهذيب والضبط)</h3>
            </div>
            <p class="text-xs text-[#475569]">قرارات صحيحة في الاتجاه ولكن تحتاج إلى انضباط نسبي:</p>
            <ul class="text-xs space-y-2.5 text-[#012383] font-semibold">
              <li class="flex items-start gap-2">
                <span class="text-blue-600 font-bold">⚡</span>
                <span>توحيد مقياس الحواف الدائرية وفق مقياس نسبي (8px / 14px / 20px).</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-blue-600 font-bold">⚡</span>
                <span>حصر خط Baloo Bhaijaan في العناوين الإعلانية الكبرى فقط.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-blue-600 font-bold">⚡</span>
                <span>ترقية كارت التذكير الجانبي الثابت ليصبح مؤشراً تفاعلياً لإنجاز الدورة.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-blue-600 font-bold">⚡</span>
                <span>توحيد الهيدر العلوي بين كافة الشاشات بنسبة 100%.</span>
              </li>
            </ul>
          </div>

          <!-- Column 3: Redesign -->
          <div class="rounded-2xl border-2 border-amber-200 bg-amber-50/30 p-5 flex flex-col gap-4">
            <div class="flex items-center gap-2">
              <span class="size-3 rounded-full bg-amber-500"></span>
              <h3 class="font-bold text-[#012383] text-base">REDESIGN (إعادة التفكير)</h3>
            </div>
            <p class="text-xs text-[#475569]">قوالب AI عامة تضعف تميز المنتج وتستوجب التغيير:</p>
            <ul class="text-xs space-y-2.5 text-[#012383] font-semibold">
              <li class="flex items-start gap-2">
                <span class="text-amber-600 font-bold">✖</span>
                <span>تفكيك البطاقات المتداخلة واستبدال الإطارات بالفضاء السلبي الذكي.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-amber-600 font-bold">✖</span>
                <span>إلغاء البانر الكحلي المسود واستبداله بأسطح تعليمية مضيئة ومبهجة.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-amber-600 font-bold">✖</span>
                <span>ترشيد الشارات البيضاوية (Pills) وحصرها في الحالات الاستثنائية فقط.</span>
              </li>
            </ul>
          </div>

        </div>
      </section>

      <!-- ============================================== -->
      <!-- SECTION D: DISTINCTIVE ALTERNATIVES            -->
      <!-- ============================================== -->
      <section id="section-d" class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <span class="text-xs font-bold text-[#0B2A8F]">القسم الرابع</span>
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]">D. استكشاف بدائل معمارية مميزة (Distinctive Design Alternatives)</h2>
          </div>
        </div>

        <div class="flex flex-col gap-6">
          
          <!-- Alternative 1 -->
          <div class="border border-[#DEE8F7] rounded-2xl p-6 bg-[#FAFCFF] flex flex-col gap-3">
            <div class="flex items-center justify-between">
              <h3 class="font-bold text-base text-[#012383]">1. بديل بطاقة متابعة الدرس في لوحة الطالب (Resume Learning Card)</h3>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">موصى به</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs mt-1">
              <div class="p-4 rounded-xl bg-white border border-[#DEE8F7]">
                <strong class="text-red-700 block mb-1">النمط الحالي (AI Generic Card):</strong>
                <p class="text-[#475569] leading-relaxed">
                  فيديو عملاق بارتفاع 320px، يعلوه شريط تقدم منفصل، وتحته بطاقة نصوص مكدسة وزر طويل. يستهلك معظم مساحة الشاشة الرأسية دون ربط المشاهدة بالواجب العملي المطلوب.
                </p>
              </div>
              <div class="p-4 rounded-xl bg-[#EAF1FB]/50 border border-[#0B2A8F]/20">
                <strong class="text-[#0B2A8F] block mb-1">البديل المقترح (Studio Split-Card):</strong>
                <p class="text-[#012383] font-medium leading-relaxed">
                  تقسيم أفقي رشيق بارتفاع 180px: النصف الأيمن للمشغل المباشر السريع من نقطة التوقف، والنصف الأيسر يعرض "الواجب العملي لدرس اليوم" وزر التسليم المباشر. يقلل الارتفاع للنصف ويزيد معدل إكمال المهام.
                </p>
              </div>
            </div>
          </div>

          <!-- Alternative 2 -->
          <div class="border border-[#DEE8F7] rounded-2xl p-6 bg-[#FAFCFF] flex flex-col gap-3">
            <div class="flex items-center justify-between">
              <h3 class="font-bold text-base text-[#012383]">2. بديل بطاقات الكتالوج والمسارات (Course Cards)</h3>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">موصى به</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs mt-1">
              <div class="p-4 rounded-xl bg-white border border-[#DEE8F7]">
                <strong class="text-red-700 block mb-1">النمط الحالي (LMS Free Template):</strong>
                <p class="text-[#475569] leading-relaxed">
                  صورة مستطيلة، عنوان، مؤشر تقدم، زر تفاصيل الدورة. شكل نمطي مطابق لأي موقع دورات مسجلة عام.
                </p>
              </div>
              <div class="p-4 rounded-xl bg-[#EAF1FB]/50 border border-[#0B2A8F]/20">
                <strong class="text-[#0B2A8F] block mb-1">البديل المقترح (Clinical Track Card):</strong>
                <p class="text-[#012383] font-medium leading-relaxed">
                  إعطاء البطاقة شخصية المسار العلاجي: شريط تخصصي دلالي (زمردي للنطق، نيلي لمخارج الحروف، موف للتدخل المبكر)، مع صور مصغرة للأخصائيين المعتمدين، ومؤشر حالة نبضي يوضح الخطوة التالية للطالب.
                </p>
              </div>
            </div>
          </div>

          <!-- Alternative 3 -->
          <div class="border border-[#DEE8F7] rounded-2xl p-6 bg-[#FAFCFF] flex flex-col gap-3">
            <div class="flex items-center justify-between">
              <h3 class="font-bold text-base text-[#012383]">3. استراتيجية خطوط العناوين (Typography Strategy)</h3>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">موصى به</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs mt-1">
              <div class="p-4 rounded-xl bg-white border border-[#DEE8F7]">
                <strong class="text-red-700 block mb-1">الوضع الحالي:</strong>
                <p class="text-[#475569] leading-relaxed">
                  استخدام Baloo Bhaijaan 2 لكافة العناوين حتى في جداول الدرجات، ووثائق الشهادات، وإعدادات الحساب، مما يضفي طابعاً ترفيهياً كرتونياً يقلل من هيبة الاعتماد الأكاديمي.
                </p>
              </div>
              <div class="p-4 rounded-xl bg-[#EAF1FB]/50 border border-[#0B2A8F]/20">
                <strong class="text-[#0B2A8F] block mb-1">البديل المقترح (Balanced Authority):</strong>
                <p class="text-[#012383] font-medium leading-relaxed">
                  حصر Baloo Bhaijaan 2 في الصفحة الرئيسية والهيرو الإعلاني، واستخدام Cairo Bold لكافة العناوين الأكاديمية والشهادات والتقارير الطبية لاستعادة الوقار والاعتمادية العيادية.
                </p>
              </div>
            </div>
          </div>

        </div>
      </section>

      <!-- ============================================== -->
      <!-- SECTION E: IDENTITY SYSTEM & TOKENS            -->
      <!-- ============================================== -->
      <section id="section-e" class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <span class="text-xs font-bold text-[#0B2A8F]">القسم الخامس</span>
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]">E. نظام الهوية المرئي الموحد (Visual Identity & Token System)</h2>
          </div>
        </div>

        <p class="text-sm text-[#475569]">
          جدول الألوان والقيم الهندسية المعيارية المقترحة لربط كود HTML بـ Design Tokens موحدة:
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
          <div class="p-3.5 rounded-xl border border-[#DEE8F7] flex flex-col gap-1.5">
            <div class="size-6 rounded-md bg-[#012383]"></div>
            <span class="font-bold text-[#012383]">Brand Primary</span>
            <span class="text-[#475569] text-[11px]">#012383</span>
            <span class="text-[10px] text-slate-400">سلطة العلامة والهيبة</span>
          </div>

          <div class="p-3.5 rounded-xl border border-[#DEE8F7] flex flex-col gap-1.5">
            <div class="size-6 rounded-md bg-[#0B2A8F]"></div>
            <span class="font-bold text-[#012383]">Brand Accent</span>
            <span class="text-[#475569] text-[11px]">#0B2A8F</span>
            <span class="text-[10px] text-slate-400">التفاعل والأزرار</span>
          </div>

          <div class="p-3.5 rounded-xl border border-[#DEE8F7] flex flex-col gap-1.5">
            <div class="size-6 rounded-md bg-[#FAFCFF] border border-[#DEE8F7]"></div>
            <span class="font-bold text-[#012383]">Surface Base</span>
            <span class="text-[#475569] text-[11px]">#FAFCFF</span>
            <span class="text-[10px] text-slate-400">خلفية مريحة للعين</span>
          </div>

          <div class="p-3.5 rounded-xl border border-[#DEE8F7] flex flex-col gap-1.5">
            <div class="size-6 rounded-md bg-[#10B77F]"></div>
            <span class="font-bold text-[#012383]">Success State</span>
            <span class="text-[#475569] text-[11px]">#10B77F</span>
            <span class="text-[10px] text-slate-400">إنجاز المهام والشهادات</span>
          </div>
        </div>

        <div class="p-4 rounded-xl bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-2 text-xs">
          <strong class="text-[#012383]">مقياس الحواف الهندسي الصارم (Radius Tokens):</strong>
          <div class="flex flex-wrap items-center gap-4 text-[#475569] mt-1 font-medium">
            <span>• <b class="text-[#012383]">8px</b> (حقول الإدخال والأزرار المصغرة)</span>
            <span>• <b class="text-[#012383]">14px</b> (بطاقات الدورات ومربعات المحتوى)</span>
            <span>• <b class="text-[#012383]">20px</b> (الحاويات والبانرات الكبرى)</span>
            <span>• <b class="text-[#012383]">9999px</b> (أزرار CTA والشارات الهامة فقط)</span>
          </div>
        </div>
      </section>

      <!-- ============================================== -->
      <!-- SECTION F: DESIGN VS IMPLEMENTATION SEPARATION -->
      <!-- ============================================== -->
      <section id="section-f" class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 sm:p-8 shadow-xs flex flex-col gap-6">
        <div class="flex items-center justify-between border-b border-[#DEE8F7] pb-4">
          <div>
            <span class="text-xs font-bold text-[#0B2A8F]">القسم السادس</span>
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]">F. الفصل بين مهام التصميم ومهام التنفيذ (Separation of Concerns)</h2>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
          <div class="p-5 rounded-2xl bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-3">
            <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
              <span class="size-6 rounded-lg bg-[#EAF1FB] flex items-center justify-center text-xs">🎨</span>
              <span>1. مسار التصميم (Design Changes)</span>
            </div>
            <ul class="space-y-2 text-[#475569] leading-relaxed">
              <li>• توحيد مقياس الحواف وإلغاء التقويس العشوائي.</li>
              <li>• استبدال البانر الكحلي المسود بأسطح تعليمية مشرقة.</li>
              <li>• ترشيد الشارات البيضاوية وإلغاء الفائض منها.</li>
              <li>• إعادة ضبط الخطوط وإبراز الطابع العيادي الموثوق في الشهادات والتقارير.</li>
            </ul>
          </div>

          <div class="p-5 rounded-2xl bg-[#FAFCFF] border border-[#DEE8F7] flex flex-col gap-3">
            <div class="flex items-center gap-2 text-[#012383] font-bold text-sm">
              <span class="size-6 rounded-lg bg-[#EAF1FB] flex items-center justify-center text-xs">💻</span>
              <span>2. مسار التنفيذ البرمجي (Implementation Changes)</span>
            </div>
            <ul class="space-y-2 text-[#475569] leading-relaxed">
              <li>• ربط التوكنز بـ Tailwind وتطهير HTML من الرموز اللونية الصلبة.</li>
              <li>• بناء مكونات مشتركة للقائمة الجانبية والهيدر لمنع تكرار الكود عبر 8 صفحات.</li>
              <li>• استكمال مفاتيح الترجمة المتبقية في ملفات ar.json و en.json.</li>
              <li>• تنظيف فئات التحويم المتعارضة مع الحالات النشطة.</li>
            </ul>
          </div>
        </div>
      </section>

      <!-- ============================================== -->
      <!-- SECTION G: INTERACTIVE APPROVAL MATRIX         -->
      <!-- ============================================== -->
      <section id="section-g" class="bg-white border-2 border-[#0B2A8F] rounded-[24px] p-6 sm:p-8 shadow-md flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#DEE8F7] pb-4">
          <div>
            <span class="text-xs font-bold text-[#0B2A8F]">القسم السابع والأخير</span>
            <h2 class="font-['Baloo_Bhaijaan_2'] font-bold text-2xl text-[#012383]">G. مصفوفة القرارات للاعتماد (Interactive Approval Matrix)</h2>
          </div>
          <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold self-start sm:self-auto">
            حدد اختياراتك هنا
          </span>
        </div>

        <p class="text-sm text-[#475569]">
          يمكنك تحديد القرارات المعتمدة بالضغط على الخيارات أدناه، ثم نسخ خلاصة الاعتماد لإرسالها في المحادثة للبدء في خطة التنفيذ المعتمدة فوراً:
        </p>

        <form id="approval-form" class="flex flex-col gap-4 text-xs">
          
          <!-- Decision 1 -->
          <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <span class="font-bold text-[#0B2A8F] block">D-1: تفكيك متلازمة البطاقات المتداخلة</span>
              <span class="text-[#475569]">معالجة تكديس الإطارات والظلال واستبدالها بالفضاء السلبي الذكي.</span>
            </div>
            <div class="flex items-center gap-3">
              <label class="flex items-center gap-1.5 cursor-pointer">
                <input type="radio" name="d1" value="keep" class="text-[#0B2A8F]" />
                <span>الإبقاء على الوضع الحالي</span>
              </label>
              <label class="flex items-center gap-1.5 cursor-pointer font-bold text-[#012383]">
                <input type="radio" name="d1" value="approve" checked class="text-[#0B2A8F]" />
                <span>اعتماد التهذيب والفضاء السلبي (موصى به)</span>
              </label>
            </div>
          </div>

          <!-- Decision 2 -->
          <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <span class="font-bold text-[#0B2A8F] block">D-2: معالجة بطاقة المهمة الكحلية القاتمة</span>
              <span class="text-[#475569]">استبدال الخلفية الداكنة المظلمة بتدرج تعليمي عيادي مشرق ومبهج.</span>
            </div>
            <div class="flex items-center gap-3">
              <label class="flex items-center gap-1.5 cursor-pointer">
                <input type="radio" name="d2" value="keep" class="text-[#0B2A8F]" />
                <span>الإبقاء على البانر القاتم</span>
              </label>
              <label class="flex items-center gap-1.5 cursor-pointer font-bold text-[#012383]">
                <input type="radio" name="d2" value="approve" checked class="text-[#0B2A8F]" />
                <span>اعتماد السطح التعليمي المشرق (موصى به)</span>
              </label>
            </div>
          </div>

          <!-- Decision 3 -->
          <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <span class="font-bold text-[#0B2A8F] block">D-3: توزيع خطوط العناوين والشهادات</span>
              <span class="text-[#475569]">حصر Baloo Bhaijaan في الهيرو واستخدام Cairo Bold للسياق الأكاديمي.</span>
            </div>
            <div class="flex items-center gap-3">
              <label class="flex items-center gap-1.5 cursor-pointer">
                <input type="radio" name="d3" value="keep" class="text-[#0B2A8F]" />
                <span>Baloo Bhaijaan لكافة العناوين</span>
              </label>
              <label class="flex items-center gap-1.5 cursor-pointer font-bold text-[#012383]">
                <input type="radio" name="d3" value="approve" checked class="text-[#0B2A8F]" />
                <span>Cairo Bold للشاشات الأكاديمية (موصى به)</span>
              </label>
            </div>
          </div>

          <!-- Decision 4 -->
          <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <span class="font-bold text-[#0B2A8F] block">D-4: توحيد مقياس الحواف الدائرية</span>
              <span class="text-[#475569]">إنهاء التشتت العشوائي واعتماد مقياس ثلاثي (8px / 14px / 20px).</span>
            </div>
            <div class="flex items-center gap-3">
              <label class="flex items-center gap-1.5 cursor-pointer">
                <input type="radio" name="d4" value="keep" class="text-[#0B2A8F]" />
                <span>الإبقاء على الأرقام المتفرقة</span>
              </label>
              <label class="flex items-center gap-1.5 cursor-pointer font-bold text-[#012383]">
                <input type="radio" name="d4" value="approve" checked class="text-[#0B2A8F]" />
                <span>اعتماد المقياس الثلاثي الموحد (موصى به)</span>
              </label>
            </div>
          </div>

          <!-- Decision 5 -->
          <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <span class="font-bold text-[#0B2A8F] block">D-5: كارت التذكير الجانبي في شريط التنقل</span>
              <span class="text-[#475569]">معالجة الكارت الثابت المكرر في جميع الصفحات.</span>
            </div>
            <div class="flex items-center gap-3">
              <label class="flex items-center gap-1.5 cursor-pointer">
                <input type="radio" name="d5" value="keep" class="text-[#0B2A8F]" />
                <span>إبقاؤه ثابتاً كما هو</span>
              </label>
              <label class="flex items-center gap-1.5 cursor-pointer font-bold text-[#012383]">
                <input type="radio" name="d5" value="dynamic" checked class="text-[#0B2A8F]" />
                <span>تحويله لمؤشر تفاعلي للإنجاز (موصى به)</span>
              </label>
            </div>
          </div>

          <!-- Decision 6 -->
          <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <span class="font-bold text-[#0B2A8F] block">I-1: مركزية الكود وربط التوكنز</span>
              <span class="text-[#475569]">استبدال القيم الصلبة في HTML بـ Design Tokens موحدة وبناء مكونات مشتركة.</span>
            </div>
            <div class="flex items-center gap-3">
              <label class="flex items-center gap-1.5 cursor-pointer font-bold text-[#012383]">
                <input type="checkbox" name="i1" checked class="text-[#0B2A8F]" />
                <span>اعتماد مركزية الكود والتوكنز (موصى به للصيانة)</span>
              </label>
            </div>
          </div>

          <!-- Copy Button & Feedback -->
          <div class="pt-4 flex items-center justify-between gap-4 border-t border-[#DEE8F7]">
            <span id="copy-status" class="text-xs font-bold text-emerald-700 opacity-0 transition-opacity">
              تم نسخ قرارات الاعتماد بنجاح! يمكنك لصقها الآن في الشات.
            </span>
            <button
              type="button"
              id="copy-decisions-btn"
              class="px-6 py-2.5 rounded-full text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all shadow-sm cursor-pointer"
            >
              نسخ خلاصة القرارات لاعتمادها
            </button>
          </div>
        </form>

      </section>

      <!-- Footer Info -->
      <footer class="text-center text-xs text-[#94A3B8] py-4 border-t border-[#DEE8F7]">
        منصة مركز الأمين للتعليم والتأهيل • وثيقة تدقيق التصميم والكود • جميع الحقوق محفوظة ٢٠٢٦
      </footer>
    </div>

    <x-slot:scripts>
<script>
      document.getElementById('copy-decisions-btn')?.addEventListener('click', () => {
        const form = document.getElementById('approval-form');
        const formData = new FormData(form);
        const d1 = formData.get('d1') === 'approve' ? 'اعتماد التهذيب والفضاء السلبي' : 'الإبقاء على الحالي';
        const d2 = formData.get('d2') === 'approve' ? 'اعتماد السطح التعليمي المشرق' : 'الإبقاء على البانر القاتم';
        const d3 = formData.get('d3') === 'approve' ? 'Cairo Bold للشاشات الأكاديمية والشهادات' : 'Baloo Bhaijaan لكافة العناوين';
        const d4 = formData.get('d4') === 'approve' ? 'اعتماد المقياس الثلاثي للحواف (8/14/20px)' : 'الإبقاء على المتفرق';
        const d5 = formData.get('d5') === 'dynamic' ? 'تحويل كارت التذكير لمؤشر تقدم تفاعلي' : 'إبقاؤه ثابتاً';
        const i1 = formData.get('i1') ? 'معتمد' : 'مؤجل';

        const summaryText = `اعتمد القرارات التالية:\n- D-1 (البطاقات): ${d1}\n- D-2 (البانر): ${d2}\n- D-3 (الخطوط): ${d3}\n- D-4 (الحواف): ${d4}\n- D-5 (كارت التذكير): ${d5}\n- I-1 (مركزية التوكنز والكود): ${i1}`;

        navigator.clipboard.writeText(summaryText).then(() => {
          const status = document.getElementById('copy-status');
          status.classList.remove('opacity-0');
          setTimeout(() => status.classList.add('opacity-0'), 3500);
        });
      });
    </script>
    </x-slot:scripts>
</x-layouts::marketing>
