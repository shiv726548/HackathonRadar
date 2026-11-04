:root {
    color-scheme: dark;
    --bg: #0c1220;
    --panel: #151e30;
    --panel-soft: #1b2840;
    --text: #edf2ff;
    --muted: #a4afc4;
    --line: #2a3851;
    --accent: #76a7ff;
    --accent-text: #071426;
    --danger: #ef737d;
    --shadow: 0 12px 32px rgb(0 0 0 / 18%);
}
:root.light-theme {
    color-scheme: light;
    --bg: #f4f7fc;
    --panel: #fff;
    --panel-soft: #edf3fd;
    --text: #152238;
    --muted: #5b687d;
    --line: #dce4f0;
    --accent: #2867cc;
    --accent-text: #fff;
    --danger: #b83242;
    --shadow: 0 12px 32px rgb(26 49 83 / 9%);
}
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
    margin: 0;
    background: var(--bg);
    color: var(--text);
    font: 16px/1.6 system-ui, -apple-system, "Segoe UI", sans-serif;
    transition: background .2s ease, color .2s ease;
}
a { color: var(--accent); text-decoration: none; }
a:hover { text-decoration: underline; }
.site-header {
    min-height: 72px;
    padding: 14px max(5vw, 20px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    background: var(--panel);
    border-bottom: 1px solid var(--line);
}
.brand { color: var(--text); font-weight: 750; display: flex; align-items: center; gap: 10px; }
.brand-icon { width: 26px; fill: none; stroke: var(--accent); stroke-width: 1.8; }
.nav-links { display: flex; flex-wrap: wrap; align-items: center; gap: 18px; }
.nav-links a { color: var(--text); font-size: .94rem; }
.theme-toggle { color: var(--text); background: transparent; border: 1px solid var(--line); border-radius: 9px; padding: 7px 11px; cursor: pointer; }
.container { width: min(1120px, 90%); min-height: 65vh; margin: 0 auto; padding: 36px 0 64px; }
.hero {
    padding: clamp(32px, 7vw, 76px);
    margin: 16px 0 52px;
    border: 1px solid var(--line);
    border-radius: 24px;
    background: radial-gradient(ellipse at 85% 15%, rgb(91 143 255 / 22%), transparent 48%), var(--panel);
}
.hero h1 { max-width: 720px; margin: 8px 0 14px; font-size: clamp(2.3rem, 6vw, 4.2rem); line-height: 1.08; letter-spacing: -.04em; }
.hero > p:not(.eyebrow) { max-width: 620px; color: var(--muted); font-size: 1.1rem; }
.eyebrow { color: var(--accent); text-transform: uppercase; letter-spacing: .12em; font-size: .78rem; font-weight: 750; }
.page-intro { margin: 10px 0 32px; }
.page-intro h1 { margin: 0; font-size: clamp(2rem, 5vw, 3rem); }
.page-intro p { color: var(--muted); }
.section { margin: 48px 0; }
.section h2 { margin: 0 0 20px; }
.section-heading { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 18px; }
.section-heading h2 { margin: 0; }
.section-heading p { margin: 0 0 4px; }
.card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 290px), 1fr)); gap: 18px; }
.card, .detail-panel, .form-panel {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: var(--shadow);
}
.card { padding: 22px; transition: transform .18s ease, border-color .18s ease; }
.card:hover { transform: translateY(-3px); border-color: var(--accent); }
.card h3 { margin: 12px 0 4px; }
.card p { color: var(--muted); }
.card p strong { color: var(--text); }
.badge { display: inline-block; padding: 3px 10px; border-radius: 999px; background: var(--panel-soft); color: var(--accent); font-size: .78rem; font-weight: 700; }
.muted { color: var(--muted); }
.small { font-size: .88rem; }
.search-form { display: flex; flex-wrap: wrap; gap: 10px; max-width: 680px; margin-top: 24px; }
input, textarea, select {
    width: 100%; padding: 12px 13px; border: 1px solid var(--line); border-radius: 9px;
    color: var(--text); background: var(--bg); font: inherit;
}
.search-form input { flex: 1 1 260px; }
label { display: block; margin: 14px 0; font-weight: 600; }
label input, label textarea, label select { margin-top: 6px; font-weight: 400; }
.button {
    display: inline-block; border: 0; border-radius: 9px; padding: 11px 16px;
    background: var(--accent); color: var(--accent-text); font: inherit; font-weight: 700;
    text-align: center; cursor: pointer; transition: filter .15s ease, transform .15s ease;
}
.button:hover { filter: brightness(1.08); transform: translateY(-1px); text-decoration: none; }
.button-secondary { background: var(--panel-soft); color: var(--text); border: 1px solid var(--line); }
.button-danger { background: var(--danger); color: #fff; }
.button-row { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-top: 16px; }
.button-row form { margin: 0; }
.form-panel { width: min(100%, 520px); margin: 32px auto; padding: clamp(22px, 5vw, 36px); }
.form-panel h1, .form-panel h2 { margin-top: 0; }
.wide-panel { width: 100%; }
.detail-panel { max-width: 800px; margin: 20px auto; padding: clamp(24px, 6vw, 48px); }
.notice { padding: 12px 15px; border: 1px solid var(--line); border-radius: 9px; background: var(--panel-soft); }
.notice-error { border-color: var(--danger); }
.admin-list { display: grid; gap: 10px; }
.admin-row { padding: 15px; display: flex; align-items: center; justify-content: space-between; gap: 16px; border: 1px solid var(--line); background: var(--panel); border-radius: 12px; }
.admin-row p { margin: 3px 0 0; }
.site-footer { padding: 24px 5%; border-top: 1px solid var(--line); text-align: center; color: var(--muted); font-size: .9rem; }
.site-footer p { margin: 5px; }
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
@media (max-width: 700px) {
    .site-header { align-items: flex-start; flex-direction: column; }
    .nav-links { gap: 12px; }
    .section-heading, .admin-row { align-items: flex-start; flex-direction: column; }
}
