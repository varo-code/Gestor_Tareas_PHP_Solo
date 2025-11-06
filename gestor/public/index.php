<?php
// Front Controller - Punto único de entrada
define('BASE_PATH', '/gestor/public');

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === RUTA PÚBLICA: API de provincias (sin autenticación) ===
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePathLen = strlen(BASE_PATH);
    if (strpos($requestUri, BASE_PATH) === 0) {
        $path = substr($requestUri, $basePathLen);
    } else {
        $path = ltrim($requestUri, '/');
    }
    $path = trim($path, '/');

    if ($path === 'api/provincias') {
        header('Content-Type: application/json; charset=utf-8');
        $comunidad_id = $_GET['comunidad_id'] ?? null;
        if (!$comunidad_id || !is_numeric($comunidad_id)) {
            echo json_encode([]);
            exit;
        }

        require_once __DIR__ . '/../app/core/Database.php';
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT cp, nombre FROM provincias WHERE comunidad_id = ? ORDER BY nombre");
            $stmt->execute([(int)$comunidad_id]);
            $provincias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($provincias);
        } catch (Exception $e) {
            echo json_encode([]);
        }
        exit;
    }
}

// Cargar clases esenciales
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/models/Usuario.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

// Obtener la ruta solicitada
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePathLen = strlen(BASE_PATH);
if (strpos($requestUri, BASE_PATH) === 0) {
    $path = substr($requestUri, $basePathLen);
} else {
    header('Location: ' . BASE_PATH . '/login');
    exit;
}
$path = trim($path, '/');

// === RUTAS PROTEGIDAS ===

// Home
if ($path === '' || $path === 'index.php') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_PATH . '/login');
        exit;
    }

    ob_start();
    ?>
    <div class="company-header">
        <h1>Bienvenido a Bunglebuild S.L.</h1>
        <p class="subtitle">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
    </div>

    <div class="company-section">
        <h2>¿Quiénes somos?</h2>
        <p>
            Bunglebuild S.L. <br><br>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Placeat numquam perferendis quos explicabo sit officiis voluptatem tenetur quas possimus reiciendis repudiandae soluta at beatae quisquam, unde, obcaecati nostrum accusamus accusantium.
        </p>
        <p>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Placeat numquam perferendis quos explicabo sit officiis voluptatem tenetur quas possimus reiciendis repudiandae soluta at beatae quisquam, unde, obcaecati nostrum accusamus accusantium.
        </p>
    </div>

    <div class="company-section">
        <h2>Servicios que ofrecemos</h2>
        <ul>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
        </ul>
    </div>

    <div class="company-values">
        <h2>Nuestros valores</h2>
        <div class="values-grid">
            <div class="value-card">
                <strong>Calidad</strong>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eum numquam, quis, porro itaque sit nobis corrupti nesciunt quibusdam odit, similique aut vero explicabo beatae quaerat. Minus corporis natus perspiciatis accusamus?</p>
            </div>
            <div class="value-card">
                <strong>Confianza</strong>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Assumenda eos reiciendis, iusto nulla rem ad aliquam rerum pariatur eaque enim illum odit doloribus, placeat et expedita, perspiciatis a quod ipsa!</p>
            </div>
            <div class="value-card">
                <strong>Transparencia</strong>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Assumenda eos reiciendis, iusto nulla rem ad aliquam rerum pariatur eaque enim illum odit doloribus, placeat et expedita, perspiciatis a quod ipsa!</p>
            </div>
        </div>
    </div>

    <div class="user-info">
        <p><strong>Rol:</strong> <?= htmlspecialchars($_SESSION['role'] === 'admin' ? 'Administrador' : 'Operario') ?></p>
        <p><strong>Último acceso:</strong> <?= date('d/m/Y H:i:s', $_SESSION['login_time'] ?? time()) ?></p>
    </div>
    <?php
    $content = ob_get_clean();
    $title = 'Home - Bunglebuild S.L.';
    include __DIR__ . '/../app/views/layouts/main.php';
    exit;
}

// Login
if ($path === 'login') {
    $controller = new AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->handleLogin();
    } else {
        $controller->showLogin();
    }
    exit;
}

// Logout
if ($path === 'logout') {
    $controller = new AuthController();
    $controller->logout();
    exit;
}

// Tareas
if ($path === 'tareas') {
    require_once __DIR__ . '/../app/models/Tarea.php';
    require_once __DIR__ . '/../app/controllers/TareaController.php';
    $controller = new TareaController();
    $controller->index();
    exit;
}

// Crear tarea
if ($path === 'tareas/crear') {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/models/Tarea.php';
    require_once __DIR__ . '/../app/controllers/TareaController.php';
    $controller = new TareaController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } else {
        $controller->create();
    }
    exit;
}

// Editar tarea
if (preg_match('#^tareas/editar/(\d+)$#', $path, $matches)) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/models/Tarea.php';
    require_once __DIR__ . '/../app/controllers/TareaController.php';
    $tareaId = (int)$matches[1];
    $controller = new TareaController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->update($tareaId);
    } else {
        $controller->edit($tareaId);
    }
    exit;
}

// Eliminar tarea
if (preg_match('#^tareas/eliminar/(\d+)$#', $path, $matches)) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/models/Tarea.php';
    require_once __DIR__ . '/../app/controllers/TareaController.php';
    $tareaId = (int)$matches[1];
    $controller = new TareaController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->eliminar($tareaId);
    } else {
        $controller->confirmarEliminar($tareaId);
    }
    exit;
}

// Completar tarea
if (preg_match('#^tareas/completar/(\d+)$#', $path, $matches)) {
    if ($_SESSION['role'] !== 'operario') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/models/Tarea.php';
    require_once __DIR__ . '/../app/controllers/TareaController.php';
    $tareaId = (int)$matches[1];
    $controller = new TareaController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->completar($tareaId);
    } else {
        $controller->completarForm($tareaId);
    }
    exit;
}

// Ver tarea individual
if (preg_match('#^tareas/ver/(\d+)$#', $path, $matches)) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_PATH . '/login');
        exit;
    }
    require_once __DIR__ . '/../app/models/Tarea.php';
    require_once __DIR__ . '/../app/controllers/TareaController.php';
    $tareaId = (int)$matches[1];
    $controller = new TareaController();
    $controller->ver($tareaId);
    exit;
}

// Perfil del usuario
if ($path === 'perfil') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_PATH . '/login');
        exit;
    }
    require_once __DIR__ . '/../app/controllers/UsuarioController.php';
    $controller = new UsuarioController();
    $controller->verPerfil();
    exit;
}

// Editar perfil
if ($path === 'perfil/editar') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_PATH . '/login');
        exit;
    }
    require_once __DIR__ . '/../app/controllers/UsuarioController.php';
    $controller = new UsuarioController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->actualizarPerfil();
    } else {
        $controller->editarPerfilForm();
    }
    exit;
}

// Gestión de usuarios (solo admin)
if ($path === 'usuarios') {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/controllers/UsuarioController.php';
    $controller = new UsuarioController();
    $controller->listarUsuarios();
    exit;
}

if ($path === 'usuarios/crear') {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/controllers/UsuarioController.php';
    $controller = new UsuarioController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->crearUsuario();
    } else {
        $controller->crearUsuarioForm();
    }
    exit;
}

if (preg_match('#^usuarios/editar/(\d+)$#', $path, $matches)) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/controllers/UsuarioController.php';
    $userId = (int)$matches[1];
    $controller = new UsuarioController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->actualizarUsuario($userId);
    } else {
        $controller->editarUsuarioForm($userId);
    }
    exit;
}

if (preg_match('#^usuarios/eliminar/(\d+)$#', $path, $matches)) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo "Acceso denegado.";
        exit;
    }
    require_once __DIR__ . '/../app/controllers/UsuarioController.php';
    $userId = (int)$matches[1];
    $controller = new UsuarioController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->eliminarUsuario($userId);
    } else {
        $controller->confirmarEliminarUsuario($userId);
    }
    exit;
}

// Ruta no encontrada
http_response_code(404);
echo "<h2>404 - Página no encontrada</h2>";
echo "<p>La ruta solicitada no existe.</p>";
?>