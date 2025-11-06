<?php

class UsuarioController
{
    // === PERFIL DEL USUARIO ACTUAL ===
    public function verPerfil()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            http_response_code(404);
            echo "Usuario no encontrado.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/perfil.php';
        $content = ob_get_clean();

        $title = 'Mi Perfil';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function editarPerfilForm()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuarioActual = $stmt->fetch();

        if (!$usuarioActual) {
            http_response_code(404);
            echo "Usuario no encontrado.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/perfil_editar.php';
        $content = ob_get_clean();

        $title = 'Editar Perfil';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function actualizarPerfil()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuarioActual = $stmt->fetch();

        if (!$usuarioActual) {
            http_response_code(404);
            echo "Usuario no encontrado.";
            exit;
        }

        $errors = [];
        $name = trim($_POST['user_name'] ?? '');
        $surname = trim($_POST['user_surname'] ?? '');
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';

        if ($name === '') $errors[] = 'El nombre es obligatorio.';
        if ($surname === '') $errors[] = 'Los apellidos son obligatorios.';
        if ($email === '') {
            $errors[] = 'El email es obligatorio.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es válido.';
        } else {
            $stmt = $pdo->prepare("SELECT user_id FROM usuario WHERE user_email = ? AND user_id != ?");
            $stmt->execute([$email, $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                $errors[] = 'Este email ya está en uso.';
            }
        }

        if (!empty($errors)) {
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $usuarioActual = $stmt->fetch();

            ob_start();
            include __DIR__ . '/../views/pages/perfil_editar.php';
            $content = ob_get_clean();
            $title = 'Editar Perfil';
            include __DIR__ . '/../views/layouts/main.php';
            return;
        }

        $sql = "UPDATE usuario SET user_name = ?, user_surname = ?, user_email = ?";
        $params = [$name, $surname, $email];

        if ($password !== '') {
            $sql .= ", user_password = ?";
            $params[] = $password; // Texto plano
        }

        $sql .= " WHERE user_id = ?";
        $params[] = $_SESSION['user_id'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $_SESSION['user_name'] = $name;
        $_SESSION['user_surname'] = $surname;

        header('Location: ' . BASE_PATH . '/perfil?editado=1');
        exit;
    }

    // === GESTIÓN DE USUARIOS (SOLO ADMIN) ===
    public function listarUsuarios()
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $pagina = (int) ($_GET['pagina'] ?? 1);
        $pagina = max(1, $pagina);
        $porPagina = 10;

        $pdo = Database::getInstance()->getConnection();

        $countStmt = $pdo->query("SELECT COUNT(*) FROM usuario");
        $totalUsuarios = (int) $countStmt->fetchColumn();
        $totalPaginas = max(1, ceil($totalUsuarios / $porPagina));

        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
        }

        $offset = ($pagina - 1) * $porPagina;
        $stmt = $pdo->prepare("SELECT * FROM usuario ORDER BY user_id LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $usuarios = $stmt->fetchAll();

        ob_start();
        include __DIR__ . '/../views/pages/usuarios.php';
        $content = ob_get_clean();

        $title = 'Gestión de Usuarios';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function crearUsuarioForm()
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/usuario_crear.php';
        $content = ob_get_clean();

        $title = 'Crear Usuario';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function crearUsuario()
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $errors = [];
        $name = trim($_POST['user_name'] ?? '');
        $surname = trim($_POST['user_surname'] ?? '');
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';
        $role = $_POST['role'] ?? 'operario';

        if ($name === '') $errors[] = 'El nombre es obligatorio.';
        if ($surname === '') $errors[] = 'Los apellidos son obligatorios.';
        if ($email === '') {
            $errors[] = 'El email es obligatorio.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es válido.';
        } else {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT user_id FROM usuario WHERE user_email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Este email ya está en uso.';
            }
        }
        if ($password === '') $errors[] = 'La contraseña es obligatoria.';

        if (!empty($errors)) {
            ob_start();
            include __DIR__ . '/../views/pages/usuario_crear.php';
            $content = ob_get_clean();
            $title = 'Crear Usuario';
            include __DIR__ . '/../views/layouts/main.php';
            return;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO usuario (user_name, user_surname, user_email, user_password, role)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $surname, $email, $password, $role]);

        header('Location: ' . BASE_PATH . '/usuarios?creado=1');
        exit;
    }

    public function editarUsuarioForm(int $userId)
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE user_id = ?");
        $stmt->execute([$userId]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            http_response_code(404);
            echo "Usuario no encontrado.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/usuario_editar.php';
        $content = ob_get_clean();

        $title = 'Editar Usuario';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function actualizarUsuario(int $userId)
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE user_id = ?");
        $stmt->execute([$userId]);
        $usuarioActual = $stmt->fetch();

        if (!$usuarioActual) {
            http_response_code(404);
            echo "Usuario no encontrado.";
            exit;
        }

        $errors = [];
        $name = trim($_POST['user_name'] ?? '');
        $surname = trim($_POST['user_surname'] ?? '');
        $email = trim($_POST['user_email'] ?? '');
        $password = $_POST['user_password'] ?? '';
        $role = $_POST['role'] ?? 'operario';

        if ($name === '') $errors[] = 'El nombre es obligatorio.';
        if ($surname === '') $errors[] = 'Los apellidos son obligatorios.';
        if ($email === '') {
            $errors[] = 'El email es obligatorio.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es válido.';
        } else {
            $stmt = $pdo->prepare("SELECT user_id FROM usuario WHERE user_email = ? AND user_id != ?");
            $stmt->execute([$email, $userId]);
            if ($stmt->fetch()) {
                $errors[] = 'Este email ya está en uso.';
            }
        }

        if (!empty($errors)) {
            ob_start();
            include __DIR__ . '/../views/pages/usuario_editar.php';
            $content = ob_get_clean();
            $title = 'Editar Usuario';
            include __DIR__ . '/../views/layouts/main.php';
            return;
        }

        $sql = "UPDATE usuario SET user_name = ?, user_surname = ?, user_email = ?, role = ?";
        $params = [$name, $surname, $email, $role];

        if ($password !== '') {
            $sql .= ", user_password = ?";
            $params[] = $password; // Texto plano
        }

        $sql .= " WHERE user_id = ?";
        $params[] = $userId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header('Location: ' . BASE_PATH . '/usuarios?editado=1');
        exit;
    }

    public function confirmarEliminarUsuario(int $userId)
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE user_id = ?");
        $stmt->execute([$userId]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            http_response_code(404);
            echo "Usuario no encontrado.";
            exit;
        }

        ob_start();
        include __DIR__ . '/../views/pages/usuario_eliminar.php';
        $content = ob_get_clean();

        $title = 'Eliminar Usuario';
        include __DIR__ . '/../views/layouts/main.php';
    }

    public function eliminarUsuario(int $userId)
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE user_id = ?");
        $stmt->execute([$userId]);

        header('Location: ' . BASE_PATH . '/usuarios?eliminado=1');
        exit;
    }
}
?>