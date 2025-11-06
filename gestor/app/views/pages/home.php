ob_start();
?>
<h1>Bienvenido al Gestor de Tareas</h1>
<p>Estás en la página principal (<strong>Home</strong>).</p>
<p><strong>Rol:</strong> <?= htmlspecialchars($_SESSION['role'] ?? 'Invitado') ?></p>
<p><strong>Hora de inicio de sesión:</strong> <?= date('Y-m-d H:i:s', $_SESSION['login_time'] ?? time()) ?></p>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <div style="margin-top: 20px;">
        <a href="<?= BASE_PATH ?>/tareas/crear" class="btn">Crear Nueva Tarea</a>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();