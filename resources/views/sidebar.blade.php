<aside class="w-64 bg-maroon text-white flex-shrink-0 flex flex-col">

    {{-- LOGO --}}
    <div class="px-5 pt-6 pb-5">
    <img src="{{ asset('images/logo-batamindo.jpg') }}"
         alt="Batamindo Investment Cakrawala"
         class="h-14 w-auto">
</div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 py-2 px-3 space-y-1 text-[13px]">

        @php
            $menu = [
                [
                    'route'   => 'dashboard',
                    'label'   => 'Dashboard',
                    'pattern' => 'dashboard',
                    'icon'    => 'grid',
                ],
                [
                    'route'   => 'penerima.index',
                    'label'   => 'Kelola Penerima',
                    'pattern' => 'penerima*',
                    'icon'    => 'user-card',
                ],
                [
                    'route'   => 'grup.index',
                    'label'   => 'Kelola Grup',
                    'pattern' => 'grup*',
                    'icon'    => 'users',
                ],
                [
                    'route'   => 'email.index',
                    'label'   => 'Buat Email',
                    'pattern' => 'email*',
                    'icon'    => 'mail-dot',
                ],
                [
                    'route'   => 'riwayat',
                    'label'   => 'Riwayat Pengiriman',
                    'pattern' => 'riwayat*',
                    'icon'    => 'history-cog',
                ],
            ];
        @endphp

        @foreach($menu as $m)
            @php
                $isActive = request()->routeIs($m['pattern']);
                $href = Route::has($m['route']) ? route($m['route']) : '#';
            @endphp

            <a href="{{ $href }}"
               class="group relative flex items-center gap-3 px-4 py-3 rounded-md transition
                      {{ $isActive
                          ? 'bg-maroonD text-white'
                          : 'text-white/60 hover:text-white hover:bg-white/[0.06]' }}">

                {{-- STRIP ORANGE KIRI --}}
                @if($isActive)
                    <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1 h-8 bg-gold rounded-r-full"></span>
                @endif

                {{-- ICON --}}
                <span class="w-5 h-5 flex items-center justify-center flex-shrink-0
                             {{ $isActive ? 'text-white' : 'text-white/50 group-hover:text-white/80' }}">
                    @switch($m['icon'])

                        {{-- Grid (Dashboard) --}}
                        @case('grid')
                            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="w-[18px] h-[18px]">
                                <rect x="3.5" y="3.5" width="7" height="7" rx="1"/>
                                <rect x="13.5" y="3.5" width="7" height="7" rx="1"/>
                                <rect x="3.5" y="13.5" width="7" height="7" rx="1"/>
                                <rect x="13.5" y="13.5" width="7" height="7" rx="1"/>
                            </svg>
                            @break

                        {{-- User card (Kelola Penerima) --}}
                        @case('user-card')
                            <svg fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" class="w-[18px] h-[18px]">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="9" cy="11" r="2"/>
                                <path d="M5.5 16.5c.8-1.6 2.4-2.5 3.5-2.5s2.7.9 3.5 2.5"/>
                                <path d="M15 10h4M15 13h3"/>
                            </svg>
                            @break

                        {{-- Users (Kelola Grup) --}}
                        @case('users')
                            <svg fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" class="w-[18px] h-[18px]">
                                <circle cx="9" cy="8" r="3"/>
                                <circle cx="17" cy="9" r="2.3"/>
                                <path d="M3.5 19a5.5 5.5 0 0 1 11 0"/>
                                <path d="M14.5 19a4.5 4.5 0 0 1 6-4"/>
                            </svg>
                            @break

                        {{-- Mail dot (Buat Email) --}}
                        @case('mail-dot')
                            <svg fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" class="w-[18px] h-[18px]">
                                <path d="M3 7.5 12 13l9-5.5"/>
                                <path d="M21 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                <circle cx="16.5" cy="17" r="1.5" fill="currentColor" stroke="none"/>
                            </svg>
                            @break

                        {{-- History cog (Riwayat Pengiriman) --}}
                        @case('history-cog')
                            <svg fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" class="w-[18px] h-[18px]">
                                <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                                <path d="M3 3v5h5"/>
                                <path d="M12 7v5l3 2"/>
                                <circle cx="18.5" cy="18.5" r="1.2"/>
                                <path d="M18.5 16.5v.5M18.5 20v.5M16.5 18.5h.5M20 18.5h.5"/>
                            </svg>
                            @break

                    @endswitch
                </span>

                {{-- LABEL --}}
                <span class="font-medium tracking-wide">{{ $m['label'] }}</span>
            </a>
        @endforeach

    </nav>

    {{-- FOOTER --}}
    <div class="px-5 py-4 text-[10px] text-white/35 border-t border-white/[0.08] tracking-wide">
        BIC MailHub v2.4
    </div>

</aside>