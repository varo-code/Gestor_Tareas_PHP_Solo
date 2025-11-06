<h1>Mi Perfil</h1>

<div class="perfil-detalle">
    <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['user_name']) ?></p>
    <p><strong>Apellidos:</strong> <?= htmlspecialchars($usuario['user_surname']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['user_email']) ?></p>
    <p><strong>Rol:</strong> 
        <?php
        $rol = match($usuario['role']) {
            'admin' => 'Administrador',
            'operario' => 'Operario',
            default => htmlspecialchars($usuario['role'])
        };
        echo $rol;
        ?>
    </p>
    <p><strong>ID de usuario:</strong> <?= (int)$usuario['user_id'] ?></p>
    <p><strong>Último inicio de sesión:</strong> <?= date('d/m/Y H:i:s', $_SESSION['login_time'] ?? time()) ?></p>
</div>

<div style="margin-top: 20px;">
    <a href="<?= BASE_PATH ?>/perfil/editar" class="btn">Editar Perfil</a>
    <a href="<?= BASE_PATH ?>/tareas" class="btn">Volver a Tareas</a>
</div>