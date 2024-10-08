<?php
require_once '../config/database.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npesonal = $_POST['npesonal'];
    $password = $_POST['password'];

    // Consultar primero en la tabla de docentes
    $sqlDocente = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
    $stmtDocente = $pdo->prepare($sqlDocente);
    $stmtDocente->execute([':npesonal' => $npesonal]);
    $docente = $stmtDocente->fetch(PDO::FETCH_ASSOC);

    // Consultar en la tabla de administradores si no encuentra al docente
    if (!$docente) {
        $sqlAdmin = "SELECT * FROM t_registro WHERE npesonal = :npesonal";
        $stmtAdmin = $pdo->prepare($sqlAdmin);
        $stmtAdmin->execute([':npesonal' => $npesonal]);
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
    }
// Verificar si es un docente válido
if ($docente) {
    if (password_verify($password, $docente['password'])) {
        $_SESSION['user'] = [
            'id' => $docente['id'], 
            'npesonal' => $docente['npesonal'],
            'nombre' => $docente['nombre'],
            'tipo_usuario' => $docente['tipo_usuario'], // Agregar el tipo de usuario a la sesión
            'id_facultad' => $docente['id_facultad'] // Asegúrate de guardar el id_facultad aquí
        ];

        // Redirigir según el tipo de usuario docente
        if ($docente['tipo_usuario'] == 1) {
            header('Location: admin/dashboard.php'); // Administrador
        } else {
            header('Location: index.php'); // Docente
        }
        exit;
    } else {
        $error = 'Contraseña incorrecta para docente';
    }
}

    // Verificar si es un administrador válido
    elseif ($admin) {
        if (password_verify($password, $admin['password'])) {
            $_SESSION['user'] = [
                'id' => $admin['id'], 
                'npesonal' => $admin['npesonal'],
                'nombre' => $admin['nombre'],
            ];

            // Redirigir según el número personal de admin
            if (strpos($npesonal, 'ADM1') === 0) {
                header('Location: admin/dashboard.php'); // Administrador
            } else {
                header('Location: index.php'); // Otros usuarios
            }
            exit;
        } else {
            $error = 'Contraseña incorrecta para administrador';
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
    <style>
        .login-container {
            display: flex;
            height: 100vh;
        }
        .left-side {
            flex: 2;
            background: url('../uv.png') no-repeat center center;
            background-size: cover;
            border-right: 1px solid #000;
        }
        .right-side {
            flex: 1;
            background: #f8f9fa;
            border-left: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-custom {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .card {
            width: 100%;
            max-width: 400px;
        }
        .modal-content {
            border-radius: 15px;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-footer {
            border-top: none;
        }
        .btn-custom-modal {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-custom-modal:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        /* Estilos para centrar la imagen dentro del modal */
        .logo-modal-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-modal-container img {
            max-width: 120px; /* Tamaño de la imagen dentro del modal */
            height: auto; /* Mantener la proporción de la imagen */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-side"></div> <!-- Imagen de fondo en la parte izquierda -->
        <div class="right-side">
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
                        <button type="submit" class="btn btn-primary btn-custom btn-block">Iniciar Sesión</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p class="mb-0">¿Deseas recuperar tu contraseña?</p>
                        <a href="#" class="btn btn-link" data-toggle="modal" data-target="#forgotPasswordModal">Recupera tu contraseña</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperación de Contraseña</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="logo-modal-container text-center">
                        <img src="../usuario.jpg" alt="Logo Modal">
                    </div>
                    <form id="forgotPasswordForm" method="POST" action="send_recovery_request.php">
                        <div class="form-group">
                            <h2 style="font-size: 28px; text-align: justify;">Para recuperar tu contraseña, envia un correo a administrador@uv.mx</h2>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
