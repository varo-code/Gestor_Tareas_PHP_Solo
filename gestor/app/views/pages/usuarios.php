<h1>Gestión de Usuarios</h1>

<a href="<?= BASE_PATH ?>/usuarios/crear" class="btn">Crear Nuevo Usuario</a>

<?php if (empty($usuarios)): ?>
    <p>No hay usuarios registrados.</p>
<?php else: ?>
    <div class="tabla-contenedor">
        <table class="tabla-tareas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= (int)$u['user_id'] ?></td>
                        <td><?= htmlspecialchars($u['user_name']) ?></td>
                        <td><?= htmlspecialchars($u['user_surname']) ?></td>
                        <td><?= htmlspecialchars($u['user_email']) ?></td>
                        <td>
                            <?php
                            $rol = match($u['role']) {
                                'admin' => 'Administrador',
                                'operario' => 'Operario',
                                default => htmlspecialchars($u['role'])
                            };
                            echo $rol;
                            ?>
                        </td>
                        <td>
                            <a href="<?= BASE_PATH ?>/usuarios/editar/<?= (int)$u['user_id'] ?>" class="btn btn-small">Editar</a>
                            <a href="<?= BASE_PATH ?>/usuarios/eliminar/<?= (int)$u['user_id'] ?>" class="btn btn-small btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="paginacion">
        <?php if ($pagina > 1): ?>
            <a href="<?= BASE_PATH ?>/usuarios?pagina=1" class="btn">Primera</a>
            <a href="<?= BASE_PATH ?>/usuarios?pagina=<?= $pagina - 1 ?>" class="btn">Anterior</a>
        <?php endif; ?>

        <span>Página <?= $pagina ?> de <?= $totalPaginas ?></span>

        <?php if ($pagina < $totalPaginas): ?>
            <a href="<?= BASE_PATH ?>/usuarios?pagina=<?= $pagina + 1 ?>" class="btn">Siguiente</a>
            <a href="<?= BASE_PATH ?>/usuarios?pagina=<?= $totalPaginas ?>" class="btn">Última</a>
        <?php endif; ?>
    </div>
<?php endif; ?>