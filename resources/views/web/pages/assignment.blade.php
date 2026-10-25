<x-layouts::app :title="__('تسليم المهمة')">
    <!-- Page Content (Node 1155:18550) -->
        <div class="px-4 sm:px-6 lg:px-[40px] pt-[24px] lg:pt-[30px] pb-[40px] flex flex-col gap-[24px] w-full" data-node-id="1155:18550">
          
          <!-- Page Introduction (Node 1155:18551) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:18551">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0b2a8f] text-start w-full" data-i18n="assignment.breadcrumb" data-node-id="1155:18552">
              مساحة التعلّم / المهام
            </p>
            <h1 class="font-['Cairo'] font-bold text-[28px] sm:text-[32px] leading-[1.3] text-[#012383] text-start w-full" data-i18n="assignment.pageTitle" data-node-id="1155:18553">
              تطبيق التمييز السمعي
            </h1>
            <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-[#475569] text-start w-full" data-i18n="assignment.pageSubtitle" data-node-id="1155:18554">
              مهمة الدرس ٨ / أساسيات التخاطب / المستوى الثاني: مهارات الصوت والاستماع
            </p>
          </div>

          <!-- Submission Context Banner (Node 1155:18555) -->
          <div class="bg-gradient-to-l from-[#EAF1FB] via-[#F4F8FD] to-white border border-[#DEE8F7] rounded-[16px] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full shadow-xs" data-node-id="1155:18555">
            
            <!-- Right in RTL: Completed Lesson Reference (Node 1155:18571) -->
            <div class="flex items-center gap-[14px]">
              <div class="size-[32px] rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center shrink-0" data-node-id="1155:19488">
                <svg class="size-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              </div>
              <div class="flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18571">
                <p class="font-['Cairo'] font-bold text-[15px] leading-[1.6] text-[#012383]" data-i18n="assignment.linkedLessonTitle" data-node-id="1155:18572">
                  الدرس المرتبط: التمييز السمعي
                </p>
                <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569]" data-i18n="assignment.linkedLessonStatus" data-node-id="1155:18573">
                  الفيديو مكتمل ١٠٠٪ • المهمة متاحة ولم تُسلّم بعد
                </p>
              </div>
            </div>

            <!-- Left in RTL: Back to Lesson 8 Button (Node 1155:18556) -->
            <a
              href="{{ route('lessons.show') }}"
              class="bg-white border border-[#DEE8F7] hover:border-[#0b2a8f]/40 hover:bg-[#EAF1FB] rounded-[10px] h-[44px] px-[18px] flex items-center justify-center font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] transition-all shrink-0 shadow-2xs"
              data-i18n="assignment.backToLesson8"
              data-node-id="1155:18556"
            >
              العودة إلى الدرس ٨
            </a>

          </div>

          <!-- Assignment Instructions and Form Grid (Node 1155:18575) -->
          <!-- In RTL: First child is on the RIGHT (Main Form Column), Second child is on the LEFT (Task Context & Tips) -->
          <div class="flex flex-col lg:flex-row gap-[24px] items-start w-full" data-node-id="1155:18575">
            
            <!-- RIGHT in RTL: Ready Submission Main Form Column (Node 1155:18594) -->
            <div class="flex-1 min-w-0 flex flex-col gap-[20px] w-full" data-node-id="1155:18594">
              
              <!-- Task Instructions Card (Node 1155:18595) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-6 sm:p-7 flex flex-col gap-[16px] text-start w-full shadow-[0px_4px_20px_rgba(11,42,143,0.03)]" data-node-id="1155:18595">
                <div class="flex items-center justify-between border-b border-[#DEE8F7]/60 pb-3">
                  <h2 class="font-['Cairo'] font-bold text-[20px] leading-[1.4] text-[#012383]" data-i18n="assignment.instructionsTitle" data-node-id="1155:18596">
                    المطلوب في هذه المهمة
                  </h2>
                  <span class="text-xs font-bold text-[#0B2A8F] bg-[#EAF1FB] px-2.5 py-0.5 rounded-full font-['Cairo']">
                    خطوات التطبيق
                  </span>
                </div>
                
                <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569]" data-i18n="assignment.instructionsDesc" data-node-id="1155:18597">
                  صمّمي ورقة نشاط قصيرة للتمييز بين الأصوات، باستخدام أمثلة تعليمية افتراضية دون بيانات شخصية.
                </p>

                <!-- Steps with Clean Badges -->
                <div class="space-y-3 pt-1">
                  <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/70 border border-[#DEE8F7]/50">
                    <span class="size-6 rounded-full bg-[#012383] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">١</span>
                    <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-slate-700" data-i18n="assignment.step1" data-node-id="1155:18598">
                      اختاري ٦ أزواج من الكلمات، مع توضيح أوجه التشابه والاختلاف الصوتي.
                    </p>
                  </div>

                  <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/70 border border-[#DEE8F7]/50">
                    <span class="size-6 rounded-full bg-[#012383] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">٢</span>
                    <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-slate-700" data-i18n="assignment.step2" data-node-id="1155:18599">
                      اكتبي تعليمات النشاط وخطوات تقديم كل زوج بترتيب واضح.
                    </p>
                  </div>

                  <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/70 border border-[#DEE8F7]/50">
                    <span class="size-6 rounded-full bg-[#012383] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">٣</span>
                    <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-slate-700" data-i18n="assignment.step3" data-node-id="1155:18600">
                      أضيفي نموذجًا لتسجيل الاستجابة وملاحظة قصيرة حول تدرّج الصعوبة.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Submission Form Card (Node 1155:18601) -->
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-6 sm:p-7 flex flex-col gap-[20px] text-start w-full shadow-[0px_4px_20px_rgba(11,42,143,0.03)]" data-node-id="1155:18601">
                
                <!-- File Selection Header (Node 1155:18602) -->
                <div class="flex items-center justify-between w-full border-b border-[#DEE8F7]/60 pb-3" data-node-id="1155:18602">
                  <h3 class="font-['Cairo'] font-bold text-[18px] sm:text-[19px] leading-[1.6] text-[#012383]" data-i18n="assignment.submissionFormTitle" data-node-id="1155:18605">
                    ملف المهمة
                  </h3>
                  <div class="bg-[#EEF7F3] border border-[#D1EADB] rounded-full px-3 py-1 flex items-center gap-1.5" data-node-id="1155:18603">
                    <span class="size-2 rounded-full bg-emerald-500"></span>
                    <span class="font-['Cairo'] font-bold text-[12px] text-emerald-800 whitespace-nowrap" data-i18n="assignment.statusReadyBadge" data-node-id="1155:18604">
                      جاهز للإرسال
                    </span>
                  </div>
                </div>

                <!-- File Drop Zone (Node 1155:18606) -->
                <div class="bg-[#FAFCFF] border-2 border-dashed border-[#CFDBEA] hover:border-[#0B2A8F] hover:bg-[#EAF1FB]/30 rounded-[14px] p-6 sm:p-8 flex flex-col gap-2.5 items-center text-center w-full transition-all cursor-pointer group" data-node-id="1155:18606">
                  <div class="size-[48px] rounded-2xl bg-[#EAF1FB] text-[#0B2A8F] flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs" data-node-id="1155:19494">
                    <img src="/assets/images/icon-upload-cloud.svg" alt="" class="size-6" />
                  </div>
                  <p class="font-['Cairo'] font-bold text-[15px] sm:text-[16px] text-[#012383]" data-i18n="assignment.dropzoneTitle" data-node-id="1155:18608">
                    اسحب الملف هنا أو اختره من جهازك
                  </p>
                  <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="assignment.dropzonePolicy" data-node-id="1155:18609">
                    سياسة رفع مقترحة: PDF أو DOCX أو MP3 أو MP4 • بحد أقصى ٢٥ م.ب
                  </p>
                  <button
                    type="button"
                    class="bg-white border border-[#DEE8F7] hover:border-[#0B2A8F] hover:bg-[#EAF1FB] rounded-[10px] h-[42px] px-5 flex items-center justify-center font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] transition-all mt-2 cursor-pointer shadow-xs"
                    data-i18n="assignment.btnChooseFile"
                    data-node-id="1155:18610"
                  >
                    اختيار ملف
                  </button>
                </div>

                <!-- Attached Submission File Row (Node 1155:18625) -->
                <div class="bg-blue-50/40 border border-[#DEE8F7] rounded-[12px] p-4 flex items-center justify-between w-full" data-node-id="1155:18625">
                  <div class="flex items-center gap-3 min-w-0">
                    <div class="size-[36px] rounded-lg bg-[#EAF1FB] flex items-center justify-center shrink-0" data-node-id="1155:19497">
                      <img src="/assets/images/icon-file-text-blue.svg" alt="" class="size-5" />
                    </div>
                    <div class="flex flex-col gap-0.5 items-start text-start min-w-0" data-node-id="1155:18627">
                      <p class="font-['Cairo'] font-bold text-[14px] text-[#012383] truncate" data-i18n="assignment.fileName" data-node-id="1155:18628">
                        أحمد_التمييز_السمعي.pdf
                      </p>
                      <p class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="assignment.fileMeta" data-node-id="1155:18629">
                        PDF • ٢٫٤ م.ب
                      </p>
                    </div>
                  </div>
                  <button
                    type="button"
                    class="font-['Cairo'] font-bold text-[12px] text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition-colors cursor-pointer shrink-0"
                    data-i18n="assignment.removeFile"
                    data-node-id="1155:18626"
                  >
                    إزالة
                  </button>
                </div>

                <!-- Optional Student Note (Node 1155:18631) -->
                <div class="flex flex-col gap-2 items-start w-full" data-node-id="1155:18631">
                  <label class="font-['Cairo'] font-bold text-[14px] text-[#012383]" data-i18n="assignment.studentNoteLabel" data-node-id="1155:18632">
                    ملاحظتك للمحاضر (اختياري)
                  </label>
                  <textarea
                    rows="3"
                    class="bg-white border border-[#DEE8F7] rounded-[10px] p-3.5 min-h-[94px] w-full font-['Cairo'] font-medium text-[14px] text-[#475569] outline-none focus:border-[#0B2A8F] focus:ring-1 focus:ring-[#0B2A8F] transition-all resize-none"
                    data-i18n="assignment.studentNoteValue"
                    data-i18n-placeholder="assignment.studentNoteValue"
                    data-node-id="1155:18633"
                  >أرفقتُ ورقة النشاط مع ترتيب الأزواج من الأسهل إلى الأصعب، ونموذجًا لتسجيل الاستجابات.</textarea>
                </div>

                <!-- Submit Assignment Action (Node 1155:18635) -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full pt-1" data-node-id="1155:18635">
                  <a
                    href="{{ route('results.show') }}"
                    class="bg-[#0B2A8F] hover:bg-[#012383] text-white rounded-[10px] h-[48px] px-8 flex items-center justify-center font-['Cairo'] font-bold text-[15px] shadow-sm hover:shadow transition-all cursor-pointer whitespace-nowrap"
                    data-i18n="assignment.submitBtn"
                    data-node-id="1155:18636"
                  >
                    إرسال المهمة إلى المحاضر
                  </a>
                  <p class="font-['Cairo'] font-medium text-[12px] text-[#475569] text-start" data-i18n="assignment.submitNotice" data-node-id="1155:18651">
                    الملف مُختار فقط؛ تبدأ المراجعة بعد إرسال المهمة.
                  </p>
                </div>

              </div>

            </div>

            <!-- LEFT in RTL: Task Context and Creative Tips Column (w: 320px - Node 1155:18576) -->
            <div class="w-full lg:w-[320px] shrink-0 flex flex-col gap-[16px]" data-node-id="1155:18576">
              
              <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-5 sm:p-6 flex flex-col gap-4 w-full shadow-[0px_4px_20px_rgba(11,42,143,0.03)]" data-node-id="1155:18581">
                
                <h3 class="font-['Cairo'] font-bold text-[17px] leading-[1.4] text-[#012383] text-start w-full" data-i18n="assignment.afterSubmitTitle" data-node-id="1155:18584">
                  بعد الضغط على «إرسال المهمة»
                </h3>

                <!-- Awaiting Review Example Card (Node 1155:18585) -->
                <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[12px] p-4 flex flex-col gap-2.5 items-start w-full text-start" data-node-id="1155:18585">
                  <div class="flex items-center gap-2 w-full" data-node-id="1155:18586">
                    <p class="font-['Cairo'] font-bold text-[14px] text-[#012383] flex-1 text-start" data-i18n="assignment.submittedTitle" data-node-id="1155:18587">
                      تم تسليم المهمة
                    </p>
                    <div class="size-[20px] shrink-0 relative" data-node-id="1155:19491">
                      <img src="/assets/images/icon-send-blue.svg" alt="" class="size-full" />
                    </div>
                  </div>

                  <div class="bg-[#EAF1FB] rounded-full px-2.5 py-0.5 flex items-center" data-node-id="1155:18589">
                    <p class="font-['Cairo'] font-bold text-[11px] text-[#0B2A8F] whitespace-nowrap" data-i18n="assignment.pendingReviewBadge" data-node-id="1155:18590">
                      بانتظار مراجعة المحاضر
                    </p>
                  </div>

                  <p class="font-['Cairo'] font-medium text-[12px] text-[#475569] text-start w-full" data-i18n="assignment.pendingReviewDesc" data-node-id="1155:18591">
                    يظهر هنا الملف المُرسل وتاريخ التسليم. لم تُرصد درجة بعد.
                  </p>

                  <p class="font-['Cairo'] font-bold text-[12px] text-[#0B2A8F] text-start w-full" data-i18n="assignment.gradePlaceholder" data-node-id="1155:18592">
                    الدرجة: — / ١٠٠
                  </p>
                </div>

                <!-- Creative Tips (Node 3153:154) -->
                <div class="flex flex-col gap-3 items-start w-full pt-1" data-node-id="3153:154">
                  
                  <div class="flex flex-col gap-1 items-start text-start w-full" data-node-id="3153:155">
                    <h4 class="font-['Cairo'] font-bold text-[16px] text-[#012383]" data-i18n="assignment.tipsTitle" data-node-id="3153:156">
                      نصائح إبداعية
                    </h4>
                    <p class="font-['Cairo'] font-medium text-[12px] text-[#475569]" data-i18n="assignment.tipsDesc" data-node-id="3153:157">
                      استخدم هذه النصائح لتحسين مهارات الاستماع والتمييز السمعي.
                    </p>
                  </div>

                  <!-- Tips Cards -->
                  <div class="space-y-2 w-full">
                    
                    <!-- Tip 1 -->
                    <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-2.5 flex items-center gap-3 w-full hover:bg-[#EAF1FB]/40 transition-colors" data-node-id="3153:158">
                      <div class="size-[42px] shrink-0 rounded-lg overflow-hidden" data-node-id="3155:196">
                        <img src="/assets/images/tip-1.png" alt="" class="size-full object-cover pointer-events-none" />
                      </div>
                      <p class="font-['Cairo'] font-bold text-[12px] text-[#152D4D] text-start flex-1" data-i18n="assignment.tip1" data-node-id="3153:161">
                        استمع بتركيز إلى نبرة الكلمة
                      </p>
                    </div>

                    <!-- Tip 2 -->
                    <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-2.5 flex items-center gap-3 w-full hover:bg-[#EAF1FB]/40 transition-colors" data-node-id="3153:162">
                      <div class="size-[42px] shrink-0 rounded-lg overflow-hidden" data-node-id="3155:197">
                        <img src="/assets/images/tip-2.png" alt="" class="size-full object-cover pointer-events-none" />
                      </div>
                      <p class="font-['Cairo'] font-bold text-[12px] text-[#152D4D] text-start flex-1" data-i18n="assignment.tip2" data-node-id="3153:165">
                        اجعل الصوت واضحًا عند التسجيل
                      </p>
                    </div>

                    <!-- Tip 3 -->
                    <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-2.5 flex items-center gap-3 w-full hover:bg-[#EAF1FB]/40 transition-colors" data-node-id="3153:166">
                      <div class="size-[42px] shrink-0 rounded-lg overflow-hidden" data-node-id="3155:198">
                        <img src="/assets/images/tip-3.png" alt="" class="size-full object-cover pointer-events-none" />
                      </div>
                      <p class="font-['Cairo'] font-bold text-[12px] text-[#152D4D] text-start flex-1" data-i18n="assignment.tip3" data-node-id="3153:169">
                        كرر الكلمة مرتين قبل إرسالها
                      </p>
                    </div>

                    <!-- Tip 4 -->
                    <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-2.5 flex items-center gap-3 w-full hover:bg-[#EAF1FB]/40 transition-colors" data-node-id="3153:170">
                      <div class="size-[42px] shrink-0 rounded-lg overflow-hidden" data-node-id="3155:199">
                        <img src="/assets/images/tip-4.png" alt="" class="size-full object-cover pointer-events-none" />
                      </div>
                      <p class="font-['Cairo'] font-bold text-[12px] text-[#152D4D] text-start flex-1" data-i18n="assignment.tip4" data-node-id="3153:173">
                        استمع إلى التسجيل قبل إرساله
                      </p>
                    </div>

                    <!-- Tip 5 -->
                    <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-2.5 flex items-center gap-3 w-full hover:bg-[#EAF1FB]/40 transition-colors" data-node-id="3153:174">
                      <div class="size-[42px] shrink-0 rounded-lg overflow-hidden" data-node-id="3155:200">
                        <img src="/assets/images/tip-5.png" alt="" class="size-full object-cover pointer-events-none" />
                      </div>
                      <p class="font-['Cairo'] font-bold text-[12px] text-[#152D4D] text-start flex-1" data-i18n="assignment.tip5" data-node-id="3153:177">
                        اختر كلمات جديدة لتحسين التمييز
                      </p>
                    </div>

                    <!-- Tip 6 -->
                    <div class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-[10px] p-2.5 flex items-center gap-3 w-full hover:bg-[#EAF1FB]/40 transition-colors" data-node-id="3153:178">
                      <div class="size-[42px] shrink-0 rounded-lg overflow-hidden" data-node-id="3155:201">
                        <img src="/assets/images/tip-6.png" alt="" class="size-full object-cover pointer-events-none" />
                      </div>
                      <p class="font-['Cairo'] font-bold text-[12px] text-[#152D4D] text-start flex-1" data-i18n="assignment.tip6" data-node-id="3153:181">
                        تدرب بانتظام لتحسين الاستماع
                      </p>
                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

</x-layouts::app>
