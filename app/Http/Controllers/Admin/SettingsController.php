<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteMenuItemRequest;
use App\Http\Requests\Admin\SaveMenuItemRequest;
use App\Http\Requests\Admin\ToggleMenuItemRequest;
use App\Http\Requests\Admin\UpdateContentBlockRequest;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Certificate;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Services\Cms\MenuService;
use App\Services\Cms\SettingsService;
use App\Services\Video\MuxVideoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
                'title_ar' => 'كورس التخاطب الشامل مع هنا المكاوي – Round 5',
                'title_en' => 'Comprehensive Speech Therapy Course with Hana Mekawy – Round 5',
                'subtitle_ar' => 'دورات تخاطب شاملة , فيديوهات وتطبيقات عملية , شهادة معتمدة من مركز الامين',
                'subtitle_en' => 'Comprehensive speech therapy courses, practical video demonstrations, and verified certification from Al-Ameen Center.',
                'metadata' => [
                    'cta_primary_ar' => 'استكشف المسارات التدريبية',
                    'cta_primary_en' => 'Explore Training Tracks',
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
                'subtitle_ar' => 'منصة تعليمية متخصصة تجمع بين المعرفة الأكاديمية والتطبيق العملي لتمكين طلاب التخاطب والدارسين من إتقان مهارات التشخيص والعلاج بأعلى معايير الجودة العلمية.',
                'subtitle_en' => 'A specialized educational platform combining academic knowledge and practical application to empower speech therapy students and practitioners to master diagnostic and therapeutic skills with the highest scientific quality standards.',
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
                'subtitle_ar' => 'كل ما تحتاج لمعرفته حول التسجيل، طرق الدفع المتاحة، والشهادات المعتمدة من مركز الأمين.',
                'subtitle_en' => 'Everything you need to know about registration, payment methods, and certified credentials.',
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
                            'answer_ar' => 'حرصاً على الفائدة والتراكم العلمي والتطبيقي، تطبق المنصة نظام القفل المتسلسل؛ حيث يفتح كل فيديو بعد إكمال نسبة المشاهدة المطلوبة للمحاضرة السابقة.',
                            'answer_en' => 'To guarantee scientific progression, sequential video locking is enforced: video N+1 unlocks only after reaching the required watch percentage of video N.',
                        ],
                        [
                            'question_ar' => 'ما هي شروط الحصول على الشهادة الرقمية المعتمدة؟',
                            'question_en' => 'What are the requirements to obtain the verified digital certificate?',
                            'answer_ar' => 'يشترط إكمال 95% على الأقل من مجموع فيديوهات الدورة والمهام التدريبية. تصدر الشهادة برمز فريد مع رمز استجابة سريعة (QR Code) للفحص والتحقق.',
                            'answer_en' => 'You must complete at least 95% of total course video progress. Certificates are generated with a unique verification code and QR code.',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        $feedbackBlock = ContentBlock::firstOrCreate(
            ['section_key' => 'feedback'],
            [
                'title_ar' => 'مركز الأمين.. ثقتكم دايمًا مسؤولية بنعتز بيها، ورضاكم هو هدفنا اللي بنسعى ليه كل يوم',
                'title_en' => 'Al-Ameen Center.. Your Trust Is Our Responsibility, Your Satisfaction Is Our Daily Goal',
                'subtitle_ar' => 'بنؤمن إن آراءكم وملاحظاتكم هي الدافع الحقيقي لينا علشان نتطور ونقدم الأفضل باستمرار ونكون عند حسن ظنكم وثقتكم فينا.',
                'subtitle_en' => 'We believe your feedback is the true motivation for us to continuously evolve, deliver excellence, and exceed expectations.',
                'metadata' => [
                    'images' => [
                        '/assets/images/alameen-images/6.jpeg',
                        '/assets/images/alameen-images/1.jpeg',
                        '/assets/images/alameen-images/2.jpeg',
                        '/assets/images/alameen-images/3.jpeg',
                        '/assets/images/alameen-images/9.jpeg',
                        '/assets/images/alameen-images/4.jpeg',
                        '/assets/images/alameen-images/5.jpeg',
                        '/assets/images/alameen-images/certificate.png',
                        '/assets/images/alameen-images/12.jpeg',
                    ],
                ],
                'is_active' => true,
            ]
        );

        $reviewsBlock = ContentBlock::firstOrCreate(
            ['section_key' => 'reviews'],
            [
                'title_ar' => 'آراء المتدربين والدارسين وتجاربهم التطبيقية',
                'title_en' => 'Trainee Reviews & Practical Experiences',
                'subtitle_ar' => 'قصص نجاح واقعية وانطباعات موثقة من أخصائيي وطلاب التخاطب الذين انضموا لدوراتنا التدريبية.',
                'subtitle_en' => 'Real success stories and verified impressions from speech therapy specialists and students who completed our courses.',
                'metadata' => [
                    'items' => [
                        [
                            'author' => 'سارة المنصور',
                            'role' => 'طالبة دراسات عليا - علوم التخاطب',
                            'badge' => 'طالب معتمد',
                            'rating' => 5,
                            'title' => 'تمارين واضحة ونتائج مباشرة في تصحيح مخارج الحروف',
                            'text' => 'الكورس ممتاز من حيث الترتيب المنهجي، الفيديوهات مركزة وملاحظات المحاضر على تحليلي الصوتي ساعدتني كثيراً في فهم مخارج الحروف وتطبيقها عملياً.',
                            'image' => '/assets/images/alameen-images/2.jpeg',
                        ],
                        [
                            'author' => 'خالد العمري',
                            'role' => 'أخصائي نطق وتخاطب مبتدئ',
                            'badge' => 'أخصائي معتمد',
                            'rating' => 5,
                            'title' => 'أفضل استثمار في مسيرتي كطالب وباحث في علوم التخاطب',
                            'text' => 'استفدت كثيراً من النماذج التطبيقية واستمارة الملاحظة المرفقة بتوجيه المحاضرين. أنصح كل دارس في علوم التخاطب بهذه المنصة.',
                            'image' => '/assets/images/alameen-images/1.jpeg',
                        ],
                        [
                            'author' => 'فهد الدوسري',
                            'role' => 'متدرب في مسار التخاطب الشامل',
                            'badge' => 'طالب معتمد',
                            'rating' => 5,
                            'title' => 'منهجية علمية واضحة في تصحيح اللدغات الصوتية',
                            'text' => 'الكورس قدّم تدريباً دقيقاً على تقنيات المحاكاة وتعديل مخارج الأصوات. التقييم المباشر للمهام من المحاضر عزز مهاراتي التطبيقية بشكل ملحوظ.',
                            'image' => '/assets/images/alameen-images/3.jpeg',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        $menus = Menu::with('items')->get();

        $sampleCertificate = Certificate::with(['user', 'course'])->latest()->first();
        if (! $sampleCertificate) {
            $sampleCertificate = new Certificate([
                'user_id' => 1,
                'certificate_code' => 'ALM-2026-XMKGR2NJ',
                'verification_url' => url('/verify-certificate/ALM-2026-XMKGR2NJ'),
                'completion_percentage' => 100.0,
                'issued_at' => now(),
                'qr_code_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="size-full"><rect width="100" height="100" fill="#fff"/><rect x="10" y="10" width="30" height="30" fill="#012383"/><rect x="60" y="10" width="30" height="30" fill="#012383"/><rect x="10" y="60" width="30" height="30" fill="#012383"/><rect x="20" y="20" width="10" height="10" fill="#fff"/><rect x="70" y="20" width="10" height="10" fill="#fff"/><rect x="20" y="70" width="10" height="10" fill="#fff"/><rect x="45" y="45" width="20" height="20" fill="#012383"/></svg>',
            ]);
            $sampleCertificate->setRelation('user', (object) ['name' => 'Abdo Goda']);
            $sampleCertificate->setRelation('course', (object) ['title' => 'دبلوم أساسيات وتطبيقات التخاطب وتنمية اللغة']);
        }

        return view('admin.settings.index', compact('settings', 'heroBlock', 'aboutBlock', 'faqBlock', 'feedbackBlock', 'reviewsBlock', 'menus', 'sampleCertificate'));
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

        // Handle custom logo image file upload
        if ($request->hasFile('site_logo_file')) {
            $path = $request->file('site_logo_file')->store('branding', 'public');
            if (is_string($path)) {
                $url = Storage::url($path);
                $this->settingsService->set('site_logo', $url, $url, 'branding');
            }
        }

        // Handle white logo image file upload (for dark theme & dark footer)
        if ($request->hasFile('site_logo_white_file')) {
            $path = $request->file('site_logo_white_file')->store('branding', 'public');
            if (is_string($path)) {
                $url = Storage::url($path);
                $this->settingsService->set('site_logo_white', $url, $url, 'branding');
            }
        }

        // Handle independent footer logo image file upload
        if ($request->hasFile('footer_logo_file')) {
            $path = $request->file('footer_logo_file')->store('branding', 'public');
            if (is_string($path)) {
                $url = Storage::url($path);
                $this->settingsService->set('footer_logo', $url, $url, 'branding');
            }
        }

        // Handle auth artwork image file upload (13.jpeg)
        if ($request->hasFile('auth_login_image_file')) {
            $path = $request->file('auth_login_image_file')->store('branding', 'public');
            if (is_string($path)) {
                $url = Storage::url($path);
                $this->settingsService->set('auth_login_image', $url, $url, 'branding');
            }
        }

        // Handle certificate custom template background upload
        if ($request->hasFile('certificate_custom_bg_file')) {
            $path = $request->file('certificate_custom_bg_file')->store('certificates/templates', 'public');
            if (is_string($path)) {
                $url = Storage::url($path);
                $this->settingsService->set('certificate_custom_bg', $url, $url, 'certificates');
            }
        }

        // Handle certificate official stamp image upload
        if ($request->hasFile('certificate_stamp_file')) {
            $path = $request->file('certificate_stamp_file')->store('certificates/stamps', 'public');
            if (is_string($path)) {
                $url = Storage::url($path);
                $this->settingsService->set('certificate_stamp_image', $url, $url, 'certificates');
            }
        }

        // Handle certificate template selection and signatories
        if ($request->filled('certificate_default_template')) {
            $tpl = (string) $request->input('certificate_default_template');
            $this->settingsService->set('certificate_default_template', $tpl, $tpl, 'certificates');
        }

        if ($request->filled('certificate_signatory_name')) {
            $name = (string) $request->input('certificate_signatory_name');
            $this->settingsService->set('certificate_signatory_name', $name, $name, 'certificates');
        }

        if ($request->filled('certificate_signatory_title')) {
            $title = (string) $request->input('certificate_signatory_title');
            $this->settingsService->set('certificate_signatory_title', $title, $title, 'certificates');
        }

        return back()->with('success', __('تم حفظ وتحديث إعدادات المنصة وتفريغ الكاش الفوري بنجاح.'));
    }

    /**
     * Update website CMS content block (Hero, Feedback, About, FAQ, Reviews, Announcement).
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

        $metadata = $block->metadata ?? [];
        if (array_key_exists('metadata', $validated) && is_array($validated['metadata'])) {
            $metadata = array_replace_recursive($metadata, $validated['metadata']);
        }

        // Handle uploaded media files (e.g. hero images/videos, feedback grid images, reviews images)
        if ($request->hasFile('media_files')) {
            $files = $request->file('media_files');
            if (is_array($files)) {
                if ($sectionKey === 'reviews') {
                    $items = $metadata['items'] ?? [];
                    foreach ($files as $idx => $file) {
                        if ($file && $file->isValid()) {
                            $path = $file->store('cms/reviews', 'public');
                            if (is_string($path)) {
                                $items[(int) $idx]['image'] = Storage::url($path);
                            }
                        }
                    }
                    $metadata['items'] = $items;
                } else {
                    $images = $metadata['images'] ?? [];
                    $folder = $sectionKey === 'hero' ? 'cms/hero' : 'cms/feedback';
                    foreach ($files as $idx => $file) {
                        if ($file && $file->isValid()) {
                            $path = $file->store($folder, 'public');
                            if (is_string($path)) {
                                $images[(int) $idx] = Storage::url($path);
                            }
                        }
                    }
                    ksort($images);
                    $metadata['images'] = $images;
                }
            }
        }

        $block->metadata = $metadata;
        $block->is_active = $request->boolean('is_active', true);
        $block->save();

        $this->settingsService->flushCache();

        return back()->with('success', __('تم حفظ وتحديث محتوى القسم وتفريغ الكاش بنجاح.'));
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

    /**
     * Test MUX credentials, API connection, and cryptographic signing keys.
     */
    public function testMuxConnection(MuxVideoService $muxService): JsonResponse
    {
        $result = $muxService->testConnection();

        return response()->json($result);
    }
}
