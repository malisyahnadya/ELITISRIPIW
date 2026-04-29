<style>
    :root {
        --admin-bg: #1b1230;
        --admin-bg-soft: #24163c;
        --admin-panel: #3a2860;
        --admin-panel-2: #4a3672;
        --admin-row: rgba(199, 183, 232, 0.30);
        --admin-line: rgba(214, 202, 246, 0.85);
        --admin-pill: #c7b7e8;
        --admin-text-dark: #1b1230;
    }

    .admin-page {
        min-height: 100vh;
        background:
            radial-gradient(circle at 50% -8rem, rgba(120, 86, 174, 0.22), transparent 22rem),
            linear-gradient(180deg, #1b1230 0%, #180f2b 58%, #150d25 100%);
        color: #fff;
        font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .admin-wrap {
        width: min(100% - 2rem, 72rem);
        margin: 0 auto;
        padding: 1.5rem 0 5rem;
    }

    .admin-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .admin-brand {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        color: #fff;
        text-decoration: none;
        font-size: clamp(1.35rem, 2vw, 2rem);
        font-weight: 950;
        letter-spacing: -0.04em;
    }

    .admin-brand i { font-size: 1.45rem; color: rgba(229, 220, 255, 0.7); }

    .admin-search {
        display: flex;
        align-items: center;
        gap: .65rem;
        min-width: min(28rem, 100%);
    }

    .admin-search i { font-size: 1.55rem; color: rgba(229, 220, 255, 0.7); }

    .admin-search input,
    .admin-input,
    .admin-select,
    .admin-textarea {
        width: 100%;
        border: 1.5px solid rgba(215, 204, 246, 0.82);
        border-radius: 999px;
        background: rgba(219, 210, 244, 0.34);
        color: #fff;
        padding: .72rem 1rem;
        outline: none;
    }

    .admin-textarea { border-radius: 1.1rem; min-height: 8rem; resize: vertical; }
    .admin-select option { color: #1b1230; }
    .admin-input::placeholder,
    .admin-textarea::placeholder,
    .admin-search input::placeholder { color: rgba(255, 255, 255, 0.72); }

    .admin-input:focus,
    .admin-select:focus,
    .admin-textarea:focus,
    .admin-search input:focus {
        border-color: #fff;
        box-shadow: 0 0 0 4px rgba(210, 197, 242, .18);
    }

    .admin-user-dot {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.8rem;
        height: 2.8rem;
        border-radius: 999px;
        background: #fff;
        color: var(--admin-bg);
        font-size: 1.65rem;
        text-decoration: none;
    }

    .admin-menu {
        display: flex;
        flex-wrap: wrap;
        gap: .55rem;
        margin-bottom: 1.7rem;
    }

    .admin-menu a,
    .admin-btn,
    .admin-btn-muted,
    .admin-btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        border: 0;
        border-radius: 999px;
        padding: .62rem 1rem;
        font-size: .78rem;
        font-weight: 900;
        line-height: 1;
        text-decoration: none;
        cursor: pointer;
        transition: transform .14s ease, background .14s ease, color .14s ease, opacity .14s ease;
    }

    .admin-menu a,
    .admin-btn-muted {
        background: rgba(210, 197, 242, .18);
        color: #fff;
        border: 1px solid rgba(210, 197, 242, .24);
    }

    .admin-menu a:hover,
    .admin-menu a.is-active,
    .admin-btn-muted:hover { background: rgba(210, 197, 242, .35); }

    .admin-btn { background: var(--admin-pill); color: var(--admin-text-dark); }
    .admin-btn:hover { background: #fff; transform: translateY(-1px); }
    .admin-btn-danger { background: rgba(255, 120, 140, .18); color: #ffd8df; border: 1px solid rgba(255, 190, 200, .32); }
    .admin-btn-danger:hover { background: rgba(255, 120, 140, .3); }

    .admin-page-title {
        display: flex;
        align-items: end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .admin-page-title h1 {
        margin: 0;
        font-size: clamp(1.8rem, 3vw, 2.75rem);
        font-weight: 950;
        letter-spacing: -0.05em;
    }

    .admin-page-title p { margin: .25rem 0 0; color: rgba(237, 230, 255, .72); font-weight: 700; }

    .admin-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.4rem;
        margin: 1.3rem 0 1.8rem;
    }

    .admin-stat {
        background: #dedede;
        color: #0f0b19;
        border-radius: .45rem;
        text-align: center;
        padding: .9rem 1rem;
        box-shadow: 0 18px 36px rgba(7, 2, 18, .28);
    }

    .admin-stat span { display: block; font-weight: 950; font-size: .86rem; }
    .admin-stat strong { display: block; font-weight: 950; font-size: 2.35rem; line-height: 1; letter-spacing: -.06em; }

    .admin-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.7rem; }

    .admin-panel {
        overflow: hidden;
        border-radius: .45rem;
        background: rgba(58, 40, 96, .96);
        box-shadow: 0 22px 52px rgba(8, 3, 20, .26);
        border: 1px solid rgba(215, 204, 246, .08);
    }

    .admin-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: .55rem 1rem;
        border-bottom: 3px solid rgba(215, 204, 246, .82);
        background: rgba(73, 53, 115, .72);
    }

    .admin-panel-head h2 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 950;
        letter-spacing: -.02em;
    }

    .admin-panel-body { padding: 1rem; }

    .admin-table-wrap { overflow-x: auto; padding: .85rem 1rem 1.1rem; }
    .admin-table { width: 100%; border-collapse: separate; border-spacing: 0 .62rem; min-width: 44rem; }
    .admin-table.compact { min-width: 30rem; }
    .admin-table th {
        padding: 0 .9rem .1rem;
        color: rgba(255, 255, 255, .86);
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 950;
        white-space: nowrap;
    }
    .admin-table td {
        padding: .72rem .9rem;
        background: var(--admin-row);
        font-weight: 700;
        vertical-align: middle;
    }
    .admin-table td:first-child { border-radius: .55rem 0 0 .55rem; }
    .admin-table td:last-child { border-radius: 0 .55rem .55rem 0; }
    .admin-table a { color: #fff; font-weight: 950; text-decoration: underline; text-underline-offset: .18rem; }
    .admin-actions { display: flex; flex-wrap: wrap; gap: .45rem; align-items: center; }

    .admin-empty {
        padding: 2.3rem 1rem;
        text-align: center;
        color: rgba(255,255,255,.76);
        font-weight: 850;
    }

    .admin-form-panel {
        border-radius: 1.2rem;
        background: rgba(58, 40, 96, .96);
        padding: clamp(1.1rem, 3vw, 2rem);
        box-shadow: 0 24px 56px rgba(8, 3, 20, .28);
        border: 1px solid rgba(215, 204, 246, .10);
    }

    .admin-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
    .admin-form-full { grid-column: 1 / -1; }
    .admin-label { display: block; margin-bottom: .38rem; font-weight: 950; color: #fff; }
    .admin-help { margin-top: .35rem; font-size: .78rem; color: rgba(237, 230, 255, .68); font-weight: 700; }
    .admin-check-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .65rem; border: 1px solid rgba(215, 204, 246, .24); border-radius: 1rem; padding: 1rem; background: rgba(20, 13, 36, .22); }
    .admin-check { display: flex; align-items: center; gap: .5rem; font-weight: 750; color: rgba(255,255,255,.9); }
    .admin-check input { width: 1rem; height: 1rem; accent-color: #c7b7e8; }
    .admin-error { margin-top: .35rem; color: #ffd2dc; font-size: .82rem; font-weight: 800; }
    .admin-flash { margin-bottom: 1rem; border-radius: 1rem; padding: .85rem 1rem; font-weight: 850; }
    .admin-flash.success { background: rgba(40, 210, 130, .14); border: 1px solid rgba(103, 255, 184, .32); color: #d9ffed; }
    .admin-flash.error { background: rgba(255, 96, 120, .14); border: 1px solid rgba(255, 160, 175, .32); color: #ffd9df; }
    .admin-thumb { width: 3.2rem; height: 4.4rem; border-radius: .6rem; object-fit: cover; background: rgba(255,255,255,.14); }
    .admin-avatar { width: 2.6rem; height: 2.6rem; border-radius: 999px; object-fit: cover; background: #ded5f6; display: inline-flex; align-items: center; justify-content: center; color: #281a43; font-weight: 950; }
    .admin-badge { display: inline-flex; border-radius: 999px; background: rgba(210, 197, 242, .22); padding: .35rem .65rem; font-size: .72rem; font-weight: 950; color: #fff; }
    .admin-divider { height: 2px; background: rgba(215, 204, 246, .65); margin: 1.5rem 0; }
    .admin-pagination { margin-top: 1rem; color: #fff; }

    @media (max-width: 900px) {
        .admin-topbar { align-items: stretch; flex-direction: column; }
        .admin-search { min-width: 100%; }
        .admin-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .admin-grid-2 { grid-template-columns: 1fr; }
        .admin-form-grid { grid-template-columns: 1fr; }
        .admin-check-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 560px) {
        .admin-stats { grid-template-columns: 1fr; }
        .admin-menu a { flex: 1 1 auto; }
    }
</style>
