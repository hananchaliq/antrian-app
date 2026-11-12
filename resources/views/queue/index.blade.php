<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>MorbisQueue — Monitor Antrian Online</title>
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

      @keyframes numberPop {
         0% {
            opacity: 0.35;
            transform: translateY(6px) scale(0.97);
         }

         100% {
            opacity: 1;
            transform: translateY(0) scale(1);
         }
      }

      @keyframes barPulse {

         0%,
         100% {
            transform: scaleY(1);
         }

         50% {
            transform: scaleY(0.62);
         }
      }

      .animate-enter {
         animation: enterUp 0.38s cubic-bezier(0.16, 1, 0.3, 1) both;
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

      .animate-number-pop {
         animation: numberPop 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      }

      .bar-item {
         transform-origin: bottom;
         animation: barPulse 2.8s ease-in-out infinite;
      }

      .bar-item:nth-child(2) {
         animation-delay: 0.3s;
      }

      .bar-item:nth-child(3) {
         animation-delay: 0.6s;
      }

      .bar-item:nth-child(4) {
         animation-delay: 0.9s;
      }

      .bar-item:nth-child(5) {
         animation-delay: 1.2s;
      }
   </style>
</head>

<body
   class="bg-sage-grid font-sans text-slate-900 dark:text-slate-100 min-h-screen p-0 sm:p-3 md:p-4 transition-colors duration-300 antialiased">

   <div
      class="max-w-7xl mx-auto min-h-screen sm:min-h-[calc(100vh-1.5rem)] md:min-h-[calc(100vh-2rem)] bg-[#F5F7F6]/85 dark:bg-[#0B110E]/85 sm:bg-[#F5F7F6]/[0.96] sm:dark:bg-[#0B110E]/[0.96] backdrop-blur-md rounded-none sm:rounded-2xl border-0 sm:border border-white/40 dark:border-[#1E2E25] shadow-2xl flex flex-col overflow-hidden transition-colors duration-300">

      <!-- Header -->
      <header
         class="sticky top-0 z-20 px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4 bg-white/90 dark:bg-[#101814]/90 sm:bg-white/[0.96] sm:dark:bg-[#101814]/[0.96] backdrop-blur-md border-b border-slate-200/80 dark:border-[#1C2A22] flex items-center justify-between gap-3">
         <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
            <div
               class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#0D1912] dark:bg-[#16261D] border border-emerald-500/30 flex items-center justify-center shadow-xs shrink-0">
               <svg class="w-5 h-5" viewBox="0 0 32 32" fill="none">
                  <rect x="6" y="12" width="3.5" height="12" rx="1.75" fill="#34D399" />
                  <rect x="14.25" y="7" width="3.5" height="17" rx="1.75" fill="#10B981" />
                  <rect x="22.5" y="14" width="3.5" height="10" rx="1.75" fill="#A7F3D0" />
               </svg>
            </div>
            <div class="min-w-0">
               <h1
                  class="font-display text-sm sm:text-lg font-bold tracking-tight text-slate-900 dark:text-white truncate">
                  Sistem Antrian Layanan
               </h1>
               <p class="text-[11px] sm:text-xs font-medium text-slate-500 dark:text-slate-400 truncate">
                  {{ now()->translatedFormat('l, d F Y') }}
               </p>
            </div>
         </div>

         <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <span
               class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs font-semibold text-emerald-800 dark:text-emerald-300 bg-emerald-500/15 border border-emerald-500/25 px-2.5 sm:px-3 py-1.5 rounded-full">
               <span class="relative flex h-2 w-2">
                  <span
                     class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
               </span>
               <span class="hidden sm:inline">Realtime</span>
            </span>

            <button id="theme-toggle" type="button" aria-label="Ubah Tema"
               class="p-2.5 rounded-xl bg-white dark:bg-[#16221B] border border-slate-200 dark:border-[#24352B] text-slate-600 dark:text-slate-300 hover:border-emerald-500/40 active:scale-95 transition-all duration-200 cursor-pointer">
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

            <a href="{{ route('login') }}"
               class="px-3 sm:px-3.5 py-2.5 rounded-xl bg-[#121C16] hover:bg-[#1B2B22] dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-[#09120D] text-xs font-bold active:scale-95 transition-all duration-200">
               Panel Admin
            </a>
         </div>
      </header>

      <!-- Konten Utama -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">

         <!-- KARTU PRIORITAS PENGGUNA -->
         <section id="priority-ticket-section" class="hidden animate-enter">
            <div id="priority-ticket-card"
               class="bg-[#121C16]/[0.97] dark:bg-[#132119]/[0.97] text-white rounded-2xl p-5 sm:p-6 lg:p-7 border-2 border-emerald-400/70 shadow-xl transition-all duration-300">
               <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 lg:gap-6">
                  <div class="flex items-center justify-between sm:justify-start gap-4 sm:gap-5">
                     <div
                        class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-emerald-500/15 border border-emerald-400/30 flex flex-col items-center justify-center shrink-0">
                        <span
                           class="font-display text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-emerald-400">Tiket</span>
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-300 mt-0.5" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                     </div>
                     <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                           <span
                              class="font-display text-[10px] sm:text-[11px] font-bold uppercase tracking-wider bg-emerald-400 text-[#09130E] px-2.5 py-0.5 rounded-md">
                              Prioritas Antrian Anda
                           </span>
                           <span id="my-ticket-status-badge"
                              class="text-[11px] sm:text-xs font-semibold bg-white/10 text-emerald-300 px-2.5 py-0.5 rounded-md">
                              Menunggu Giliran
                           </span>
                        </div>
                        <div class="mt-1.5 sm:mt-2 flex flex-wrap items-baseline gap-x-3.5 gap-y-1">
                           <span id="my-ticket-number"
                              class="font-ticket tabular-nums text-4xl sm:text-5xl font-extrabold tracking-tight text-white">
                              A-001
                           </span>
                           <span id="my-ticket-message" class="text-xs sm:text-sm text-slate-300">
                              Menghitung posisi antrian...
                           </span>
                        </div>
                     </div>
                  </div>

                  <div
                     class="grid grid-cols-2 sm:flex items-center gap-2.5 sm:gap-4 pt-3.5 lg:pt-0 border-t lg:border-t-0 border-white/10">
                     <div
                        class="bg-white/5 border border-white/10 rounded-xl px-3.5 sm:px-4 py-2.5 sm:py-3 sm:min-w-[125px]">
                        <p class="text-[11px] text-slate-400">Antrian di Depan</p>
                        <p id="my-queues-ahead"
                           class="font-ticket tabular-nums text-lg sm:text-xl font-bold text-emerald-400 mt-0.5">0 Orang
                        </p>
                     </div>
                     <div
                        class="bg-white/5 border border-white/10 rounded-xl px-3.5 sm:px-4 py-2.5 sm:py-3 sm:min-w-[125px] flex items-center justify-between gap-2">
                        <div>
                           <p class="text-[11px] text-slate-400">Estimasi Tunggu</p>
                           <p id="my-est-wait"
                              class="font-ticket tabular-nums text-lg sm:text-xl font-bold text-white mt-0.5">~0 Mnt</p>
                        </div>
                        <button id="btn-clear-ticket" type="button" title="Hapus / Reset tiket dari layar ini"
                           class="sm:hidden p-2 rounded-lg bg-white/5 hover:bg-red-500/20 text-slate-400 hover:text-red-300 transition cursor-pointer">
                           <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                           </svg>
                        </button>
                     </div>
                     <button id="btn-clear-ticket-desktop" type="button" title="Hapus / Reset tiket dari layar ini"
                        class="hidden sm:flex p-3 rounded-xl bg-white/5 hover:bg-red-500/20 border border-white/10 hover:border-red-400/40 text-slate-400 hover:text-red-300 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                     </button>
                  </div>
               </div>
            </div>
         </section>

         <!-- Baris KPI: 3 Kartu Monitor -->
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">

            <!-- Kartu 1: Sedang Dilayani -->
            <div
               class="sm:col-span-2 lg:col-span-1 animate-enter bg-[#121C16]/[0.97] dark:bg-[#131F18]/[0.97] text-white rounded-2xl p-5 sm:p-6 border border-[#223429] shadow-lg flex items-center justify-between transition-transform duration-300 hover:-translate-y-0.5">
               <div>
                  <span class="text-xs font-semibold text-slate-400 tracking-wide">Nomor Antrian Saat Ini</span>
                  <div id="current-number"
                     class="font-ticket tabular-nums mt-1 sm:mt-1.5 text-5xl sm:text-6xl font-extrabold tracking-tight text-white">
                     ---
                  </div>
                  <p id="current-status-text" class="mt-1.5 sm:mt-2 text-xs text-emerald-400 font-medium">
                     Menunggu pemanggilan petugas
                  </p>
               </div>
               <div class="flex items-end gap-1.5 h-12 sm:h-14 px-2">
                  <span class="bar-item w-1.5 h-8 bg-emerald-400 rounded-full"></span>
                  <span class="bar-item w-1.5 h-5 bg-emerald-500/40 rounded-full"></span>
                  <span class="bar-item w-1.5 h-12 bg-emerald-400 rounded-full"></span>
                  <span class="bar-item w-1.5 h-7 bg-emerald-500/40 rounded-full"></span>
                  <span class="bar-item w-1.5 h-10 bg-emerald-400 rounded-full"></span>
               </div>
            </div>

            <!-- Kartu 2: Antrian Berikutnya -->
            <div
               class="animate-enter delay-1 bg-white/[0.97] dark:bg-[#111915]/[0.97] rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-[#1E2C24] shadow-sm flex items-center justify-between transition-all duration-300 hover:-translate-y-0.5">
               <div>
                  <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Urutan Berikutnya</span>
                  <div id="next-number"
                     class="font-ticket tabular-nums mt-1.5 sm:mt-2 text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                     ---
                  </div>
                  <p class="mt-1.5 sm:mt-2 text-xs text-slate-500 dark:text-slate-400">
                     Persiapkan diri menuju loket
                  </p>
               </div>
               <div class="flex items-end gap-1.5 h-11 sm:h-12 px-2">
                  <span class="w-1.5 h-6 bg-slate-200 dark:bg-slate-700 rounded-full"></span>
                  <span class="w-1.5 h-9 bg-emerald-500 rounded-full"></span>
                  <span class="w-1.5 h-5 bg-slate-200 dark:bg-slate-700 rounded-full"></span>
                  <span class="w-1.5 h-7 bg-slate-200 dark:bg-slate-700 rounded-full"></span>
               </div>
            </div>

            <!-- Kartu 3: Total Menunggu -->
            <div
               class="animate-enter delay-2 bg-white/[0.97] dark:bg-[#111915]/[0.97] rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-[#1E2C24] shadow-sm flex items-center justify-between transition-all duration-300 hover:-translate-y-0.5">
               <div>
                  <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Menunggu</span>
                  <div class="mt-1.5 sm:mt-2 flex items-baseline gap-2">
                     <span id="waiting-count"
                        class="font-ticket tabular-nums text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">0</span>
                     <span
                        class="font-display text-xs font-semibold uppercase tracking-wider text-slate-400">Orang</span>
                  </div>
                  <p id="last-updated" class="mt-1.5 sm:mt-2 text-xs text-slate-500 dark:text-slate-400">
                     Sinkronisasi otomatis aktif
                  </p>
               </div>
               <div class="flex items-end gap-1.5 h-11 sm:h-12 px-2">
                  <span class="w-1.5 h-8 bg-emerald-500 rounded-full"></span>
                  <span class="w-1.5 h-4 bg-slate-200 dark:bg-slate-700 rounded-full"></span>
                  <span class="w-1.5 h-6 bg-slate-200 dark:bg-slate-700 rounded-full"></span>
                  <span class="w-1.5 h-10 bg-emerald-400 rounded-full"></span>
               </div>
            </div>

         </div>

         <!-- Baris Bawah: Ambil Nomor & Daftar Antrian -->
         <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">

            <!-- Kolom Kiri (7 Col): Registrasi Mandiri -->
            <div class="md:col-span-7 flex flex-col gap-4 sm:gap-5 animate-enter delay-2">
               <div
                  class="bg-white/[0.97] dark:bg-[#111915]/[0.97] rounded-2xl p-5 sm:p-6 lg:p-8 border border-slate-200/80 dark:border-[#1E2C24] shadow-sm">
                  <div
                     class="flex flex-col sm:flex-row md:flex-col lg:flex-row sm:items-center md:items-start lg:items-center justify-between gap-4 sm:gap-5">
                     <div class="space-y-1.5">
                        <span
                           class="font-display inline-block text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-md">
                           Registrasi Mandiri
                        </span>
                        <h2
                           class="font-display text-base sm:text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                           Ambil Nomor Antrian Baru
                        </h2>
                        <p id="take-queue-desc"
                           class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md leading-relaxed">
                           Tekan tombol untuk mendapatkan nomor antrian digital hari ini secara instan.
                        </p>
                     </div>

                     <button id="btn-take-queue" type="button"
                        class="w-full sm:w-auto md:w-full lg:w-auto px-6 py-3.5 rounded-xl bg-[#121C16] hover:bg-[#1C2C23] dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-[#08110C] text-sm font-bold shadow-sm transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span id="btn-take-label">Ambil Nomor</span>
                     </button>
                  </div>
               </div>

               <!-- Panduan Singkat -->
               <div
                  class="bg-white/[0.97] dark:bg-[#111915]/[0.97] rounded-2xl p-4 sm:p-5 border border-slate-200/80 dark:border-[#1E2C24] grid grid-cols-1 sm:grid-cols-3 md:grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4 text-xs">
                  <div class="flex items-start gap-3">
                     <span
                        class="font-ticket tabular-nums w-6 h-6 rounded-lg bg-slate-100 dark:bg-[#19261F] text-slate-700 dark:text-emerald-400 font-bold flex items-center justify-center shrink-0">1</span>
                     <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Ambil nomor antrian; tiket Anda
                        otomatis diprioritaskan di bagian atas.</p>
                  </div>
                  <div class="flex items-start gap-3">
                     <span
                        class="font-ticket tabular-nums w-6 h-6 rounded-lg bg-slate-100 dark:bg-[#19261F] text-slate-700 dark:text-emerald-400 font-bold flex items-center justify-center shrink-0">2</span>
                     <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Pantau sisa antrian di depan Anda
                        secara realtime tanpa refresh.</p>
                  </div>
                  <div class="flex items-start gap-3">
                     <span
                        class="font-ticket tabular-nums w-6 h-6 rounded-lg bg-slate-100 dark:bg-[#19261F] text-slate-700 dark:text-emerald-400 font-bold flex items-center justify-center shrink-0">3</span>
                     <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Segera menuju loket saat kartu tiket
                        Anda berubah status dipanggil.</p>
                  </div>
               </div>
            </div>

            <!-- Kolom Kanan (5 Col): Kartu Sage Daftar Antrian Menunggu -->
            <div class="md:col-span-5 animate-enter delay-3">
               <div
                  class="bg-[#DCE8DE]/[0.96] dark:bg-[#14221A]/[0.96] rounded-2xl p-4 sm:p-6 border border-[#C9DBC9] dark:border-[#21362A] h-full flex flex-col">
                  <div class="flex items-center justify-between mb-3.5 sm:mb-4">
                     <div>
                        <h2
                           class="font-display text-sm sm:text-base font-bold tracking-tight text-slate-900 dark:text-white">
                           Daftar Antrian Menunggu</h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400">Urutan panggilan berikutnya</p>
                     </div>
                     <span id="waiting-badge"
                        class="font-ticket tabular-nums text-xs font-extrabold bg-white/95 dark:bg-[#0D1611]/95 text-slate-900 dark:text-emerald-400 px-3 py-1 rounded-lg shadow-2xs">
                        0
                     </span>
                  </div>

                  <div id="waiting-list"
                     class="space-y-2.5 max-h-[340px] sm:max-h-[360px] overflow-y-auto overscroll-contain pr-1 flex-1">
                     <div
                        class="bg-white/95 dark:bg-[#0E1812]/95 rounded-xl p-6 text-center text-xs text-slate-500 dark:text-slate-400">
                        Memuat daftar antrian...
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </main>
   </div>

   <script>
      document.getElementById('theme-toggle').addEventListener('click', () => {
         const isDark = document.documentElement.classList.toggle('dark');
         localStorage.setItem('morbis_theme', isDark ? 'dark' : 'light');
      });

      const TODAY_KEY = '{{ now()->toDateString() }}';
      const STORAGE_KEY = 'morbis_user_ticket';

      const currentNumberEl = document.getElementById('current-number');
      const currentStatusTextEl = document.getElementById('current-status-text');
      const nextNumberEl = document.getElementById('next-number');
      const waitingListEl = document.getElementById('waiting-list');
      const waitingCountEl = document.getElementById('waiting-count');
      const waitingBadgeEl = document.getElementById('waiting-badge');
      const lastUpdatedEl = document.getElementById('last-updated');
      const btnTakeQueue = document.getElementById('btn-take-queue');
      const btnTakeLabel = document.getElementById('btn-take-label');
      const takeQueueDesc = document.getElementById('take-queue-desc');

      const prioritySection = document.getElementById('priority-ticket-section');
      const priorityCard = document.getElementById('priority-ticket-card');
      const myTicketNumberEl = document.getElementById('my-ticket-number');
      const myTicketStatusBadge = document.getElementById('my-ticket-status-badge');
      const myTicketMessage = document.getElementById('my-ticket-message');
      const myQueuesAhead = document.getElementById('my-queues-ahead');
      const myEstWait = document.getElementById('my-est-wait');

      let lastCurrentNumber = null;

      function getSavedTicket() {
         try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return null;
            const parsed = JSON.parse(raw);
            if (parsed.date !== TODAY_KEY) {
               localStorage.removeItem(STORAGE_KEY);
               return null;
            }
            return parsed;
         } catch {
            return null;
         }
      }

      function saveTicket(queueObj) {
         localStorage.setItem(STORAGE_KEY, JSON.stringify({
            raw_number: queueObj.queue_number,
            formatted_number: queueObj.formatted_number,
            date: TODAY_KEY
         }));
      }

      function clearSavedTicket() {
         localStorage.removeItem(STORAGE_KEY);
         prioritySection.classList.add('hidden');
         btnTakeQueue.disabled = false;
         btnTakeLabel.textContent = 'Ambil Nomor';
         takeQueueDesc.textContent = 'Tekan tombol untuk mendapatkan nomor antrian digital hari ini secara instan.';
         fetchQueueStatus();
      }

      document.getElementById('btn-clear-ticket').addEventListener('click', clearSavedTicket);
      document.getElementById('btn-clear-ticket-desktop').addEventListener('click', clearSavedTicket);

      function updatePriorityTicketUI(data) {
         const saved = getSavedTicket();
         if (!saved) {
            prioritySection.classList.add('hidden');
            btnTakeQueue.disabled = false;
            btnTakeLabel.textContent = 'Ambil Nomor';
            return;
         }

         prioritySection.classList.remove('hidden');
         myTicketNumberEl.textContent = saved.formatted_number;

         const isServingNow = data.current && data.current.queue_number === saved.raw_number;
         const waitingIndex = data.waiting_list.findIndex(item => item.queue_number === saved.raw_number);

         if (isServingNow) {
            priorityCard.classList.add('ring-4', 'ring-emerald-400/40', 'border-emerald-300');
            myTicketStatusBadge.textContent = 'GILIRAN ANDA SEKARANG!';
            myTicketStatusBadge.className = 'font-display text-[11px] sm:text-xs font-bold bg-emerald-400 text-[#09130E] px-2.5 py-0.5 rounded-md animate-pulse';
            myTicketMessage.textContent = 'Silakan segera menuju loket pelayanan.';
            myQueuesAhead.textContent = '0 Orang';
            myEstWait.textContent = 'Sekarang';

            btnTakeQueue.disabled = true;
            btnTakeLabel.textContent = 'Sedang Dilayani';
            takeQueueDesc.textContent = `Nomor ${saved.formatted_number} sedang dipanggil di loket.`;
         } else if (waitingIndex !== -1) {
            const aheadCount = waitingIndex + (data.current ? 1 : 0);
            priorityCard.classList.remove('ring-4', 'ring-emerald-400/40', 'border-emerald-300');
            myTicketStatusBadge.textContent = waitingIndex === 0 ? 'Bersiap! Urutan Berikutnya' : 'Menunggu Giliran';
            myTicketStatusBadge.className = 'text-[11px] sm:text-xs font-semibold bg-white/10 text-emerald-300 px-2.5 py-0.5 rounded-md';
            myTicketMessage.textContent = waitingIndex === 0
               ? 'Anda berada di urutan terdepan daftar tunggu.'
               : `Terdapat ${aheadCount} antrian sebelum giliran Anda.`;
            myQueuesAhead.textContent = `${aheadCount} Orang`;
            myEstWait.textContent = `~${aheadCount * 3} Mnt`;

            btnTakeQueue.disabled = true;
            btnTakeLabel.textContent = `Tiket Aktif (${saved.formatted_number})`;
            takeQueueDesc.textContent = 'Anda sudah memiliki nomor antrian aktif. Pantau status prioritas di atas.';
         } else {
            priorityCard.classList.remove('ring-4', 'ring-emerald-400/40', 'border-emerald-300');
            myTicketStatusBadge.textContent = 'Selesai / Terlewat';
            myTicketStatusBadge.className = 'text-[11px] sm:text-xs font-semibold bg-slate-700 text-slate-300 px-2.5 py-0.5 rounded-md';
            myTicketMessage.textContent = 'Nomor antrian ini telah selesai diproses.';
            myQueuesAhead.textContent = '-';
            myEstWait.textContent = 'Selesai';

            btnTakeQueue.disabled = false;
            btnTakeLabel.textContent = 'Ambil Nomor Baru';
            takeQueueDesc.textContent = 'Tiket Anda sebelumnya sudah selesai. Klik untuk mengambil nomor baru.';
         }
      }

      async function fetchQueueStatus() {
         try {
            const response = await fetch('{{ route("queue.status") }}', {
               headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) return;

            const data = await response.json();
            const activeNum = data.current ? data.current.formatted_number : '---';

            if (activeNum !== lastCurrentNumber) {
               currentNumberEl.textContent = activeNum;
               currentNumberEl.classList.remove('animate-number-pop');
               void currentNumberEl.offsetWidth;
               currentNumberEl.classList.add('animate-number-pop');
               lastCurrentNumber = activeNum;
            }

            currentStatusTextEl.textContent = data.current
               ? 'Silakan menuju loket pelayanan'
               : 'Belum ada antrian yang dipanggil';

            nextNumberEl.textContent = data.waiting_list.length > 0
               ? data.waiting_list[0].formatted_number
               : '---';

            waitingCountEl.textContent = data.waiting_count;
            waitingBadgeEl.textContent = data.waiting_count;
            lastUpdatedEl.textContent = `Diperbarui ${data.updated_at}`;

            updatePriorityTicketUI(data);

            const saved = getSavedTicket();

            if (data.waiting_list.length === 0) {
               waitingListEl.innerHTML = `
                        <div class="bg-white/95 dark:bg-[#0E1812]/95 rounded-xl p-8 text-center text-xs text-slate-500 dark:text-slate-400">
                            Tidak ada antrian menunggu saat ini.
                        </div>
                    `;
            } else {
               waitingListEl.innerHTML = data.waiting_list.map((item, index) => {
                  const isMine = saved && item.queue_number === saved.raw_number;
                  return `
                            <div class="bg-white/[0.97] dark:bg-[#0E1812]/[0.97] rounded-xl px-3.5 sm:px-4 py-3 flex items-center justify-between border ${isMine ? 'border-emerald-500 ring-2 ring-emerald-500/20' : (index === 0 ? 'border-emerald-500/40' : 'border-transparent')} transition-all duration-200">
                                <div class="flex items-center gap-2.5 sm:gap-3">
                                    <span class="font-ticket tabular-nums w-7 h-7 rounded-lg ${isMine || index === 0 ? 'bg-[#121C16] text-emerald-400' : 'bg-slate-100 dark:bg-[#18261E] text-slate-600 dark:text-slate-300'} text-xs font-extrabold flex items-center justify-center shrink-0">
                                        ${index + 1}
                                    </span>
                                    <span class="font-ticket tabular-nums font-extrabold text-base sm:text-lg tracking-tight text-slate-900 dark:text-white">${item.formatted_number}</span>
                                    ${isMine ? '<span class="font-display text-[10px] sm:text-[11px] font-bold bg-emerald-500 text-slate-950 px-2 py-0.5 rounded-md">Nomor Anda</span>' : ''}
                                </div>
                                ${index === 0
                        ? '<span class="text-[11px] font-semibold bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 px-2.5 py-0.5 rounded-md">Berikutnya</span>'
                        : '<span class="text-xs font-medium text-slate-500 dark:text-slate-400 capitalize">' + item.status + '</span>'
                     }
                            </div>
                        `;
               }).join('');
            }
         } catch (error) {
            console.error('Gagal memperbarui status antrian:', error);
         }
      }

      btnTakeQueue.addEventListener('click', async () => {
         btnTakeQueue.disabled = true;
         btnTakeLabel.textContent = 'Memproses...';

         try {
            const response = await fetch('{{ route("queue.store") }}', {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
               }
            });

            if (response.ok) {
               const result = await response.json();
               saveTicket(result.queue);
               await fetchQueueStatus();
               prioritySection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
         } catch {
            btnTakeQueue.disabled = false;
            btnTakeLabel.textContent = 'Ambil Nomor';
         }
      });

      fetchQueueStatus();
      setInterval(fetchQueueStatus, 2000);
   </script>
</body>

</html>