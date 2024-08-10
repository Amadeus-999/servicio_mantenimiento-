<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $npesonal = $_POST['npesonal'];
    $nombre = $_POST['nombre'];
    $apellido_p = $_POST['apellido_p'];
    $apellido_m = $_POST['apellido_m'];
    $extension = $_POST['extension'];
    $correo = $_POST['correo'];
    $facultad = $_POST['facultad'];
    $password = $_POST['password']; // Contraseña en texto plano

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO t_registro (npesonal, nombre, apellido_p, apellido_m, extension, correo, facultad, password)
            VALUES (:npesonal, :nombre, :apellido_p, :apellido_m, :extension, :correo, :facultad, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':npesonal' => $npesonal,
        ':nombre' => $nombre,
        ':apellido_p' => $apellido_p,
        ':apellido_m' => $apellido_m,
        ':extension' => $extension,
        ':correo' => $correo,
        ':facultad' => $facultad,
        ':password' => $hashed_password
    ]);

    // Redirigir o mostrar un mensaje de éxito
    header('Location: login.php');
    exit;
}
?>



<?php
// Obtener las facultades disponibles para el menú desplegable
require_once '../config/database.php';

$sql = "SELECT facultad FROM t_facultad";
$stmt = $pdo->query($sql);
$facultades = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h4>Registro de Usuario</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="npesonal">Número Personal</label>
                        <input type="text" class="form-control" id="npesonal" name="npesonal" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_p">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_m">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" required>
                    </div>
                    <div class="form-group">
                        <label for="extension">Extensión</label>
                        <input type="text" class="form-control" id="extension" name="extension">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label for="facultad">Facultad</label>
                        <select class="form-control" id="facultad" name="facultad" required>
                            <?php foreach ($facultades as $facultad): ?>
                                <option value="<?php echo htmlspecialchars($facultad); ?>">
                                    <?php echo htmlspecialchars($facultad); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>