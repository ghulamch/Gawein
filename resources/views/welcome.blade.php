<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GAWEIN | Portal Pekerjaan Informal Masa Depan</title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    
    <div class="cursor-inner"></div>
    <div class="cursor-outer"></div>

    
    <div id="preloader">
        <div class="loader-content">
            <img src="{{ asset('logo.png') }}" alt="GAWEIN" style="height: 48px; width: auto;" class="mb-4 mx-auto animate-pulse">
            <div class="loader-progress"></div>
        </div>
    </div>

    
    <nav id="navbar">
        <div class="container nav-container">
            <a href="#" class="logo inline-block">
                <img src="{{ asset('logo.png') }}" alt="GAWEIN" style="height: 35px; width: auto;">
            </a>
            <ul class="nav-links">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#features">Fitur</a></li>
            </ul>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                
                <button class="theme-toggle" id="theme-toggle" title="Ganti Tema">
                    <i class="fa-solid fa-sun"></i>
                    <i class="fa-solid fa-moon"></i>
                </button>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-glow">Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" style="color: var(--text-heading); font-weight: 600; text-transform:uppercase; font-size:0.9rem; letter-spacing: 1px; transition: color 0.3s;" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='var(--text-heading)'">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-border">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    
    <section id="home" class="hero">
        <div class="hero-bg-animated">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>
        
        <div class="container hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <i class="fa-solid fa-bolt"></i> Jaringan Pekerjaan Masa Depan
                </div>
                <h1 class="hero-title">
                    <span class="line"><span>Mendefinisikan Ulang</span></span>
                    <span class="line"><span>Talenta <span class="text-gradient">Lokal</span></span></span>
                </h1>
                <p class="hero-desc">Rasakan masa depan dunia kerja informal. Terhubung secara instan dengan tenaga profesional lokal terverifikasi melalui platform yang imersif, aman, dan super cepat.</p>
                <div class="hero-btns">
                    <a href="{{ route('register') ?? '#' }}" class="btn btn-glow">Mulai Sekarang</a>
                    <a href="#features" class="btn btn-border"><i class="fa-solid fa-play" style="margin-right:8px;"></i> Cara Kerjanya</a>
                </div>
            </div>
            
            <div class="hero-visual">
                
                <div class="floating-element fe-1">
                    <i class="fa-solid fa-circle-check" style="color: var(--secondary); font-size: 1.5rem;"></i>
                    <div>
                        <div style="font-weight: 700; font-size: 0.9rem;">Pekerja Terverifikasi</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Identitas Dicek</div>
                    </div>
                </div>
                <div class="floating-element fe-2">
                    <div style="display: flex; gap: 5px; color: #FFD700;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <div style="font-weight: 700; font-size: 0.9rem;">Rating 5.0</div>
                </div>

                
                <div class="glass-card-3d" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.2">
                    <div class="card-inner">
                        <div class="card-tag"><span class="status-dot"></span> Tersedia Sekarang</div>
                        <h3 class="card-title">Teknisi Pipa Senior</h3>
                        <p style="color: var(--text-muted); margin-bottom: 1.5rem;"><i class="fa-solid fa-location-dot" style="color: var(--secondary); margin-right: 5px;"></i> Jakarta Selatan, Jarak 2km</p>
                        
                        <div style="display: flex; align-items: center; gap: 1rem; border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
                            <img src="https://ui-avatars.com/api/?name=Budi+S&background=0047FF&color=fff&size=120" style="width: 60px; height: 60px; border-radius: 50%; border: 2px solid var(--secondary);" alt="User">
                            <div>
                                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-heading);">Budi Santoso</h4>
                                <p style="font-size: 0.85rem; color: var(--secondary);">120+ Pekerjaan Selesai</p>
                            </div>
                        </div>
                        
                        <div class="card-price">Rp 150rb <span>/ jam</span></div>
                        
                        <button class="btn btn-glow" style="width: 100%;">Sewa Instan</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <div class="marquee-wrapper">
        <div class="marquee">
            <span>Teknisi Listrik <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Pipa <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Kayu <i class="fa-solid fa-asterisk"></i></span>
            <span>Pembersih <i class="fa-solid fa-asterisk"></i></span>
            <span>Mekanik <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Cat <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Kebun <i class="fa-solid fa-asterisk"></i></span>
            <span>Teknisi Listrik <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Pipa <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Kayu <i class="fa-solid fa-asterisk"></i></span>
            <span>Pembersih <i class="fa-solid fa-asterisk"></i></span>
            <span>Mekanik <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Cat <i class="fa-solid fa-asterisk"></i></span>
            <span>Tukang Kebun <i class="fa-solid fa-asterisk"></i></span>
        </div>
    </div>

    
    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Dirancang untuk <span>Keunggulan</span></h2>
                <p class="section-desc">Kami memanfaatkan teknologi mutakhir untuk menciptakan ekosistem yang mulus bagi pekerja informal dan penyedia kerja.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card f-card">
                    <div class="f-icon"><i class="fa-solid fa-location-crosshairs"></i></div>
                    <h3 class="f-title">Radar Hyper-Lokal</h3>
                    <p class="f-desc">Algoritma canggih kami mencocokkan Anda dengan tenaga profesional terbaik di sekitar Anda, mengurangi waktu tunggu menjadi nol.</p>
                </div>
                <div class="feature-card f-card">
                    <div class="f-icon"><i class="fa-solid fa-fingerprint"></i></div>
                    <h3 class="f-title">Verifikasi Biometrik</h3>
                    <p class="f-desc">Protokol keamanan tingkat tinggi memastikan setiap pengguna terverifikasi. Kepercayaan dan keamanan terpasang di inti platform.</p>
                </div>
                <div class="feature-card f-card">
                    <div class="f-icon"><i class="fa-solid fa-bolt-lightning"></i></div>
                    <h3 class="f-title">Pemesanan Kilat</h3>
                    <p class="f-desc">Sistem pemesanan satu sentuhan. Negosiasi instan via obrolan terenkripsi dan selesaikan pekerjaan Anda lebih cepat dari sebelumnya.</p>
                </div>
                <div class="feature-card f-card">
                    <div class="f-icon"><i class="fa-solid fa-wallet"></i></div>
                    <h3 class="f-title">Escrow Pintar</h3>
                    <p class="f-desc">Uang Anda aman. Pembayaran ditahan dengan aman dan hanya dilepaskan ketika pekerjaan diselesaikan sesuai kepuasan Anda.</p>
                </div>
                <div class="feature-card f-card">
                    <div class="f-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h3 class="f-title">Analitik Reputasi</h3>
                    <p class="f-desc">Dapatkan wawasan mendalam tentang riwayat performa pekerja, ulasan transparan, dan skor keandalan berbasis AI.</p>
                </div>
                <div class="feature-card f-card">
                    <div class="f-icon"><i class="fa-solid fa-globe"></i></div>
                    <h3 class="f-title">Pusat Komunitas</h3>
                    <p class="f-desc">Lebih dari sekadar portal. Bergabung dengan forum lokal, berbagi keahlian, dan tumbuh bersama di komunitas digital yang aktif.</p>
                </div>
            </div>
        </div>
    </section>

    
    <section class="stats-wrap">
        <div class="container stats-grid">
            <div class="stat-box">
                <div class="num" data-target="25000">0</div>
                <div class="label">Pengguna Aktif</div>
            </div>
            <div class="stat-box">
                <div class="num" data-target="85000">0</div>
                <div class="label">Pekerjaan Selesai</div>
            </div>
            <div class="stat-box">
                <div class="num" data-target="150">0</div>
                <div class="label">Kota Aktif</div>
            </div>
            <div class="stat-box">
                <div class="num" data-target="99">0</div>
                <div class="label">Tingkat Kesuksesan (%)</div>
            </div>
        </div>
    </section>

    
    <section class="cta-area">
        <div class="cta-glow"></div>
        <div class="container">
            <h2 class="cta-title">Siap untuk Merubah <span>Paradigma?</span></h2>
            <p style="font-size: 1.2rem; color: var(--text-muted); max-width: 650px; margin: 0 auto 3rem;">Bergabunglah dengan GAWEIN hari ini dan rasakan jaringan pekerjaan informal terbaik yang dibangun untuk era modern.</p>
            <a href="{{ route('register') ?? '#' }}" class="btn btn-glow" style="padding: 1.2rem 4rem; font-size: 1.1rem;">Buat Akun Sekarang</a>
        </div>
    </section>

    
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="#" class="inline-block mb-4">
                        <img src="{{ asset('logo.png') }}" alt="GAWEIN" style="height: 35px; width: auto;" class="filter brightness-0 invert">
                    </a>
                    <p style="color: var(--text-muted); line-height: 1.8;">Merevolusi sektor informal melalui teknologi, kepercayaan, dan pemberdayaan komunitas.</p>
                    <div class="socials">
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div style="text-align: center; border-top: 1px solid var(--glass-border); padding-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
                &copy; {{ date('Y') }} GAWEIN. Hak Cipta Dilindungi. Dirancang untuk Masa Depan.
            </div>
        </div>
    </footer>

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    <script>

        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;


        const savedTheme = localStorage.getItem('gawein-theme');
        if (savedTheme === 'light') {
            body.classList.add('light-mode');
        } else if (!savedTheme && window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
            body.classList.add('light-mode');
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            if (body.classList.contains('light-mode')) {
                localStorage.setItem('gawein-theme', 'light');
            } else {
                localStorage.setItem('gawein-theme', 'dark');
            }
        });


        const cursorInner = document.querySelector('.cursor-inner');
        const cursorOuter = document.querySelector('.cursor-outer');
        
        window.addEventListener('mousemove', (e) => {
            const posX = e.clientX;
            const posY = e.clientY;
            
            cursorInner.style.left = `${posX}px`;
            cursorInner.style.top = `${posY}px`;
            
            cursorOuter.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, { duration: 400, fill: "forwards", easing: "ease-out" });
        });

        const hoverElements = document.querySelectorAll('a, button, .glass-card-3d, .feature-card, .btn');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
            el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
        });


        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        });


        gsap.registerPlugin(ScrollTrigger);


        const tlLoader = gsap.timeline();
        tlLoader.to('.loader-bar', { width: '100%', duration: 1.5, ease: "power2.inOut" })
                .to('.loader-brand', { opacity: 0, scale: 0.8, duration: 0.5 }, "+=0.2")
                .to('#preloader', { yPercent: -100, duration: 0.8, ease: "power4.inOut" })
                .call(initHeroAnimations);

        function initHeroAnimations() {
            const tlHero = gsap.timeline();
            
            tlHero.to('.hero-badge', { opacity: 1, y: 0, duration: 0.6, ease: "back.out(1.7)" })
                  .to('.hero-title .line span', { y: 0, duration: 0.8, stagger: 0.2, ease: "power4.out" }, "-=0.2")
                  .to('.hero-desc', { opacity: 1, duration: 0.8 }, "-=0.4")
                  .to('.hero-btns', { opacity: 1, duration: 0.5 }, "-=0.4")
                  .to('.glass-card-3d', { opacity: 1, duration: 1, ease: "power2.out" }, "-=0.5");
        }


        gsap.utils.toArray('.f-card').forEach((card, i) => {
            gsap.to(card, {
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                },
                y: 0,
                opacity: 1,
                duration: 0.8,
                ease: "power3.out",
                delay: i * 0.1
            });
        });


        const stats = document.querySelectorAll('.stat-box .num');
        stats.forEach(stat => {
            let target = parseInt(stat.getAttribute('data-target'));
            
            ScrollTrigger.create({
                trigger: stat,
                start: "top 90%",
                onEnter: () => {
                    gsap.to(stat, {
                        innerHTML: target,
                        duration: 2.5,
                        snap: { innerHTML: 1 },
                        ease: "power2.out",
                        onUpdate: function() {

                            if(target > 1000) {
                                stat.innerHTML = Math.round(this.targets()[0].innerHTML).toLocaleString() + "+";
                            } else {
                                stat.innerHTML = Math.round(this.targets()[0].innerHTML) + (target === 99 ? "%" : "+");
                            }
                        }
                    });
                },
                once: true
            });
        });


        gsap.from('.cta-title', {
            scrollTrigger: {
                trigger: '.cta-area',
                start: "top 80%",
                scrub: 1
            },
            scale: 0.8,
            opacity: 0.5
        });

    </script>
</body>
</html>
