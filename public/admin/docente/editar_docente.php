<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

// Verificar si el ID del docente existe en la URL
if (!isset($_GET['npesonal'])) {
    header('Location: docentes.php');
    exit;
}

$npesonal = $_GET['npesonal'];

// Obtener las facultades disponibles para el menú desplegable
$sql = "SELECT id_facultad, facultad FROM t_facultad";
$stmt = $pdo->query($sql);
$facultades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los datos del docente para editar
$sql = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':npesonal', $npesonal, PDO::PARAM_STR);
$stmt->execute();
$docente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$docente) {
    header('Location: docentes.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Docente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .card {
            width: 100%;
            max-width: 800px;
            /* Tamaño máximo del cuadro */
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-left: 300px;
            /* Añadimos margen para desplazar a la derecha */
        }
        .form-group i {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="card">
            <div class="card-header text-center bg-primary text-white">
                <h4><i class="fas fa-user-edit"></i> Editar Docente</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="update_docente.php">
                    <!-- Campo oculto para enviar el número personal del docente -->
                    <input type="hidden" name="npesonal_original" value="<?php echo htmlspecialchars($docente['npesonal']); ?>">

                    <div class="form-group">
                        <label for="npesonal"><i class="fas fa-id-card"></i> Número Personal</label>
                        <input type="number" class="form-control" id="npesonal" name="npesonal" value="<?php echo htmlspecialchars($docente['npesonal']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre"><i class="fas fa-user"></i> Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($docente['nombre']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_p"><i class="fas fa-user-tag"></i> Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="<?php echo htmlspecialchars($docente['apellido_p']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_m"><i class="fas fa-user-tag"></i> Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="<?php echo htmlspecialchars($docente['apellido_m']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="extension"><i class="fas fa-phone"></i> Extensión</label>
                        <input type="number" class="form-control" id="extension" name="extension" value="<?php echo htmlspecialchars($docente['extension']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($docente['correo']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="facultad"><i class="fas fa-university"></i> Facultad</label>
                        <select class="form-control" id="facultad" name="id_facultad" required>
                            <?php foreach ($facultades as $facultad): ?>
                                <option value="<?php echo htmlspecialchars($facultad['id_facultad']); ?>" <?php echo $facultad['id_facultad'] == $docente['id_facultad'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($facultad['facultad']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipo_usuario"><i class="fas fa-user-cog"></i> Tipo de Usuario</label>
                        <select class="form-control" id="tipo_usuario" name="tipo_usuario" required>
                            <option value="0" <?php echo $docente['tipo_usuario'] == 0 ? 'selected' : ''; ?>>Docente</option>
                            <option value="1" <?php echo $docente['tipo_usuario'] == 1 ? 'selected' : ''; ?>>Servidor Técnico</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Deje este campo vacío si no desea cambiar la contraseña.</small>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> Guardar Cambios</button>
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