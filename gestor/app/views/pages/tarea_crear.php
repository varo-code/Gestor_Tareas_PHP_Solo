<h1>Crear Nueva Tarea</h1>

<form method="POST" action="<?= BASE_PATH ?>/tareas/crear" id="form-tarea">
    <!-- Contacto -->
    <?php if (!empty($errors['client_contacto'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['client_contacto']) ?></div>
    <?php endif; ?>
    <label for="client_contacto">Contacto *</label>
    <input type="text" name="client_contacto" value="<?= htmlspecialchars($_POST['client_contacto'] ?? '') ?>">

    <!-- Email -->
    <?php if (!empty($errors['client_email'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['client_email']) ?></div>
    <?php endif; ?>
    <label for="client_email">Email *</label>
    <input type="text" name="client_email" value="<?= htmlspecialchars($_POST['client_email'] ?? '') ?>">

    <!-- Teléfono -->
    <?php if (!empty($errors['client_telefono'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['client_telefono']) ?></div>
    <?php endif; ?>
    <label for="client_telefono">Teléfono (9 dígitos)</label>
    <input type="text" name="client_telefono" value="<?= htmlspecialchars($_POST['client_telefono'] ?? '') ?>">

    <!-- NIF/CIF -->
    <?php if (!empty($errors['nif_cif'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['nif_cif']) ?></div>
    <?php endif; ?>
    <label for="nif_cif">NIF/CIF</label>
    <input type="text" name="nif_cif" value="<?= htmlspecialchars($_POST['nif_cif'] ?? '') ?>">

    <!-- Dirección -->
    <?php if (!empty($errors['client_direccion'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['client_direccion']) ?></div>
    <?php endif; ?>
    <label for="client_direccion">Dirección *</label>
    <input type="text" name="client_direccion" value="<?= htmlspecialchars($_POST['client_direccion'] ?? '') ?>">

    <!-- Comunidad -->
    <?php if (!empty($errors['comunidad_id'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['comunidad_id']) ?></div>
    <?php endif; ?>
    <label for="comunidad_id">Comunidad Autónoma *</label>
    <select name="comunidad_id" id="comunidad_id">
        <option value="">Seleccione...</option>
        <?php foreach ($comunidades as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= (($_POST['comunidad_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Provincia -->
    <?php if (!empty($errors['provincia_cp'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['provincia_cp']) ?></div>
    <?php endif; ?>
    <label for="provincia_cp">Provincia *</label>
    <select name="provincia_cp" id="provincia_cp" disabled>
        <option value="">Seleccione una comunidad primero</option>
    </select>
    <input type="hidden" name="provincia_nombre" id="provincia_nombre">

    <!-- Población -->
    <label for="client_poblacion">Población</label>
    <input type="text" name="client_poblacion" id="client_poblacion" disabled value="<?= htmlspecialchars($_POST['client_poblacion'] ?? '') ?>">

    <!-- CP -->
    <?php if (!empty($errors['cp3'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['cp3']) ?></div>
    <?php endif; ?>
    <label for="cp3">Código Postal (últimos 3 dígitos) *</label>
    <input type="text" name="cp3" id="cp3" maxlength="3" value="<?= htmlspecialchars($_POST['cp3'] ?? '') ?>" disabled>

    <!-- Operario -->
    <?php if (!empty($errors['user_id'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['user_id']) ?></div>
    <?php endif; ?>
    <label for="user_id">Operario Encargado *</label>
    <select name="user_id">
        <option value="">Seleccione...</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= (int)$u['user_id'] ?>" <?= (($_POST['user_id'] ?? '') == $u['user_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['user_name'] . ' ' . $u['user_surname']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Estado -->
    <?php if (!empty($errors['estado'])): ?>
        <div class="error-field"><?= htmlspecialchars($errors['estado']) ?></div>
    <?php endif; ?>
    <label for="estado">Estado *</label>
    <select name="estado" id="estado">
        <option value="P" <?= (($_POST['estado'] ?? 'P') == 'P') ? 'selected' : '' ?>>Pendiente</option>
        <option value="B" <?= (($_POST['estado'] ?? 'P') == 'B') ? 'selected' : '' ?>>Borrador</option>
        <option value="R" <?= (($_POST['estado'] ?? 'P') == 'R') ? 'selected' : '' ?>>Realizada</option>
        <option value="C" <?= (($_POST['estado'] ?? 'P') == 'C') ? 'selected' : '' ?>>Cancelada</option>
    </select>

    <!-- Campos condicionales -->
    <div id="campos-realizada" style="display:<?= (($_POST['estado'] ?? 'P') === 'R') ? 'block' : 'none' ?>;">
        <?php if (!empty($errors['fecha_realizacion'])): ?>
            <div class="error-field"><?= htmlspecialchars($errors['fecha_realizacion']) ?></div>
        <?php endif; ?>
        <label for="fecha_realizacion">Fecha de Realización (AAAA-MM-DD) *</label>
        <input type="text" name="fecha_realizacion" value="<?= htmlspecialchars($_POST['fecha_realizacion'] ?? '') ?>">

        <?php if (!empty($errors['anotaciones_posteriores'])): ?>
            <div class="error-field"><?= htmlspecialchars($errors['anotaciones_posteriores']) ?></div>
        <?php endif; ?>
        <label for="anotaciones_posteriores">Anotaciones Posteriores *</label>
        <textarea name="anotaciones_posteriores" rows="4"><?= htmlspecialchars($_POST['anotaciones_posteriores'] ?? '') ?></textarea>
    </div>

    <!-- Anotaciones anteriores -->
    <label for="anotaciones_anteriores">Anotaciones Anteriores</label>
    <textarea name="anotaciones_anteriores" rows="4"><?= htmlspecialchars($_POST['anotaciones_anteriores'] ?? 'Sin anotaciones anteriores') ?></textarea>

    <button type="submit">Crear Tarea</button>
</form>

<script>
    const BASE_PATH = <?= json_encode(BASE_PATH) ?>;
</script>
<script src="<?= BASE_PATH ?>/../assets/js/tarea_form.js"></script>

<style>
.error-field {
    color: #d32f2f;
    font-size: 0.9em;
    margin-bottom: 4px;
}
</style>