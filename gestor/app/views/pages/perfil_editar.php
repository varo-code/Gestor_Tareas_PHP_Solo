<h1>Editar Perfil</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_PATH ?>/perfil/editar">
    <label for="user_name">Nombre *</label>
    <input type="text" name="user_name" value="<?= htmlspecialchars($usuarioActual['user_name']) ?>">

    <label for="user_surname">Apellidos *</label>
    <input type="text" name="user_surname" value="<?= htmlspecialchars($usuarioActual['user_surname']) ?>">

    <label for="user_email">Email *</label>
    <input type="text" name="user_email" value="<?= htmlspecialchars($usuarioActual['user_email']) ?>">

    <label for="user_password">Contraseña (dejar en blanco para no cambiar)</label>
    <input type="password" name="user_password">

    <button type="submit">Guardar Cambios</button>
    <a href="<?= BASE_PATH ?>/perfil" class="btn">Cancelar</a>
</form>