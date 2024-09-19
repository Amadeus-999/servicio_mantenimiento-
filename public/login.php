<?php
require_once '../config/database.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npesonal = $_POST['npesonal'];
    $password = $_POST['password'];

    // Consulta para obtener el docente por número personal
    $sql = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':npesonal' => $npesonal]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verifica la contraseña
        if (password_verify($password, $user['password'])) {
            // Guarda la información del usuario en la sesión
            $_SESSION['user'] = [
                'id' => $user['id'], 
                'npesonal' => $user['npesonal'],
                'nombre' => $user['nombre'],
                'tipo_usuario' => $user['tipo_usuario'], // Guardar el tipo de usuario
            ];

          

            // Redirige según el tipo de usuario
            if ((int)$user['tipo_usuario'] === 1) {
                header('Location: admin/dashboard.php'); // Redirige a admin
            } else {
                header('Location: index.php'); // Redirige a usuario normal
            }
            exit;
        } else {
            $error = 'Contraseña incorrecta';
        }
    } else {
        $error = 'Número personal incorrecto';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="card mt-5">
            <div class="card-header text-center">
                <h4>Inicio de Sesión</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="npesonal">Número Personal</label>
                        <input type="text" class="form-control" id="npesonal" name="npesonal" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                </form>
                <div class="mt-3 text-center">
                    <p class="mb-0">¿No tienes una cuenta?</p>
                    <a href="register.php" class="btn btn-link">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
