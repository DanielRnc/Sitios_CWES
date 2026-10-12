<?php
// Obtener la subcarpeta actual a través del parámetro 'dir'
$sub = isset($_GET['dir']) ? $_GET['dir'] : '';
// Evitar salir de la raíz por seguridad
$sub = str_replace(array('../', '..\\'), '', $sub);
$sub = trim($sub, '/');

$baseDir = "./";
$currentDir = $baseDir . ($sub ? $sub . '/' : '');

if (!is_dir($currentDir)) {
    die("La carpeta no existe.");
}

$items = array_diff(scandir($currentDir), array('.', '..'));

$folders = [];
$files = [];

foreach ($items as $item) {
    if (strpos($item, '.') === 0) continue; // Ocultar archivos ocultos (.git, etc.)
    if (is_dir($currentDir . $item)) {
        $folders[] = $item;
    } else {
        $files[] = $item;
    }
}

natcasesort($folders);
natcasesort($files);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorador de Proyectos</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a; color: #f8fafc; min-height: 100vh; padding: 2rem; }
        .container { max-width: 900px; margin: 0 auto; }
        header { margin-bottom: 2rem; border-bottom: 1px solid #334155; padding-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; }
        h1 { font-size: 1.5rem; color: #38bdf8; }
        .back-btn { background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500; }
        .back-btn:hover { background: #2563eb; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; }
        .card { background-color: #1e293b; border: 1px solid #334155; border-radius: 8px; padding: 1rem; text-decoration: none; color: inherit; display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s ease-in-out; }
        .card:hover { border-color: #38bdf8; transform: translateY(-2px); background-color: #334155; }
        .card.folder { border-left: 4px solid #f59e0b; }
        .card.file { border-left: 4px solid #3b82f6; }
        .icon { font-size: 1.5rem; }
        .details { overflow: hidden; }
        .name { font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .meta { font-size: 0.8rem; color: #94a3b8; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📁 <?= $sub ? htmlspecialchars($sub) : 'Raíz' ?></h1>
            <?php if ($sub): ?>
                <?php 
                    $parentDir = dirname($sub);
                    $parentLink = ($parentDir === '.' || $parentDir === '') ? 'index.php' : 'index.php?dir=' . urlencode($parentDir);
                ?>
                <a href="<?= $parentLink ?>" class="back-btn">⬅ Volver</a>
            <?php endif; ?>
        </header>

        <div class="grid">
            <!-- Renderizar Carpetas -->
            <?php foreach ($folders as $folder): ?>
                <?php $nextDir = $sub ? $sub . '/' . $folder : $folder; ?>
                <a href="index.php?dir=<?= urlencode($nextDir) ?>" class="card folder">
                    <span class="icon">📁</span>
                    <div class="details">
                        <div class="name"><?= htmlspecialchars($folder) ?></div>
                        <div class="meta">Carpeta</div>
                    </div>
                </a>
            <?php endforeach; ?>

            <!-- Renderizar Archivos -->
            <?php foreach ($files as $file): ?>
                <?php 
                    if (!$sub && $file === 'index.php') continue;
                    $filePath = $currentDir . $file;
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $icon = '📄';
                    if ($ext === 'php') $icon = '🐘';
                    if ($ext === 'html') $icon = '🌐';
                    if ($ext === 'rar' || $ext === 'zip') $icon = '📦';
                ?>
                <a href="<?= htmlspecialchars($currentDir . $file) ?>" class="card file">
                    <span class="icon"><?= $icon ?></span>
                    <div class="details">
                        <div class="name"><?= htmlspecialchars($file) ?></div>
                        <div class="meta"><?= strtoupper($ext) ?> &bull; <?= is_file($filePath) ? round(filesize($filePath) / 1024, 1) : 0 ?> KB</div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>