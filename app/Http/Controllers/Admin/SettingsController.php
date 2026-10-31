<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteMenuItemRequest;
use App\Http\Requests\Admin\SaveMenuItemRequest;
use App\Http\Requests\Admin\ToggleMenuItemRequest;
use App\Http\Requests\Admin\UpdateContentBlockRequest;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Services\Cms\MenuService;
use App\Services\Cms\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService,
        protected MenuService $menuService
    ) {}

    /**
     * Display dynamic settings and CMS control panel.
     */
    public function index(): View
    {
        $settings = $this->settingsService->allCached();

        $heroBlock = ContentBlock::firstOrCreate(
            ['section_key' => 'hero'],
            [
                'title_ar' => 'أتقن الممارسة الإكلينيكية في التخاطب والنطق بأعلى المعايير العالمية',
                'title_en' => 'Master Clinical Speech-Language Pathology with Global Standards',
                'subtitle_ar' => 'دورات سريرية متقدمة، فيديوهات تطبيقية عملية، وتقييم صوتي سريري مباشر مع شهادات معتمدة برمز تحقق.',
                'subtitle_en' => 'Advanced clinical courses, practical demonstrations, voice assignment grading, and verifiable certificates.',
                'metadata' => [
                    'cta_primary_ar' => 'استكشف المسارات التدريبية',
                    'cta_primary_en' => 'Explore Clinical Tracks',
                    'cta_primary_url' => '/courses',
                    'cta_secondary_ar' => 'تواصل معنا',
                    'cta_secondary_en' => 'Contact Us',
                    'cta_secondary_url' => '/contact',
                    'students_count' => '2500+',
                    'courses_count' => '45+',
                    'supervisors_count' => '12+',
                    'success_rate' => '98%',
                ],
                'is_active' => true,
            ]
        );

        $aboutBlock = ContentBlock::firstOrCreate(
            ['section_key' => 'about'],
            [
                'title_ar' => 'منصة مركز الأمين.. شريكك الأول في تأهيل التخاطب والنطق',
                'title_en' => 'Al-Ameen Platform.. Your Premier Partner in Speech & Pronunciation Rehabilitation',
                'subtitle_ar' => 'منصة تعليمية متخصصة تجمع بين المعرفة الأكاديمية والتطبيق السريري لتمكين طلاب التخاطب والدارسين من إتقان مهارات التشخيص والعلاج بأعلى معايير الجودة العلمية.',
                'subtitle_en' => 'A specialized educational platform combining academic knowledge and clinical application to empower speech therapy students and practitioners to master diagnostic and therapeutic skills with the highest scientific quality standards.',
                'metadata' => [
                    'pillars' => [
                        [
                            'icon' => '🎯',
                            'title_ar' => 'مناهج تدريبية معتمدة',
                            'title_en' => 'Accredited Training Curricula',
                            'desc_ar' => 'مسارات متسلسلة تبدأ من التشخيص الأولي وتمتد إلى جلسات النطق المتقدمة وتعديل السلوك خطوة بخطوة.',
                            'desc_en' => 'Sequential pathways starting from initial diagnosis and extending to advanced articulation sessions and step-by-step behavior modification.',
                        ],
                        [
                            'icon' => '🎙️',
                            'title_ar' => 'مهام صوتية وتقييم مباشر',
                            'title_en' => 'Voice Assignments & Direct Assessment',
                            'desc_ar' => 'تطبيق عملي من خلال تسجيل المقاطع الصوتية ومخارج الحروف وتلقي ملاحظات وتوجيهات دقيقة من نخبة المحاضرين.',
                            'desc_en' => 'Practical application through recording audio samples and articulation exercises, receiving precise guidance from expert lecturers.',
                        ],
                        [
                            'icon' => '👨‍⚕️',
                            'title_ar' => 'أخصائيون واستشاريون موثوقون',
                            'title_en' => 'Certified Specialists & Consultants',
                            'desc_ar' => 'فريق متكامل من أطباء ومحاضري التخاطب ذوي الخبرات الطويلة في المستشفيات والمراكز التأهيلية الكبرى.',
                            'desc_en' => 'A comprehensive team of speech physicians and lecturers with extensive clinical experience in major hospitals and rehabilitation centers.',
                        ],
                        [
                            'icon' => '📜',
                            'title_ar' => 'شهادات إتمام موثقة برمز QR',
                            'title_en' => 'Verified Certificates with QR Codes',
                            'desc_ar' => 'شهادات إنجاز رسمية موثقة برقم مرجعي ورمز استجابة سريعة تثبت كفاءتك وساعاتك التدريبية في التخصص.',
                            'desc_en' => 'Official achievement certificates documented with reference numbers and QR codes verifying your competence and training hours.',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        $faqBlock = ContentBlock::firstOrCreate(
            ['section_key' => 'faq'],
            [
                'title_ar' => 'الأسئلة الشائعة والأكثر تكراراً',
                'title_en' => 'Frequently Asked Questions',
                'subtitle_ar' => 'كل ما تحتاج لمعرفته حول التسجيل، طرق الدفع المتاحة، والشهادات السريرية المعتمدة.',
                'subtitle_en' => 'Everything you need to know about registration, payment methods, and certified clinical credentials.',
                'metadata' => [
                    'items' => [
                        [
                            'question_ar' => 'كيف يتم تفعيل اشتراكي بعد التحويل عبر فودافون كاش أو إنستاباي؟',
                            'question_en' => 'How is my enrollment activated after paying via Vodafone Cash or InstaPay?',
                            'answer_ar' => 'بمجرد تحويل المبلغ، قم برفع صورة الإيصال في صفحة الكورس، وسيقوم المشرف المالي بمراجعتها واعتماد الوصول الفوري لحسابك تلقائياً.',
                            'answer_en' => 'After transferring, simply upload your receipt screenshot on the course page. Our finance team will review and auto-grant immediate access.',
                        ],
                        [
                            'question_ar' => 'هل الفيديوهات والمحاضرات مفتوحة بالكامل أم مقيدة بتسلسل؟',
                            'question_en' => 'Are video lectures unlocked all at once or sequentially locked?',
                            'answer_ar' => 'حرصاً على الفائدة السريرية والتراكم العلمي، تطبق المنصة نظام القفل المتسلسل؛ حيث يفتح كل فيديو بعد إكمال نسبة المشاهدة المطلوبة للمحاضرة السابقة.',
                            'answer_en' => 'To guarantee scientific progression, sequential video locking is enforced: video N+1 unlocks only after reaching the required watch percentage of video N.',
                        ],
                        [
                            'question_ar' => 'ما هي شروط الحصول على الشهادة الرقمية المعتمدة؟',
                            'question_en' => 'What are the requirements to obtain the verified digital certificate?',
                            'answer_ar' => 'يشترط إكمال 95% على الأقل من مجموع فيديوهات الدورة والمهام السريرية. تصدر الشهادة برمز فريد مع رمز استجابة سريعة (QR Code) للفحص والتحقق.',
                            'answer_en' => 'You must complete at least 95% of total course video progress. Certificates are generated with a unique verification code and QR code.',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        $menus = Menu::with('items')->get();

        return view('admin.settings.index', compact('settings', 'heroBlock', 'aboutBlock', 'faqBlock', 'menus'));
    }

    /**
     * Save/update global payment, branding, academic policies, or contact settings.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $settings = $validated['settings'] ?? [];

        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                $this->settingsService->set(
                    key: $key,
                    valueAr: isset($value['ar']) ? (string) $value['ar'] : null,
                    valueEn: isset($value['en']) ? (string) $value['en'] : null,
                    group: $value['group'] ?? 'general'
                );
            }
        }

        return back()->with('success', __('تم حفظ وتحديث إعدادات المنصة وتفريغ الكاش الفوري بنجاح.'));
    }

    /**
     * Update website CMS content block (Hero, About, FAQ, Announcement).
     */
    public function updateContentBlock(UpdateContentBlockRequest $request, string $sectionKey): RedirectResponse
    {
        $validated = $request->validated();

        $block = ContentBlock::firstOrNew(['section_key' => $sectionKey]);
        $block->title_ar = $validated['title_ar'] ?? $block->title_ar;
        $block->title_en = $validated['title_en'] ?? $block->title_en;
        $block->subtitle_ar = $validated['subtitle_ar'] ?? $block->subtitle_ar;
        $block->subtitle_en = $validated['subtitle_en'] ?? $block->subtitle_en;

        if (array_key_exists('content_ar', $validated)) {
            $block->content_ar = $validated['content_ar'];
        }
        if (array_key_exists('content_en', $validated)) {
            $block->content_en = $validated['content_en'];
        }
        if (array_key_exists('metadata', $validated)) {
            $block->metadata = $validated['metadata'];
        }

        $block->is_active = $request->boolean('is_active', true);
        $block->save();

        return back()->with('success', __('تم حفظ وتحديث محتوى القسم بنجاح.'));
    }

    /**
     * Store or update a dynamic menu item.
     */
    public function saveMenuItem(SaveMenuItemRequest $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validated();

        $this->menuService->saveItem(
            menu: $menu,
            titleAr: $validated['title_ar'],
            titleEn: $validated['title_en'] ?? null,
            url: $validated['url'],
            routeName: $validated['route_name'] ?? null,
            sortOrder: (int) ($validated['sort_order'] ?? 0),
            target: $validated['target'] ?? '_self',
            isActive: $request->boolean('is_active', true),
            itemId: $request->input('item_id') ? (int) $request->input('item_id') : null
        );

        return back()->with('success', __('تم تحديث رابط القائمة بنجاح.'));
    }

    /**
     * Toggle active state of a menu item.
     */
    public function toggleMenuItem(ToggleMenuItemRequest $request, MenuItem $item): RedirectResponse
    {
        $this->menuService->toggleItem($item);

        return back()->with('success', __('تم تغيير حالة تفعيل الرابط بنجاح.'));
    }

    /**
     * Delete a menu item.
     */
    public function deleteMenuItem(DeleteMenuItemRequest $request, MenuItem $item): RedirectResponse
    {
        $this->menuService->deleteItem($item);

        return back()->with('success', __('تم حذف الرابط من القائمة بنجاح.'));
    }
}
