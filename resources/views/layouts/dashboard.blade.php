<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | GAWEIN</title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0a0f25',
                        secondary: '#00f0ff',
                        accent: '#ff003c',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .glass-panel {
                @apply bg-white border border-slate-200/80 shadow-sm rounded-2xl;
            }
            .card-hover {
                @apply transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-200 hover:border-slate-300;
            }
            .sidebar-link {
                @apply flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-slate-400 transition-all duration-300 hover:bg-slate-800/80 hover:text-slate-100 hover:translate-x-1;
            }
            .sidebar-link.active {
                @apply bg-blue-500/10 text-blue-400 border border-blue-500/20 shadow-none font-semibold;
            }
            .btn-action {
                @apply px-4 py-2 rounded-lg font-semibold text-sm transition-all shadow-sm hover:-translate-y-0.5 hover:shadow-md active:translate-y-0;
            }
        }
        
        /* Custom Scrollbar for a cleaner SaaS look */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    
    @yield('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-blue-500 selection:text-white">
    
    <div class="cursor-inner hidden md:block"></div>
    <div class="cursor-outer hidden md:block"></div>

    <div class="flex h-screen overflow-hidden">
        
        
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 transform -translate-x-full lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out bg-[#0f172a] border-r border-slate-800 flex flex-col h-full shadow-2xl lg:shadow-none">
            
            <div class="px-6 py-8 flex items-center justify-between border-b border-slate-800/60">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ asset('logo.png') }}" alt="GAWEIN" class="h-10 w-auto filter brightness-0 invert">
                </a>
                <button class="lg:hidden text-slate-400 hover:text-white" id="close-sidebar">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider px-4 mb-3">Menu Utama</div>
                <ul class="flex flex-col gap-1.5">
                    @yield('sidebar_menu')
                </ul>
            </div>
            
            
            <div class="p-6 border-t border-slate-800/60 bg-slate-900/50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-semibold text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#f8fafc]">
            
            <header class="h-20 px-6 lg:px-10 flex items-center justify-between bg-white/70 backdrop-blur-lg border-b border-slate-200/70 z-40 sticky top-0">
                <div class="flex items-center gap-4 flex-1">
                    <button class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-white shadow-sm border border-slate-200 text-slate-600 hover:text-blue-600 transition-colors" id="open-sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h2 class="font-heading font-bold text-2xl text-slate-800 hidden sm:block whitespace-nowrap tracking-tight">@yield('title', 'Dashboard')</h2>
                    
                    
                    @php 
                        $searchRoute = Auth::check() && Auth::user()->role == 'employer' ? route('employer.candidates.index') : route('jobseeker.jobs.index'); 
                    @endphp
                    <form action="{{ $searchRoute }}" method="GET" class="hidden md:flex ml-8 max-w-md w-full relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-11 pr-4 py-2.5 border border-slate-200 rounded-xl bg-white focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none shadow-sm placeholder:text-slate-400 font-medium" placeholder="Cari data...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md border border-slate-200">Enter</span>
                        </div>
                    </form>
                </div>
                
                <div class="flex items-center gap-4 lg:gap-6">
                    
                    
                    <div class="relative">
                        <div id="profile-dropdown-btn" class="flex items-center gap-3 cursor-pointer group">
                            <div class="text-right hidden sm:block">
                                <div class="font-bold text-slate-900 text-sm group-hover:text-blue-600 transition-colors">@yield('user_name', 'Pengguna')</div>
                                <div class="text-[11px] font-bold text-slate-500 group-hover:text-blue-500 transition-colors">@yield('user_role', 'Role')</div>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white shadow-sm ring-2 ring-transparent group-hover:ring-blue-100 transition-all overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(View::yieldContent('user_name', 'User')) }}&background=0D8ABC&color=fff" class="w-full h-full object-cover">
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-hover:text-slate-600 transition-colors hidden sm:block"></i>
                        </div>
                        
                        
                        <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform origin-top-right transition-all z-50">
                            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                                <p class="text-sm font-bold text-slate-800 truncate">@yield('user_name', 'Pengguna')</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            <div class="p-2 space-y-1">
                                @php 
                                    $profileRoute = Auth::check() && Auth::user()->role == 'employer' ? route('employer.profile.index') : (Auth::check() && Auth::user()->role == 'jobseeker' ? route('jobseeker.profile.index') : '#'); 
                                @endphp
                                <a href="{{ $profileRoute }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-user w-4 text-center"></i> Profil Saya
                                </a>
                                @php 
                                    $settingsRoute = Auth::check() && Auth::user()->role == 'employer' ? route('employer.settings.index') : (Auth::check() && Auth::user()->role == 'jobseeker' ? route('jobseeker.settings.index') : '#'); 
                                @endphp
                                <a href="{{ $settingsRoute }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-gear w-4 text-center"></i> Pengaturan
                                </a>
                            </div>
                            <div class="p-2 border-t border-slate-100">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-bold text-rose-600 rounded-xl hover:bg-rose-50 transition-colors text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            
            <div class="flex-1 overflow-auto p-6 lg:p-10 pb-24 relative">
                
                @if(Auth::check() && in_array(Auth::user()->role, ['employer', 'jobseeker']))
                    @if(Auth::user()->verification_status === 'unverified' || Auth::user()->verification_status === 'rejected')
                        <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-rose-500 rounded-bl-full -z-10 opacity-5"></div>
                            <div class="flex gap-4 items-start">
                                <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-rose-800 text-lg mb-1">
                                        {{ Auth::user()->verification_status === 'rejected' ? 'Verifikasi KTP Ditolak' : 'Verifikasi Identitas Diperlukan' }}
                                    </h4>
                                    <p class="text-rose-600 text-sm font-medium">
                                        {{ Auth::user()->verification_status === 'rejected' ? 'KTP yang Anda unggah ditolak oleh sistem. Mohon unggah ulang KTP yang jelas dan valid agar dapat menggunakan layanan.' : 'Akun Anda belum terverifikasi. Anda harus mengunggah KTP agar dapat melamar atau memasang lowongan kerja.' }}
                                    </p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('ktpModal').classList.remove('hidden')" class="shrink-0 bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md shadow-rose-500/20 transition-all hover:-translate-y-0.5">
                                <i class="fa-solid fa-upload"></i> Unggah KTP
                            </button>
                        </div>
                    @elseif(Auth::user()->verification_status === 'pending')
                        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex gap-4 items-start shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-amber-800 text-lg mb-1">Verifikasi Sedang Ditinjau</h4>
                                <p class="text-amber-700 text-sm font-medium">KTP Anda telah kami terima dan sedang dalam proses peninjauan oleh Admin. Mohon tunggu sebentar ya!</p>
                            </div>
                        </div>
                    @endif
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

    
    <div id="ktpModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('ktpModal').classList.add('hidden')"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-slate-100">
                <div class="absolute top-0 right-0 w-40 h-40 bg-blue-50 rounded-bl-full -z-10 opacity-60"></div>
                
                <div class="px-6 py-6 border-b border-slate-100 flex justify-between items-center bg-white/50 backdrop-blur-xl">
                    <h3 class="text-xl font-heading font-bold text-slate-800">Verifikasi KTP</h3>
                    <button onclick="document.getElementById('ktpModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <form action="{{ route('verification.upload') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 relative">
                    @csrf
                    <div class="mb-6">
                        <div class="text-sm font-medium text-slate-600 mb-4">Mohon unggah foto Kartu Tanda Penduduk (KTP) Anda yang jelas dan dapat terbaca untuk keperluan verifikasi keamanan.</div>
                        <div class="w-full relative border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:bg-slate-50 hover:border-blue-400 transition-all group">
                            <input type="file" name="ktp_image" accept="image/jpeg,image/png,image/jpg" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('ktpPreviewName').textContent = this.files[0].name">
                            <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 mx-auto flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <div class="text-sm font-bold text-slate-700 mb-1">Klik untuk memilih atau seret gambar ke sini</div>
                            <div class="text-xs font-medium text-slate-400">PNG, JPG, JPEG (Maks. 4MB)</div>
                            <div id="ktpPreviewName" class="mt-4 text-sm font-bold text-blue-600"></div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-3.5 font-bold text-sm shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <script>

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        
        const toggleSidebar = () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        };

        if(openBtn) openBtn.addEventListener('click', toggleSidebar);
        if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);


        const profileBtn = document.getElementById('profile-dropdown-btn');
        const profileMenu = document.getElementById('profile-dropdown-menu');
        
        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });
            
            document.addEventListener('click', (e) => {
                if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });
        }


        if (window.matchMedia("(pointer: fine)").matches) {
            const cursorInner = document.querySelector('.cursor-inner');
            const cursorOuter = document.querySelector('.cursor-outer');
            window.addEventListener('mousemove', (e) => {
                cursorInner.style.left = `${e.clientX}px`; cursorInner.style.top = `${e.clientY}px`;
                cursorOuter.animate({ left: `${e.clientX}px`, top: `${e.clientY}px` }, { duration: 400, fill: "forwards", easing: "ease-out" });
            });
            

            const setupHovering = () => {
                document.querySelectorAll('a, button, input, .glass-panel').forEach(el => {
                    el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
                    el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
                });
            };
            setupHovering();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>
</html>
