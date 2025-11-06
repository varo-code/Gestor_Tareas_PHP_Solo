<?php
class AuthController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_PATH . '/');
            exit;
        }
        include __DIR__ . '/../views/login.php';
    }

    public function handleLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        if ($email === '') {
            $errors[] = 'El campo email es obligatorio.';
        }
        if ($password === '') {
            $errors[] = 'El campo contraseña es obligatorio.';
        }

        if (!empty($errors)) {
            include __DIR__ . '/../views/login.php';
            return;
        }

        $usuario = $this->usuarioModel->findByEmail($email);

        if (!$usuario) {
            $errors[] = 'No existe ningún usuario con ese email.';
            include __DIR__ . '/../views/login.php';
            return;
        }

        if ($usuario['user_password'] !== $password) {
            $errors[] = 'La contraseña es incorrecta.';
            include __DIR__ . '/../views/login.php';
            return;
        }

        unset($usuario['user_password']);
        $_SESSION['user_id'] = $usuario['user_id'];
        $_SESSION['user_name'] = $usuario['user_name'];
        $_SESSION['user_surname'] = $usuario['user_surname'];
        $_SESSION['role'] = $usuario['role'];
        $_SESSION['login_time'] = time();

        header('Location: ' . BASE_PATH . '/');
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: ' . BASE_PATH . '/login');
        exit;
    }
}
?>