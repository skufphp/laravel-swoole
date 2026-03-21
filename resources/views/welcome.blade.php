<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Octane · Swoole</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    <style>
        *, ::before, ::after { box-sizing: border-box; border-width: 0; border-style: solid; }
        html { line-height: 1.5; -webkit-text-size-adjust: 100%; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        body { margin: 0; }
        h1, h2, h3, h4 { font-size: inherit; font-weight: inherit; margin: 0; }
        p { margin: 0; }
        ul, ol { list-style: none; margin: 0; padding: 0; }
        a { color: inherit; text-decoration: inherit; }
        svg { display: block; vertical-align: middle; }
        table { border-collapse: collapse; }

        body {
            background-color: #FDFDFC;
            color: #1b1b18;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            width: 100%;
        }
        .main-inner {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 335px;
        }

        .hero-panel {
            background-color: #fff2f2;
            border-radius: 0.5rem 0.5rem 0 0;
            overflow: hidden;
            position: relative;
            aspect-ratio: 335/220;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            flex-shrink: 0;
        }
        .hero-logo {
            width: 100%;
            max-width: 260px;
            color: #F53003;
        }
        .hero-badge {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        .hero-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1b1b18;
            text-align: center;
            letter-spacing: -0.01em;
        }
        .hero-subtitle {
            font-size: 0.8125rem;
            color: #706f6c;
            text-align: center;
        }
        .version-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 0.75rem;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 500;
            border: 1px solid rgba(25,20,0,0.12);
            background: rgba(255,255,255,0.7);
            color: #1b1b18;
        }
        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background-color: #f53003;
            flex-shrink: 0;
        }
        .badge-dot.green { background-color: #22c55e; }
        .badge-dot.blue { background-color: #3b82f6; }
        .badge-dot.purple { background-color: #a855f7; }
        .badge-dot.orange { background-color: #f97316; }
        .badge-dot.red { background-color: #f53003; }

        .hero-inset {
            position: absolute;
            inset: 0;
            border-radius: inherit;
            box-shadow: inset 0 0 0 1px rgba(26,26,0,0.16);
            pointer-events: none;
        }

        .content-panel {
            font-size: 0.8125rem;
            line-height: 1.5385;
            flex: 1;
            padding: 1.5rem 1.5rem 3rem;
            background: white;
            box-shadow: inset 0 0 0 1px rgba(26,26,0,0.16);
            border-radius: 0 0 0.5rem 0.5rem;
            order: 1;
        }

        .section { margin-bottom: 1.75rem; }
        .section:last-child { margin-bottom: 0; }
        .section-title {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #706f6c;
            margin-bottom: 0.75rem;
        }

        .stack-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }
        .stack-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.625rem;
            border-radius: 0.375rem;
            border: 1px solid #e3e3e0;
            background: #FDFDFC;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1b1b18;
        }
        .stack-icon {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.625rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .stack-icon.red { background: #fee2e2; color: #dc2626; }
        .stack-icon.orange { background: #ffedd5; color: #ea580c; }
        .stack-icon.blue { background: #dbeafe; color: #2563eb; }
        .stack-icon.green { background: #dcfce7; color: #16a34a; }
        .stack-icon.purple { background: #f3e8ff; color: #9333ea; }

        .compare-table { width: 100%; border-collapse: collapse; font-size: 0.75rem; }
        .compare-table th {
            text-align: left;
            padding: 0.375rem 0.5rem;
            font-weight: 600;
            color: #706f6c;
            border-bottom: 1px solid #e3e3e0;
            font-size: 0.6875rem;
        }
        .compare-table td {
            padding: 0.375rem 0.5rem;
            border-bottom: 1px solid #f0f0ee;
            color: #1b1b18;
            vertical-align: top;
        }
        .compare-table tr:last-child td { border-bottom: none; }
        .compare-table .highlight { color: #f53003; font-weight: 500; }

        .services-list { display: flex; flex-direction: column; gap: 0.375rem; }
        .service-row {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0ee;
            font-size: 0.75rem;
        }
        .service-row:last-child { border-bottom: none; }
        .service-name { font-weight: 500; flex: 1; }
        .service-env {
            font-size: 0.6875rem;
            color: #a1a09a;
        }

        .divider {
            height: 1px;
            background: #e3e3e0;
            margin: 1.5rem 0;
        }

        .site-footer {
            width: 100%;
            max-width: 335px;
            margin: 1.5rem auto 0;
            font-size: 0.75rem;
            color: #a1a09a;
            text-align: center;
        }
        .footer-link { color: #f53003; }

        @media (min-width: 1024px) {
            .main-inner { max-width: 56rem; flex-direction: row; }
            .hero-panel {
                border-radius: 0 0.5rem 0.5rem 0;
                aspect-ratio: auto;
                width: 380px;
                margin-left: -1px;
                margin-bottom: 0;
            }
            .content-panel {
                padding: 2.5rem;
                border-radius: 0.5rem 0 0 0.5rem;
                order: 0;
            }
            .site-footer { max-width: 56rem; }
        }

        @media (min-width: 640px) {
            .stack-grid { grid-template-columns: repeat(3, 1fr); }
        }

        @media (prefers-color-scheme: dark) {
            body { background-color: #0a0a0a; color: #EDEDEC; }
            .hero-panel { background-color: #1D0002; }
            .hero-logo { color: #F61500; }
            .hero-title { color: #EDEDEC; }
            .hero-subtitle { color: #A1A09A; }
            .badge { background: rgba(255,255,255,0.06); border-color: #3E3E3A; color: #EDEDEC; }
            .hero-inset { box-shadow: inset 0 0 0 1px rgba(255,250,237,0.18); }
            .content-panel { background: #161615; color: #EDEDEC; box-shadow: inset 0 0 0 1px rgba(255,250,237,0.18); }
            .section-title { color: #A1A09A; }
            .stack-item { border-color: #3E3E3A; background: #1a1a19; color: #EDEDEC; }
            .stack-icon.red { background: rgba(220,38,38,0.15); color: #f87171; }
            .stack-icon.orange { background: rgba(234,88,12,0.15); color: #fb923c; }
            .stack-icon.blue { background: rgba(37,99,235,0.15); color: #60a5fa; }
            .stack-icon.green { background: rgba(22,163,74,0.15); color: #4ade80; }
            .stack-icon.purple { background: rgba(147,51,234,0.15); color: #c084fc; }
            .compare-table th { color: #A1A09A; border-color: #3E3E3A; }
            .compare-table td { border-color: #2a2a28; color: #EDEDEC; }
            .compare-table .highlight { color: #FF4433; }
            .service-row { border-color: #2a2a28; }
            .service-env { color: #62605b; }
            .divider { background: #3E3E3A; }
            .site-footer { color: #62605b; }
            .footer-link { color: #FF4433; }
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
<div class="main-wrapper">
    <div class="main-inner">
        <div class="content-panel">
            <div class="section">
                <div class="section-title">Технологический стек</div>
                <div class="stack-grid">
                    <div class="stack-item"><div class="stack-icon red">L</div><span>Laravel 13</span></div>
                    <div class="stack-item"><div class="stack-icon orange">O</div><span>Octane 2.x</span></div>
                    <div class="stack-item"><div class="stack-icon purple">SW</div><span>Swoole</span></div>
                    <div class="stack-item"><div class="stack-icon blue">PHP</div><span>PHP 8.5</span></div>
                    <div class="stack-item"><div class="stack-icon green">PG</div><span>PostgreSQL 18</span></div>
                    <div class="stack-item"><div class="stack-icon red">R</div><span>Redis 8.6</span></div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="section">
                <div class="section-title">Сервисы Docker</div>
                <div class="services-list">
                    <div class="service-row">
                        <span class="badge-dot red"></span>
                        <span class="service-name">Laravel Octane · Swoole</span>
                        <span class="service-env">HTTP сервер</span>
                    </div>
                    <div class="service-row">
                        <span class="badge-dot green"></span>
                        <span class="service-name">PostgreSQL 18.2 Alpine</span>
                        <span class="service-env">база данных</span>
                    </div>
                    <div class="service-row">
                        <span class="badge-dot red"></span>
                        <span class="service-name">Redis 8.6 Alpine</span>
                        <span class="service-env">кэш · очереди · сессии</span>
                    </div>
                    <div class="service-row">
                        <span class="badge-dot orange"></span>
                        <span class="service-name">Queue Worker</span>
                        <span class="service-env">фоновые задачи</span>
                    </div>
                    <div class="service-row">
                        <span class="badge-dot orange"></span>
                        <span class="service-name">Scheduler</span>
                        <span class="service-env">cron · каждые 60 сек</span>
                    </div>
                    <div class="service-row">
                        <span class="badge-dot blue"></span>
                        <span class="service-name">Node.js 24 · Vite HMR</span>
                        <span class="service-env">dev only</span>
                    </div>
                    <div class="service-row">
                        <span class="badge-dot purple"></span>
                        <span class="service-name">pgAdmin</span>
                        <span class="service-env">dev only</span>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="section">
                <div class="section-title">Nginx + PHP-FPM vs Octane + Swoole</div>
                <table class="compare-table">
                    <thead>
                    <tr>
                        <th>Аспект</th>
                        <th>Nginx + PHP-FPM</th>
                        <th class="highlight">Octane + Swoole</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Контейнеры</td>
                        <td>2 (Nginx + PHP-FPM)</td>
                        <td class="highlight">1 (Swoole HTTP server)</td>
                    </tr>
                    <tr>
                        <td>Протокол</td>
                        <td>FastCGI</td>
                        <td class="highlight">Встроенный HTTP сервер</td>
                    </tr>
                    <tr>
                        <td>Модель</td>
                        <td>Процесс на запрос</td>
                        <td class="highlight">Persistent workers</td>
                    </tr>
                    <tr>
                        <td>Bootstrap</td>
                        <td>На каждый запрос</td>
                        <td class="highlight">Один раз</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>

        </div>

        <div class="hero-panel">
            <svg class="hero-logo" viewBox="0 0 438 104" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.2036 -3H0V102.197H49.5189V86.7187H17.2036V-3Z" fill="currentColor" />
                <path d="M110.256 41.6337C108.061 38.1275 104.945 35.3731 100.905 33.3681C96.8667 31.3647 92.8016 30.3618 88.7131 30.3618C83.4247 30.3618 78.5885 31.3389 74.201 33.2923C69.8111 35.2456 66.0474 37.928 62.9059 41.3333C59.7643 44.7401 57.3198 48.6726 55.5754 53.1293C53.8287 57.589 52.9572 62.274 52.9572 67.1813C52.9572 72.1925 53.8287 76.8995 55.5754 81.3069C57.3191 85.7173 59.7636 89.6241 62.9059 93.0293C66.0474 96.4361 69.8119 99.1155 74.201 101.069C78.5885 103.022 83.4247 103.999 88.7131 103.999C92.8016 103.999 96.8667 102.997 100.905 100.994C104.945 98.9911 108.061 96.2359 110.256 92.7282V102.195H126.563V32.1642H110.256V41.6337ZM108.76 75.7472C107.762 78.4531 106.366 80.8078 104.572 82.8112C102.776 84.8161 100.606 86.4183 98.0637 87.6206C95.5202 88.823 92.7004 89.4238 89.6103 89.4238C86.5178 89.4238 83.7252 88.823 81.2324 87.6206C78.7388 86.4183 76.5949 84.8161 74.7998 82.8112C73.004 80.8078 71.6319 78.4531 70.6856 75.7472C69.7356 73.0421 69.2644 70.1868 69.2644 67.1821C69.2644 64.1758 69.7356 61.3205 70.6856 58.6154C71.6319 55.9102 73.004 53.5571 74.7998 51.5522C76.5949 49.5495 78.738 47.9451 81.2324 46.7427C83.7252 45.5404 86.5178 44.9396 89.6103 44.9396C92.7012 44.9396 95.5202 45.5404 98.0637 46.7427C100.606 47.9451 102.776 49.5487 104.572 51.5522C106.367 53.5571 107.762 55.9102 108.76 58.6154C109.756 61.3205 110.256 64.1758 110.256 67.1821C110.256 70.1868 109.756 73.0421 108.76 75.7472Z" fill="currentColor" />
                <path d="M242.805 41.6337C240.611 38.1275 237.494 35.3731 233.455 33.3681C229.416 31.3647 225.351 30.3618 221.262 30.3618C215.974 30.3618 211.138 31.3389 206.75 33.2923C202.36 35.2456 198.597 37.928 195.455 41.3333C192.314 44.7401 189.869 48.6726 188.125 53.1293C186.378 57.589 185.507 62.274 185.507 67.1813C185.507 72.1925 186.378 76.8995 188.125 81.3069C189.868 85.7173 192.313 89.6241 195.455 93.0293C198.597 96.4361 202.361 99.1155 206.75 101.069C211.138 103.022 215.974 103.999 221.262 103.999C225.351 103.999 229.416 102.997 233.455 100.994C237.494 98.9911 240.611 96.2359 242.805 92.7282V102.195H259.112V32.1642H242.805V41.6337ZM241.31 75.7472C240.312 78.4531 238.916 80.8078 237.122 82.8112C235.326 84.8161 233.156 86.4183 230.614 87.6206C228.07 88.823 225.251 89.4238 222.16 89.4238C219.068 89.4238 216.275 88.823 213.782 87.6206C211.289 86.4183 209.145 84.8161 207.35 82.8112C205.554 80.8078 204.182 78.4531 203.236 75.7472C202.286 73.0421 201.814 70.1868 201.814 67.1821C201.814 64.1758 202.286 61.3205 203.236 58.6154C204.182 55.9102 205.554 53.5571 207.35 51.5522C209.145 49.5495 211.288 47.9451 213.782 46.7427C216.275 45.5404 219.068 44.9396 222.16 44.9396C225.251 44.9396 228.07 45.5404 230.614 46.7427C233.156 47.9451 235.326 49.5487 237.122 51.5522C238.917 53.5571 240.312 55.9102 241.31 58.6154C242.306 61.3205 242.806 64.1758 242.806 67.1821C242.805 70.1868 242.305 73.0421 241.31 75.7472Z" fill="currentColor" />
                <path d="M438 -3H421.694V102.197H438V-3Z" fill="currentColor" />
                <path d="M139.43 102.197H155.735V48.2834H183.712V32.1665H139.43V102.197Z" fill="currentColor" />
                <path d="M324.49 32.1665L303.995 85.794L283.498 32.1665H266.983L293.748 102.197H314.242L341.006 32.1665H324.49Z" fill="currentColor" />
                <path d="M376.571 30.3656C356.603 30.3656 340.797 46.8497 340.797 67.1828C340.797 89.6597 356.094 104 378.661 104C391.29 104 399.354 99.1488 409.206 88.5848L398.189 80.0226C398.183 80.031 389.874 90.9895 377.468 90.9895C363.048 90.9895 356.977 79.3111 356.977 73.269H411.075C413.917 50.1328 398.775 30.3656 376.571 30.3656ZM357.02 61.0967C357.145 59.7487 359.023 43.3761 376.442 43.3761C393.861 43.3761 395.978 59.7464 396.099 61.0967H357.02Z" fill="currentColor" />
            </svg>
            <div class="hero-badge">
                <div class="hero-title">Octane · Swoole</div>
                <div class="hero-subtitle">High-performance Laravel with persistent workers</div>
                <div class="version-badges">
                    <span class="badge"><span class="badge-dot orange"></span>PHP 8.5</span>
                    <span class="badge"><span class="badge-dot purple"></span>Swoole</span>
                    <span class="badge"><span class="badge-dot green"></span>Docker</span>
                </div>
            </div>
            <div class="hero-inset"></div>
        </div>
    </div>
</div>

<footer class="site-footer">
    Laravel Octane + Swoole · <a href="https://laravel.com/docs/13.x/octane" class="footer-link">laravel.com/docs/octane</a>
</footer>
</body>
</html>
