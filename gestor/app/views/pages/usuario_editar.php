<h1>Editar Usuario</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_PATH ?>/usuarios/editar/<?= (int)$usuario['user_id'] ?>">
    <label for="user_name">Nombre *</label>
    <input type="text" name="user_name" value="<?= htmlspecialchars($usuario['user_name']) ?>" required>

    <label for="user_surname">Apellidos *</label>
    <input type="text" name="user_surname" value="<?= htmlspecialchars($usuario['user_surname']) ?>" required>

    <label for="user_email">Email *</label>
    <input type="text" name="user_email" value="<?= htmlspecialchars($usuario['user_email']) ?>" required>

    <label for="user_password">Contraseña (dejar en blanco para no cambiar)</label>
    <input type="password" name="user_password">

    <?php if ($usuario['user_id'] != $_SESSION['user_id']): ?>
        <label for="role">Rol *</label>
        <select name="role" required>
            <option value="operario" <?= ($usuario['role'] === 'operario') ? 'selected' : '' ?>>Operario</option>
            <option value="admin" <?= ($usuario['role'] === 'admin') ? 'selected' : '' ?>>Administrador</option>
        </select>
    <?php else: ?>
        <label>Rol</label>
        <input type="text" value="<?= $usuario['role'] === 'admin' ? 'Administrador' : 'Operario' ?>" disabled>
        <input type="hidden" name="role" value="<?= htmlspecialchars($usuario['role']) ?>">
    <?php endif; ?>

    <button type="submit">Guardar Cambios</button>
    <a href="<?= BASE_PATH ?>/usuarios" class="btn">Cancelar</a>
</form>