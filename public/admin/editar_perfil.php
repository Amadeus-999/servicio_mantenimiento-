<?php
session_start();
require_once '../../config/database.php';

$sql = "SELECT id_facultad, facultad FROM t_facultad";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$facultades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si el parámetro npesonal está en la URL
if (!isset($_GET['npesonal'])) {
    die("Número personal no especificado.");
}

$npesonal = $_GET['npesonal'];

$sql = "SELECT * FROM t_registro WHERE npesonal = :npesonal";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':npesonal', $npesonal);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("No se encontró el usuario con el número personal especificado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_p = $_POST['apellido_p'];
    $apellido_m = $_POST['apellido_m'];
    $extension = $_POST['extension'];
    $correo = $_POST['correo'];
    $id_facultad = $_POST['id_facultad'];
    $password = $_POST['password'];

    $sql = "UPDATE t_registro SET nombre = :nombre, apellido_p = :apellido_p, apellido_m = :apellido_m, 
            extension = :extension, correo = :correo, id_facultad = :id_facultad";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = :password";
    }

    $sql .= " WHERE npesonal = :npesonal";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido_p', $apellido_p);
    $stmt->bindParam(':apellido_m', $apellido_m);
    $stmt->bindParam(':extension', $extension);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':id_facultad', $id_facultad);
    $stmt->bindParam(':npesonal', $npesonal);

    if (!empty($password)) {
        $stmt->bindParam(':password', $hashed_password);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Perfil actualizado con éxito.";
    } else {
        $_SESSION['error_message'] = "Error al actualizar el perfil.";
    }

    header('Location: editar_perfil.php?npesonal=' . $npesonal);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Agregar FontAwesome -->
    <style>
        .profile-form {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: #f7f7f7;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
        }
        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-label {
            display: flex;
            align-items: center;
        }
        .form-label i {
            margin-right: 10px;
            color: #007bff;
        }
        .btn-custom {
            padding: 10px 15px; /* Padding adicional para botones */
            margin-bottom: 10px; /* Espacio entre botones */
        }
        .btn-custom-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-custom-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-custom-primary:hover, .btn-custom-secondary:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-form">
            <div class="form-title">Editar Perfil</div>
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="npesonal" class="form-label"><i class="fas fa-id-badge"></i> Número de Personal</label>
                    <input type="text" class="form-control" id="npesonal" name="npesonal" value="<?php echo htmlspecialchars($user['npesonal']); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="apellido_p" class="form-label"><i class="fas fa-user-tie"></i> Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="<?php echo htmlspecialchars($user['apellido_p']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="apellido_m" class="form-label"><i class="fas fa-user-tie"></i> Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="<?php echo htmlspecialchars($user['apellido_m']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="extension" class="form-label"><i class="fas fa-phone"></i> Extensión</label>
                    <input type="text" class="form-control" id="extension" name="extension" value="<?php echo htmlspecialchars($user['extension']); ?>">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="id_facultad" class="form-label"><i class="fas fa-building"></i> Facultad</label>
                    <select class="form-control" id="id_facultad" name="id_facultad" required>
                        <?php foreach ($facultades as $facultad): ?>
                            <option value="<?php echo htmlspecialchars($facultad['id_facultad']); ?>" <?php echo $user['id_facultad'] == $facultad['id_facultad'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($facultad['facultad']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fas fa-lock"></i> Actualizar Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <button type="submit" class="btn btn-custom btn-custom-primary w-100">Guardar Cambios</button>
                <a href="dashboard.php" class="btn btn-custom btn-custom-secondary w-100"><i class="fas fa-arrow-left"></i> Volver</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

