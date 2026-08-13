<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hot Rocks') - Rock Show Coverage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #F7F4EE;
            --surface: #FFFFFF;
            --ink: #15130F;
            --ink-soft: #6B675C;
            --ink-faint: #A7A296;
            --line: #E6E1D4;
            --accent: #C8102E;
            --accent-dark: #96081F;
            --accent-soft: #FBDEE2;
            --on-accent: #FFFFFF;
            --radius-lg: 20px;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 3px rgba(21,19,15,.07);
            --shadow-md: 0 10px 30px -12px rgba(21,19,15,.22);
            --shadow-lg: 0 28px 60px -20px rgba(21,19,15,.30);
            --header-h: 82px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--ink);
            line-height: 1.65;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, .display {
            font-family: 'Bebas Neue', 'Inter', sans-serif;
            font-weight: 400;
            letter-spacing: .5px;
            line-height: 1.05;
            color: var(--ink);
        }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 24px; }

        /* ---------- Kicker / section titles ---------- */
        .kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 10px;
        }
        .kicker::before {
            content: "";
            width: 22px;
            height: 2px;
            background: var(--accent);
            display: inline-block;
        }
        .section-head { margin-bottom: 36px; }
        .section-head h2 {
            font-size: clamp(2.2rem, 4.5vw, 3.2rem);
            text-transform: uppercase;
        }

        /* ---------- Header ---------- */
        header.site-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(247, 244, 238, .82);
            backdrop-filter: blur(14px) saturate(160%);
            -webkit-backdrop-filter: blur(14px) saturate(160%);
            border-bottom: 1px solid var(--line);
            transition: box-shadow .3s ease;
        }
        header.site-header.is-scrolled { box-shadow: var(--shadow-sm); }
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header-h);
            gap: 24px;
        }
        .logo-link { display: flex; align-items: center; gap: 10px; }
        .logo-img {
            height: 58px;
            width: auto;
            border-radius: 10px;
            box-shadow: var(--shadow-sm);
            transition: transform .35s cubic-bezier(.2,.8,.2,1);
        }
        .logo-link:hover .logo-img { transform: rotate(-4deg) scale(1.06); }

        nav.main-nav { display: flex; align-items: center; gap: 30px; }
        nav.main-nav a:not(.btn) {
            font-size: .92rem;
            font-weight: 600;
            color: var(--ink-soft);
            position: relative;
            padding: 6px 0;
        }
        nav.main-nav a:not(.btn)::after {
            content: "";
            position: absolute;
            left: 0; right: 100%;
            bottom: 0;
            height: 2px;
            background: var(--accent);
            transition: right .28s cubic-bezier(.2,.8,.2,1);
        }
        nav.main-nav a:not(.btn):hover { color: var(--ink); }
        nav.main-nav a:not(.btn):hover::after,
        nav.main-nav a:not(.btn).is-active::after { right: 0; }
        nav.main-nav a:not(.btn).is-active { color: var(--ink); }

        .nav-toggle {
            display: none;
            width: 40px; height: 40px;
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            background: var(--surface);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 4px;
        }
        .nav-toggle span { width: 18px; height: 2px; background: var(--ink); display: block; transition: transform .25s ease, opacity .25s ease; }
        .nav-toggle.is-open span:nth-child(1) { transform: translateY(6px) rotate(45deg); }
        .nav-toggle.is-open span:nth-child(2) { opacity: 0; }
        .nav-toggle.is-open span:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }

        /* ---------- Buttons ---------- */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: .88rem;
            padding: 11px 22px;
            border-radius: 999px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform .25s cubic-bezier(.2,.8,.2,1), box-shadow .25s ease, background .2s ease, color .2s ease, border-color .2s ease;
            white-space: nowrap;
        }
        .btn-accent { background: var(--accent); color: var(--on-accent); box-shadow: var(--shadow-sm); }
        .btn-accent:hover { background: var(--accent-dark); transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .btn-outline { background: transparent; color: var(--ink); border-color: var(--line); }
        .btn-outline:hover { border-color: var(--ink); transform: translateY(-2px); }
        .btn-ghost { background: var(--surface); color: var(--ink); border-color: var(--line); }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { padding: 7px 14px; font-size: .78rem; }
        .btn-block { width: 100%; justify-content: center; }

        /* ---------- Cards ---------- */
        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s ease, border-color .35s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: transparent;
        }
        .media-crop { overflow: hidden; border-radius: var(--radius-lg) var(--radius-lg) 0 0; }
        .media-crop img { transition: transform .6s cubic-bezier(.2,.8,.2,1); }
        .card-hover:hover .media-crop img { transform: scale(1.07); }

        /* ---------- Tags / badges ---------- */
        .tag {
            display: inline-block;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 5px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent-dark);
        }
        .tag-outline { background: transparent; border: 1px solid var(--line); color: var(--ink-soft); }
        .badge { display: inline-block; padding: 5px 13px; border-radius: 999px; font-size: .78rem; font-weight: 700; }
        .badge-pending { background: #FFF1C9; color: #8A6300; }
        .badge-approved { background: #D9F2E3; color: #157347; }
        .badge-rejected { background: #FBDADA; color: #B42318; }

        /* ---------- Forms ---------- */
        label { display: block; font-weight: 700; font-size: .85rem; margin-bottom: 8px; }
        input[type=text], input[type=url], input[type=email], input[type=password],
        input[type=datetime-local], input[type=date], textarea, select {
            width: 100%;
            font-family: inherit;
            font-size: .95rem;
            padding: 12px 14px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            color: var(--ink);
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }
        .field { margin-bottom: 22px; }
        .field-error { color: #C0392B; font-size: .82rem; margin-top: 6px; display: block; }
        .field-checkbox { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; }
        .field-checkbox input { width: auto; }
        .field-checkbox label { margin: 0; font-weight: 600; }
        .field-hint { font-size: .8rem; color: var(--ink-soft); margin-top: 6px; }
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            max-height: 220px;
            overflow-y: auto;
        }
        .checkbox-grid label { display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: .88rem; margin: 0; }
        .checkbox-grid input { width: auto; }
        .current-image { width: 100%; max-width: 260px; border-radius: var(--radius-sm); border: 1px solid var(--line); margin-bottom: 12px; }
        .gallery-manage { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .gallery-manage-item { position: relative; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--line); aspect-ratio: 1; }
        .gallery-manage-item img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-manage-item form { position: absolute; top: 6px; right: 6px; }
        .gallery-manage-item button {
            width: 26px; height: 26px;
            border-radius: 50%;
            border: none;
            background: rgba(21,19,15,.75);
            color: #fff;
            cursor: pointer;
            font-size: .95rem;
            line-height: 1;
        }
        .gallery-manage-item button:hover { background: var(--accent); }
        input[type=file] {
            width: 100%;
            font-size: .88rem;
            padding: 10px 12px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
        }

        /* ---------- Pagination ---------- */
        .pagination-wrap { display: flex; justify-content: center; margin-top: 48px; }
        .pagination-wrap nav > div { display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; }

        /* ---------- Alerts ---------- */
        .alert { padding: 16px 20px; border-radius: var(--radius-sm); margin-bottom: 24px; border-left: 4px solid; font-weight: 600; font-size: .92rem; }
        .alert-success { background: #EAF7EF; border-color: #28A745; color: #14532D; }
        .alert-error { background: #FCEBEB; border-color: #DC3545; color: #7F1D1D; }

        main.site-main { min-height: 60vh; padding: 56px 0 90px; }

        /* ---------- Scroll reveal ---------- */
        .js .reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s cubic-bezier(.2,.8,.2,1), transform .7s cubic-bezier(.2,.8,.2,1); }
        .js .reveal.is-visible { opacity: 1; transform: translateY(0); }

        /* ---------- Footer ---------- */
        footer.site-footer {
            border-top: 1px solid var(--line);
            margin-top: 80px;
            padding: 56px 0 32px;
            background: var(--ink);
            color: #C9C6BC;
        }
        .footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .footer-blurb { margin-top: 14px; font-size: .9rem; max-width: 34ch; color: #A7A296; }
        .footer-col h4 { font-family: 'Inter', sans-serif; font-weight: 700; font-size: .8rem; letter-spacing: 1.5px; text-transform: uppercase; color: #F7F4EE; margin-bottom: 16px; }
        .footer-col a { display: block; font-size: .9rem; color: #A7A296; margin-bottom: 10px; transition: color .2s ease; }
        .footer-col a:hover { color: var(--accent); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.1); padding-top: 24px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: .78rem; color: #79766C; }

        /* ---------- Lightbox ---------- */
        [data-lightbox] { cursor: zoom-in; }
        .lightbox {
            position: fixed;
            inset: 0;
            z-index: 1000;
            background: rgba(10,9,7,.92);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
        }
        .lightbox.is-open { opacity: 1; pointer-events: auto; }
        .lightbox img {
            max-width: 100%;
            max-height: 100%;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            transform: scale(.96);
            transition: transform .25s ease;
        }
        .lightbox.is-open img { transform: scale(1); }
        .lightbox-close {
            position: absolute;
            top: 20px; right: 24px;
            width: 44px; height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.25);
            background: rgba(255,255,255,.08);
            color: #F7F4EE;
            font-size: 1.6rem;
            line-height: 1;
            cursor: pointer;
        }
        .lightbox-close:hover { background: var(--accent); border-color: var(--accent); }

        @media (max-width: 860px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 760px) {
            nav.main-nav {
                position: fixed;
                top: var(--header-h);
                left: 0; right: 0;
                background: var(--bg);
                border-bottom: 1px solid var(--line);
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 8px 24px 20px;
                transform: translateY(-12px);
                opacity: 0;
                pointer-events: none;
                transition: transform .25s ease, opacity .25s ease;
            }
            nav.main-nav.is-open { transform: translateY(0); opacity: 1; pointer-events: auto; }
            nav.main-nav a:not(.btn) { padding: 14px 0; border-bottom: 1px solid var(--line); }
            nav.main-nav a:not(.btn)::after { display: none; }
            nav.main-nav .btn { margin-top: 14px; }
            .nav-toggle { display: flex; }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
        }
    </style>
    @yield('extra-styles')
</head>
<body>
    <script>document.documentElement.classList.add('js');</script>

    <header class="site-header" id="siteHeader">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="logo-link">
                @include('partials.logo')
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <nav class="main-nav" id="mainNav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Inicio</a>
                <a href="{{ route('reviews.index') }}" class="{{ request()->routeIs('reviews.*') ? 'is-active' : '' }}">Reseñas</a>
                <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'is-active' : '' }}">Noticias</a>
                <a href="{{ route('bands.index') }}" class="{{ request()->routeIs('bands.*') ? 'is-active' : '' }}">Bandas</a>
                <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'is-active' : '' }}">Agenda</a>
                @auth
                    <a href="{{ route('shows.submit') }}" class="{{ request()->routeIs('shows.submit') ? 'is-active' : '' }}">Cargar Show</a>
                    @if(auth()->user()->isBand())
                        <a href="{{ route('band.profile.edit') }}" class="{{ request()->routeIs('band.profile.*') ? 'is-active' : '' }}">Mi Banda</a>
                    @endif
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                        <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.*') ? 'is-active' : '' }}">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display:contents;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'is-active' : '' }}">Ingresar</a>
                @endauth
                <a href="{{ env('SHOP_URL', '#') }}" class="btn btn-accent" target="_blank" rel="noopener">Shop →</a>
            </nav>
        </div>
    </header>

    <main class="site-main">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="{{ route('home') }}" class="logo-link">@include('partials.logo')</a>
                    <p class="footer-blurb">Cobertura independiente de los mejores shows en vivo: Reseñas, agenda y las bandas que están moviendo la escena.</p>
                </div>
                <div class="footer-col">
                    <h4>Explorar</h4>
                    <a href="{{ route('reviews.index') }}">Reseñas</a>
                    <a href="{{ route('news.index') }}">Noticias</a>
                    <a href="{{ route('bands.index') }}">Bandas</a>
                    <a href="{{ route('agenda.index') }}">Agenda</a>
                </div>
                <div class="footer-col">
                    <h4>Hot Rocks</h4>
                    <a href="{{ env('SHOP_URL', '#') }}" target="_blank" rel="noopener">Shop</a>
                    <a href="{{ env('INSTAGRAM_URL', '#') }}" target="_blank" rel="noopener">Instagram</a>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} Hot Rocks Shows</span>
                <span>Logo y marca sujetos a revisión antes de lanzamiento público</span>
            </div>
        </div>
    </footer>

    <div class="lightbox" id="lightbox" aria-hidden="true">
        <button type="button" class="lightbox-close" id="lightboxClose" aria-label="Cerrar">&times;</button>
        <img src="" alt="" id="lightboxImg">
    </div>

    <script>
        (function () {
            var header = document.getElementById('siteHeader');
            var onScroll = function () {
                header.classList.toggle('is-scrolled', window.scrollY > 8);
            };
            document.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            var toggle = document.getElementById('navToggle');
            var nav = document.getElementById('mainNav');
            toggle.addEventListener('click', function () {
                var open = nav.classList.toggle('is-open');
                toggle.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', open);
            });

            if ('IntersectionObserver' in window) {
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            io.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
                document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
            } else {
                document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('is-visible'); });
            }

            var lightbox = document.getElementById('lightbox');
            var lightboxImg = document.getElementById('lightboxImg');
            var lightboxClose = document.getElementById('lightboxClose');

            function openLightbox(src, alt) {
                lightboxImg.src = src;
                lightboxImg.alt = alt || '';
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }
            function closeLightbox() {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
                lightboxImg.src = '';
                document.body.style.overflow = '';
            }

            document.addEventListener('click', function (e) {
                var trigger = e.target.closest('[data-lightbox]');
                if (!trigger) return;
                e.preventDefault();
                var img = trigger.querySelector('img');
                openLightbox(trigger.getAttribute('data-lightbox'), trigger.getAttribute('data-lightbox-alt') || (img ? img.alt : ''));
            });
            lightboxClose.addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', function (e) {
                if (e.target === lightbox) closeLightbox();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeLightbox();
            });
        })();
    </script>
    @yield('extra-scripts')
</body>
</html>
