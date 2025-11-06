<h1>Editar Tarea</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error" style="margin-bottom: 20px;">
        <strong>Corrija los siguientes errores:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_PATH ?>/tareas/editar/<?= (int)$tarea['tarea_id'] ?>">
    <!-- Contacto -->
    <?php if (!empty($errors['client_contacto'])): ?><div class="error-field"><?= htmlspecialchars($errors['client_contacto']) ?></div><?php endif; ?>
    <label for="client_contacto">Contacto *</label>
    <input type="text" name="client_contacto" value="<?= htmlspecialchars($tarea['client_contacto']) ?>">

    <!-- Email -->
    <?php if (!empty($errors['client_email'])): ?><div class="error-field"><?= htmlspecialchars($errors['client_email']) ?></div><?php endif; ?>
    <label for="client_email">Email *</label>
    <input type="text" name="client_email" value="<?= htmlspecialchars($tarea['client_email']) ?>">

    <!-- Teléfono -->
    <?php if (!empty($errors['client_telefono'])): ?><div class="error-field"><?= htmlspecialchars($errors['client_telefono']) ?></div><?php endif; ?>
    <label for="client_telefono">Teléfono (9 dígitos)</label>
    <input type="text" name="client_telefono" value="<?= htmlspecialchars($tarea['client_telefono'] ?? '') ?>">

    <!-- NIF/CIF -->
    <?php if (!empty($errors['nif_cif'])): ?><div class="error-field"><?= htmlspecialchars($errors['nif_cif']) ?></div><?php endif; ?>
    <label for="nif_cif">NIF/CIF</label>
    <input type="text" name="nif_cif" value="<?= htmlspecialchars($tarea['nif_cif'] ?? '') ?>">

    <!-- Dirección -->
    <?php if (!empty($errors['client_direccion'])): ?><div class="error-field"><?= htmlspecialchars($errors['client_direccion']) ?></div><?php endif; ?>
    <label for="client_direccion">Dirección *</label>
    <input type="text" name="client_direccion" value="<?= htmlspecialchars($tarea['client_direccion'] ?? '') ?>">

    <!-- Comunidad Autónoma -->
    <?php if (!empty($errors['comunidad_id'])): ?><div class="error-field"><?= htmlspecialchars($errors['comunidad_id']) ?></div><?php endif; ?>
    <label for="comunidad_id">Comunidad Autónoma *</label>
    <select name="comunidad_id" id="comunidad_id">
        <option value="">Seleccione...</option>
        <?php foreach ($comunidades as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ((int)$comunidad_id_inicial === (int)$c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Provincia -->
    <?php if (!empty($errors['provincia_cp'])): ?><div class="error-field"><?= htmlspecialchars($errors['provincia_cp']) ?></div><?php endif; ?>
    <label for="provincia_cp">Provincia *</label>
    <select name="provincia_cp" id="provincia_cp" disabled>
        <option value="">Seleccione una comunidad primero</option>
    </select>
    <input type="hidden" name="provincia_nombre" id="provincia_nombre" value="<?= htmlspecialchars($tarea['provincia']) ?>">

    <!-- Población -->
    <label for="client_poblacion">Población</label>
    <input type="text" name="client_poblacion" value="<?= htmlspecialchars($tarea['client_poblacion'] ?? '') ?>">

    <!-- CP -->
    <?php if (!empty($errors['cp3'])): ?><div class="error-field"><?= htmlspecialchars($errors['cp3']) ?></div><?php endif; ?>
    <label for="cp3">Código Postal (últimos 3 dígitos) *</label>
    <input type="text" name="cp3" id="cp3" maxlength="3" value="<?= htmlspecialchars($cp3) ?>">

    <!-- Operario -->
    <?php if (!empty($errors['user_id'])): ?><div class="error-field"><?= htmlspecialchars($errors['user_id']) ?></div><?php endif; ?>
    <label for="user_id">Operario Encargado *</label>
    <select name="user_id">
        <option value="">Seleccione...</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= (int)$u['user_id'] ?>" <?= ((int)$tarea['user_id'] === $u['user_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['user_name'] . ' ' . $u['user_surname']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Estado -->
    <?php if (!empty($errors['estado'])): ?><div class="error-field"><?= htmlspecialchars($errors['estado']) ?></div><?php endif; ?>
    <label for="estado">Estado *</label>
    <select name="estado" id="estado">
        <option value="P" <?= ($tarea['estado'] === 'P') ? 'selected' : '' ?>>Pendiente</option>
        <option value="B" <?= ($tarea['estado'] === 'B') ? 'selected' : '' ?>>Borrador</option>
        <option value="R" <?= ($tarea['estado'] === 'R') ? 'selected' : '' ?>>Realizada</option>
        <option value="C" <?= ($tarea['estado'] === 'C') ? 'selected' : '' ?>>Cancelada</option>
    </select>

    <!-- Anotaciones anteriores -->
    <label for="anotaciones_anteriores">Anotaciones Anteriores</label>
    <textarea name="anotaciones_anteriores" rows="4"><?= htmlspecialchars($tarea['anotaciones_anteriores'] ?? '') ?></textarea>

    <!-- Campos condicionales -->
    <div id="campos-realizada" style="display:<?= ($tarea['estado'] === 'R') ? 'block' : 'none' ?>;">
        <?php if (!empty($errors['fecha_realizacion'])): ?><div class="error-field"><?= htmlspecialchars($errors['fecha_realizacion']) ?></div><?php endif; ?>
        <label for="fecha_realizacion">Fecha de Realización (AAAA-MM-DD) *</label>
        <input type="text" name="fecha_realizacion" value="<?= htmlspecialchars($tarea['fecha_realizacion'] ?? '') ?>">

        <?php if (!empty($errors['anotaciones_posteriores'])): ?><div class="error-field"><?= htmlspecialchars($errors['anotaciones_posteriores']) ?></div><?php endif; ?>
        <label for="anotaciones_posteriores">Anotaciones Posteriores *</label>
        <textarea name="anotaciones_posteriores" rows="4"><?= htmlspecialchars($tarea['anotaciones_posteriores'] ?? '') ?></textarea>
    </div>

    <button type="submit">Guardar Cambios</button>
</form>

<script>
    const BASE_PATH = <?= json_encode(BASE_PATH) ?>;
    const COMUNIDAD_ID_INICIAL = <?= json_encode($comunidad_id_inicial) ?>;
    const CP2_INICIAL = <?= json_encode($cp2_inicial) ?>;

    document.addEventListener('DOMContentLoaded', function () {
        const comunidadSelect = document.getElementById('comunidad_id');
        const provinciaSelect = document.getElementById('provincia_cp');
        const cp3Input = document.getElementById('cp3');
        const provinciaNombreInput = document.getElementById('provincia_nombre');

        function cargarProvincias(comunidadId) {
            fetch(BASE_PATH + '/api/provincias?comunidad_id=' + encodeURIComponent(comunidadId))
                .then(response => response.json())
                .then(provincias => {
                    provinciaSelect.innerHTML = '<option value="">Seleccione...</option>';
                    provincias.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.cp;
                        opt.textContent = p.nombre;
                        opt.dataset.nombre = p.nombre;
                        if (p.cp === CP2_INICIAL) {
                            opt.selected = true;
                            provinciaNombreInput.value = p.nombre;
                        }
                        provinciaSelect.appendChild(opt);
                    });
                    provinciaSelect.disabled = false;
                    if (CP2_INICIAL) {
                        cp3Input.disabled = false;
                    }
                })
                .catch(() => {
                    provinciaSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        }

        if (COMUNIDAD_ID_INICIAL) {
            cargarProvincias(COMUNIDAD_ID_INICIAL);
        }

        comunidadSelect.addEventListener('change', function () {
            const cid = this.value;
            provinciaSelect.innerHTML = '<option value="">Cargando...</option>';
            provinciaSelect.disabled = true;
            cp3Input.disabled = true;
            provinciaNombreInput.value = '';
            if (cid) {
                cargarProvincias(cid);
            } else {
                provinciaSelect.innerHTML = '<option value="">Seleccione una comunidad primero</option>';
            }
        });

        provinciaSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            provinciaNombreInput.value = opt.dataset.nombre || '';
            if (this.value) {
                cp3Input.disabled = false;
            }
        });

        document.getElementById('estado').addEventListener('change', function () {
            document.getElementById('campos-realizada').style.display = (this.value === 'R') ? 'block' : 'none';
        });
    });
</script>