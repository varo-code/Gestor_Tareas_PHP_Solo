<h1>Crear Nuevo Usuario</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_PATH ?>/usuarios/crear">
    <label for="user_name">Nombre *</label>
    <input type="text" name="user_name" value="<?= htmlspecialchars($_POST['user_name'] ?? '') ?>">

    <label for="user_surname">Apellidos *</label>
    <input type="text" name="user_surname" value="<?= htmlspecialchars($_POST['user_surname'] ?? '') ?>" >

    <label for="user_email">Email *</label>
    <input type="text" name="user_email" value="<?= htmlspecialchars($_POST['user_email'] ?? '') ?>" >

    <label for="user_password">Contraseña *</label>
    <input type="password" name="user_password" >

    <label for="role">Rol *</label>
    <select name="role">
        <option value="operario" <?= (($_POST['role'] ?? 'operario') === 'operario') ? 'selected' : '' ?>>Operario</option>
        <option value="admin" <?= (($_POST['role'] ?? 'operario') === 'admin') ? 'selected' : '' ?>>Administrador</option>
    </select>

    <button type="submit">Crear Usuario</button>
    <a href="<?= BASE_PATH ?>/usuarios" class="btn">Cancelar</a>
</form>