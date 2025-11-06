<h1>Completar Tarea</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_PATH ?>/tareas/completar/<?= (int)$tarea['tarea_id'] ?>" enctype="multipart/form-data">
    <p><strong>Tarea de:</strong> <?= htmlspecialchars($tarea['client_contacto']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($tarea['client_email']) ?></p>
    <p><strong>Dirección:</strong> <?= htmlspecialchars($tarea['client_direccion'] ?? 'N/A') ?></p>
    <p><strong>Provincia:</strong> <?= htmlspecialchars($tarea['provincia']) ?></p>
    <p><strong>Fecha de creación:</strong> <?= date('d/m/Y', strtotime($tarea['fecha_creacion'])) ?></p>

    <input type="hidden" name="estado" value="R">

    <label for="fecha_realizacion">Fecha de Realización *</label>
    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 15px;">
        <input type="text" name="fecha_realizacion" id="fecha_realizacion"
               value="<?= htmlspecialchars($_POST['fecha_realizacion'] ?? '') ?>"
               placeholder="AAAA-MM-DD">
        <button type="button" class="btn" onclick="document.getElementById('fecha_realizacion').value='<?= date('Y-m-d') ?>'">
            Hoy
        </button>
    </div>

    <label for="anotaciones_posteriores">Anotaciones Posteriores *</label>
    <textarea name="anotaciones_posteriores" id="anotaciones_posteriores" rows="5"><?= htmlspecialchars($_POST['anotaciones_posteriores'] ?? '') ?></textarea>

    <label for="fichero_resumen">Archivos adjuntos (máximo 5)</label>
    <input type="file" name="fichero_resumen[]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" multiple>

    <div style="margin-top: 20px;">
        <button type="submit">Marcar como Realizada</button>
        <a href="<?= BASE_PATH ?>/tareas" class="btn">Cancelar</a>
    </div>
</form>