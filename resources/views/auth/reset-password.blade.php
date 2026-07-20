<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Sandi Baru | GAWEIN</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; position: relative; z-index: 10; padding: 2rem; }
        .auth-card { background: var(--glass-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 24px; padding: 3rem; width: 100%; max-width: 450px; box-shadow: 0 30px 60px var(--card-shadow); text-align: center; }
        .form-group { margin-bottom: 1.5rem; text-align: left; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-heading); font-weight: 600; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 1rem 1.5rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-light); font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.3s; }
        body.light-mode .form-control { background: rgba(255,255,255,0.5); }
        .form-control:focus { outline: none; border-color: var(--secondary); box-shadow: 0 0 15px rgba(0, 163, 255, 0.2); background: rgba(0,0,0,0.4); }
        body.light-mode .form-control:focus { background: rgba(255,255,255,0.8); }
        
        .icon-circle { width: 80px; height: 80px; background: rgba(0, 163, 255, 0.1); border: 1px solid rgba(0, 163, 255, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--secondary); margin: 0 auto 1.5rem; box-shadow: inset 0 0 20px rgba(0, 163, 255, 0.1); }
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
    
    <a href="{{ route('otp') }}" class="theme-toggle" style="position: fixed; top: 2rem; left: 2rem; z-index: 100;" title="Kembali">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <div class="auth-container">
        <div class="auth-card" data-tilt data-tilt-max="3" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.05">
            <div class="icon-circle">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2 style="color: var(--text-heading); font-size: 1.8rem; margin-bottom: 0.5rem; font-family: 'Outfit';">Buat Sandi Baru</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; line-height: 1.6;">OTP berhasil diverifikasi. Silakan masukkan kata sandi baru Anda yang kuat dan aman.</p>
            
            @if(session('error'))
                <div style="background: rgba(255, 59, 48, 0.1); color: #ff3b30; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: center; border: 1px solid rgba(255, 59, 48, 0.3);">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background: rgba(255, 59, 48, 0.1); color: #ff3b30; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: center; border: 1px solid rgba(255, 59, 48, 0.3);">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('reset-password.post') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
                <div class="form-group">
                    <label>Kata Sandi Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" placeholder="••••••••" style="padding-right: 2.5rem;" required>
                        <i class="fa-solid fa-eye-slash toggle-password" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); transition: 0.3s;" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='var(--text-muted)'"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Sandi Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" style="padding-right: 2.5rem;" required>
                        <i class="fa-solid fa-eye-slash toggle-password" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); transition: 0.3s;" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='var(--text-muted)'"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-glow" style="width: 100%; border-radius: 12px; padding: 1.2rem; margin-top: 1rem;">Simpan & Masuk</button>
            </form>
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
