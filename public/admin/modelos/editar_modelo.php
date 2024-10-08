<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

// Verificar si el ID del modelo existe en la URL
if (!isset($_GET['id_modelo'])) {
    header('Location: modelo.php');
    exit;
}

$id_modelo = $_GET['id_modelo'];

// Obtener los tipos de equipo para el menú desplegable
$sql = "SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo";
$stmt = $pdo->query($sql);
$tipos_equipo = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los datos del modelo para editar
$sql = "SELECT * FROM t_modelo_equipo WHERE id_modelo = :id_modelo";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_modelo', $id_modelo, PDO::PARAM_INT);
$stmt->execute();
$modelo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$modelo) {
    header('Location: modelo.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Modelo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }

        .form-group i {
            margin-right: 5px;
        }

        .button-container {
            text-align: center;
        }

        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container mt-5">
            <div class="form-container">
                <h2><i class="fas fa-cogs"></i> Editar Modelo</h2>
                <form method="POST" action="update_modelo.php">
                    <!-- Campo oculto para enviar el ID del modelo -->
                    <input type="hidden" name="id_modelo" value="<?php echo htmlspecialchars($modelo['id_modelo']); ?>">

                    <div class="form-group">
                        <label for="modelo"><i class="fas fa-cogs"></i> Nombre del Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo htmlspecialchars($modelo['modelo']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_equipo"><i class="fas fa-box"></i> Tipo de Equipo</label>
                        <select id="tipo_equipo" name="tipo_equipo" class="form-control" required>
                            <?php foreach ($tipos_equipo as $tipo): ?>
                                <option value="<?php echo $tipo['id_tipo_equipo']; ?>" <?php echo $tipo['id_tipo_equipo'] == $modelo['id_tipo_equipo'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo['tipo_equipo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="modelo.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
