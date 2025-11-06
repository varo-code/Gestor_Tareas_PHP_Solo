<h1>¿Eliminar usuario?</h1>

<div class="alert alert-warning">
    <p><strong>Esta acción no se puede deshacer.</strong></p>
    <p>Se eliminará al siguiente usuario:</p>
    <ul>
        <li><strong>Nombre:</strong> <?= htmlspecialchars($usuario['user_name'] . ' ' . $usuario['user_surname']) ?></li>
        <li><strong>Email:</strong> <?= htmlspecialchars($usuario['user_email']) ?></li>
        <li><strong>Rol:</strong> 
            <?= $usuario['role'] === 'admin' ? 'Administrador' : 'Operario' ?>
        </li>
    </ul>
</div>

<form method="POST" action="<?= BASE_PATH ?>/usuarios/eliminar/<?= (int)$usuario['user_id'] ?>">
    <button type="submit" class="btn btn-danger" style="margin-right: 10px;">Sí, eliminar</button>
    <a href="<?= BASE_PATH ?>/usuarios" class="btn">Cancelar</a>
</form>