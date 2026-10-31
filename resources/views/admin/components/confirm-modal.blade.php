<!-- Global Alameen Confirmation Modal -->
<div 
    id="alameen-confirm-modal" 
    class="modal-backdrop fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4 modal-hidden bg-slate-950/60 backdrop-blur-xs select-none"
    role="dialog"
    aria-modal="true"
    aria-labelledby="alameen-confirm-title"
    aria-describedby="alameen-confirm-message"
    onclick="if(event.target === this) window.handleConfirmModalCancel()"
>
    <div 
        class="modal-content bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 sm:p-7 flex flex-col gap-5 shadow-2xl border border-[#DEE8F7] dark:border-slate-800 transform transition-all text-center"
        onclick="event.stopPropagation()"
    >
        {{-- Warning / Danger Icon Badge --}}
        <div class="mx-auto size-14 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-600 dark:text-rose-400 flex items-center justify-center shadow-xs">
            <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        {{-- Title & Message --}}
        <div class="flex flex-col gap-2">
            <h3 id="alameen-confirm-title" class="font-['Baloo_Bhaijaan_2'] font-bold text-xl text-slate-900 dark:text-white leading-tight">
                {{ __('تأكيد الإجراء') }}
            </h3>
            <p id="alameen-confirm-message" class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                {{ __('هل أنت متأكد من رغبتك في المتابعة؟') }}
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3 pt-2">
            <button 
                type="button" 
                id="alameen-confirm-btn" 
                onclick="window.handleConfirmModalSubmit()" 
                class="flex-1 h-11 px-5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                {{ __('نعم، تأكيد') }}
            </button>
            <button 
                type="button" 
                id="alameen-confirm-cancel" 
                onclick="window.handleConfirmModalCancel()" 
                class="flex-1 h-11 px-5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-all flex items-center justify-center cursor-pointer border border-slate-200 dark:border-slate-700"
            >
                {{ __('إلغاء الأمر') }}
            </button>
        </div>
    </div>
</div>

