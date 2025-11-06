<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Bunglebuild S.L. - Gestor de Tareas') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/../assets/css/main.css">
</head>
<body>

        <!-- Menú superior -->
        <nav class="navbar">
            <div class="navbar-logo">
                <a href="<?= BASE_PATH ?>/">Bunglebuild S.L.</a>
            </div>
            <div class="navbar-links">
                <a href="<?= BASE_PATH ?>/tareas">Tareas</a>
                <a href="<?= BASE_PATH ?>/perfil">Perfil</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="<?= BASE_PATH ?>/usuarios">Usuarios</a>
                <?php endif; ?>
            </div>
            <div class="navbar-user">
                <?php if (isset($_SESSION['user_name'])): ?>
                    <span>Hola, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    &nbsp;|&nbsp;
                    <a href="<?= BASE_PATH ?>/logout" class="logout-link">Cerrar sesión</a>
                <?php endif; ?>
            </div>
        </nav>
    <div class="container">


        <!-- Contenido principal -->
        <main class="content">
            <?= $content ?? '' ?>
        </main>

    </div>

            <!-- Footer -->
        <footer class="footer">
            Bunglebuild S.L. © 2025 - Gestor de Tareas Interno
        </footer>


</body>
</html>