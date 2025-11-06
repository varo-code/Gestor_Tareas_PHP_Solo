<h1>Lista de Tareas</h1>

<div style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="<?= BASE_PATH ?>/tareas/crear" class="btn">Crear Nueva Tarea</a>
    <?php endif; ?>

    <?php if (isset($_GET['pendientes']) && $_GET['pendientes'] == '1'): ?>
        <a href="<?= BASE_PATH ?>/tareas" class="btn" style="background: #4CAF50;">Mostrar todas</a>
    <?php else: ?>
        <a href="<?= BASE_PATH ?>/tareas?pendientes=1" class="btn" style="background: #FF9800;">Solo pendientes</a>
    <?php endif; ?>
</div>

<?php if (!empty($errorBusqueda)): ?>
    <div class="alert alert-error" style="margin: 10px 0; padding: 10px; background: #ffebee; color: #c62828; border-radius: 4px;">
        <?= htmlspecialchars($errorBusqueda) ?>
    </div>
<?php endif; ?>

<!-- Búsqueda simple -->
<form method="GET" action="<?= BASE_PATH ?>/tareas" style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px;">
    <h3>Búsqueda de tareas</h3>
    <div style="display: flex; gap: 10px; align-items: end; flex-wrap: wrap;">
        <select name="campo" style="width: 200px;">
            <option value="">Seleccione campo </option>
            <option value="Contacto" <?= ($_GET['campo'] ?? '') === 'Contacto' ? 'selected' : '' ?>>Contacto</option>
            <option value="Teléfono" <?= ($_GET['campo'] ?? '') === 'Teléfono' ? 'selected' : '' ?>>Teléfono</option>
            <option value="Email" <?= ($_GET['campo'] ?? '') === 'Email' ? 'selected' : '' ?>>Email</option>
            <option value="Dirección" <?= ($_GET['campo'] ?? '') === 'Dirección' ? 'selected' : '' ?>>Dirección</option>
            <option value="Población" <?= ($_GET['campo'] ?? '') === 'Población' ? 'selected' : '' ?>>Población</option>
            <option value="CP" <?= ($_GET['campo'] ?? '') === 'CP' ? 'selected' : '' ?>>Código Postal</option>
            <option value="Provincia" <?= ($_GET['campo'] ?? '') === 'Provincia' ? 'selected' : '' ?>>Provincia</option>
            <option value="Estado" <?= ($_GET['campo'] ?? '') === 'Estado' ? 'selected' : '' ?>>Estado</option>
            <option value="Operario" <?= ($_GET['campo'] ?? '') === 'Operario' ? 'selected' : '' ?>>Operario</option>
        </select>
        <input type="text" name="valor" 
               value="<?= htmlspecialchars($_GET['valor'] ?? '') ?>" 
               placeholder="Mínimo 3 caracteres" 
               style="width: 180px;">
        <button type="submit" class="btn">Buscar</button>
        <a href="<?= BASE_PATH ?>/tareas" class="btn" style="background: #6c757d;">Limpiar</a>
    </div>
    </form>

<?php if (empty($tareas)): ?>
    <p>No hay tareas disponibles.</p>
<?php else: ?>
    <div class="tabla-contenedor">
        <table class="tabla-tareas">
            <thead>
                <tr>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Población</th>
                    <th>CP</th>
                    <th>Provincia</th>
                    <th>Estado</th>
                    <th>Operario</th>
                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operario'): ?>
                        <th>Acción</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $t): ?>
                    <tr>
                        <td>
                            <a href="<?= BASE_PATH ?>/tareas/ver/<?= (int)$t['tarea_id'] ?>" style="text-decoration: none; color: inherit;">
                                <?= htmlspecialchars($t['client_contacto']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($t['client_telefono'] ?? '') ?></td>
                        <td><?= htmlspecialchars($t['client_email']) ?></td>
                        <td><?= htmlspecialchars($t['client_direccion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($t['client_poblacion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($t['cp'] ?? '') ?></td>
                        <td><?= htmlspecialchars($t['provincia']) ?></td>
                        <td>
                            <?php
                            $estado = match($t['estado']) {
                                'B' => 'Borrador',
                                'P' => 'Pendiente',
                                'R' => 'Realizada',
                                'C' => 'Cancelada',
                                default => $t['estado']
                            };
                            echo htmlspecialchars($estado);
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($t['user_name'])): ?>
                                <?= htmlspecialchars($t['user_name'] . ' ' . $t['user_surname']) ?>
                            <?php else: ?>
                                <em>Sin asignar</em>
                            <?php endif; ?>
                        </td>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <td>
                                <a href="<?= BASE_PATH ?>/tareas/editar/<?= (int)$t['tarea_id'] ?>" class="btn btn-small">Editar</a>
                                <a href="<?= BASE_PATH ?>/tareas/eliminar/<?= (int)$t['tarea_id'] ?>" class="btn btn-small btn-danger">
                                    Eliminar
                                </a>
                            </td>
                        <?php elseif ($_SESSION['role'] === 'operario' && $t['estado'] === 'P'): ?>
                            <td>
                                <a href="<?= BASE_PATH ?>/tareas/completar/<?= (int)$t['tarea_id'] ?>" class="btn btn-small" style="background: #4CAF50; color: white;">
                                    Completar
                                </a>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="paginacion">
        <?php if ($pagina > 1): ?>
            <a href="<?= BASE_PATH ?>/tareas?pagina=1<?= !empty($_GET['campo']) ? '&campo=' . urlencode($_GET['campo']) . '&valor=' . urlencode($_GET['valor']) : (!empty($_GET['pendientes']) ? '&pendientes=1' : '') ?>" class="btn">Primera</a>
            <a href="<?= BASE_PATH ?>/tareas?pagina=<?= $pagina - 1 ?><?= !empty($_GET['campo']) ? '&campo=' . urlencode($_GET['campo']) . '&valor=' . urlencode($_GET['valor']) : (!empty($_GET['pendientes']) ? '&pendientes=1' : '') ?>" class="btn">Anterior</a>
        <?php endif; ?>

        <span>Página <?= $pagina ?> de <?= $totalPaginas ?></span>

        <?php if ($pagina < $totalPaginas): ?>
            <a href="<?= BASE_PATH ?>/tareas?pagina=<?= $pagina + 1 ?><?= !empty($_GET['campo']) ? '&campo=' . urlencode($_GET['campo']) . '&valor=' . urlencode($_GET['valor']) : (!empty($_GET['pendientes']) ? '&pendientes=1' : '') ?>" class="btn">Siguiente</a>
            <a href="<?= BASE_PATH ?>/tareas?pagina=<?= $totalPaginas ?><?= !empty($_GET['campo']) ? '&campo=' . urlencode($_GET['campo']) . '&valor=' . urlencode($_GET['valor']) : (!empty($_GET['pendientes']) ? '&pendientes=1' : '') ?>" class="btn">Última</a>
        <?php endif; ?>
    </div>
<?php endif; ?>