<h1>Detalle de la Tarea</h1>

<div class="tarea-detalle">
    <h2>Datos del Cliente</h2>
    <p><strong>Contacto:</strong> <?= htmlspecialchars($tarea['client_contacto']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($tarea['client_email']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($tarea['client_telefono'] ?? 'N/A') ?></p>
    <p><strong>NIF/CIF:</strong> <?= htmlspecialchars($tarea['nif_cif'] ?? 'N/A') ?></p>

    <h2>Ubicación</h2>
    <p><strong>Dirección:</strong> <?= htmlspecialchars($tarea['client_direccion'] ?? 'N/A') ?></p>
    <p><strong>Población:</strong> <?= htmlspecialchars($tarea['client_poblacion'] ?? 'N/A') ?></p>
    <p><strong>Código Postal:</strong> <?= htmlspecialchars($tarea['cp'] ?? 'N/A') ?></p>
    <p><strong>Provincia:</strong> <?= htmlspecialchars($tarea['provincia']) ?></p>

    <h2>Asignación y Estado</h2>
    <p><strong>Operario:</strong> 
        <?php if (!empty($tarea['user_name'])): ?>
            <?= htmlspecialchars($tarea['user_name'] . ' ' . $tarea['user_surname']) ?>
        <?php else: ?>
            <em>Sin asignar</em>
        <?php endif; ?>
    </p>
    <p><strong>Estado:</strong> 
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
    </p>

    <h2>Fechas</h2>
    <p><strong>Fecha de creación:</strong> <?= date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])) ?></p>
    <?php if ($tarea['fecha_realizacion']): ?>
        <p><strong>Fecha de realización:</strong> <?= date('d/m/Y', strtotime($tarea['fecha_realizacion'])) ?></p>
    <?php endif; ?>

    <h2>Anotaciones</h2>
    <p><strong>Anteriores:</strong> <?= nl2br(htmlspecialchars($tarea['anotaciones_anteriores'] ?? 'Ninguna')) ?></p>
    <p><strong>Posteriores:</strong> <?= nl2br(htmlspecialchars($tarea['anotaciones_posteriores'] ?? 'Ninguna')) ?></p>

    <?php if (!empty($tarea['fichero_resumen'])): ?>
        <h2>Archivos adjuntos</h2>
        <ul>
            <?php foreach (explode(',', $tarea['fichero_resumen']) as $archivo): ?>
                <?php $archivo = trim($archivo); ?>
                <?php if (!empty($archivo)): ?>
                    <li>
                        <a href="<?= BASE_PATH ?>/storage/uploads/<?= htmlspecialchars($archivo) ?>" target="_blank">
                            <?= htmlspecialchars($archivo) ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<div style="margin-top: 20px;">
    <a href="<?= BASE_PATH ?>/tareas" class="btn">Volver a la lista</a>
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="<?= BASE_PATH ?>/tareas/editar/<?= (int)$tarea['tarea_id'] ?>" class="btn">Editar</a>
    <?php endif; ?>
</div>