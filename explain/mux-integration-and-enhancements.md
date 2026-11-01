# توثيق تكامل سحابة MUX وخارطة طريق التطوير المتقدم
## In-Depth Analysis of MUX Video Integration and Architectural Enhancements

---

## 1. ما هي خدمة MUX ولماذا تم اختيارها في منصة الأمين؟ (Why MUX?)

تُعد **[MUX Video](https://mux.com)** المنصة السحابية الرائدة عالمياً في تقديم البنية التحتية المتخصصة لخدمات الفيديو الموجهة للمطورين (Video API Platform). تم اختيارها لمنصة الأمين التعليمية للأسباب التالية:

* **البث التكيفي الذكي (HLS Adaptive Bitrate Streaming)**: تقوم MUX تلقائياً بترميز وضغط الفيديو لعدة جودات (1080p, 720p, 480p, 360p) وتوليد ملف تشغيل `playlist.m3u8`، مما يضمن تشغيل المحاضرات فوراً وبدون أي تقطيع لجميع الطلاب حتى على شبكات الجيل الثالث 3G أو الباقات المحدودة.
* **تخفيف العبء عن خوادم المنصة**: يتم استقبال وتخزين وبث الفيديوهات عبر خوادم MUX السحابية الموزعة عالمياً، مما يمنع بطء أو توقف سيرفر المنصة الرئيسي أثناء فترات الامتحانات أو الذروة.
* **واجهات برمجة تطبيقات متطورة (Developer-First APIs)**: إمكانية الرفع المباشر من المتصفح (Direct Uploads) واستقبال إشعارات المعالجة عبر Webhooks فائقة الموثوقية.
* **مشغل فيديو حديث `<mux-player>`**: مشغل ويب أصيل (Custom Web Component) يتميز بالسرعة، الاستجابة، ودعم الهواتف المحمولة والمتصفحات الحديثة تلقائياً.

---

## 2. ما تفعله منصتنا حالياً مع MUX (Current State Deep-Dive)

تتكامل منصة الأمين حالياً مع سحابة MUX عبر بنية برمجية متماسكة تغطي دورة حياة الفيديو من الرفع حتى التشغيل:

```
+---------------------------------------------------------------------------------------------------+
|                                 CURRENT MUX INTEGRATION FLOW                                      |
+---------------------------------------------------------------------------------------------------+
|                                                                                                   |
|  [ 1. التهيئة والإعدادات ]                                                                        |
|    └── config/video.php ──> قراءة MUX_TOKEN_ID, MUX_TOKEN_SECRET, MUX_WEBHOOK_SECRET              |
|                                                                                                   |
|  [ 2. مسارات رفع الفيديو إلى MUX ]                                                                |
|    ├── أ. رفع سيرفري (Server-to-MUX):                                                             |
|    │    تجميع الشرائح ──> فتح Stream للقراءة ──> إرسال PUT مباشر لـ MUX Direct Upload URL          |
|    └── ب. رفع مباشر من المتصفح (Browser Direct Upload):                                           |
|         طلب رابط مشفر POST /admin/mux/direct-upload ──> رفع العميل مباشرة لسحابة MUX             |
|                                                                                                   |
|  [ 3. معالجة الإشعارات والربط التلقائي (Webhook Processing) ]                                     |
|    ├── استقبال طلب POST /webhooks/mux                                                             |
|    ├── التحقق الأمني من توقيع Mux-Signature عبر خوارزمية HMAC-SHA256                             |
|    ├── معالجة حدث video.asset.ready:                                                             |
|    │    استخراج Playback ID والمدة الزمنية ──> تحديث جدول videos أو جدول courses                 |
|    └── معالجة حدث video.upload.asset_created:                                                    |
|         ربط upload_id الداخلي بـ asset_id السحابي                                                 |
|                                                                                                   |
|  [ 4. مشغل العرض وتجربة المتدرب ]                                                                 |
|    ├── تضمين المشغل: <mux-player playback-id="..." stream-type="on-demand">                       |
|    └── رسم العلامة المائية الفيزيائية المتحركة عبر Canvas فوق المشغل لحماية المحتوى               |
+---------------------------------------------------------------------------------------------------+
```

---

### أ. ملف التهيئة والمحرك (`config/video.php` & `MuxDriver`)
يتم تخزين بيانات الاتصال السحابية في ملف الإعدادات:
```php
'mux' => [
    'token_id' => env('MUX_TOKEN_ID'),
    'token_secret' => env('MUX_TOKEN_SECRET'),
    'webhook_secret' => env('MUX_WEBHOOK_SECRET'),
    'base_url' => env('MUX_BASE_URL', 'https://api.mux.com/video/v1'),
    'playback_policy' => ['public'],
    'cors_origin' => env('MUX_CORS_ORIGIN', '*'),
],
```

يقوم الكلاس `App\Services\Video\Drivers\MuxDriver` بتنفيذ عمليات التكامل الأساسية:
1. **فحص التهيئة (`isConfigured`)**: التأكد من وجود `token_id` و `token_secret` قبل توجيه أي طلبات.
2. **إنشاء رابط الرفع المباشر (`createDirectUploadUrl`)**: استدعاء `POST https://api.mux.com/video/v1/uploads` مع توثيق HTTP Basic Auth للحصول على رابط رفع سحابي مؤقت.
3. **الاستعلام عن حالة الأصول (`getAsset` & `getUpload`)**: الاستفسار عن تفاصيل المعالجة وحالة الجودات.
4. **تحليل المدخلات الذكي (`parseInput`)**: التعرف التلقائي على معرف التشغيل Playback ID أو روابط `stream.mux.com/{id}.m3u8` وتصنيف المصدر كـ `mux`.

---

### ب. محرك معالجة إشعارات الويب السحابية (`MuxWebhookController`)
تعتمد المنصة على معمارية معالجة فورية للأحداث الصادرة من MUX:
1. **التحقق من التوقيع الرقمي لمنع التزوير (HMAC-SHA256 Verification)**:
   * تقرأ المنصة ترويسة `Mux-Signature: t=timestamp,v1=hash`.
   * تمنع هجمات إعادة الإرسال (Replay Attacks) عبر رفض أي طلب بفارق زمني يتجاوز 10 دقائق (600 ثانية).
   * تطابق الهاش المتوقع `hash_hmac('sha256', "{$timestamp}.{$rawBody}", $webhookSecret)` مع توقيع MUX باستخدام `hash_equals`.
2. **معالجة الأحداث (Event Handlers)**:
   * **`video.asset.ready`**:
     * يحدث فور انتهاء MUX من ترميز الفيديو بكافة الجودات.
     * يستخرج النظام `playback_id`، `asset_id`، والمدة الزمنية الدقيقة بالثواني `duration`.
     * يتم تحديث سجل المحاضرة المعنية في جدول `videos`، أو فيديو البرومو للكورس في جدول `courses`.
   * **`video.upload.asset_created`**:
     * يربط معرف الرفع المباشر `upload_id` بمعرف الأصل `asset_id` فور استلام MUX للملف وقبل بدء الترميز.
   * **`video.asset.errored`**:
     * يسجل في سجلات الخادم أي خطأ تقني في صيغة أو ترميز الفيديو المرفوع لتنبيه الإدارة.

---

### ج. واجهة العرض والمشغل السحابي (`<mux-player>`)
تستخدم المنصة مكون المشغل الرسمي من MUX في `components/video-watermark-player.blade.php`:
```html
<mux-player 
    playback-id="{{ $muxPlaybackId }}" 
    stream-type="on-demand" 
    controls 
    autoplay="false"
    preload="auto"
    class="w-full h-full absolute inset-0 z-10"
></mux-player>
```
مع دمج طبقة Canvas العائمة بالأعلى لعرض بيانات المتدرب الحية (الاسم، الهاتف، المعرف، عنوان IP، والتوقيت).

---

## 3. تدقيق الثغرات ونقاط القصور في الوضع الحالي (Audit of Current Limitations)

رغم أن التكامل الحالي يعمل بكفاءة عالية ويلبي الاحتياجات الأساسية، إلا أن هناك **7 نقاط حرجة** تحتاج للتطوير للوصول إلى معايير المنصات التعليمية العالمية الفائقة:

### 1. سياسة التشغيل العامة (Public Playback Policy Risk - الأهم أمنياً!)
* **الوضع الحالي**: تم ضبط `playback_policy => ['public']` في `config/video.php`.
* **الخلل الأمني**: هذا يعني أن معرف التشغيل `mux_playback_id` يمكن لأي طالب استخراجه من الـ Source Code، وفتح رابط `https://stream.mux.com/{id}.m3u8` على أي مشغل خارجي (مثل VLC)، أو تحميل الفيديو بالكامل عبر أدوات مثل `yt-dlp` أو إضافات المتصفح، ومشاركته مع أي شخص غير مسجل بالمنصة!
* **الحل المطلوب**: التحول الفوري إلى **Signed Playback IDs** وتوليد رموز JWT موقعة بمفتاح خاص (RS256) تنتهي صلاحيتها خلال ساعات ومربوطة بجلسة الطالب.

### 2. المعالجة التزامنية للـ Webhooks (Synchronous Webhook Execution)
* **الوضع الحالي**: ينفذ `MuxWebhookController` تحديثات قاعدة البيانات والبحث المتعدد مباشرة داخل دورة حياة طلب HTTP القادم من MUX.
* **المخاطرة**: في حال قام المشرف برفع عدة محاضرات في نفس الوقت أو حدث بطء لحظي بقاعدة البيانات، قد يتجاوز الطلب مهلة MUX المقدرة بـ 5 ثوانٍ، مما يؤدي إلى فشل الـ Webhook وإعادة إرساله مراراً.
* **الحل المطلوب**: استقبال الطلب والرد بـ `200 OK` فوراً، مع إرسال مهمة المعالجة إلى طابور خلفي غير متزامن (`Queue Worker`).

### 3. الاعتماد على جودة الترميز الأساسية (`basic` Quality Tier)
* **الوضع الحالي**: يتم إنشاء الرفع بـ `'video_quality' => 'basic'`.
* **القصور**: تحرم هذه القيمة المنصة من ميزات MUX الحديثة مثل الترميز الذكي المتقدم (Smart Per-Title Encoding) أو جودة `plus`، التي تدعم ترميزات الجيل القادم (HEVC و VP9)، مما يقلل استهلاك باقات الإنترنت لدى الطلاب بنسبة 35% مع وضوح أعلى للمصطلحات الطبية الدقيقة.

### 4. غياب تحليلات MUX لمراقبة تجربة المشاهدة (Missing MUX Data / Analytics)
* **الوضع الحالي**: لا يتم تمرير أي ترويسات أو خصائص بيانات من مشغل `<mux-player>`.
* **القصور**: لا تملك الإدارة أية بيانات حول:
  * نسبة التقطيع (Rebuffering Percentage) لدى الطلاب في مختلف المحافظات.
  * وقت بدء التشغيل (Video Startup Time).
  * خروج الطالب قبل بدء الفيديو (Exit Before Video Starts - EBVS).
  * الخريطة الحرارية للمشاهدة وتحديد أجزاء المحاضرة الأكثر إعادة أو تخطياً.

### 5. غياب التفريغ الصوتي والترجمة التلقائية (Missing AI Subtitles & Transcriptions)
* **الوضع الحالي**: لا تطلب المنصة توليد نصوص أو ترجمة مصاحبة أثناء رفع الفيديو إلى MUX.
* **القصور**: في التعليم الطبي، تمثل المصطلحات اللاتينية والإنجليزية تحدياً كبيراً. توفر MUX حالياً ميزة النسخ الصوتي التلقائي المعتمد على Whisper AI، وتفعيلها سيمكن الطلاب من القراءة أثناء الاستماع والبحث في نصوص المحاضرات.

### 6. غياب شريط المعاينة التفاعلي المصغر (No Storyboard Scrubbing)
* **الوضع الحالي**: شريط التقدم في المشغل لا يعرض صوراً مصغرة (Thumbnails) للمشاهد عند تمرير الماوس فوق الشريط.
* **القصور**: يضطر الطالب لتقديم الفيديو عشوائياً للبحث عن حالة سريرية معينة بدلاً من رؤية معاينة مرئية سريعة.

### 7. عدم وجود غرف للبث السريري المباشر (No Live Streaming Integration)
* **الوضع الحالي**: المنصة تدعم فقط الفيديو المسجل مسبقاً (Video-On-Demand - VOD).
* **الفرصة المتاحة**: تدعم MUX إطلاق بث حي مباشر فائق السرعة (Low-Latency Live Streaming) عبر بروتوكولات RTMP/SRT مع حفظ البث تلقائياً كمحاضرة مسجلة بجودة عالية فور انتهاء البث.

---

## 4. خارطة طريق التطوير والتحديث الشامل (Actionable Enhancement Blueprint)

---

### المحور الأول: تأمين الفيديوهات بواسطة مفاتيح التوقيع المشفرة (Signed Playback Tokens)

بدلاً من جعل رابط MUX عاماً للجميع، نستخدم نظام الرموز الموقعة رقمياً بخوارزمية **RS256**:

```
+---------------------------------------------------------------------------------------------------+
|                            SIGNED PLAYBACK TOKENS ARCHITECTURE                                    |
+---------------------------------------------------------------------------------------------------+
|                                                                                                   |
|  [ 1. إعداد مفتاح التوقيع في لوحة MUX ]                                                           |
|       توليد Signing Key Pair ──> حفظ MUX_SIGNING_KEY_ID و MUX_PRIVATE_KEY_BASE64 في المنصة       |
|                                                                                                   |
|  [ 2. تدفق طلب مشاهدة المحاضرة ]                                                                  |
|       طالب مسجل ──> فحص صلاحية الوصول والقفل التسلسلي ──> توليد JWT Token مؤقت (صالح لساعتين)    |
|       └── Payload: { sub: playback_id, aud: 'v', exp: time() + 7200, kid: key_id }                |
|                                                                                                   |
|  [ 3. تزويد المشغل بالرمز الموقع ]                                                               |
|       <mux-player playback-id="..." tokens:playback="{{ $jwtToken }}"></mux-player>               |
|                                                                                                   |
|  [ 4. التحقق السحابي في خوادم MUX ]                                                               |
|       MUX تفحص التوقيع بالمفتاح العام ──> إن كان سليماً وسارياً يتم البث، وإلا يتم الحجب 403      |
+---------------------------------------------------------------------------------------------------+
```

#### خطة التنفيذ البرمجية لخدمة التوقيع:
إنشاء فئة مخصصة `App\Services\Video\MuxTokenSigner`:
```php
<?php

declare(strict_types=1);

namespace App\Services\Video;

use Firebase\JWT\JWT;

class MuxTokenSigner
{
    protected ?string $signingKeyId;
    protected ?string $privateKey;

    public function __construct()
    {
        $this->signingKeyId = config('video.drivers.mux.signing_key_id');
        $rawKey = config('video.drivers.mux.private_key');
        $this->privateKey = $rawKey ? base64_decode($rawKey) : null;
    }

    /**
     * Generate RS256 Signed JWT for MUX Playback.
     */
    public function generatePlaybackToken(string $playbackId, int $expiresIn = 7200): ?string
    {
        if (! $this->signingKeyId || ! $this->privateKey) {
            return null; // Fallback to public if keys are not set
        }

        $now = time();
        $payload = [
            'sub' => $playbackId,
            'aud' => 'v', // 'v' for video playback, 't' for thumbnails, 's' for storyboards
            'exp' => $now + $expiresIn,
            'kid' => $this->signingKeyId,
        ];

        return JWT::encode($payload, $this->privateKey, 'RS256', $this->signingKeyId);
    }

    /**
     * Generate Signed Token for Storyboard Scrubbing thumbnails.
     */
    public function generateStoryboardToken(string $playbackId, int $expiresIn = 7200): ?string
    {
        if (! $this->signingKeyId || ! $this->privateKey) {
            return null;
        }

        return JWT::encode([
            'sub' => $playbackId,
            'aud' => 's',
            'exp' => time() + $expiresIn,
            'kid' => $this->signingKeyId,
        ], $this->privateKey, 'RS256', $this->signingKeyId);
    }
}
```

---

### المحور الثاني: المعالجة غير المتزامنة لـ Webhooks عبر طوابير المهام (Queued Webhooks)

لضمان سرعة استجابة المنصة واستقرارها حتى أثناء رفع عشرات الفيديوهات دفعة واحدة:

1. الرد الفوري على MUX بالرمز `200 OK` خلال أجزاء من الثانية.
2. تفويض المعالجة لمهمة مجدولة `ProcessMuxWebhookJob` مع إعادة المحاولة تلقائياً (Automatic Retry) حتى 3 مرات في حال انشغال قاعدة البيانات.

```php
// في MuxWebhookController:
public function handle(Request $request): JsonResponse
{
    // 1. تحقق سريع من التوقيع
    if (! $this->verifySignature(...)) {
        return response()->json(['error' => 'Invalid signature'], 401);
    }

    // 2. إرسال المهمة للطابور الخلفي
    dispatch(new \App\Jobs\ProcessMuxWebhookJob($request->json()->all()));

    // 3. تأكيد فوري بالاستلام لسحابة MUX
    return response()->json(['status' => 'queued'], 200);
}
```

---

### المحور الثالث: تفعيل تحليلات الأداء وتجربة المشاهدة (MUX Data & Viewer Analytics)

يتضمن مشغل `<mux-player>` دعماً مدمجاً لمراقبة جودة التجربة (QoE). نقوم بتمرير بيانات الجلسة تلقائياً:

```html
<mux-player 
    playback-id="{{ $video->mux_playback_id }}"
    stream-type="on-demand"
    controls
    preload="auto"
    {{-- MUX Data Telemetry Metadata --}}
    metadata-video-id="video_{{ $video->id }}"
    metadata-video-title="{{ $video->title_ar }}"
    metadata-viewer-user-id="user_{{ auth()->id() }}"
    metadata-experiment-name="alameen-medical-v1"
    class="w-full h-full absolute inset-0 z-10"
></mux-player>
```

#### الفوائد التشغيلية المكتسبة:
* **لوحة تحكم إحصائية حية في حساب MUX**: معرفة ما إذا كان طلاب مدينة أو مزود إنترنت معين يعانون من بطء أو تقطيع.
* **رصد الأجهزة والمتصفحات**: معرفة نسب مشاهدة المحاضرات عبر الهواتف الذكية مقابل الحواسيب المكتبية لتوجيه تجربة الاستخدام.
* **اكتشاف الأعطال قبل شكاوى الطلاب**: تلقي تنبيهات عند حدوث أي خطأ في فك ترميز ملفات الفيديو.

---

### المحور الرابع: النسخ الصوتي الطبي والترجمة الذكية (Auto-Generated Medical Captions)

تتيح MUX توليد الترجمة المصاحبة للمحاضرات تلقائياً فور رفع الفيديو. نقوم بتحديث إعدادات إنشاء الأصل السحابي في `MuxDriver`:

```php
// في دالة createDirectUploadUrl:
'new_asset_settings' => [
    'playback_policy' => ['signed'], // تحويل السياسة إلى مشفرة
    'video_quality' => 'basic',
    'generated_subtitles' => [
        [
            'name' => 'English Transcription',
            'passthrough' => 'Medical Lecture Transcription',
            'language_code' => 'en',
        ],
    ],
],
```

#### القيمة التعليمية للمتدربين:
* إظهار الترجمة والمصطلحات الطبية كتابياً أسفل الفيديو داخل مشغل `<mux-player>`.
* تمكين المتدربين من البحث اللحظي عن كلمة أو عرض مرضي داخل نص المحاضرة والقفز إلى الثانية المحددة التي نُطق بها المصطلح!

---

### المحور الخامس: تفعيل شريط المعاينة المرئي (Storyboard & Scrubbing Previews)

توفر MUX تلقائياً شبكة صور مصغرة (VTT Storyboard) تمثل شرائح الفيديو عبر الزمن:
* رابط المعاينة التلقائي: `https://image.mux.com/{PLAYBACK_ID}/storyboard.vtt`
* المشغل `<mux-player>` يدعم هذه الميزة بصورة مدمجة تماماً؛ فعند تحريك الماوس أو إصبع المتدرب على شريط التقدم، تظهر شاشة منبثقة مصغرة تكشف محتوى الثانية المقصودة دون الحاجة للانتظار.
* توليد أغلفة المحاضرات الديناميكية تلقائياً بدلاً من الحاجة لتصميم بوستر لكل محاضرة:
  `https://image.mux.com/{PLAYBACK_ID}/thumbnail.jpg?time=15&width=640`

---

### المحور السادس: البث الحي التفاعلي للحالات والعمليات السريرية (MUX Live Streaming)

لإطلاق ورش عمل جراحية أو محاضرات تفاعلية مباشرة:
1. **إنشاء مسار للبث المباشر (Create Live Stream)**:
   * تطلب المنصة عبر API من MUX إنشاء نقطة بث حي: `POST /video/v1/live-streams`.
   * توفر MUX للمحاضر مفتاح بث RTMP مشفر (Stream Key) وخادم بث مباشر لربطه ببرنامج مثل OBS Studio.
2. **البث اللحظي للطلاب**:
   * يشاهد الطلاب البث عبر مشغل `<mux-player stream-type="live">` بتأخير لا يتجاوز 3 ثوانٍ (Low Latency).
3. **الأرشفة والتحويل التلقائي لمحاضرة VOD**:
   * فور انتهاء المحاضر من البث، تقوم MUX تلقائياً بإنهاء البث وحفظ المحاضرة كأصل دائم (VOD Asset) بنفس معايير الحماية والقفل التسلسلي، وترسل إشعار `video.asset.ready` ليربطه النظام بالدورة مباشرة.

---

## 5. مصفوفة التحول وخطة التنفيذ الزمنية (Evolution Roadmap Matrix)

| المرحلة | الخاصية المستهدفة | الملفات المتأثرة | الأثر والنتيجة المتوقعة |
| :--- | :--- | :--- | :--- |
| **المرحلة 1 (الأولوية القصوى)** | تفعيل الرموز الموقعة (Signed Playback IDs) | `config/video.php`<br>`MuxDriver.php`<br>`MuxTokenSigner.php`<br>`lesson.blade.php` | القضاء التام على إمكانية تحميل أو تسريب روابط MUX خارج المنصة؛ كل رابط مشفر بصلاحية ساعتين فقط. |
| **المرحلة 2 (الموثوقية)** | تحويل الـ Webhooks لطوابير غير متزامنة (Queued Jobs) | `MuxWebhookController.php`<br>`ProcessMuxWebhookJob.php` | ضمان سرعة استجابة الخادم بنسبة 100% ومنع أي فقدان لإشعارات جاهزية الفيديو أثناء الرفع المتعدد. |
| **المرحلة 3 (تجربة المتدرب)** | شريط المعاينة المصغر (Storyboard Scrubbing) | `<mux-player>` Blade Component | تسهيل تصفح المحاضرات الطبية الطويلة واستعراض اللقطات السريرية بمجرد تمرير المؤشر. |
| **المرحلة 4 (ذكاء الأعمال)** | تفعيل تحليلات MUX Data Telemetry | `<mux-player>` Props<br>`User` Metadata | الحصول على مؤشرات أداء تفصيلية حول سرعة البث، التقطيع، وسلوك مشاهدة المتدربين. |
| **المرحلة 5 (المحتوى الطبي)** | التفريغ والترجمة الصوتية التلقائية بـ AI | `MuxDriver.php` (`generated_subtitles`) | تعزيز استيعاب الطلاب للمصطلحات الإكلينيكية وإمكانية البحث في المادة العلمية. |
| **المرحلة 6 (التوسع)** | غرف البث السريري المباشر (MUX Live Streams) | `MuxLiveStreamService.php`<br>لوحة المشرف وواجهة الطالب | تقديم ورش عمل وبث مباشر للعمليات والحالات السريرية مع أرشفة فورية بالمقرر. |
