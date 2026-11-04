@props([
    'studentName' => 'Yara Mahmoud Ahmed',
    'courseTitle' => 'Level 1 of the Comprehensive Speech and Communication Course',
    'description' => null,
    'bgUrl' => null,
    'serialNumber' => null,
    'completionDate' => null,
    'class' => '',
])

@php
    $cmsSettings = app(\App\Services\Cms\SettingsService::class);
    $bg = $bgUrl ?: $cmsSettings->getCertificateBg();
    
    $defaultDesc = 'Who has successfully completed ' . ($courseTitle ?: 'Level 1 of the Comprehensive Speech and Communication Course') . ".\nThis level, focusing on Speech Pathology, included the study of speech disorders, voice disorders, and language disorders.";
    $finalDesc = $description ?: $defaultDesc;
@endphp

<div class="relative w-full aspect-[1024/724] select-none rounded-xl overflow-hidden shadow-xl border border-[#DEE8F7] bg-white {{ $class }}" style="container-type: inline-size;" dir="ltr">
    <!-- Template Blank Background -->
    <img 
        src="{{ $bg }}" 
        alt="Al-Ameen Certificate Template" 
        class="absolute inset-0 size-full object-fill pointer-events-none z-0" 
    />

    <!-- Dynamic Overlay Layer (Exact Positioning Matching Canva Master) -->
    <div class="absolute inset-0 z-10 pointer-events-none">
        
        <!-- Recipient Student Name -->
        <div 
            class="absolute text-start font-serif font-black tracking-normal leading-none"
            style="
                left: 36%;
                right: 10%;
                top: 34.5%;
                color: #C59B27;
                font-family: 'Playfair Display', Georgia, Cambria, 'Times New Roman', serif;
                font-size: clamp(14px, 3.6cqw, 36px);
                text-shadow: 0 1px 1px rgba(0,0,0,0.08);
            "
        >
            <span class="truncate block max-w-full pb-1">{{ $studentName }}</span>
        </div>

        <!-- Course Description & Honors -->
        <div 
            class="absolute text-start font-serif leading-[1.5] text-[#1F2937]"
            style="
                left: 36%;
                right: 9%;
                top: 45.5%;
                font-family: 'Source Serif Pro', Georgia, serif;
                font-size: clamp(9px, 1.8cqw, 18px);
                max-height: 29%;
                overflow: hidden;
            "
        >
            <p class="whitespace-pre-line text-slate-800 font-medium">
                {{ $finalDesc }}
            </p>
        </div>

        @if(!empty($serialNumber))
            <!-- Certificate Serial Number (Subtle top/bottom tracking) -->
            <div 
                class="absolute text-[8px] sm:text-[10px] font-mono tracking-widest text-slate-400"
                style="right: 8%; bottom: 3%;"
            >
                ID: {{ $serialNumber }}
            </div>
        @endif

        @if(!empty($completionDate))
            <!-- Issue Date -->
            <div 
                class="absolute text-[9px] sm:text-[11px] font-serif text-slate-500 font-medium"
                style="left: 36%; bottom: 6%;"
            >
                {{ $completionDate }}
            </div>
        @endif

    </div>
</div>

