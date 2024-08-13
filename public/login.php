<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npesonal = $_POST['npesonal'];
    $password = $_POST['password'];

    // Buscar el usuario por el número personal
    $sql = "SELECT * FROM t_registro WHERE npesonal = :npesonal";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':npesonal' => $npesonal]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;

            if (strpos($npesonal, 'ADM1') === 0) {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
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