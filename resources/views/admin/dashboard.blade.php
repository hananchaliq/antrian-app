<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>MorbisQueue — Panel Admin Antrian</title>
   <link rel="icon" type="image/svg+xml"
      href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%23121C16'/%3E%3Crect x='16' y='24' width='6' height='22' rx='3' fill='%2334D399'/%3E%3Crect x='29' y='16' width='6' height='30' rx='3' fill='%2310B981'/%3E%3Crect x='42' y='28' width='6' height='18' rx='3' fill='%23A7F3D0'/%3E%3C/svg%3E">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link
      href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=DM+Mono:wght@500&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Outfit:wght@600;700;800&display=swap"
      rel="stylesheet">
   <script src="https://cdn.tailwindcss.com"></script>
   <script>
      tailwind.config = {
         darkMode: 'class',
         theme: {
            extend: {
               fontFamily: {
                  sans: ['"DM Sans"', 'sans-serif'],
                  display: ['"Outfit"', 'sans-serif'],
                  ticket: ['"Bricolage Grotesque"', 'sans-serif'],
                  mono: ['"DM Mono"', 'monospace'],
               }
            }
         }
      };

      if (localStorage.getItem('morbis_theme') === 'dark') {
         document.documentElement.classList.add('dark');
      }
   </script>
   <style>
      :root {
         color-scheme: light;
         --sb-track: rgba(220, 232, 222, 0.75);
         --sb-thumb: #3d795a;
         --sb-thumb-hover: #28563f;
      }

      html.dark {
         color-scheme: dark;
         --sb-track: #0d1912;
         --sb-thumb: #10b981;
         --sb-thumb-hover: #34d399;
      }

      @supports not selector(::-webkit-scrollbar) {

         html,
         * {
            scrollbar-width: thin;
            scrollbar-color: var(--sb-thumb) var(--sb-track);
         }
      }

      ::-webkit-scrollbar {
         width: 7px;
         height: 7px;
      }

      ::-webkit-scrollbar-track {
         background: var(--sb-track);
         border-radius: 9999px;
      }

      ::-webkit-scrollbar-thumb {
         background-color: var(--sb-thumb);
         border-radius: 9999px;
         border: 1.5px solid transparent;
         background-clip: content-box;
      }

      ::-webkit-scrollbar-thumb:hover {
         background-color: var(--sb-thumb-hover);
      }

      .bg-sage-grid {
         background-color: #559371;
         background-image:
            linear-gradient(to right, rgba(255, 255, 255, 0.14) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.14) 1px, transparent 1px),
            radial-gradient(circle at 28% 8%, #a6d1b0 0%, transparent 45%),
            linear-gradient(180deg, #7cb690 0%, #3d795a 52%, #68a581 100%);
         background-size: 44px 44px, 44px 44px, 100% 100%, 100% 100%;
         background-attachment: fixed;
      }

      .dark .bg-sage-grid {
         background-color: #09130e;
         background-image:
            linear-gradient(to right, rgba(74, 222, 128, 0.07) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(74, 222, 128, 0.07) 1px, transparent 1px),
            radial-gradient(circle at 28% 8%, #163323 0%, transparent 50%),
            linear-gradient(180deg, #0f2218 0%, #08120d 55%, #0c1b13 100%);
         background-size: 44px 44px, 44px 44px, 100% 100%, 100% 100%;
         background-attachment: fixed;
      }

      @keyframes enterUp {
         from {
            opacity: 0;
            transform: translateY(8px);
         }

         to {
            opacity: 1;
            transform: translateY(0);
         }
      }

      @keyframes barPulse {

         0%,
         100% {
            transform: scaleY(1);
         }

         50% {
            transform: scaleY(0.6);
         }
      }

      .animate-enter {
         animation: enterUp 0.36s cubic-bezier(0.16, 1, 0.3, 1) both;
      }

      .delay-1 {
         animation-delay: 0.05s;
      }

      .delay-2 {
         animation-delay: 0.1s;
      }

      .delay-3 {
         animation-delay: 0.15s;
      }

      .bar-item {
         transform-origin: bottom;
         animation: barPulse 2.6s ease-in-out infinite;
      }

      .bar-item:nth-child(2) {
         animation-delay: 0.25s;
      }

      .bar-item:nth-child(3) {
         animation-delay: 0.5s;
      }

      .bar-item:nth-child(4) {
         animation-delay: 0.75s;
      }

      .no-scrollbar::-webkit-scrollbar {
         display: none;
      }

      .no-scrollbar {
         -ms-overflow-style: none;
         scrollbar-width: none;
      }
   </style>
</head>

<body
   class="bg-sage-grid font-sans text-slate-900 dark:text-slate-100 min-h-screen p-0 sm:p-3 md:p-4 transition-colors duration-300 antialiased">

   @php
      $totalCount = $queues->count();
      $waitingCount = $queues->where('status', 'menunggu')->count();
      $completedCount = $queues->where('status', 'selesai')->count();

      $mapQueue = fn($q) => [
         'formatted_number' => $q->formatted_number,
         'status' => $q->status,
         'date_label' => $q->queue_date ? $q->queue_date->translatedFormat('d M Y') : '-',
         'created_time' => $q->created_at ? $q->created_at->format('H:i:s') : '-',
         'called_time' => $q->called_at ? $q->called_at->format('H:i:s') : '-',
         'admin_name' => $q->admin ? $q->admin->name : '-',
      ];

      $todayJson = $queues->map($mapQueue)->values();
      $historyJson = ($historyQueues ?? collect())->map($mapQueue)->values();
    @endphp

   <!-- Backdrop Drawer Sidebar -->
   <div id="sidebar-backdrop"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity"></div>

   <div
      class="max-w-[1440px] mx-auto min-h-screen sm:min-h-[calc(100vh-1.5rem)] md:min-h-[calc(100vh-2rem)] flex gap-3.5 relative">

      <!-- Sidebar Kiri -->
      <aside id="sidebar"
         class="fixed lg:static inset-y-2 left-2 z-40 w-64 bg-[#0D1912]/95 lg:bg-[#0D1912]/[0.97] backdrop-blur-md text-white rounded-2xl p-4 flex flex-col justify-between border border-white/15 shadow-2xl transform -translate-x-[112%] lg:translate-x-0 transition-transform duration-300 ease-out">
         <div class="space-y-6">
            <div class="px-2 pt-1 flex items-center justify-between">
               <div class="flex items-center gap-2.5">
                  <div
                     class="w-8 h-8 rounded-lg bg-[#16261D] border border-emerald-500/30 flex items-center justify-center shrink-0">
                     <svg class="w-4 h-4" viewBox="0 0 32 32" fill="none">
                        <rect x="6" y="12" width="3.5" height="12" rx="1.75" fill="#34D399" />
                        <rect x="14.25" y="7" width="3.5" height="17" rx="1.75" fill="#10B981" />
                        <rect x="22.5" y="14" width="3.5" height="10" rx="1.75" fill="#A7F3D0" />
                     </svg>
                  </div>
                  <span class="font-display font-bold text-base tracking-tight">MorbisQueue</span>
               </div>
               <button id="close-sidebar" type="button" aria-label="Tutup Menu"
                  class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white active:scale-95 transition cursor-pointer">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
               </button>
            </div>

            <div class="bg-[#15241B] border border-emerald-500/20 rounded-xl p-3 flex items-center gap-3">
               <div
                  class="w-9 h-9 rounded-lg bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400 shrink-0">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
               </div>
               <div class="min-w-0">
                  <p class="font-display text-[10px] uppercase tracking-wider text-emerald-400/80 font-semibold">Unit
                     Layanan</p>
                  <p class="text-xs font-semibold text-white truncate">Loket Pelayanan Utama</p>
               </div>
            </div>

            <!-- Navigasi -->
            <div class="space-y-1.5">
               <p class="px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-500">Navigasi</p>

               <button type="button" data-nav-dataset="today"
                  class="nav-dataset-btn w-full flex items-center justify-between px-3 py-3 rounded-xl bg-[#1A2C21] text-white text-xs font-semibold border border-emerald-500/20 cursor-pointer transition">
                  <span class="flex items-center gap-2.5">
                     <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                           d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                     </svg>
                     <span>Antrian Hari Ini</span>
                  </span>
                  <span
                     class="font-ticket tabular-nums text-[11px] font-bold text-emerald-400">{{ $todayJson->count() }}</span>
               </button>

               <button type="button" data-nav-dataset="history"
                  class="nav-dataset-btn w-full flex items-center justify-between px-3 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-xs font-medium border border-transparent cursor-pointer transition">
                  <span class="flex items-center gap-2.5">
                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                           d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     <span>Riwayat Antrian</span>
                  </span>
                  <span
                     class="font-ticket tabular-nums text-[11px] font-bold opacity-75">{{ $historyJson->count() }}</span>
               </button>

               <a href="{{ route('queue.index') }}" target="_blank"
                  class="flex items-center justify-between px-3 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-xs font-medium transition-colors">
                  <span class="flex items-center gap-2.5">
                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                           d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                     </svg>
                     <span>Layar Monitor Publik</span>
                  </span>
                  <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                  </svg>
               </a>
            </div>
         </div>

         <!-- Profil Petugas & Logout -->
         <div class="pt-4 border-t border-white/10 space-y-3">
            <div class="px-2 flex items-center gap-3">
               <div
                  class="font-display w-9 h-9 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 font-bold text-xs flex items-center justify-center shrink-0">
                  {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
               </div>
               <div class="min-w-0">
                  <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                  <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
               </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
               @csrf
               <button type="submit"
                  class="w-full py-2.5 px-3 rounded-xl bg-white/5 hover:bg-red-500/15 border border-white/10 hover:border-red-500/30 text-xs font-medium text-slate-300 hover:text-red-300 flex items-center justify-center gap-2 transition-all cursor-pointer">
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  <span>Keluar Sesi</span>
               </button>
            </form>
         </div>
      </aside>

      <!-- Area Konten Utama -->
      <div
         class="flex-1 min-w-0 bg-[#F5F7F6]/85 dark:bg-[#0B110E]/85 sm:bg-[#F5F7F6]/[0.96] sm:dark:bg-[#0B110E]/[0.96] backdrop-blur-md rounded-none sm:rounded-2xl border-0 sm:border border-white/40 dark:border-[#1B2821] shadow-2xl flex flex-col overflow-hidden transition-colors duration-300">

         <!-- Header Dashboard -->
         <header
            class="sticky top-0 z-20 px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4 bg-white/90 dark:bg-[#101814]/90 sm:bg-white/[0.96] sm:dark:bg-[#101814]/[0.96] backdrop-blur-md border-b border-slate-200/80 dark:border-[#1C2A22] flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
               <button id="open-sidebar" type="button" aria-label="Buka Menu"
                  class="lg:hidden p-2.5 rounded-xl bg-white dark:bg-[#16221B] border border-slate-200 dark:border-[#24352B] text-slate-700 dark:text-slate-200 active:scale-95 transition cursor-pointer shrink-0">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
               </button>
               <div class="min-w-0">
                  <h1
                     class="font-display text-base sm:text-xl font-bold tracking-tight text-slate-900 dark:text-white truncate">
                     Dashboard</h1>
                  <p class="text-[11px] sm:text-xs font-medium text-slate-500 dark:text-slate-400 truncate">
                     {{ now()->translatedFormat('l, d F Y') }}</p>
               </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
               <button id="theme-toggle" type="button" aria-label="Ubah Tema"
                  class="p-2.5 rounded-xl bg-white dark:bg-[#16221B] border border-slate-200 dark:border-[#24352B] text-slate-600 dark:text-slate-300 hover:border-emerald-500/40 active:scale-95 transition-all cursor-pointer">
                  <svg class="w-4 h-4 hidden dark:block text-emerald-400" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                  <svg class="w-4 h-4 block dark:hidden text-slate-700" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                  </svg>
               </button>

               <a href="{{ route('queue.index') }}" target="_blank"
                  class="px-3 sm:px-3.5 py-2.5 rounded-xl bg-[#121C16] hover:bg-[#1B2B22] dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-[#08110C] text-xs font-bold active:scale-95 transition-all">
                  Layar Publik
               </a>
            </div>
         </header>

         <!-- Konten Dashboard -->
         <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">

            @if (session('status'))
               <div
                  class="animate-enter p-3.5 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-xs font-medium text-emerald-900 dark:text-emerald-300 flex items-center justify-between gap-2">
                  <span>{{ session('status') }}</span>
                  <span class="font-mono text-[11px] opacity-75 shrink-0">{{ now()->format('H:i:s') }}</span>
               </div>
            @endif

            <!-- Baris Atas: 3 Kartu Utama -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-5">

               <!-- Kartu 1: Dark Hero Card Sedang Dilayani -->
               <div
                  class="md:col-span-6 lg:col-span-4 animate-enter bg-[#121C16]/[0.97] dark:bg-[#131F18]/[0.97] text-white rounded-2xl p-5 sm:p-6 border border-[#223429] shadow-lg flex items-center justify-between">
                  <div>
                     <span class="text-xs font-semibold text-slate-400">Sedang Dilayani</span>
                     <div
                        class="font-ticket tabular-nums mt-1 sm:mt-1.5 text-5xl sm:text-6xl font-extrabold tracking-tight">
                        {{ $current ? $current->formatted_number : '---' }}
                     </div>
                     <p class="mt-1.5 sm:mt-2 text-xs text-emerald-400 font-medium">
                        {{ $current ? 'Aktif di loket pelayanan' : 'Belum ada nomor aktif' }}
                     </p>
                  </div>
                  <div class="flex items-end gap-1.5 h-12 sm:h-14 px-1">
                     <span class="bar-item w-1.5 h-9 bg-emerald-400 rounded-full"></span>
                     <span class="bar-item w-1.5 h-5 bg-emerald-500/35 rounded-full"></span>
                     <span class="bar-item w-1.5 h-12 bg-emerald-400 rounded-full"></span>
                     <span class="bar-item w-1.5 h-7 bg-emerald-500/35 rounded-full"></span>
                  </div>
               </div>

               <!-- Kartu 2: Kontrol Navigasi Next / Prev -->
               <div
                  class="md:col-span-6 lg:col-span-5 animate-enter delay-1 bg-white/[0.97] dark:bg-[#111915]/[0.97] rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-[#1E2C24] shadow-sm flex flex-col justify-between gap-4">
                  <div>
                     <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Kontrol Pemanggilan</span>
                     <h2 class="font-display text-base font-bold tracking-tight text-slate-900 dark:text-white mt-1">
                        Navigasi Antrian</h2>
                  </div>

                  <div class="grid grid-cols-2 gap-3">
                     <form method="POST" action="{{ route('admin.queue.prev') }}">
                        @csrf
                        <button type="submit"
                           class="w-full py-3.5 px-3 sm:px-4 rounded-xl bg-slate-100 hover:bg-slate-200/80 dark:bg-[#18241D] dark:hover:bg-[#1F2E25] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-[#26382E] text-xs sm:text-sm font-bold flex items-center justify-center gap-1.5 sm:gap-2 transition-all active:scale-[0.98] cursor-pointer">
                           <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                              stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                           </svg>
                           <span>Sebelumnya</span>
                        </button>
                     </form>

                     <form method="POST" action="{{ route('admin.queue.next') }}">
                        @csrf
                        <button type="submit"
                           class="w-full py-3.5 px-3 sm:px-4 rounded-xl bg-[#121C16] hover:bg-[#1C2C23] dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-[#08110C] text-xs sm:text-sm font-bold flex items-center justify-center gap-1.5 sm:gap-2 shadow-sm transition-all active:scale-[0.98] cursor-pointer">
                           <span>Berikutnya</span>
                           <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                              stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                           </svg>
                        </button>
                     </form>
                  </div>
               </div>

               <!-- Kartu 3: Kartu Sage Ringkasan Hari Ini -->
               <div
                  class="md:col-span-12 lg:col-span-3 animate-enter delay-2 bg-[#DCE8DE]/[0.96] dark:bg-[#14221A]/[0.96] rounded-2xl p-5 border border-[#C9DBC9] dark:border-[#21362A] flex flex-col justify-between gap-3">
                  <div class="flex items-baseline justify-between">
                     <div>
                        <span
                           class="font-ticket tabular-nums text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalCount }}</span>
                        <span
                           class="font-display text-[11px] font-bold uppercase text-slate-600 dark:text-slate-400 ml-1">Tiket</span>
                     </div>
                     <span class="text-[11px] font-semibold text-emerald-800 dark:text-emerald-400">Hari Ini</span>
                  </div>

                  <div class="grid grid-cols-2 lg:grid-cols-1 gap-2">
                     <div
                        class="bg-white/95 dark:bg-[#0E1812]/95 rounded-xl px-3.5 py-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-600 dark:text-slate-400 font-medium">Menunggu</span>
                        <span
                           class="font-ticket tabular-nums font-extrabold text-slate-900 dark:text-white">{{ $waitingCount }}</span>
                     </div>
                     <div
                        class="bg-white/95 dark:bg-[#0E1812]/95 rounded-xl px-3.5 py-2.5 flex items-center justify-between text-xs">
                        <span class="text-slate-600 dark:text-slate-400 font-medium">Selesai</span>
                        <span
                           class="font-ticket tabular-nums font-extrabold text-emerald-700 dark:text-emerald-400">{{ $completedCount }}</span>
                     </div>
                  </div>
               </div>

            </div>

            <!-- Baris Bawah: Data Antrian -->
            <section id="data-section"
               class="animate-enter delay-3 bg-white/[0.97] dark:bg-[#111915]/[0.97] rounded-2xl border border-slate-200/80 dark:border-[#1E2C24] shadow-sm overflow-hidden">

               <!-- Header Tab Pemisah Hari Ini vs Riwayat & Pencarian -->
               <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-[#1E2C24] space-y-4">
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                     <div
                        class="inline-flex items-center p-1 rounded-xl bg-slate-100 dark:bg-[#0C130F] border border-slate-200/70 dark:border-[#1E2C24] self-start">
                        <button type="button" data-dataset="today"
                           class="dataset-tab px-3.5 py-2 rounded-lg font-display text-xs font-bold bg-[#121C16] text-white dark:bg-emerald-500 dark:text-[#08110C] shadow-xs transition cursor-pointer flex items-center gap-2">
                           <span>Data Hari Ini</span>
                           <span
                              class="font-ticket tabular-nums text-[11px] font-extrabold px-1.5 py-0.2 rounded bg-white/15 dark:bg-black/15">{{ $todayJson->count() }}</span>
                        </button>
                        <button type="button" data-dataset="history"
                           class="dataset-tab px-3.5 py-2 rounded-lg font-display text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition cursor-pointer flex items-center gap-2">
                           <span>Data Riwayat</span>
                           <span
                              class="font-ticket tabular-nums text-[11px] font-bold px-1.5 py-0.2 rounded bg-slate-200/80 dark:bg-white/10">{{ $historyJson->count() }}</span>
                        </button>
                     </div>

                     <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-64">
                           <svg
                              class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                              fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                           </svg>
                           <input id="search-input" type="text" placeholder="Cari nomor, petugas, tanggal..."
                              class="w-full pl-9 pr-8 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-[#0C130F] border border-slate-200 dark:border-[#24352B] rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-emerald-500 transition">
                           <button id="clear-search" type="button" aria-label="Hapus pencarian"
                              class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 dark:hover:text-white cursor-pointer">
                              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                           </button>
                        </div>

                        <select id="per-page-select" aria-label="Jumlah data per halaman"
                           class="font-ticket tabular-nums px-2.5 py-2 text-xs font-bold bg-slate-50 dark:bg-[#0C130F] border border-slate-200 dark:border-[#24352B] rounded-xl text-slate-800 dark:text-slate-200 focus:outline-none focus:border-emerald-500 cursor-pointer shrink-0">
                           <option value="5" selected>5 / hal</option>
                           <option value="10">10 / hal</option>
                           <option value="15">15 / hal</option>
                           <option value="20">20 / hal</option>
                        </select>
                     </div>
                  </div>

                  <!-- Filter Status -->
                  <div class="flex items-center justify-between gap-3 flex-wrap">
                     <div
                        class="flex items-center gap-1.5 bg-slate-100 dark:bg-[#0C130F] p-1 rounded-xl text-xs overflow-x-auto no-scrollbar max-w-full">
                        <button type="button" data-filter="all"
                           class="filter-btn px-3 py-1.5 rounded-lg font-bold bg-white dark:bg-[#19261F] text-slate-900 dark:text-white shadow-2xs transition cursor-pointer shrink-0">
                           Semua
                        </button>
                        <button type="button" data-filter="menunggu"
                           class="filter-btn px-3 py-1.5 rounded-lg font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition cursor-pointer shrink-0">
                           Menunggu
                        </button>
                        <button type="button" data-filter="dilayani"
                           class="filter-btn px-3 py-1.5 rounded-lg font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition cursor-pointer shrink-0">
                           Dilayani
                        </button>
                        <button type="button" data-filter="selesai"
                           class="filter-btn px-3 py-1.5 rounded-lg font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition cursor-pointer shrink-0">
                           Selesai
                        </button>
                        <button type="button" data-filter="dilewati"
                           class="filter-btn px-3 py-1.5 rounded-lg font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition cursor-pointer shrink-0">
                           Dilewati
                        </button>
                     </div>

                     <span id="dataset-subtitle" class="text-[11px] font-medium text-slate-500 dark:text-slate-400">
                        Menampilkan antrian aktif tanggal {{ now()->format('d/m/Y') }}
                     </span>
                  </div>
               </div>

               <!-- TAMPILAN KHUSUS HANDPHONE (< md): Container Kecil Adaptif -->
               <div id="mobile-cards-container" class="md:hidden p-4 space-y-3 bg-slate-50/60 dark:bg-[#0D1511]/60">
               </div>

               <!-- TAMPILAN TABLET & DESKTOP (>= md): Tabel Terstruktur -->
               <div class="hidden md:block overflow-x-auto">
                  <table class="w-full text-left border-collapse">
                     <thead>
                        <tr
                           class="font-display border-b border-slate-100 dark:border-[#1E2C24] text-[11px] font-semibold text-slate-400 uppercase tracking-wider whitespace-nowrap">
                           <th class="px-6 py-3.5">Nomor Antrian</th>
                           <th id="th-date" class="px-6 py-3.5 hidden">Tanggal</th>
                           <th class="px-6 py-3.5">Status</th>
                           <th class="px-6 py-3.5">Waktu Ambil</th>
                           <th class="px-6 py-3.5">Waktu Dipanggil</th>
                           <th class="px-6 py-3.5">Petugas</th>
                        </tr>
                     </thead>
                     <tbody id="desktop-table-body"
                        class="divide-y divide-slate-100 dark:divide-[#19251E] text-sm whitespace-nowrap"></tbody>
                  </table>
               </div>

               <!-- Footer Paginasi -->
               <div
                  class="px-4 sm:px-6 py-3.5 border-t border-slate-100 dark:border-[#1E2C24] bg-white/80 dark:bg-[#0F1713]/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                  <p id="pagination-info" class="text-xs font-medium text-slate-500 dark:text-slate-400">
                     Menampilkan 0–0 dari 0 data
                  </p>

                  <div id="pagination-controls" class="flex items-center gap-1.5"></div>
               </div>

            </section>

         </main>
      </div>
   </div>

   <script>
      document.getElementById('theme-toggle').addEventListener('click', () => {
         const isDark = document.documentElement.classList.toggle('dark');
         localStorage.setItem('morbis_theme', isDark ? 'dark' : 'light');
      });

      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      const openBtn = document.getElementById('open-sidebar');
      const closeBtn = document.getElementById('close-sidebar');

      function toggleSidebar(show) {
         if (show) {
            sidebar.classList.remove('-translate-x-[112%]');
            backdrop.classList.remove('hidden');
         } else {
            sidebar.classList.add('-translate-x-[112%]');
            backdrop.classList.add('hidden');
         }
      }

      openBtn.addEventListener('click', () => toggleSidebar(true));
      closeBtn.addEventListener('click', () => toggleSidebar(false));
      backdrop.addEventListener('click', () => toggleSidebar(false));

      const DATASETS = {
         today: @json($todayJson),
         history: @json($historyJson),
      };

      let state = {
         dataset: 'today',
         status: 'all',
         search: '',
         perPage: 5,
         page: 1,
      };

      const mobileContainer = document.getElementById('mobile-cards-container');
      const desktopBody = document.getElementById('desktop-table-body');
      const thDate = document.getElementById('th-date');
      const subtitleEl = document.getElementById('dataset-subtitle');
      const paginationInfo = document.getElementById('pagination-info');
      const paginationControls = document.getElementById('pagination-controls');
      const searchInput = document.getElementById('search-input');
      const clearSearchBtn = document.getElementById('clear-search');
      const perPageSelect = document.getElementById('per-page-select');

      function getStatusBadge(status) {
         if (status === 'dilayani') {
            return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-amber-500/15 text-amber-800 dark:text-amber-300 rounded-lg">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Dilayani
                </span>`;
         }
         if (status === 'selesai') {
            return `<span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 rounded-lg">Selesai</span>`;
         }
         if (status === 'dilewati') {
            return `<span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold bg-red-500/15 text-red-700 dark:text-red-300 rounded-lg">Dilewati</span>`;
         }
         return `<span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-slate-100 dark:bg-[#19261F] text-slate-700 dark:text-slate-300 rounded-lg">Menunggu</span>`;
      }

      function renderData() {
         const rawList = DATASETS[state.dataset] || [];
         const query = state.search.trim().toLowerCase();

         const filtered = rawList.filter(item => {
            const matchStatus = state.status === 'all' || item.status === state.status;
            if (!matchStatus) return false;
            if (!query) return true;

            return (
               item.formatted_number.toLowerCase().includes(query) ||
               item.status.toLowerCase().includes(query) ||
               item.admin_name.toLowerCase().includes(query) ||
               item.date_label.toLowerCase().includes(query)
            );
         });

         const totalItems = filtered.length;
         const totalPages = Math.max(1, Math.ceil(totalItems / state.perPage));
         if (state.page > totalPages) state.page = totalPages;

         const startIdx = (state.page - 1) * state.perPage;
         const pageItems = filtered.slice(startIdx, startIdx + state.perPage);
         const isHistory = state.dataset === 'history';

         thDate.classList.toggle('hidden', !isHistory);
         subtitleEl.textContent = isHistory
            ? 'Arsip riwayat antrian dari hari-hari sebelumnya'
            : 'Menampilkan antrian aktif tanggal {{ now()->format("d/m/Y") }}';

         if (pageItems.length === 0) {
            const emptyText = query || state.status !== 'all'
               ? 'Tidak ada data antrian yang cocok dengan filter/pencarian.'
               : (isHistory ? 'Belum ada arsip riwayat antrian.' : 'Belum ada data antrian untuk hari ini.');

            mobileContainer.innerHTML = `
                    <div class="bg-white dark:bg-[#111915] rounded-xl p-6 text-center text-xs text-slate-400 border border-slate-200/70 dark:border-[#1E2C24]">
                        ${emptyText}
                    </div>
                `;
            desktopBody.innerHTML = `
                    <tr>
                        <td colspan="${isHistory ? 6 : 5}" class="px-6 py-10 text-center text-xs text-slate-400">
                            ${emptyText}
                        </td>
                    </tr>
                `;
         } else {
            mobileContainer.innerHTML = pageItems.map(item => `
                    <div class="rounded-xl p-3.5 border transition-colors ${item.status === 'dilayani'
                  ? 'bg-emerald-500/10 border-emerald-500/40'
                  : 'bg-white dark:bg-[#111915] border-slate-200/80 dark:border-[#1E2C24]'
               }">
                        <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-slate-100 dark:border-[#1C2A22]">
                            <div class="flex items-center gap-2">
                                <span class="font-ticket tabular-nums text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">${item.formatted_number}</span>
                                ${isHistory ? `<span class="text-[11px] font-medium px-2 py-0.5 rounded-md bg-slate-100 dark:bg-[#19261F] text-slate-600 dark:text-slate-300">${item.date_label}</span>` : ''}
                            </div>
                            ${getStatusBadge(item.status)}
                        </div>

                        <div class="grid grid-cols-3 gap-2 pt-2.5 text-[11px]">
                            <div>
                                <span class="text-slate-400 block">Ambil</span>
                                <span class="font-mono font-medium text-slate-700 dark:text-slate-300">${item.created_time}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Dipanggil</span>
                                <span class="font-mono font-medium text-slate-700 dark:text-slate-300">${item.called_time}</span>
                            </div>
                            <div class="text-right min-w-0">
                                <span class="text-slate-400 block">Petugas</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300 truncate block">${item.admin_name}</span>
                            </div>
                        </div>
                    </div>
                `).join('');

            desktopBody.innerHTML = pageItems.map(item => `
                    <tr class="transition-colors ${item.status === 'dilayani' ? 'bg-emerald-500/10' : 'hover:bg-slate-50/80 dark:hover:bg-white/[0.02]'}">
                        <td class="px-6 py-3.5 font-ticket tabular-nums text-base font-extrabold tracking-tight text-slate-900 dark:text-white">${item.formatted_number}</td>
                        ${isHistory ? `<td class="px-6 py-3.5 text-xs font-medium text-slate-600 dark:text-slate-300">${item.date_label}</td>` : ''}
                        <td class="px-6 py-3.5">${getStatusBadge(item.status)}</td>
                        <td class="px-6 py-3.5 font-mono text-xs text-slate-500 dark:text-slate-400">${item.created_time}</td>
                        <td class="px-6 py-3.5 font-mono text-xs text-slate-500 dark:text-slate-400">${item.called_time}</td>
                        <td class="px-6 py-3.5 text-xs font-medium text-slate-700 dark:text-slate-300">${item.admin_name}</td>
                    </tr>
                `).join('');
         }

         const showFrom = totalItems === 0 ? 0 : startIdx + 1;
         const showTo = Math.min(startIdx + state.perPage, totalItems);
         paginationInfo.textContent = `Menampilkan ${showFrom}–${showTo} dari ${totalItems} data`;

         renderPaginationButtons(totalPages);
      }

      function renderPaginationButtons(totalPages) {
         if (totalPages <= 1) {
            paginationControls.innerHTML = '';
            return;
         }

         let buttonsHTML = `
                <button type="button" data-page="${state.page - 1}" ${state.page === 1 ? 'disabled' : ''}
                    class="page-btn px-2.5 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 dark:border-[#24352B] bg-white dark:bg-[#16221B] text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:border-emerald-500/50 transition cursor-pointer">
                    Prev
                </button>
            `;

         let startPage = Math.max(1, state.page - 2);
         let endPage = Math.min(totalPages, startPage + 4);
         if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
         }

         for (let p = startPage; p <= endPage; p++) {
            const isActive = p === state.page;
            buttonsHTML += `
                    <button type="button" data-page="${p}"
                        class="page-btn w-8 h-8 rounded-lg font-ticket tabular-nums text-xs font-bold transition cursor-pointer ${isActive
                  ? 'bg-[#121C16] text-white dark:bg-emerald-500 dark:text-[#08110C] shadow-xs'
                  : 'bg-white dark:bg-[#16221B] border border-slate-200 dark:border-[#24352B] text-slate-700 dark:text-slate-300 hover:border-emerald-500/50'
               }">
                        ${p}
                    </button>
                `;
         }

         buttonsHTML += `
                <button type="button" data-page="${state.page + 1}" ${state.page === totalPages ? 'disabled' : ''}
                    class="page-btn px-2.5 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 dark:border-[#24352B] bg-white dark:bg-[#16221B] text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:border-emerald-500/50 transition cursor-pointer">
                    Next
                </button>
            `;

         paginationControls.innerHTML = buttonsHTML;

         paginationControls.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', () => {
               const targetPage = Number(btn.dataset.page);
               if (targetPage >= 1 && targetPage <= totalPages) {
                  state.page = targetPage;
                  renderData();
               }
            });
         });
      }

      function switchDataset(targetDataset) {
         state.dataset = targetDataset;
         state.page = 1;

         document.querySelectorAll('.dataset-tab').forEach(tab => {
            const active = tab.dataset.dataset === targetDataset;
            tab.classList.toggle('bg-[#121C16]', active);
            tab.classList.toggle('text-white', active);
            tab.classList.toggle('dark:bg-emerald-500', active);
            tab.classList.toggle('dark:text-[#08110C]', active);
            tab.classList.toggle('font-bold', active);
            tab.classList.toggle('shadow-xs', active);
            tab.classList.toggle('text-slate-600', !active);
            tab.classList.toggle('dark:text-slate-400', !active);
            tab.classList.toggle('font-semibold', !active);
         });

         document.querySelectorAll('.nav-dataset-btn').forEach(nav => {
            const active = nav.dataset.navDataset === targetDataset;
            nav.classList.toggle('bg-[#1A2C21]', active);
            nav.classList.toggle('text-white', active);
            nav.classList.toggle('border-emerald-500/20', active);
            nav.classList.toggle('font-semibold', active);
            nav.classList.toggle('text-slate-400', !active);
            nav.classList.toggle('border-transparent', !active);
         });

         renderData();
      }

      document.querySelectorAll('.dataset-tab').forEach(tab => {
         tab.addEventListener('click', () => switchDataset(tab.dataset.dataset));
      });

      document.querySelectorAll('.nav-dataset-btn').forEach(nav => {
         nav.addEventListener('click', () => {
            switchDataset(nav.dataset.navDataset);
            toggleSidebar(false);
            document.getElementById('data-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
         });
      });

      document.querySelectorAll('.filter-btn').forEach(btn => {
         btn.addEventListener('click', () => {
            state.status = btn.dataset.filter;
            state.page = 1;

            document.querySelectorAll('.filter-btn').forEach(b => {
               b.classList.remove('bg-white', 'dark:bg-[#19261F]', 'text-slate-900', 'dark:text-white', 'font-bold', 'shadow-2xs');
               b.classList.add('text-slate-600', 'dark:text-slate-400', 'font-semibold');
            });

            btn.classList.add('bg-white', 'dark:bg-[#19261F]', 'text-slate-900', 'dark:text-white', 'font-bold', 'shadow-2xs');
            btn.classList.remove('text-slate-600', 'dark:text-slate-400', 'font-semibold');

            renderData();
         });
      });

      searchInput.addEventListener('input', (e) => {
         state.search = e.target.value;
         state.page = 1;
         clearSearchBtn.classList.toggle('hidden', !state.search);
         renderData();
      });

      clearSearchBtn.addEventListener('click', () => {
         searchInput.value = '';
         state.search = '';
         state.page = 1;
         clearSearchBtn.classList.add('hidden');
         renderData();
      });

      perPageSelect.addEventListener('change', (e) => {
         state.perPage = Number(e.target.value);
         state.page = 1;
         renderData();
      });

      renderData();
   </script>
</body>

</html>