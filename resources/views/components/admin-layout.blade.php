@props([
    'title' => 'Admin',
    'subtitle' => null,
    'eyebrow' => null,
])

<x-app-layout>
    <div class="admin-page">
        <style>
            :root {
                --elit-bg: #1b1230;
                --elit-bg-deep: #0f091b;
                --elit-sidebar: #120b21;
                --elit-panel: #3b2861;
                --elit-panel-soft: #493476;
                --elit-panel-deep: #2e1d4f;
                --elit-border: rgba(216, 205, 246, .22);
                --elit-border-soft: rgba(216, 205, 246, .12);
                --elit-accent: #c4b7e8;
                --elit-accent-strong: #d8cdf6;
                --elit-text: #ffffff;
                --elit-muted: rgba(229, 220, 255, .72);
            }

            .admin-page {
                min-height: 100vh;
                background:
                    radial-gradient(circle at 30% 0%, rgba(132, 101, 190, .25), transparent 34rem),
                    linear-gradient(135deg, #1a0f2f 0%, #24153d 46%, #120a22 100%);
                color: var(--elit-text);
                font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            .admin-shell {
                min-height: 100vh;
                display: grid;
                grid-template-columns: 18.75rem minmax(0, 1fr);
            }

            .admin-sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                align-self: start;
                background:
                    radial-gradient(circle at 35% 8%, rgba(124, 93, 181, .18), transparent 18rem),
                    linear-gradient(180deg, #171028 0%, #10091d 58%, #0b0614 100%);
                border-right: 1px solid rgba(216, 205, 246, .12);
                box-shadow: 22px 0 55px rgba(5, 2, 13, .26);
                padding: 1.85rem 1.55rem;
                z-index: 10;
            }

            .admin-sidebar-inner {
                min-height: calc(100vh - 3.7rem);
                display: flex;
                flex-direction: column;
            }

            .admin-brand-card {
                display: flex;
                align-items: center;
                gap: .9rem;
                min-height: 4.85rem;
                border-radius: 1.35rem;
                border: 1px solid rgba(216, 205, 246, .26);
                background: rgba(24, 14, 43, .88);
                color: #fff;
                text-decoration: none;
                padding: .92rem 1.05rem;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .06), 0 18px 44px rgba(8, 3, 22, .26);
            }

            .admin-brand-card:hover { border-color: rgba(216, 205, 246, .42); }
            .admin-brand-icon {
                width: 2.75rem;
                height: 2.75rem;
                border-radius: .72rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #7e66b7, #584188);
                color: #fff;
                font-size: 1.35rem;
                box-shadow: 0 12px 22px rgba(20, 9, 43, .28);
            }
            .admin-brand-card strong { display: block; font-size: .95rem; line-height: 1.1; font-weight: 950; letter-spacing: -.02em; }
            .admin-brand-card small { display: block; margin-top: .18rem; color: var(--elit-muted); font-size: .84rem; font-weight: 700; }

            .admin-sidebar-section { margin-top: 2.05rem; }
            .admin-sidebar-site-section { margin-top: 2.35rem; }
            .admin-sidebar-section-label {
                margin: 0 0 .9rem .35rem;
                color: rgba(216, 205, 246, .78);
                font-size: .78rem;
                font-weight: 900;
                letter-spacing: .28em;
                text-transform: uppercase;
            }

            .admin-sidebar-nav { display: grid; gap: .62rem; }
            .admin-sidebar-link {
                display: flex;
                align-items: center;
                gap: .82rem;
                min-height: 3.22rem;
                border-radius: .95rem;
                padding: .58rem .82rem;
                color: rgba(244, 239, 255, .88);
                text-decoration: none;
                font-size: .98rem;
                font-weight: 850;
                border: 1px solid transparent;
                transition: transform .15s ease, background .15s ease, border-color .15s ease, box-shadow .15s ease;
            }
            .admin-sidebar-link:hover {
                background: rgba(196, 183, 232, .12);
                border-color: rgba(216, 205, 246, .16);
                transform: translateX(2px);
            }
            .admin-sidebar-link.is-active {
                background: linear-gradient(135deg, #b6a6ec 0%, #9079d5 100%);
                color: #fff;
                border-color: rgba(255, 255, 255, .34);
                box-shadow: 0 14px 30px rgba(110, 87, 178, .36), inset 0 1px 0 rgba(255,255,255,.25);
            }
            .admin-sidebar-icon {
                width: 2.05rem;
                height: 2.05rem;
                border-radius: .65rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 auto;
                background: rgba(116, 91, 166, .45);
                color: rgba(255,255,255,.88);
                font-size: 1rem;
            }
            .admin-sidebar-link.is-active .admin-sidebar-icon { background: rgba(255, 255, 255, .22); }
            .admin-sidebar-label { min-width: 0; }
            .admin-site-link { width: fit-content; padding-right: 1.15rem; }

            .admin-user-card {
                margin-top: auto;
                border: 1px solid rgba(216, 205, 246, .23);
                border-radius: 1.2rem;
                background: rgba(22, 13, 39, .85);
                padding: 1.15rem 1.25rem;
                box-shadow: inset 0 1px 0 rgba(255,255,255,.05);
            }
            .admin-user-card span {
                display: block;
                color: rgba(216, 205, 246, .74);
                font-size: .78rem;
                letter-spacing: .24em;
                text-transform: uppercase;
                font-weight: 850;
            }
            .admin-user-card strong { display: block; margin-top: .65rem; font-size: 1.1rem; font-weight: 950; letter-spacing: -.02em; }
            .admin-user-card small { display: block; margin-top: .25rem; color: rgba(216, 205, 246, .78); font-size: .86rem; }

            .admin-main {
                min-width: 0;
                padding: 2.55rem 2.8rem 4.5rem;
            }

            .admin-page-head {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
                margin-bottom: 1.9rem;
            }
            .admin-page-eyebrow {
                display: block;
                margin-bottom: .45rem;
                color: rgba(216, 205, 246, .86);
                letter-spacing: .32em;
                text-transform: uppercase;
                font-weight: 850;
                font-size: .82rem;
            }
            .admin-page-head h1 {
                margin: 0;
                color: #fff;
                font-size: clamp(2rem, 4vw, 3rem);
                line-height: 1;
                font-weight: 950;
                letter-spacing: -.07em;
            }
            .admin-page-head p { margin: .55rem 0 0; color: var(--elit-muted); font-weight: 720; }

            .admin-stats {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 1.15rem;
            }
            .admin-stat {
                min-height: 7.85rem;
                border: 1px solid rgba(216, 205, 246, .26);
                border-radius: 1rem;
                background: linear-gradient(180deg, rgba(72, 51, 112, .95), rgba(58, 40, 95, .96));
                color: #fff;
                padding: 1.28rem 1.38rem;
                box-shadow: 0 22px 48px rgba(8, 3, 22, .22), inset 0 1px 0 rgba(255,255,255,.06);
            }
            .admin-stat span {
                display: block;
                color: rgba(216, 205, 246, .88);
                font-size: .82rem;
                font-weight: 950;
                letter-spacing: .04em;
                text-transform: uppercase;
            }
            .admin-stat strong {
                display: block;
                margin-top: .42rem;
                font-size: 2.25rem;
                line-height: 1;
                font-weight: 950;
                letter-spacing: -.055em;
            }
            .admin-stat small { display: block; margin-top: .7rem; color: rgba(232, 224, 255, .68); font-size: .84rem; font-weight: 700; }

            .admin-dashboard-grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(20rem, 1fr); gap: 1.25rem; }
            .admin-dashboard-cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.25rem; }
            .admin-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.4rem; }

            .admin-panel {
                overflow: hidden;
                border-radius: 1.08rem;
                background: linear-gradient(180deg, rgba(59, 40, 97, .96), rgba(53, 36, 86, .96));
                border: 1px solid rgba(216, 205, 246, .22);
                box-shadow: 0 22px 52px rgba(8, 3, 22, .24), inset 0 1px 0 rgba(255,255,255,.05);
            }
            .admin-panel-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                border-bottom: 1px solid rgba(216, 205, 246, .22);
                background: rgba(75, 54, 117, .56);
                padding: 1.1rem 1.25rem;
                flex-wrap: wrap;
            }
            .admin-panel-head h2 { margin: 0; font-size: 1.15rem; font-weight: 950; letter-spacing: -.035em; }
            .admin-panel-body { padding: 1rem 1.25rem; }
            .admin-table-wrap { overflow-x: auto; }
            .admin-table { width: 100%; min-width: 680px; border-collapse: collapse; font-size: .91rem; }
            .admin-table.compact { min-width: 30rem; }
            .admin-table th {
                padding: .9rem 1rem;
                text-align: left;
                color: rgba(232, 224, 255, .78);
                font-size: .76rem;
                font-weight: 950;
                text-transform: uppercase;
                letter-spacing: .08em;
                white-space: nowrap;
            }
            .admin-table td {
                border-top: 1px solid rgba(216, 205, 246, .16);
                padding: .92rem 1rem;
                color: rgba(255, 255, 255, .9);
                vertical-align: middle;
            }
            .admin-table td:first-child { font-weight: 900; color: #fff; }
            .admin-table a { color: #fff; font-weight: 950; text-decoration: none; }
            .admin-table a:hover { text-decoration: underline; text-underline-offset: .18rem; }

            .admin-list { display: grid; gap: .85rem; }
            .admin-list-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                border: 1px solid rgba(216, 205, 246, .18);
                border-radius: .86rem;
                background: rgba(99, 75, 143, .42);
                padding: .86rem 1rem;
                color: #fff;
                text-decoration: none;
            }
            .admin-list-row:hover { border-color: rgba(216, 205, 246, .36); background: rgba(111, 86, 158, .5); }
            .admin-list-row strong { font-size: 1rem; font-weight: 950; }
            .admin-list-row span { color: rgba(232, 224, 255, .72); font-size: .85rem; }

            .admin-empty { border-radius: .9rem; text-align: center; padding: 2rem; font-weight: 850; color: rgba(255, 255, 255, .72); }
            .admin-actions { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
            .admin-btn, .admin-btn-soft, .admin-btn-danger, .admin-btn-outline, .admin-btn-muted {
                border: 0;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: .38rem;
                min-height: 2.15rem;
                padding: .56rem 1rem;
                text-decoration: none;
                font-size: .78rem;
                font-weight: 950;
                cursor: pointer;
                line-height: 1;
            }
            .admin-btn { background: var(--elit-accent); color: var(--elit-bg); }
            .admin-btn:hover { background: #fff; }
            .admin-btn-soft, .admin-btn-muted { background: rgba(210, 197, 242, .22); color: #fff; }
            .admin-btn-soft:hover, .admin-btn-muted:hover { background: rgba(210, 197, 242, .36); }
            .admin-btn-outline { border: 1px solid rgba(210, 197, 242, .55); background: transparent; color: #fff; }
            .admin-btn-outline:hover { background: rgba(210, 197, 242, .14); }
            .admin-btn-danger { background: rgba(248, 113, 113, .2); color: #fecaca; border: 1px solid rgba(248, 113, 113, .45); }

            .admin-search-card { border-radius: 1rem; background: rgba(58, 40, 96, .72); padding: 1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; border: 1px solid rgba(216, 205, 246, .14); }
            .admin-filter-form { display: flex; align-items: center; gap: .65rem; flex-wrap: wrap; }
            .admin-input, .admin-select, .admin-textarea { width: 100%; border: 2px solid rgba(210, 197, 242, .62); border-radius: 999px; background: rgba(24, 15, 43, .54); color: #fff; padding: .72rem 1rem; outline: none; }
            .admin-select option { color: var(--elit-bg); }
            .admin-textarea { border-radius: 1rem; min-height: 7.5rem; resize: vertical; }
            .admin-input::placeholder, .admin-textarea::placeholder { color: rgba(255,255,255,.58); }
            .admin-input:focus, .admin-select:focus, .admin-textarea:focus { border-color: #fff; box-shadow: 0 0 0 4px rgba(196, 183, 232, .18); }
            .admin-filter-form .admin-input, .admin-filter-form .admin-select { width: auto; min-width: 14rem; padding: .58rem 1rem; }
            .admin-form { display: grid; gap: 1.1rem; }
            .admin-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.1rem; }
            .admin-field label, .admin-field legend, .admin-label { display: block; margin-bottom: .42rem; color: #fff; font-size: .85rem; font-weight: 950; }
            .admin-help { color: rgba(239, 232, 255, .68); font-size: .76rem; margin-top: .35rem; font-weight: 750; }
            .admin-error { margin-top: .35rem; color: #ffd2dc; font-size: .82rem; font-weight: 850; }
            .admin-span-2, .admin-form-full { grid-column: 1 / -1; }
            .admin-check-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .65rem; border: 1px solid rgba(210, 197, 242, .25); border-radius: 1rem; padding: .85rem; max-height: 20rem; overflow: auto; }
            .admin-check-row, .admin-check { display: flex; align-items: center; gap: .5rem; border-radius: .7rem; background: rgba(196, 183, 232, .12); padding: .55rem .65rem; font-size: .84rem; font-weight: 750; }
            .admin-actor-row { display: grid; gap: .5rem; border-radius: .75rem; background: rgba(196, 183, 232, .12); padding: .65rem; }
            .admin-file { display: block; width: 100%; color: rgba(255, 255, 255, .86); }
            .admin-file::file-selector-button { border: 0; border-radius: 999px; background: var(--elit-accent); color: var(--elit-bg); padding: .55rem 1rem; font-weight: 950; margin-right: .8rem; }
            .admin-preview { display: flex; align-items: center; gap: .8rem; margin-top: .6rem; color: rgba(255, 255, 255, .72); font-size: .78rem; font-weight: 800; }
            .admin-preview img { width: 5.2rem; height: 5.2rem; object-fit: cover; border-radius: .8rem; border: 1px solid rgba(210, 197, 242, .3); }
            .admin-preview-wide img { width: 8rem; height: 4.4rem; }
            .admin-badge { display: inline-flex; align-items: center; border-radius: 999px; background: rgba(210, 197, 242, .22); color: #fff; padding: .28rem .62rem; font-size: .72rem; font-weight: 950; white-space: nowrap; }
            .admin-badge-green { background: rgba(45, 212, 191, .22); color: #ccfbf1; }
            .admin-badge-red { background: rgba(248, 113, 113, .22); color: #fecaca; }
            .admin-alert { border-radius: .9rem; padding: .85rem 1rem; margin-bottom: 1rem; font-size: .88rem; font-weight: 850; }
            .admin-alert-success { border: 1px solid rgba(52, 211, 153, .34); background: rgba(16, 185, 129, .14); color: #d1fae5; }
            .admin-alert-error { border: 1px solid rgba(248, 113, 113, .34); background: rgba(239, 68, 68, .14); color: #fee2e2; }
            .admin-pagination { margin-top: 1rem; color: #fff; }
            .admin-kpi-row { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .75rem; }
            .admin-kpi { border-radius: .8rem; background: rgba(196, 183, 232, .15); padding: .85rem; }
            .admin-kpi span { display: block; color: rgba(239, 232, 255, .72); font-size: .72rem; font-weight: 950; text-transform: uppercase; }
            .admin-kpi strong { display: block; margin-top: .2rem; font-size: 1.3rem; }
            .admin-review-text { max-width: 34rem; line-height: 1.4; color: rgba(255, 255, 255, .86); }
            .admin-readonly-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .8rem; margin-bottom: 1.1rem; }
            .admin-readonly-item { border-radius: .85rem; background: rgba(196, 183, 232, .13); padding: .8rem; }
            .admin-readonly-item span { display: block; color: rgba(239, 232, 255, .65); font-size: .72rem; font-weight: 950; text-transform: uppercase; }
            .admin-readonly-item strong { display: block; margin-top: .2rem; overflow-wrap: anywhere; }

            @media (max-width: 1180px) {
                .admin-shell { grid-template-columns: 16.5rem minmax(0, 1fr); }
                .admin-main { padding-inline: 1.8rem; }
                .admin-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .admin-dashboard-grid, .admin-dashboard-cards { grid-template-columns: 1fr; }
            }
            @media (max-width: 860px) {
                .admin-shell { display: block; }
                .admin-sidebar { position: static; height: auto; }
                .admin-sidebar-inner { min-height: 0; }
                .admin-user-card { margin-top: 2rem; }
                .admin-main { padding: 1.4rem 1rem 3rem; }
                .admin-sidebar-nav { grid-template-columns: repeat(2, minmax(0, 1fr)); display: grid; }
                .admin-sidebar-link { min-height: 2.85rem; }
            }
            @media (max-width: 640px) {
                .admin-stats, .admin-grid-2, .admin-form-grid, .admin-kpi-row, .admin-readonly-grid { grid-template-columns: 1fr; }
                .admin-sidebar-nav { grid-template-columns: 1fr; }
                .admin-filter-form .admin-input, .admin-filter-form .admin-select { width: 100%; min-width: 0; }
                .admin-check-grid { grid-template-columns: 1fr; }
            }
        </style>

        <div class="admin-shell">
            <x-admin-sidebar />

            <main class="admin-main">
                <div class="admin-page-head">
                    <div>
                        <span class="admin-page-eyebrow">{{ $eyebrow ?? $title }}</span>
                        <h1>{{ $title }}</h1>
                        @if($subtitle)
                            <p>{{ $subtitle }}</p>
                        @endif
                    </div>
                    @isset($actions)
                        <div class="admin-actions">{{ $actions }}</div>
                    @endisset
                </div>

                @if(session('success'))
                    <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="admin-alert admin-alert-error">{{ session('error') }}</div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</x-app-layout>
