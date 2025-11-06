document.addEventListener('DOMContentLoaded', function () {
    const comunidadSelect = document.getElementById('comunidad_id');
    const provinciaSelect = document.getElementById('provincia_cp');
    const poblacionInput = document.getElementById('client_poblacion');
    const cp3Input = document.getElementById('cp3');
    const estadoSelect = document.getElementById('estado');
    const camposRealizada = document.getElementById('campos-realizada');

    // Cargar provincias al cambiar comunidad
    comunidadSelect.addEventListener('change', function () {
        const comunidadId = this.value;
        provinciaSelect.innerHTML = '<option value="">Cargando...</option>';
        provinciaSelect.disabled = true;
        poblacionInput.disabled = true;
        cp3Input.disabled = true;

        if (!comunidadId) {
            provinciaSelect.innerHTML = '<option value="">Seleccione una comunidad primero</option>';
            return;
        }

        fetch(BASE_PATH + '/api/provincias?comunidad_id=' + comunidadId)
            .then(response => response.json())
            .then(data => {
                provinciaSelect.innerHTML = '<option value="">Seleccione...</option>';
                data.forEach(prov => {
                    const option = document.createElement('option');
                    option.value = prov.cp;
                    option.textContent = prov.nombre;
                    option.dataset.nombre = prov.nombre;
                    provinciaSelect.appendChild(option);
                });
                provinciaSelect.disabled = false;
            })
            .catch(() => {
                provinciaSelect.innerHTML = '<option value="">Error al cargar</option>';
            });
    });

    // Al seleccionar provincia, habilitar cp3 y población
    provinciaSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const cp = this.value;
        const nombre = selectedOption.dataset.nombre || '';

        document.getElementById('provincia_nombre').value = nombre;
        if (cp) {
            cp3Input.disabled = false;
            poblacionInput.disabled = false;
        } else {
            cp3Input.disabled = true;
            poblacionInput.disabled = true;
        }
    });

    // Mostrar/ocultar campos condicionales
    estadoSelect.addEventListener('change', function () {
        camposRealizada.style.display = (this.value === 'R') ? 'block' : 'none';
    });
});