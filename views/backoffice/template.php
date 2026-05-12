<?php
/** @var string $contenu */
header('Content-Type: text/html; charset=UTF-8');
$module = isset($_GET['module']) ? (string) $_GET['module'] : 'home';
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'index';
$title = $pageTitle ?? 'Administration';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root { --primary: #1D9E75; --bg: #f6f7f8; --text: #24312d; --muted: #64716d; --line: #dfe5e2; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: var(--text); background: var(--bg); }
        .admin-shell { min-height: 100vh; padding-left: 250px; }
        .sidebar { position: fixed; inset: 0 auto 0 0; width: 250px; background: var(--primary); color: #fff; padding: 1.5rem 0; }
        .brand { padding: 0 1.4rem 1.4rem; font-size: 1.25rem; font-weight: 800; }
        .nav a { display: block; color: #fff; text-decoration: none; padding: 0.85rem 1.4rem; border-left: 4px solid transparent; }
        .nav a.active { border-left-color: #fff; background: rgba(255,255,255,0.16); }
        .nav a:hover { background: rgba(255,255,255,0.12); }
        .topbar { height: 68px; display: flex; align-items: center; justify-content: space-between; background: #fff; padding: 0 1.75rem; box-shadow: 0 2px 14px rgba(19, 48, 40, 0.08); }
        .topbar h1 { color: var(--primary); font-size: 1.35rem; margin: 0; }
        .topbar a { color: var(--primary); text-decoration: none; font-weight: 700; }
        .content { padding: 1.75rem; }
        .page-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
        .page-header h2 { margin: 0; color: var(--primary); font-size: 1.55rem; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 0.6rem; margin: 0 0 1rem; }
        .btn, button { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; padding: 0.45rem 0.8rem; border: 1px solid var(--primary); background: var(--primary); color: #fff; text-decoration: none; border-radius: 6px; font-weight: 700; cursor: pointer; }
        .btn.secondary { background: #fff; color: var(--primary); }
        .panel, .form-card { background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 1rem; box-shadow: 0 8px 24px rgba(34, 50, 45, 0.06); }
        .data-table { width: 100%; border-collapse: collapse; background: #fff; }
        .data-table th, .data-table td { border-bottom: 1px solid var(--line); padding: 0.7rem 0.75rem; text-align: left; vertical-align: top; }
        .data-table th { color: var(--muted); font-size: 0.85rem; text-transform: uppercase; background: #f8faf9; }
        .actions { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
        .table-actions { display: flex; flex-wrap: wrap; gap: 0.45rem; }
        .table-actions a { color: var(--primary); font-weight: 700; text-decoration: none; }
        label { display: block; margin-top: 0.85rem; font-weight: 700; }
        input, select { width: 100%; padding: 0.55rem 0.65rem; margin-top: 0.25rem; border: 1px solid #ccd6d2; border-radius: 6px; background: #fff; }
        .errors { color: #9b1c1c; margin: 0 0 1rem; padding-left: 1.2rem; }
        .flash { padding: 0.7rem 0.85rem; margin: 0 0 1rem; border: 1px solid var(--line); border-radius: 6px; background: #fff; }
        .flash.success { border-color: #1D9E75; color: #11674b; }
        .flash.error { border-color: #b42318; color: #8a1f15; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">NovaTech</div>
        <nav class="nav">
            <a class="<?= $module === 'home' && $action === 'dashboard' ? 'active' : '' ?>" href="index.php?module=home&action=dashboard">Dashboard</a>
            <a class="<?= $module === 'home' && $action !== 'dashboard' ? 'active' : '' ?>" href="index.php?module=home&action=backoffice">Accueil</a>
            <a class="<?= $module === 'equipment' ? 'active' : '' ?>" href="index.php?module=equipment&action=index">Equipements</a>
            <a class="<?= $module === 'assignment' ? 'active' : '' ?>" href="index.php?module=assignment&action=index">Affectations</a>
        </nav>
    </aside>
    <main class="admin-shell">
        <header class="topbar">
            <h1><?= htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8') ?></h1>
            <a href="index.php?module=home&action=index">Site public</a>
        </header>
        <section class="content">
            <?= $contenu ?>
        </section>
    </main>
</body>
</html>
