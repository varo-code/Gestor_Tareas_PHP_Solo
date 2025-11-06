<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Gestor de Tareas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/../assets/css/auth.css">
    <style>
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            background: #f9f9f9;
        }

        input:focus {
            outline: none;
            border-color: #573b8a;
            background: #fff;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="login">
            <form method="POST" action="<?= BASE_PATH ?>/login">
                <h2>Iniciar Sesión</h2>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <?php foreach ($errors as $error): ?>
                            <div><?= htmlspecialchars($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" value="">
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>