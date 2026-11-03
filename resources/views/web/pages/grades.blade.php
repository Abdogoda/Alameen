<x-layouts::app :title="__('درجاتي وتقييماتي')">
    <!-- Page Content (Node 1155:18886) -->
    <div class="flex flex-col gap-6 w-full text-start" data-node-id="1155:18886">
          
          <!-- Page Introduction (Node 1155:18887) -->
          <div class="flex flex-col gap-[4px] items-start text-start w-full" data-node-id="1155:18887">
            <p class="font-['Cairo'] font-semibold text-[12px] leading-[1.8] text-[#0B2A8F] text-start w-full" data-i18n="grades.breadcrumb" data-node-id="1155:18888">{{ __('grades.breadcrumb') }}</p>
            <h1 class="font-['Cairo'] font-bold text-[28px] sm:text-[32px] leading-[1.3] text-[#012383] text-start w-full" data-i18n="grades.pageTitle" data-node-id="1155:18889">{{ __('grades.pageTitle') }}</h1>
            <p class="font-['Cairo'] font-medium text-[13px] sm:text-[14px] leading-[1.7] text-[#475569] text-start w-full" data-i18n="grades.pageSubtitle" data-node-id="1155:18890">{{ __('grades.pageSubtitle') }}</p>
          </div>

          <!-- Course Grade Filter (Node 1155:18891) -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 w-full" data-node-id="1155:18891">
            <!-- Right in RTL: Course Selector -->
            <div class="flex gap-[12px] items-center" data-node-id="1155:18893">
              <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#475569] whitespace-nowrap" data-i18n="grades.courseLabel" data-node-id="1155:18897">{{ __('grades.courseLabel') }}</span>
              <div class="bg-white border border-[#DEE8F7] hover:border-[#0B2A8F]/40 flex gap-[16px] h-[46px] items-center px-[16px] rounded-[10px] w-full sm:w-[290px] justify-between cursor-pointer transition-colors shadow-2xs" data-node-id="1155:18894">
                <span class="font-['Cairo'] font-bold text-[14px] leading-[1.8] text-[#012383] flex-1 text-start" data-i18n="grades.selectedCourse" data-node-id="1155:18896">{{ __('grades.selectedCourse') }}</span>
                <div class="size-[16px] shrink-0 relative" data-node-id="1155:19560">
                  <img src="/assets/images/icon-chevron-down.svg" alt="" class="size-full" />
                </div>
              </div>
            </div>

            <!-- Left in RTL: Filter notice -->
            <p class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569]" data-i18n="grades.filterNotice" data-node-id="1155:18892">{{ __('grades.filterNotice') }}</p>
          </div>

          <!-- Current Grading Summary Metrics (Node 1155:18898) -->
          <div class="bg-white border border-[#DEE8F7] rounded-[16px] p-6 sm:p-7 flex flex-col items-start w-full shadow-[0px_4px_20px_rgba(11,42,143,0.03)]" data-node-id="1155:18898">
            <div class="flex flex-col md:flex-row gap-6 md:gap-[40px] items-start md:items-center w-full" data-node-id="1155:18899">
              
              <!-- Current Equal Weight Average (Right in RTL - w:340px) -->
              <div class="w-full md:w-[340px] flex flex-col gap-[4px] items-start text-start shrink-0 border-b md:border-b-0 md:border-s-0 md:border-l border-[#DEE8F7] pb-4 md:pb-0 md:pe-6" data-node-id="1155:18908">
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#334155] text-start w-full" data-i18n="grades.avgTitle" data-node-id="1155:18909">{{ __('grades.avgTitle') }}</p>
                <div class="flex items-baseline gap-2.5">
                  <p class="font-['Cairo'] font-black text-[46px] sm:text-[52px] leading-none text-[#012383] text-start tracking-tight tabular-nums" data-i18n="grades.avgScore" data-node-id="1155:18910">{{ __('grades.avgScore') }}</p>
                  <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full font-['Cairo']">
                    {{ __('grades.avgLevel') }}
                  </span>
                </div>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full tabular-nums" data-i18n="grades.avgMeta" data-node-id="1155:18911">{{ __('grades.avgMeta') }}</p>
              </div>

              <!-- Submissions (Middle) -->
              <div class="flex flex-1 flex-col gap-[4px] items-start text-start" data-node-id="1155:18904">
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#334155] text-start w-full" data-i18n="grades.submissionsTitle" data-node-id="1155:18905">{{ __('grades.submissionsTitle') }}</p>
                <p class="font-['Cairo'] font-black text-[28px] sm:text-[32px] leading-[1.2] text-[#012383] text-start w-full tabular-nums" data-i18n="grades.submissionsCount" data-node-id="1155:18906">{{ __('grades.submissionsCount') }}</p>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full tabular-nums" data-i18n="grades.submissionsMeta" data-node-id="1155:18907">{{ __('grades.submissionsMeta') }}</p>
              </div>

              <!-- Available Tasks (Left in RTL) -->
              <div class="flex flex-1 flex-col gap-[4px] items-start text-start" data-node-id="1155:18900">
                <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#334155] text-start w-full" data-i18n="grades.availableTasksTitle" data-node-id="1155:18901">{{ __('grades.availableTasksTitle') }}</p>
                <p class="font-['Cairo'] font-black text-[28px] sm:text-[32px] leading-[1.2] text-[#012383] text-start w-full tabular-nums" data-i18n="grades.availableTasksCount" data-node-id="1155:18902">{{ __('grades.availableTasksCount') }}</p>
                <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#475569] text-start w-full tabular-nums" data-i18n="grades.availableTasksMeta" data-node-id="1155:18903">{{ __('grades.availableTasksMeta') }}</p>
              </div>

            </div>
          </div>

          <!-- Information Policy Note (Node 1155:18912) -->
          <div class="bg-[#EAF1FB] rounded-[12px] p-[18px] flex gap-[14px] items-start w-full border border-[#DEE8F7]/70" data-node-id="1155:18912">
            <div class="size-[20px] shrink-0 relative mt-[2px]" data-node-id="1155:19563">
              <img src="/assets/images/icon-info-circle.svg" alt="" class="size-full" />
            </div>
            <div class="flex-1 flex flex-col gap-[4px] items-start text-start" data-node-id="1155:18913">
              <h3 class="font-['Cairo'] font-bold text-[14px] leading-[1.6] text-[#012383] text-start w-full" data-i18n="grades.policyTitle" data-node-id="1155:18914">{{ __('grades.policyTitle') }}</h3>
              <p class="font-['Cairo'] font-medium text-[13px] leading-[1.7] text-[#475569] text-start w-full" data-i18n="grades.policyDesc" data-node-id="1155:18915">{{ __('grades.policyDesc') }}</p>
            </div>
          </div>

          <!-- Task Grades Table Card (Node 1155:18917) -->
          <div class="border border-[#DEE8F7] rounded-[16px] overflow-hidden w-full bg-white shadow-[0px_4px_20px_rgba(11,42,143,0.03)]" data-node-id="1155:18917">
            
            <!-- Grades Table Heading (Node 1155:18918) -->
            <div class="bg-white p-[20px] flex items-center justify-between text-start w-full whitespace-nowrap" data-node-id="1155:18918">
              <h2 class="font-['Cairo'] font-bold text-[19px] sm:text-[20px] leading-[1.4] text-[#012383]" data-i18n="grades.tableTitle" data-node-id="1155:18920">{{ __('grades.tableTitle') }}</h2>
              <span class="font-['Cairo'] font-medium text-[12px] sm:text-[13px] text-[#475569] bg-slate-50 border border-[#DEE8F7] px-3 py-1 rounded-full" data-i18n="grades.tableCount" data-node-id="1155:18919">{{ __('grades.tableCount') }}</span>
            </div>

            <!-- Responsive Table Scroll Wrapper -->
            <div class="w-full overflow-x-auto">
              <div class="min-w-[680px]">

                <!-- Grades Column Headings (Node 1155:18921) -->
                <div class="bg-[#EAF1FB] flex gap-[18px] items-center px-[18px] py-[14px] font-['Cairo'] font-bold text-[12px] leading-[1.8] text-[#334155] text-start w-full border-t border-[#DEE8F7]" data-node-id="1155:18921">
                  <div class="flex-1 min-w-0" data-i18n="grades.colTask" data-node-id="1155:18926">{{ __('grades.colTask') }}</div>
                  <div class="w-[120px] shrink-0" data-i18n="grades.colLevel" data-node-id="1155:18925">{{ __('grades.colLevel') }}</div>
                  <div class="w-[175px] shrink-0" data-i18n="grades.colStatus" data-node-id="1155:18924">{{ __('grades.colStatus') }}</div>
                  <div class="w-[105px] shrink-0" data-i18n="grades.colGrade" data-node-id="1155:18923">{{ __('grades.colGrade') }}</div>
                  <div class="w-[110px] shrink-0" data-i18n="grades.colDetails" data-node-id="1155:18922">{{ __('grades.colDetails') }}</div>
                </div>

                <!-- Row 1 (Node 1155:18927) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18927">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18934">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row1Title" data-node-id="1155:18935">{{ __('grades.row1Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row1Sub" data-node-id="1155:18936">{{ __('grades.row1Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row1Level" data-node-id="1155:18933">{{ __('grades.row1Level') }}</p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18930">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18932">{{ __('grades.statusGraded') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row1Score" data-node-id="1155:18929">{{ __('grades.row1Score') }}</p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18928">{{ __('grades.viewFeedback') }}</a>
                </div>

                <!-- Row 2 (Node 1155:18937) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18937">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18944">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row2Title" data-node-id="1155:18945">{{ __('grades.row2Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row2Sub">{{ __('grades.row2Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row2Level" data-node-id="1155:18943">{{ __('grades.row2Level') }}</p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18940">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18942">{{ __('grades.statusGraded') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row2Score" data-node-id="1155:18939">{{ __('grades.row2Score') }}</p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18938">{{ __('grades.viewFeedback') }}</a>
                </div>

                <!-- Row 3 (Node 1155:18947) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18947">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18954">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row3Title" data-node-id="1155:18955">{{ __('grades.row3Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row3Sub" data-node-id="1155:18956">{{ __('grades.row3Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row3Level" data-node-id="1155:18953">{{ __('grades.row3Level') }}</p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18950">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18952">{{ __('grades.statusGraded') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row3Score" data-node-id="1155:18949">{{ __('grades.row3Score') }}</p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18948">{{ __('grades.viewFeedback') }}</a>
                </div>

                <!-- Row 4 (Node 1155:18957) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18957">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18964">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row4Title" data-node-id="1155:18965">{{ __('grades.row4Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row4Sub" data-node-id="1155:18966">{{ __('grades.row4Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row4Level" data-node-id="1155:18963">{{ __('grades.row4Level') }}</p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18960">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18962">{{ __('grades.statusGraded') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row4Score" data-node-id="1155:18959">{{ __('grades.row4Score') }}</p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18958">{{ __('grades.viewFeedback') }}</a>
                </div>

                <!-- Row 5 (Node 1155:18967) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18967">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18974">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row5Title" data-node-id="1155:18975">{{ __('grades.row5Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row5Sub" data-node-id="1155:18976">{{ __('grades.row5Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row5Level" data-node-id="1155:18973">{{ __('grades.row5Level') }}</p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18970">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18972">{{ __('grades.statusGraded') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row5Score" data-node-id="1155:18969">{{ __('grades.row5Score') }}</p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18968">{{ __('grades.viewFeedback') }}</a>
                </div>

                <!-- Row 6 - HIGHLIGHTED ROW (Node 1155:18977) -->
                <div class="bg-[#EAF1FB] border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full" data-node-id="1155:18977">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18984">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row6Title" data-node-id="1155:18985">{{ __('grades.row6Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row6Sub" data-node-id="1155:18986">{{ __('grades.row6Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row6Level" data-node-id="1155:18983">{{ __('grades.row6Level') }}</p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:18980">
                    <span class="bg-[#EEF7F3] text-[#216350] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusGraded" data-node-id="1155:18982">{{ __('grades.statusGraded') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-black text-[20px] text-[#012383] text-start shrink-0 tabular-nums" data-i18n="grades.row6Score" data-node-id="1155:18979">{{ __('grades.row6Score') }}</p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-bold text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewFeedback" data-node-id="1155:18978">{{ __('grades.viewFeedback') }}</a>
                </div>

                <!-- Row 7 (Node 1155:18987 - Awaiting Review) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18987">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18995">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row7Title" data-node-id="1155:18996">{{ __('grades.row7Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row7Sub" data-node-id="1155:18997">{{ __('grades.row7Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row7Level" data-node-id="1155:18994">{{ __('grades.row7Level') }}</p>
                  <div class="w-[175px] shrink-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:18990">
                    <span class="bg-[#F8F4EA] text-[#705B2E] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusPending" data-node-id="1155:18992">{{ __('grades.statusPending') }}</span>
                    <span class="font-['Cairo'] font-medium text-[11px] text-[#475569]" data-i18n="grades.submittedToInstructor" data-node-id="1155:18993">{{ __('grades.submittedToInstructor') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-bold text-[20px] text-[#94A3B8] text-start shrink-0 tabular-nums" data-node-id="1155:18989">
                    —
                  </p>
                  <a href="{{ route('results.show') }}" class="w-[110px] font-['Cairo'] font-medium text-[13px] text-[#475569] hover:text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.viewSubmission" data-node-id="1155:18988">{{ __('grades.viewSubmission') }}</a>
                </div>

                <!-- Row 8 (Node 1155:18998 - Not Submitted) -->
                <div class="bg-white border-t border-[#DEE8F7] flex gap-[18px] items-center min-h-[82px] px-[18px] py-[14px] w-full hover:bg-slate-50 transition-colors" data-node-id="1155:18998">
                  <div class="flex-1 min-w-0 flex flex-col gap-[2px] items-start text-start" data-node-id="1155:19005">
                    <p class="font-['Cairo'] font-semibold text-[14px] text-[#012383] w-full" data-i18n="grades.row8Title" data-node-id="1155:19006">{{ __('grades.row8Title') }}</p>
                    <p class="font-['Cairo'] font-medium text-[11px] text-[#475569] w-full" data-i18n="grades.row8Sub" data-node-id="1155:19007">{{ __('grades.row8Sub') }}</p>
                  </div>
                  <p class="w-[120px] font-['Cairo'] font-medium text-[13px] text-[#334155] text-start shrink-0 tabular-nums" data-i18n="grades.row8Level" data-node-id="1155:19004">{{ __('grades.row8Level') }}</p>
                  <div class="w-[175px] shrink-0" data-node-id="1155:19001">
                    <span class="bg-[#FAFCFF] border border-[#DEE8F7] text-[#475569] font-['Cairo'] font-bold text-[12px] px-[10px] py-[3px] rounded-full inline-block" data-i18n="grades.statusNotSubmitted" data-node-id="1155:19003">{{ __('grades.statusNotSubmitted') }}</span>
                  </div>
                  <p class="w-[105px] font-['Cairo'] font-bold text-[20px] text-[#94A3B8] text-start shrink-0 tabular-nums" data-node-id="1155:19000">
                    —
                  </p>
                  <a href="{{ route('assignments.index') }}" class="w-[110px] font-['Cairo'] font-medium text-[13px] text-[#0B2A8F] hover:underline cursor-pointer shrink-0" data-i18n="grades.openTask" data-node-id="1155:18999">{{ __('grades.openTask') }}</a>
                </div>

              </div>
            </div>

            <!-- Unavailable Later Tasks Notice (Node 1155:19008) -->
            <div class="bg-[#FAFCFF] border-t border-[#DEE8F7] p-[20px] flex gap-[14px] items-center w-full" data-node-id="1155:19008">
              <div class="size-[20px] shrink-0 relative" data-node-id="1155:19566">
                <img src="/assets/images/icon-lock-keyhole.svg" alt="" class="size-full" />
              </div>
              <p class="flex-1 font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start" data-i18n="grades.unavailableNotice" data-node-id="1155:19009">{{ __('grades.unavailableNotice') }}</p>
            </div>

          </div>

          <!-- Bottom Certificate Eligibility Notice (Node 1155:19011) -->
          <p class="font-['Cairo'] font-medium text-[13px] leading-[1.8] text-[#475569] text-start w-full" data-i18n="grades.certRuleNote" data-node-id="1155:19011">{{ __('grades.certRuleNote') }}</p>

        </div>

</x-layouts::app>
