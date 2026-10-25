<x-layouts::app :title="__('الملف الشخصي')">
    <!-- ============================================== -->
        <!-- 3. PAGE BODY CONTENT                           -->
        <!-- ============================================== -->
        <div class="px-[40px] pt-[30px] pb-[40px] flex-1 flex flex-col gap-[24px]">
          
          <!-- Page Introduction -->
          <div class="flex flex-col gap-[2px] items-start text-start w-full overflow-clip">
            <p class="font-['Cairo'] font-medium text-[12px] leading-[1.8] text-[#0B2A8F] w-full text-start" data-i18n="profile.breadcrumb">
              مساحة التعلّم  /  حسابي
            </p>
            <h1 class="font-['Baloo_Bhaijaan_2'] font-bold text-[34px] leading-[1.45] text-[#012383] w-full text-start" data-i18n="profile.pageTitle">
              حسابي
            </h1>
            <p class="font-['Cairo'] font-medium text-[14px] leading-[1.8] text-[#475569] w-full text-start" data-i18n="profile.pageDesc">
              إدارة بياناتك الشخصية، كلمة المرور، وتفضيلات الإشعارات.
            </p>
          </div>

          <!-- Profile Hero Card (Clean & Focused) -->
          <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-6">
            
            <div class="flex items-center gap-5">
              <!-- Avatar with edit trigger -->
              <div class="relative group">
                <div class="size-20 rounded-full bg-gradient-to-tr from-[#012383] to-[#0B2A8F] text-white flex items-center justify-center font-bold text-2xl shadow-md shrink-0">
                  أ
                </div>
                <button
                  type="button"
                  id="avatar-upload-btn"
                  title="تغيير الصورة"
                  class="absolute inset-0 rounded-full bg-black/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                >
                  <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
              </div>

              <div class="flex flex-col gap-1.5 text-start">
                <div class="flex items-center gap-3">
                  <h2 class="font-bold text-xl text-[#012383]" data-i18n="header.studentName">أحمد إبراهيم السالم</h2>
                  <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-[#EEF7F3] text-emerald-700 text-xs font-bold">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span data-i18n="profile.studentRole">طالب مسجل</span>
                  </span>
                </div>
                <p class="text-xs text-[#475569]">ahmed.ibrahim@example.com</p>
                <div class="flex items-center gap-2 mt-0.5">
                  <span class="bg-[#EAF1FB] text-[#0B2A8F] text-[11px] font-bold px-2.5 py-1 rounded-full" data-i18n="profile.joinedDate">
                    تاريخ الانضمام: أكتوبر ٢٠٢٦
                  </span>
                </div>
              </div>
            </div>

            <!-- Avatar Action Button -->
            <div>
              <button
                type="button"
                id="change-avatar-trigger"
                class="flex items-center gap-2 px-4 py-2 bg-[#EAF1FB] hover:bg-[#DEE8F7] text-[#012383] font-bold text-xs rounded-xl transition-colors cursor-pointer"
              >
                <svg class="size-4 text-[#0B2A8F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span data-i18n="profile.changeAvatar">تغيير الصورة</span>
              </button>
            </div>

          </div>

          <!-- Profile Settings Card -->
          <div class="bg-white border border-[#DEE8F7] rounded-[20px] p-6 shadow-xs flex flex-col gap-6">
            
            <!-- Tab Navigation Header (3 Clean Tabs) -->
            <div class="flex items-center gap-2 border-b border-[#DEE8F7] pb-3" id="profile-tabs-header">
              <button
                type="button"
                data-tab="personal"
                class="profile-tab-btn active px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#012383] text-white cursor-pointer"
                data-i18n="profile.tabPersonal"
              >
                البيانات الشخصية
              </button>
              <button
                type="button"
                data-tab="security"
                class="profile-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all text-[#475569] hover:bg-[#EAF1FB] cursor-pointer"
                data-i18n="profile.tabSecurity"
              >
                كلمة المرور والأمان
              </button>
              <button
                type="button"
                data-tab="notifications"
                class="profile-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all text-[#475569] hover:bg-[#EAF1FB] cursor-pointer"
                data-i18n="profile.tabNotifications"
              >
                تفضيلات الإشعارات
              </button>
            </div>

            <!-- Tab 1: Personal Info -->
            <div id="tab-personal" class="profile-tab-content flex flex-col gap-5">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.fullNameLabel">الاسم الكامل</label>
                  <input
                    type="text"
                    value="أحمد إبراهيم السالم"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.emailLabel">البريد الإلكتروني</label>
                  <input
                    type="email"
                    value="ahmed.ibrahim@example.com"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.phoneLabel">رقم الجوال</label>
                  <input
                    type="tel"
                    value="+966 54 987 6543"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5 text-start">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.cityLabel">المدينة والدولة</label>
                  <input
                    type="text"
                    value="الرياض، المملكة العربية السعودية"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
              </div>

              <div class="flex flex-col gap-1.5 text-start">
                <label class="text-xs font-bold text-[#012383]" data-i18n="profile.bioLabel">نبذة تعريفية</label>
                <textarea
                  rows="3"
                  class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl p-3.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15 resize-none leading-relaxed"
                  data-i18n-placeholder="profile.bioPlaceholder"
                  placeholder="اكتب نبذة تعريفية مختصرة عن اهتماماتك في علوم التخاطب والتعلم..."
                >طالب ودارس في علوم التخاطب واضطرابات النطق، أتابع كورس منصة مركز الأمين للتطبيق العملي واكتساب الخبرة السريرية المعتمدة.</textarea>
              </div>
            </div>

            <!-- Tab 2: Password & Security (Hidden by default) -->
            <div id="tab-security" class="profile-tab-content hidden flex-col gap-4">
              <div class="max-w-md flex flex-col gap-4 text-start">
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.currentPasswordLabel">كلمة المرور الحالية</label>
                  <input
                    type="password"
                    placeholder="••••••••"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.newPasswordLabel">كلمة المرور الجديدة</label>
                  <input
                    type="password"
                    placeholder="أدخل كلمة المرور الجديدة"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs font-bold text-[#012383]" data-i18n="profile.confirmNewPasswordLabel">تأكيد كلمة المرور الجديدة</label>
                  <input
                    type="password"
                    placeholder="أعد إدخال كلمة المرور الجديدة"
                    class="bg-[#FAFCFF] border border-[#DEE8F7] rounded-xl px-4 py-2.5 text-xs text-[#012383] font-medium outline-none transition-all focus:border-[#0B2A8F] focus:ring-2 focus:ring-[#0B2A8F]/15"
                  />
                </div>

                <div class="p-3.5 rounded-xl bg-[#EAF1FB]/50 border border-[#DEE8F7] flex items-center gap-2.5">
                  <svg class="size-4 text-[#0B2A8F] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="text-[11px] text-[#012383] font-medium leading-relaxed" data-i18n="profile.passwordHint">
                    يجب أن تتكون كلمة المرور من ٨ خانات على الأقل وتتضمن أرقامًا وحروفًا.
                  </p>
                </div>
              </div>
            </div>

            <!-- Tab 3: Notification Preferences (Hidden by default) -->
            <div id="tab-notifications" class="profile-tab-content hidden flex-col gap-3">
              
              <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex items-center justify-between gap-4">
                <div class="text-start">
                  <h4 class="text-xs font-bold text-[#012383]" data-i18n="profile.notifNewLessons">إشعارات الدروس والمحتوى الجديد</h4>
                  <p class="text-[11px] text-[#475569] mt-0.5" data-i18n="profile.notifNewLessonsDesc">تنبيهك فور إضافة دروس وفيديوهات جديدة في كورساتك المسجلة.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                  <input type="checkbox" checked class="sr-only peer" />
                  <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0B2A8F]"></div>
                </label>
              </div>

              <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex items-center justify-between gap-4">
                <div class="text-start">
                  <h4 class="text-xs font-bold text-[#012383]" data-i18n="profile.notifAssignments">إشعارات تقييم المهام والواجبات</h4>
                  <p class="text-[11px] text-[#475569] mt-0.5" data-i18n="profile.notifAssignmentsDesc">تنبيهك عند استلام تقييم وملاحظات المحاضر على واجباتك المرسلة.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                  <input type="checkbox" checked class="sr-only peer" />
                  <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0B2A8F]"></div>
                </label>
              </div>

              <div class="p-4 rounded-xl border border-[#DEE8F7] bg-[#FAFCFF] flex items-center justify-between gap-4">
                <div class="text-start">
                  <h4 class="text-xs font-bold text-[#012383]" data-i18n="profile.notifReminders">إشعارات التذكير بمتابعة التعلّم</h4>
                  <p class="text-[11px] text-[#475569] mt-0.5" data-i18n="profile.notifRemindersDesc">تذكيرات أسبوعية خفيفة لمساعدتك على إكمال مسارك التعليمي.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                  <input type="checkbox" checked class="sr-only peer" />
                  <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0B2A8F]"></div>
                </label>
              </div>

            </div>

            <!-- Footer Save Actions -->
            <div class="pt-4 border-t border-[#DEE8F7] flex items-center justify-end gap-3">
              <button
                type="button"
                id="profile-cancel-btn"
                class="px-5 py-2.5 rounded-full text-xs font-bold text-[#475569] hover:bg-[#EAF1FB] transition-colors cursor-pointer"
                data-i18n="profile.cancelBtn"
              >
                إلغاء
              </button>
              <button
                type="button"
                id="profile-save-btn"
                class="px-7 py-2.5 rounded-full text-xs font-bold bg-[#012383] text-white hover:bg-[#0B2A8F] transition-all shadow-sm hover:shadow-md cursor-pointer"
                data-i18n="profile.saveChangesBtn"
              >
                حفظ التغييرات
              </button>
            </div>

          </div>

        </div>

    <x-slot:scripts>
    <script>
      // ==========================================
      // Profile Tabs & Save Interactive Script
      // ==========================================
      document.addEventListener('DOMContentLoaded', () => {
        const tabBtns = document.querySelectorAll('.profile-tab-btn');
        const tabContents = document.querySelectorAll('.profile-tab-content');
        const saveBtn = document.getElementById('profile-save-btn');
        const cancelBtn = document.getElementById('profile-cancel-btn');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        // Tab Switching
        tabBtns.forEach(btn => {
          btn.addEventListener('click', () => {
            const targetTab = btn.getAttribute('data-tab');

            tabBtns.forEach(b => {
              b.classList.remove('active', 'bg-[#012383]', 'text-white', 'shadow-xs');
              b.classList.add('text-[#475569]', 'hover:bg-[#EAF1FB]');
            });
            btn.classList.add('active', 'bg-[#012383]', 'text-white', 'shadow-xs');
            btn.classList.remove('text-[#475569]', 'hover:bg-[#EAF1FB]');

            tabContents.forEach(content => {
              if (content.id === `tab-${targetTab}`) {
                content.classList.remove('hidden');
                content.classList.add('flex');
              } else {
                content.classList.add('hidden');
                content.classList.remove('flex');
              }
            });
          });
        });

        // Save Button Feedback
        saveBtn?.addEventListener('click', () => {
          const isEn = document.documentElement.lang === 'en';
          toastMessage.textContent = isEn ? 'Changes have been saved successfully!' : 'تم حفظ التغييرات بنجاح!';
          toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
          setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
          }, 3500);
        });

        // Cancel Button Feedback
        cancelBtn?.addEventListener('click', () => {
          const isEn = document.documentElement.lang === 'en';
          toastMessage.textContent = isEn ? 'Changes reverted.' : 'تم التراجع عن التغييرات.';
          toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
          setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
          }, 2500);
        });
      });
    </script>
    </x-slot:scripts>
</x-layouts::app>
