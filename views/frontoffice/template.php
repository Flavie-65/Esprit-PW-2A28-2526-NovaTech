<?php
/** @var string $contenu */
header('Content-Type: text/html; charset=UTF-8');
$title = $pageTitle ?? 'Gestion equipement';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root { --primary: #1D9E75; --bg: #F1EFE8; --text: #24312d; --muted: #64716d; --line: #ddd8ca; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: var(--bg); color: var(--text); }
        .site-header { height: 74px; display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0 2rem; background: rgba(255,255,255,0.72); border-bottom: 1px solid rgba(29,158,117,0.18); }
        .logo { color: var(--primary); font-weight: 850; font-size: 1.35rem; text-decoration: none; }
        .admin-link, .btn { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; padding: 0.45rem 0.8rem; border-radius: 6px; border: 1px solid var(--primary); color: var(--primary); background: #fff; text-decoration: none; font-weight: 700; }
        .front-content { width: min(1080px, calc(100% - 2rem)); margin: 0 auto; padding: 2rem 0; }
        .hero, .panel { background: rgba(255,255,255,0.72); border: 1px solid var(--line); border-radius: 8px; padding: 1.25rem; }
        .page-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
        h1, h2 { color: var(--primary); margin-top: 0; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 0.6rem; margin: 0 0 1rem; }
        .data-table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.8); }
        .data-table th, .data-table td { border-bottom: 1px solid var(--line); padding: 0.7rem 0.75rem; text-align: left; }
        .data-table th { color: var(--muted); font-size: 0.85rem; text-transform: uppercase; background: rgba(255,255,255,0.72); }
        .muted { color: var(--muted); }
    </style>
</head>
<body>
    <header class="site-header">
        <a class="logo" href="index.php?module=home&action=index">NovaTech</a>
        <a class="admin-link" href="index.php?module=home&action=backoffice">Administration</a>
    </header>
    <main class="front-content">
        <?= $contenu ?>
    </main>
</body>
</html>
