<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MailHub Control Room')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon:  '#803033',
                        maroonD: '#6B282B',
                        cream:   '#F5ECEA',
                        gold:    '#FF8C00',
                        goldD:   '#E67A00',
                        ink:     '#202124',
                        muted:   '#6B7280',
                        outline: '#E0D5D2',
                    },
                    fontFamily: { sans: ['Inter','system-ui','sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-batamindo.jpg') }}">

    <style>
        html, body { font-family: 'Inter', system-ui, sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb { background: #E0D5D2; border-radius: 4px; }
        ::-webkit-scrollbar-track { background: #F5ECEA; }
        aside::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); }
        aside::-webkit-scrollbar-track { background: transparent; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.2s ease-out; }
    </style>

    @stack('styles')
</head>
<body class="bg-cream text-ink antialiased">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    @include('sidebar')

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">

        {{-- HEADER --}}
        <header class="bg-white border-b border-outline px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-batamindo.jpg') }}"
                     alt="Batamindo" class="h-6 w-auto">
                <h1 class="text-ink font-semibold text-[15px] tracking-tight">MailHub Control Room</h1>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" title="Refresh"
                        class="w-8 h-8 flex items-center justify-center text-muted hover:text-ink rounded-md hover:bg-cream transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M4 12a8 8 0 1 0 2.3-5.7L4 8"/><path d="M4 3v5h5"/>
                    </svg>
                </button>
            </div>
        </header>

        {{-- FLASH MESSAGE --}}
        @if(session('success') || session('error'))
            <div class="px-6 pt-4">
                @if(session('success'))
                    <div class="fade-in flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 text-[13px] px-4 py-3 rounded-md">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="fade-in flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-[13px] px-4 py-3 rounded-md mt-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>
        @endif

        {{-- CONTENT --}}
        <main class="flex-1 p-6 overflow-auto">
            @yield('content')
        </main>

        {{-- FOOTER (di dalam area main — sejajar header) --}}
        <footer class="bg-white border-t border-outline px-6 py-3 flex justify-between items-center text-[11px] text-muted flex-shrink-0">
            <span>© {{ date('Y') }} Batamindo Investment Cakrawala — BIC MailHub v2.4</span>
            <span>Sistem internal. Tidak untuk didistribusikan.</span>
        </footer>

    </div>
</div>

@stack('scripts')
</body>
</html>