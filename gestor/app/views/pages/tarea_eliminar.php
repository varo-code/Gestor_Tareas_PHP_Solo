<h1>¿Eliminar tarea?</h1>

<div class="alert alert-warning">
    <p><strong>Esta acción no se puede deshacer.</strong></p>
    <p>Se eliminará la siguiente tarea:</p>
    <ul>
        <li><strong>Contacto:</strong> <?= htmlspecialchars($tarea['client_contacto']) ?></li>
        <li><strong>Email:</strong> <?= htmlspecialchars($tarea['client_email']) ?></li>
        <li><strong>Dirección:</strong> <?= htmlspecialchars($tarea['client_direccion'] ?? 'N/A') ?></li>
        <li><strong>Provincia:</strong> <?= htmlspecialchars($tarea['provincia']) ?></li>
        <li><strong>Estado:</strong> 
            <?php
            $estado = match($tarea['estado']) {
                'B' => 'Borrador',
                'P' => 'Pendiente',
                'R' => 'Realizada',
                'C' => 'Cancelada',
                default => $tarea['estado']
            };
            echo htmlspecialchars($estado);
            ?>
        </li>
        <li><strong>Operario:</strong> 
            <?= htmlspecialchars(($tarea['user_name'] ?? '') . ' ' . ($tarea['user_surname'] ?? '')) ?: '<em>Sin asignar</em>' ?>
        </li>
    </ul>
</div>

<form method="POST" action="<?= BASE_PATH ?>/tareas/eliminar/<?= (int)$tarea['tarea_id'] ?>">
    <button type="submit" class="btn btn-danger" style="margin-right: 10px;">Sí, eliminar</button>
    <a href="<?= BASE_PATH ?>/tareas" class="btn">Cancelar</a>
</form>