<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | GAWEIN</title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; position: relative; z-index: 10; padding: 2rem; }
        .auth-card { background: var(--glass-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 24px; padding: 3rem; width: 100%; max-width: 450px; box-shadow: 0 30px 60px var(--card-shadow); }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-heading); font-weight: 600; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 1rem 1.5rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-light); font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.3s; }
        body.light-mode .form-control { background: rgba(255,255,255,0.5); }
        .form-control:focus { outline: none; border-color: var(--secondary); box-shadow: 0 0 15px rgba(0, 163, 255, 0.2); background: rgba(0,0,0,0.4); }
        body.light-mode .form-control:focus { background: rgba(255,255,255,0.8); }
        .auth-header { text-align: center; margin-bottom: 2.5rem; }
        .auth-header .logo { font-size: 2.5rem; margin-bottom: 1rem; display: inline-block; }
    </style>
</head>
<body>
    
    <div class="cursor-inner"></div>
    <div class="cursor-outer"></div>

    
    <div class="hero-bg-animated" style="position: fixed;">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    
    <button class="theme-toggle" id="theme-toggle" title="Ganti Tema" style="position: fixed; top: 2rem; right: 2rem; z-index: 100;">
        <i class="fa-solid fa-sun"></i>
        <i class="fa-solid fa-moon"></i>
    </button>
    <a href="{{ url('/') }}" class="theme-toggle" style="position: fixed; top: 2rem; left: 2rem; z-index: 100;" title="Kembali ke Beranda">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    
    <div class="auth-container">
        <div class="auth-card" data-tilt data-tilt-max="3" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.05">
            <div class="auth-header">
                <a href="{{ url('/') }}" class="inline-block mb-3">
                    <img src="{{ asset('logo.png') }}" alt="GAWEIN" style="height: 40px; width: auto;">
                </a>
                <h2 style="color: var(--text-heading); font-size: 1.8rem; margin-bottom: 0.5rem;">Selamat Datang Kembali</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Silakan masuk untuk melanjutkan.</p>
            </div>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                @if ($errors->any())
                    <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.3); color: #ff6b6b; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" placeholder="••••••••" style="padding-right: 2.5rem;" required>
                        <i class="fa-solid fa-eye-slash toggle-password" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); transition: 0.3s;" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='var(--text-muted)'"></i>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; font-size: 0.85rem;">
                    <label style="display: flex; align-items: center; gap: 8px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" style="cursor: pointer;"> Ingat saya
                    </label>
                    <a href="{{ route('forgot-password') }}" style="color: var(--secondary); font-weight: 600; text-decoration: none;">Lupa Sandi?</a>
                </div>
                <button type="submit" class="btn btn-glow" style="width: 100%; border-radius: 12px; padding: 1.2rem;">Masuk</button>
            </form>
            
            <div style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: var(--text-muted);">
                Belum punya akun? <a href="{{ route('register') }}" style="color: var(--secondary); font-weight: 700;">Daftar Sekarang</a>
            </div>
        </div>
    </div>

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script>

        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;
        const savedTheme = localStorage.getItem('gawein-theme');
        if (savedTheme === 'light') { body.classList.add('light-mode'); }
        else if (!savedTheme && window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) { body.classList.add('light-mode'); }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            localStorage.setItem('gawein-theme', body.classList.contains('light-mode') ? 'light' : 'dark');
        });


        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });


        const cursorInner = document.querySelector('.cursor-inner');
        const cursorOuter = document.querySelector('.cursor-outer');
        window.addEventListener('mousemove', (e) => {
            cursorInner.style.left = `${e.clientX}px`; cursorInner.style.top = `${e.clientY}px`;
            cursorOuter.animate({ left: `${e.clientX}px`, top: `${e.clientY}px` }, { duration: 400, fill: "forwards", easing: "ease-out" });
        });
        document.querySelectorAll('a, button, input').forEach(el => {
            el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
            el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
        });
    </script>
</body>
</html>
