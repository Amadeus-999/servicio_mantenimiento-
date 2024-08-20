<?php
require_once '../../../config/database.php';

// Obtener las facultades disponibles para el menú desplegable
$sql = "SELECT id_facultad, facultad FROM t_facultad";
$stmt = $pdo->query($sql);
$facultades = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npesonal = $_POST['npesonal'];
    $nombre = $_POST['nombre'];
    $apellido_p = $_POST['apellido_p'];
    $apellido_m = $_POST['apellido_m'];
    $extension = $_POST['extension'];
    $correo = $_POST['correo'];
    $id_facultad = $_POST['id_facultad'];  // Usar id_facultad

    // Verificar si el número personal ya está registrado
    $check_sql = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([':npesonal' => $npesonal]);
    $existing_user = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        $error = "El número personal ya está registrado.";
    } else {
        // Insertar el nuevo docente en la base de datos
        $sql = "INSERT INTO t_docente (npesonal, nombre, apellido_p, apellido_m, extension, correo, id_facultad)
                VALUES (:npesonal, :nombre, :apellido_p, :apellido_m, :extension, :correo, :id_facultad)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':npesonal' => $npesonal,
            ':nombre' => $nombre,
            ':apellido_p' => $apellido_p,
            ':apellido_m' => $apellido_m,
            ':extension' => $extension,
            ':correo' => $correo,
            ':id_facultad' => $id_facultad
        ]);

        header('Location: docentes.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Docente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_doc.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center bg-primary text-white">
                <h4><i class="fas fa-user-plus"></i> Agregar Nuevo Docente</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" action="add_docente.php">
                    <div class="form-group">
                        <label for="npesonal"><i class="fas fa-id-card"></i> Número Personal</label>
                        <input type="text" class="form-control" id="npesonal" name="npesonal" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre"><i class="fas fa-user"></i> Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_p"><i class="fas fa-user-tag"></i> Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_m"><i class="fas fa-user-tag"></i> Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" required>
                    </div>
                    <div class="form-group">
                        <label for="extension"><i class="fas fa-phone"></i> Extensión</label>
                        <input type="text" class="form-control" id="extension" name="extension">
                    </div>
                    <div class="form-group">
                        <label for="correo"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label for="facultad"><i class="fas fa-university"></i> Facultad</label>
                        <select class="form-control" id="facultad" name="id_facultad" required> <!-- Cambiar name a id_facultad -->
                            <option value="">Seleccionar Facultad</option>
                            <?php foreach ($facultades as $facultad): ?>
                                <option value="<?php echo htmlspecialchars($facultad['id_facultad']); ?>"> <!-- Usar id_facultad -->
                                    <?php echo htmlspecialchars($facultad['facultad']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> Guardar Docente</button>
                    <a href="docentes.php" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Volver</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

