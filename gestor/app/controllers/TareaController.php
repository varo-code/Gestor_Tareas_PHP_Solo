<?php

class TareaController
{
    private $tareaModel;

    public function __construct()
    {
        $this->tareaModel = new Tarea();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $pagina = (int) ($_GET['pagina'] ?? 1);
        $pagina = max(1, $pagina);
        $porPagina = 10;

        $filtros = [];
        $campo = $_GET['campo'] ?? '';
        $valor = $_GET['valor'] ?? '';
        $errorBusqueda = null;

        if ($campo !== '' || $valor !== '') {
            if ($campo === '') {
                $errorBusqueda = 'Debe seleccionar un campo para buscar.';
            } elseif (strlen($valor) < 3) {
                $errorBusqueda = 'El valor debe tener al menos 3 caracteres.';
            } else {
                $filtros[] = ['campo' => $campo, 'valor' => $valor];
            }
        }

        $rol = $_SESSION['role'] ?? 'operario';
        $userId = $_SESSION['user_id'] ?? null;

        if (!empty($filtros)) {
            $resultado = $this->tareaModel->buscarTareas($filtros, $rol, $userId, $pagina, $porPagina);
        } else {
            $soloPendientes = isset($_GET['pendientes']) && $_GET['pendientes'] == '1';
            $resultado = $this->tareaModel->getTareasPaginadas($pagina, $porPagina, $rol, $userId, $soloPendientes);
        }

        $tareas = $resultado['tareas'];
        $totalTareas = $resultado['total'];
        $totalPaginas = max(1, ceil($totalTareas / $porPagina));

        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
        }

        ob_start();
        include __DIR__ . '/../views/pages/tareas.php';
        $content = ob_get_clean();

        $title = 'Lista de Tareas';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function create()
    {
        $pdo = Database::getInstance()->getConnection();
        $comunidades = $pdo->query("SELECT id, nombre FROM comunidad_autonoma ORDER BY nombre")->fetchAll();
        $usuarios = $pdo->query("SELECT user_id, user_name, user_surname FROM usuario WHERE role = 'operario' ORDER BY user_name")->fetchAll();

        ob_start();
        include __DIR__ . '/../views/pages/tarea_crear.php';
        $content = ob_get_clean();

        $title = 'Crear Nueva Tarea';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function store()
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $data = [
            'client_contacto' => trim($_POST['client_contacto'] ?? ''),
            'client_email' => trim($_POST['client_email'] ?? ''),
            'client_telefono' => trim($_POST['client_telefono'] ?? ''),
            'nif_cif' => trim($_POST['nif_cif'] ?? ''),
            'client_direccion' => trim($_POST['client_direccion'] ?? ''),
            'comunidad_id' => $_POST['comunidad_id'] ?? '',
            'provincia_cp' => $_POST['provincia_cp'] ?? '',
            'provincia_nombre' => $_POST['provincia_nombre'] ?? '',
            'client_poblacion' => trim($_POST['client_poblacion'] ?? ''),
            'cp3' => trim($_POST['cp3'] ?? ''),
            'user_id' => $_POST['user_id'] ?? '',
            'estado' => $_POST['estado'] ?? 'P',
            'fecha_realizacion' => trim($_POST['fecha_realizacion'] ?? ''),
            'anotaciones_posteriores' => trim($_POST['anotaciones_posteriores'] ?? ''),
            'anotaciones_anteriores' => trim($_POST['anotaciones_anteriores'] ?? 'Sin anotaciones anteriores')
        ];

        $errors = [];

        if ($data['client_contacto'] === '') $errors['client_contacto'] = 'El contacto es obligatorio.';
        if ($data['client_email'] === '') {
            $errors['client_email'] = 'El email es obligatorio.';
        } elseif (!filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['client_email'] = 'El email no es válido.';
        }
        if ($data['client_direccion'] === '') $errors['client_direccion'] = 'La dirección es obligatoria.';
        if ($data['provincia_cp'] === '') $errors['provincia_cp'] = 'Seleccione una provincia.';
        if ($data['user_id'] === '') $errors['user_id'] = 'Seleccione un operario encargado.';
        if (!in_array($data['estado'], ['B', 'P', 'R', 'C'])) $errors['estado'] = 'Estado inválido.';

        if ($data['cp3'] === '') {
            $errors['cp3'] = 'El código postal es obligatorio.';
        } elseif (!ctype_digit($data['cp3']) || strlen($data['cp3']) !== 3) {
            $errors['cp3'] = 'Código postal: solo 3 dígitos.';
        }

        if ($data['nif_cif'] !== '' && !$this->validarNIF($data['nif_cif'])) {
            $errors['nif_cif'] = 'DNI/NIF no válido.';
        }

        if ($data['client_telefono'] !== '') {
            $telefonoSoloDigitos = preg_replace('/[^0-9]/', '', $data['client_telefono']);
            if (strlen($telefonoSoloDigitos) !== 9) {
                $errors['client_telefono'] = 'El teléfono debe tener 9 dígitos.';
            }
        }

        if ($data['estado'] === 'R') {
            if ($data['fecha_realizacion'] === '') {
                $errors['fecha_realizacion'] = 'Fecha de realización obligatoria.';
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_realizacion'])) {
                $errors['fecha_realizacion'] = 'Formato de fecha: AAAA-MM-DD.';
            }
            if ($data['anotaciones_posteriores'] === '') {
                $errors['anotaciones_posteriores'] = 'Anotaciones posteriores obligatorias.';
            }
        }

        if (!empty($errors)) {
            $pdo = Database::getInstance()->getConnection();
            $comunidades = $pdo->query("SELECT id, nombre FROM comunidad_autonoma ORDER BY nombre")->fetchAll();
            $usuarios = $pdo->query("SELECT user_id, user_name, user_surname FROM usuario WHERE role = 'operario' ORDER BY user_name")->fetchAll();

            ob_start();
            include __DIR__ . '/../views/pages/tarea_crear.php';
            $content = ob_get_clean();
            $title = 'Crear Nueva Tarea';
            include __DIR__ . '/../views/layouts/main.php';
            return;
        }

        $cp_completo = $data['provincia_cp'] . $data['cp3'];
        $telefonoGuardado = preg_replace('/[^0-9]/', '', $data['client_telefono']) ?: null;

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO tarea (
                client_contacto, client_telefono, client_email, client_direccion,
                client_poblacion, cp, provincia, estado, fecha_realizacion,
                anotaciones_posteriores, user_id, nif_cif,
                created_at, anotaciones_anteriores
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
        ");

        $stmt->execute([
            $data['client_contacto'],
            $telefonoGuardado,
            $data['client_email'],
            $data['client_direccion'],
            $data['client_poblacion'] ?: null,
            $cp_completo,
            $data['provincia_nombre'],
            $data['estado'],
            $data['estado'] === 'R' ? $data['fecha_realizacion'] : null,
            $data['estado'] === 'R' ? $data['anotaciones_posteriores'] : null,
            (int)$data['user_id'],
            $data['nif_cif'] ?: null,
            $data['anotaciones_anteriores']
        ]);

        header('Location: ' . BASE_PATH . '/tareas?creada=1');
        exit;
    }

    public function confirmarEliminar(int $tareaId)
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            SELECT t.*, u.user_name, u.user_surname
            FROM tarea t
            LEFT JOIN usuario u ON t.user_id = u.user_id
            WHERE t.tarea_id = ?
        ");
        $stmt->execute([$tareaId]);
        $tarea = $stmt->fetch();

        if (!$tarea) {
            http_response_code(404);
            echo "Tarea no encontrada.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/tarea_eliminar.php';
        $content = ob_get_clean();

        $title = 'Confirmar Eliminación';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function eliminar(int $tareaId)
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("DELETE FROM tarea WHERE tarea_id = ?");
        $stmt->execute([$tareaId]);

        header('Location: ' . BASE_PATH . '/tareas?eliminada=1');
        exit;
    }

    public function edit(int $tareaId)
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM tarea WHERE tarea_id = ?");
        $stmt->execute([$tareaId]);
        $tarea = $stmt->fetch();

        if (!$tarea) {
            http_response_code(404);
            echo "Tarea no encontrada.";
            exit;
        }

        $comunidad_id_inicial = null;
        $cp2_inicial = '';
        if (!empty($tarea['provincia'])) {
            $sql = "
                SELECT ca.id, p.cp
                FROM provincias p
                JOIN comunidad_autonoma ca ON p.comunidad_id = ca.id
                WHERE p.nombre = ?
            ";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->bindValue(1, $tarea['provincia'], PDO::PARAM_STR);
            $stmt2->execute();
            $resultado = $stmt2->fetch();
            if ($resultado) {
                $comunidad_id_inicial = (int)$resultado['id'];
                $cp2_inicial = $resultado['cp'];
            }
        }

        $comunidades = $pdo->query("SELECT id, nombre FROM comunidad_autonoma ORDER BY nombre")->fetchAll();
        $usuarios = $pdo->query("SELECT user_id, user_name, user_surname FROM usuario WHERE role = 'operario' ORDER BY user_name")->fetchAll();
        $cp3 = strlen($tarea['cp']) >= 5 ? substr($tarea['cp'], 2) : '';

        ob_start();
        include __DIR__ . '/../views/pages/tarea_editar.php';
        $content = ob_get_clean();

        $title = 'Editar Tarea';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function update(int $tareaId)
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $data = [
            'client_contacto' => trim($_POST['client_contacto'] ?? ''),
            'client_email' => trim($_POST['client_email'] ?? ''),
            'client_telefono' => trim($_POST['client_telefono'] ?? ''),
            'nif_cif' => trim($_POST['nif_cif'] ?? ''),
            'client_direccion' => trim($_POST['client_direccion'] ?? ''),
            'provincia_cp' => $_POST['provincia_cp'] ?? '',
            'provincia_nombre' => $_POST['provincia_nombre'] ?? '',
            'client_poblacion' => trim($_POST['client_poblacion'] ?? ''),
            'cp3' => trim($_POST['cp3'] ?? ''),
            'user_id' => $_POST['user_id'] ?? '',
            'estado' => $_POST['estado'] ?? 'P',
            'fecha_realizacion' => trim($_POST['fecha_realizacion'] ?? ''),
            'anotaciones_posteriores' => trim($_POST['anotaciones_posteriores'] ?? ''),
            'anotaciones_anteriores' => trim($_POST['anotaciones_anteriores'] ?? '')
        ];

        $errors = [];

        if ($data['client_contacto'] === '') $errors['client_contacto'] = 'El contacto es obligatorio.';
        if ($data['client_email'] === '') {
            $errors['client_email'] = 'El email es obligatorio.';
        } elseif (!filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['client_email'] = 'El email no es válido.';
        }
        if ($data['client_direccion'] === '') $errors['client_direccion'] = 'La dirección es obligatoria.';
        if ($data['provincia_cp'] === '') $errors['provincia_cp'] = 'Seleccione una provincia.';
        if ($data['user_id'] === '') $errors['user_id'] = 'Seleccione un operario encargado.';
        if (!in_array($data['estado'], ['B', 'P', 'R', 'C'])) $errors['estado'] = 'Estado inválido.';

        if ($data['cp3'] === '') {
            $errors['cp3'] = 'El código postal es obligatorio.';
        } elseif (!ctype_digit($data['cp3']) || strlen($data['cp3']) !== 3) {
            $errors['cp3'] = 'Código postal: solo 3 dígitos.';
        }

        if ($data['nif_cif'] !== '' && !$this->validarNIF($data['nif_cif'])) {
            $errors['nif_cif'] = 'DNI/NIF no válido.';
        }

        if ($data['client_telefono'] !== '') {
            $telefonoSoloDigitos = preg_replace('/[^0-9]/', '', $data['client_telefono']);
            if (strlen($telefonoSoloDigitos) !== 9) {
                $errors['client_telefono'] = 'El teléfono debe tener 9 dígitos.';
            }
        }

        if ($data['estado'] === 'R') {
            if ($data['fecha_realizacion'] === '') {
                $errors['fecha_realizacion'] = 'Fecha de realización obligatoria.';
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_realizacion'])) {
                $errors['fecha_realizacion'] = 'Formato de fecha: AAAA-MM-DD.';
            }
            if ($data['anotaciones_posteriores'] === '') {
                $errors['anotaciones_posteriores'] = 'Anotaciones posteriores obligatorias.';
            }
        }

        if (!empty($errors)) {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM tarea WHERE tarea_id = ?");
            $stmt->execute([$tareaId]);
            $tarea = $stmt->fetch();
            $comunidades = $pdo->query("SELECT id, nombre FROM comunidad_autonoma ORDER BY nombre")->fetchAll();
            $usuarios = $pdo->query("SELECT user_id, user_name, user_surname FROM usuario WHERE role = 'operario' ORDER BY user_name")->fetchAll();

            $comunidad_id_inicial = null;
            $cp2_inicial = '';
            if (!empty($tarea['provincia'])) {
                $sql = "SELECT ca.id, p.cp FROM provincias p JOIN comunidad_autonoma ca ON p.comunidad_id = ca.id WHERE p.nombre = ?";
                $stmt2 = $pdo->prepare($sql);
                $stmt2->bindValue(1, $tarea['provincia'], PDO::PARAM_STR);
                $stmt2->execute();
                $resultado = $stmt2->fetch();
                if ($resultado) {
                    $comunidad_id_inicial = (int)$resultado['id'];
                    $cp2_inicial = $resultado['cp'];
                }
            }
            $cp3 = strlen($tarea['cp']) >= 5 ? substr($tarea['cp'], 2) : '';

            ob_start();
            include __DIR__ . '/../views/pages/tarea_editar.php';
            $content = ob_get_clean();
            $title = 'Editar Tarea';
            include __DIR__ . '/../views/layouts/main.php';
            return;
        }

        $cp_completo = $data['provincia_cp'] . $data['cp3'];
        $telefonoGuardado = preg_replace('/[^0-9]/', '', $data['client_telefono']) ?: null;

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            UPDATE tarea SET
                client_contacto = ?,
                client_telefono = ?,
                client_email = ?,
                client_direccion = ?,
                client_poblacion = ?,
                cp = ?,
                provincia = ?,
                estado = ?,
                fecha_realizacion = ?,
                anotaciones_posteriores = ?,
                anotaciones_anteriores = ?,
                user_id = ?,
                nif_cif = ?,
                updated_at = NOW()
            WHERE tarea_id = ?
        ");

        $stmt->execute([
            $data['client_contacto'],
            $telefonoGuardado,
            $data['client_email'],
            $data['client_direccion'],
            $data['client_poblacion'] ?: null,
            $cp_completo,
            $data['provincia_nombre'],
            $data['estado'],
            $data['estado'] === 'R' ? $data['fecha_realizacion'] : null,
            $data['estado'] === 'R' ? $data['anotaciones_posteriores'] : null,
            $data['anotaciones_anteriores'],
            (int)$data['user_id'],
            $data['nif_cif'] ?: null,
            $tareaId
        ]);

        header('Location: ' . BASE_PATH . '/tareas?editada=1');
        exit;
    }

    public function completarForm(int $tareaId)
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM tarea WHERE tarea_id = ? AND user_id = ?");
        $stmt->execute([$tareaId, $_SESSION['user_id']]);
        $tarea = $stmt->fetch();

        if (!$tarea || $tarea['estado'] !== 'P') {
            http_response_code(403);
            echo "No tiene permisos para completar esta tarea o ya está completada.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/tarea_completar.php';
        $content = ob_get_clean();

        $title = 'Completar Tarea';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function completar(int $tareaId)
    {
        if ($_SESSION['role'] !== 'operario') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM tarea WHERE tarea_id = ? AND user_id = ?");
        $stmt->execute([$tareaId, $_SESSION['user_id']]);
        $tarea = $stmt->fetch();

        if (!$tarea || $tarea['estado'] !== 'P') {
            http_response_code(403);
            echo "No tiene permisos para completar esta tarea o ya está completada.";
            exit;
        }

        $errors = [];
        $anotaciones = trim($_POST['anotaciones_posteriores'] ?? '');
        $fecha = trim($_POST['fecha_realizacion'] ?? '');

        if ($anotaciones === '') {
            $errors[] = 'Las anotaciones posteriores son obligatorias.';
        }

        $fechaHoy = date('Y-m-d');
        $fechaCreacion = $tarea['fecha_creacion'] ? date('Y-m-d', strtotime($tarea['fecha_creacion'])) : $fechaHoy;

        if ($fecha === '') {
            $errors[] = 'La fecha de realización es obligatoria.';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $errors[] = 'Formato de fecha: AAAA-MM-DD.';
        } else {
            if ($fecha > $fechaHoy) {
                $errors[] = 'La fecha de realización no puede ser posterior a hoy (' . $fechaHoy . ').';
            } elseif ($fecha < $fechaCreacion) {
                $errors[] = 'La fecha de realización no puede ser anterior a la fecha de creación (' . $fechaCreacion . ').';
            }
        }

        // === Subir hasta 5 archivos ===
        $fichero_resumen = null;
        $archivosSubidos = [];

        if (!empty($_FILES['fichero_resumen']['name'][0])) {
            $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
            $maxFiles = 5;
            $count = 0;

            foreach ($_FILES['fichero_resumen']['name'] as $key => $name) {
                if ($count >= $maxFiles) break;
                if (empty($name)) continue;

                $size = $_FILES['fichero_resumen']['size'][$key];
                $tmpName = $_FILES['fichero_resumen']['tmp_name'][$key];
                $error = $_FILES['fichero_resumen']['error'][$key];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if ($error !== UPLOAD_ERR_OK) continue;

                if (!in_array($ext, $allowed)) {
                    $errors[] = "Archivo '$name': formato no permitido.";
                    continue;
                }
                if ($size > 5 * 1024 * 1024) {
                    $errors[] = "Archivo '$name': no puede superar 5 MB.";
                    continue;
                }

                $uploadDir = __DIR__ . '/../../public/storage/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename = 'resumen_' . $tareaId . '_' . ($count + 1) . '.' . $ext;
                $filepath = $uploadDir . $filename;

                if (move_uploaded_file($tmpName, $filepath)) {
                    $archivosSubidos[] = $filename;
                    $count++;
                }
            }

            if (!empty($archivosSubidos)) {
                $fichero_resumen = implode(',', $archivosSubidos);
            }
        }

        if (!empty($errors)) {
            ob_start();
            include __DIR__ . '/../views/pages/tarea_completar.php';
            $content = ob_get_clean();
            $title = 'Completar Tarea';
            include __DIR__ . '/../views/layouts/main.php';
            return;
        }

        $stmt = $pdo->prepare("
            UPDATE tarea SET
                estado = 'R',
                anotaciones_posteriores = ?,
                fecha_realizacion = ?,
                fichero_resumen = ?,
                updated_at = NOW()
            WHERE tarea_id = ?
        ");
        $stmt->execute([$anotaciones, $fecha, $fichero_resumen, $tareaId]);

        header('Location: ' . BASE_PATH . '/tareas?completada=1');
        exit;
    }

    public function ver(int $tareaId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            SELECT t.*, u.user_name, u.user_surname
            FROM tarea t
            LEFT JOIN usuario u ON t.user_id = u.user_id
            WHERE t.tarea_id = ?
        ");
        $stmt->execute([$tareaId]);
        $tarea = $stmt->fetch();

        if (!$tarea) {
            http_response_code(404);
            echo "Tarea no encontrada.";
            exit;
        }

        if ($_SESSION['role'] === 'operario' && $tarea['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "No tiene permisos para ver esta tarea.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/tarea_ver.php';
        $content = ob_get_clean();

        $title = 'Tarea #' . $tareaId;
        include __DIR__ . '/../views/layouts/main.php';
    }

    private function validarNIF(string $nif): bool
    {
        $nif = strtoupper(trim($nif));
        if (strlen($nif) !== 9) return false;

        $numero = substr($nif, 0, 8);
        $letra = substr($nif, 8, 1);

        if (!ctype_digit($numero)) return false;

        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $letraCorrecta = $letras[(int)$numero % 23];

        return $letra === $letraCorrecta;
    }
}
?>