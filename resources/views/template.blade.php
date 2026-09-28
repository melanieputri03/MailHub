@extends('layout')
@section('title', 'Pilih Template')

@section('content')

{{-- HEADER --}}
<div class="flex items-start justify-between gap-4 mb-5">
    <div class="flex-1 min-w-0">
        <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <path d="M14 2v6h6M8 13h8M8 17h5"/>
            </svg>
            Pilih Template
        </h1>
        <p class="text-[13px] text-muted mt-1 ml-7">Pilih template yang ingin digunakan untuk mengirim email.</p>
    </div>

    <a href="{{ Route::has('email.index') ? route('email.index') : '#' }}"
       class="bg-white border border-outline text-[13px] text-ink px-4 py-2.5 rounded-md flex items-center gap-2
              hover:bg-cream transition flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M19 12H5M11 6l-6 6 6 6"/>
        </svg>
        Kembali
    </a>
</div>

{{-- GRID TEMPLATE --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

    @php
        // Data dummy — nanti dari database (tabel email_templates)
        $templates = [
            [
                'id' => 1,
                'nama' => 'Pengumuman',
                'icon' => '📢',
                'desc' => 'Pengumuman resmi perusahaan',
                'subject' => '[PENGUMUMAN] Informasi Penting',
                'body' => 'Kepada seluruh karyawan, Dalam rangka pelaksanaan kegiatan perusahaan, kami sampaikan informasi penting berikut ini...',
            ],
            [
                'id' => 2,
                'nama' => 'Undangan',
                'icon' => '✉️',
                'desc' => 'Undangan rapat & kegiatan',
                'subject' => '[UNDANGAN] Rapat Koordinasi',
                'body' => 'Kami mengundang Bapak/Ibu untuk hadir dalam rapat koordinasi yang akan dilaksanakan pada Hari/Tanggal: ..., Waktu: ..., Tempat: ...',
            ],
            [
                'id' => 3,
                'nama' => 'Informasi Perusahaan',
                'icon' => '🏢',
                'desc' => 'Informasi operasional & internal',
                'subject' => '[INFORMASI] Update Operasional',
                'body' => 'Berikut informasi operasional terkait kegiatan perusahaan yang perlu diketahui oleh seluruh karyawan...',
            ],
            [
                'id' => 4,
                'nama' => 'Kegiatan / Event',
                'icon' => '🎉',
                'desc' => 'Agustusan, gathering, event',
                'subject' => '[EVENT] Kegiatan Perusahaan',
                'body' => 'Dalam rangka memperingati dan mempererat kebersamaan, perusahaan akan mengadakan kegiatan yang diikuti seluruh karyawan...',
            ],
        ];
    @endphp

    @foreach($templates as $tpl)
        <div class="bg-white border border-outline rounded-lg overflow-hidden hover:shadow-lg hover:border-gold transition flex flex-col">

            {{-- PREVIEW MINI EMAIL --}}
            <div class="bg-cream/40 p-3">
                <div class="bg-white rounded-sm border border-outline/60 overflow-hidden h-[320px] flex flex-col shadow-sm">

                    {{-- HEADER MINI --}}
                    <div class="bg-white px-3 py-2 border-b-2 border-maroon">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-maroon rounded flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-[5px]">BIC</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-maroon font-bold text-[5px] leading-tight truncate">BATAMINDO INDUSTRIAL PARK</div>
                                <div class="text-muted text-[4px] leading-tight truncate">PT BATAMINDO INVESTMENT CAKRAWALA</div>
                            </div>
                            <div class="text-[4px] text-muted text-right flex-shrink-0">
                                {{ date('d M Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- BODY MINI --}}
                    <div class="flex-1 p-3 overflow-hidden">
                        <div class="text-center mb-2">
                            <div class="text-[10px] mb-0.5">{{ $tpl['icon'] }}</div>
                            <div class="text-[8px] font-bold text-maroon uppercase tracking-wider">{{ $tpl['nama'] }}</div>
                        </div>
                        <div class="text-[6px] text-gray-700 leading-relaxed line-clamp-6">
                            {{ $tpl['body'] }}
                        </div>
                    </div>

                    {{-- FOOTER MINI --}}
                    <div class="bg-[#1a1a1a] px-2 py-2">
                        <div class="text-center text-[4px] text-white font-bold mb-0.5">PT BATAMINDO INVESTMENT</div>
                        <div class="text-center text-[3.5px] text-gray-400 leading-tight">
                            Wisma Batamindo Lt. 3<br>
                            Telp: (0778) 431 888
                        </div>
                    </div>
                </div>
            </div>

            {{-- INFO TEMPLATE --}}
            <div class="px-4 pt-3 pb-2 flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[16px]">{{ $tpl['icon'] }}</span>
                    <h3 class="font-semibold text-[13px] text-ink">{{ $tpl['nama'] }}</h3>
                </div>
                <p class="text-[11px] text-muted leading-snug">{{ $tpl['desc'] }}</p>
            </div>

            {{-- TOMBOL PILIH --}}
            <div class="px-4 pb-4">
                <a href="{{ Route::has('email.index') ? route('email.index', ['template' => $tpl['id']]) : '#' }}"
                   class="block w-full text-center bg-gold hover:bg-goldD text-white text-[13px] font-semibold
                          px-4 py-2.5 rounded-md transition shadow-sm">
                    Pilih Template
                </a>
            </div>
        </div>
    @endforeach

</div>

{{-- INFO TAMBAHAN --}}
<div class="mt-6 bg-cream/60 border border-outline rounded-lg p-4 flex items-start gap-3">
    <svg class="w-4 h-4 text-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="9"/>
        <path d="M12 8v5M12 16h.01"/>
    </svg>
    <p class="text-[12px] text-muted leading-relaxed">
        Template diambil langsung dari database perusahaan. Admin dapat menambah template baru kapan saja
        tanpa perlu mengubah kode program.
    </p>
</div>

@endsection