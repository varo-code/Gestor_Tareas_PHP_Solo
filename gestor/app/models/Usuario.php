<?php
// Modelo de Usuario con autenticación en texto plano

class Usuario
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Autentica un usuario por email y contraseña en texto plano
     */
    public function login(string $email, string $password)
    {
        $stmt = $this->pdo->prepare("SELECT user_id, user_name, user_surname, user_email, user_password, role FROM usuario WHERE user_email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        // Comparación en texto plano (NO seguro, pero conforme a tu requisito)
        if ($usuario && $usuario['user_password'] === $password) {
            unset($usuario['user_password']); // No exponer en sesión
            return $usuario;
        }

        return false;
    }

    /**
     * Busca un usuario por email (para validación en edición)
     */
    public function findByEmail(string $email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE user_email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}
?>