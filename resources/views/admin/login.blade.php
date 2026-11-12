<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>MorbisQueue — Login Admin</title>
   <link rel="icon" type="image/svg+xml"
      href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%23121C16'/%3E%3Crect x='16' y='24' width='6' height='22' rx='3' fill='%2334D399'/%3E%3Crect x='29' y='16' width='6' height='30' rx='3' fill='%2310B981'/%3E%3Crect x='42' y='28' width='6' height='18' rx='3' fill='%23A7F3D0'/%3E%3C/svg%3E">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Outfit:wght@600;700;800&display=swap"
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
            transform: translateY(10px);
         }

         to {
            opacity: 1;
            transform: translateY(0);
         }
      }

      .animate-enter {
         animation: enterUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
      }
   </style>
</head>

<body
   class="bg-sage-grid font-sans text-slate-900 dark:text-slate-100 min-h-screen p-3 sm:p-5 flex items-center justify-center transition-colors duration-300 antialiased">

   <div
      class="w-full max-w-4xl bg-white/90 dark:bg-[#0B110E]/90 sm:bg-white/[0.96] sm:dark:bg-[#0B110E]/[0.96] backdrop-blur-md rounded-2xl border border-white/40 dark:border-[#1E2E25] shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 animate-enter">

      <!-- Panel Kiri -->
      <div
         class="md:col-span-5 bg-[#111E16]/[0.97] text-white p-5 sm:p-7 md:p-9 flex flex-col justify-between relative overflow-hidden">
         <div class="relative z-10">
            <div class="flex items-center justify-between">
               <div class="flex items-center gap-2.5">
                  <div
                     class="w-9 h-9 rounded-xl bg-[#192B20] border border-emerald-500/30 flex items-center justify-center shrink-0">
                     <svg class="w-5 h-5" viewBox="0 0 32 32" fill="none">
                        <rect x="6" y="12" width="3.5" height="12" rx="1.75" fill="#34D399" />
                        <rect x="14.25" y="7" width="3.5" height="17" rx="1.75" fill="#10B981" />
                        <rect x="22.5" y="14" width="3.5" height="10" rx="1.75" fill="#A7F3D0" />
                     </svg>
                  </div>
                  <span class="font-display font-bold tracking-tight text-base">MorbisQueue</span>
               </div>

               <button id="theme-toggle-mobile" type="button" aria-label="Ubah Tema"
                  class="md:hidden p-2 rounded-xl bg-[#192B20] border border-emerald-500/20 text-emerald-300 active:scale-95 transition cursor-pointer">
                  <svg class="w-4 h-4 hidden dark:block text-emerald-400" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                  <svg class="w-4 h-4 block dark:hidden text-emerald-300" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                  </svg>
               </button>
            </div>

            <div class="mt-5 md:mt-10 space-y-2 md:space-y-3">
               <span
                  class="font-display inline-block text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-md">
                  Kontrol Loket
               </span>
               <h2 class="font-display text-lg sm:text-xl md:text-2xl font-bold leading-snug tracking-tight">
                  Panel Manajemen Antrian Terpadu
               </h2>
               <p class="hidden sm:block text-xs text-slate-400 leading-relaxed">
                  Kelola pemanggilan nomor antrian berikutnya, tinjau riwayat pelayanan harian, dan sinkronkan layar
                  monitor publik secara langsung.
               </p>
            </div>
         </div>

         <div class="hidden md:flex relative z-10 mt-10 pt-6 border-t border-white/10 items-end justify-between">
            <div>
               <p class="text-[11px] text-slate-400">Status Sistem</p>
               <p class="text-xs font-semibold text-emerald-400 mt-0.5">Terhubung & Siap</p>
            </div>
            <div class="flex items-end gap-1.5 h-10">
               <span class="w-1.5 h-4 bg-emerald-500/30 rounded-full"></span>
               <span class="w-1.5 h-7 bg-emerald-400 rounded-full"></span>
               <span class="w-1.5 h-5 bg-emerald-500/30 rounded-full"></span>
               <span class="w-1.5 h-9 bg-emerald-400 rounded-full"></span>
               <span class="w-1.5 h-6 bg-emerald-500/60 rounded-full"></span>
            </div>
         </div>
      </div>

      <!-- Panel Kanan -->
      <div
         class="md:col-span-7 bg-white/[0.97] dark:bg-[#111915]/[0.97] p-5 sm:p-8 md:p-10 flex flex-col justify-center">
         <div class="flex items-center justify-between mb-5 sm:mb-7">
            <div>
               <h1 class="font-display text-lg sm:text-xl font-bold tracking-tight text-slate-900 dark:text-white">Masuk
                  Panel Admin</h1>
               <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan akun petugas untuk melanjutkan.</p>
            </div>

            <button id="theme-toggle" type="button" aria-label="Ubah Tema"
               class="hidden md:inline-flex p-2.5 rounded-xl bg-slate-50 dark:bg-[#18241D] border border-slate-200 dark:border-[#24352B] text-slate-600 dark:text-slate-300 hover:border-emerald-500/40 active:scale-95 transition-all cursor-pointer">
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
         </div>

         @if ($errors->any())
            <div
               class="mb-4 sm:mb-5 p-3.5 rounded-xl bg-red-500/10 border border-red-500/30 text-xs font-medium text-red-700 dark:text-red-300">
               {{ $errors->first() }}
            </div>
         @endif

         <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf
            <div>
               <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat
                  Email</label>
               <input type="email" id="email" name="email" value="{{ old('email', 'budi@morbis.id') }}" required
                  autofocus
                  class="w-full px-3.5 py-3 sm:py-2.5 text-base sm:text-sm bg-slate-50 dark:bg-[#0C130F] border border-slate-200 dark:border-[#24352B] rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors">
            </div>

            <div>
               <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kata
                  Sandi</label>
               <div class="relative">
                  <input type="password" id="password" name="password" required
                     class="w-full px-3.5 py-3 sm:py-2.5 pr-11 text-base sm:text-sm bg-slate-50 dark:bg-[#0C130F] border border-slate-200 dark:border-[#24352B] rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors">
                  <button type="button" id="toggle-password" aria-label="Tampilkan sandi"
                     class="absolute inset-y-0 right-0 px-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                           d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                     </svg>
                  </button>
               </div>
            </div>

            <button type="submit"
               class="w-full py-3.5 sm:py-3 mt-2 bg-[#121C16] hover:bg-[#1C2C23] dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-[#08110C] text-sm font-bold rounded-xl shadow-sm transition-all duration-200 active:scale-[0.98] cursor-pointer">
               Masuk ke Dashboard
            </button>
         </form>

         <div
            class="mt-5 sm:mt-6 pt-4 sm:pt-5 border-t border-slate-100 dark:border-[#1E2C24] flex items-center justify-between text-xs">
            <a href="{{ route('queue.index') }}"
               class="inline-flex items-center gap-1.5 py-1 font-medium text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
               <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
               </svg>
               <span>Kembali ke Layar Antrian</span>
            </a>
         </div>
      </div>

   </div>

   <script>
      function toggleAppTheme() {
         const isDark = document.documentElement.classList.toggle('dark');
         localStorage.setItem('morbis_theme', isDark ? 'dark' : 'light');
      }
      document.getElementById('theme-toggle').addEventListener('click', toggleAppTheme);
      document.getElementById('theme-toggle-mobile').addEventListener('click', toggleAppTheme);

      const passwordInput = document.getElementById('password');
      document.getElementById('toggle-password').addEventListener('click', () => {
         passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
      });
   </script>
</body>

</html>