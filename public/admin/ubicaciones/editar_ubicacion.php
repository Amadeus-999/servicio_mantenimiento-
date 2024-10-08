<?php
require_once '../../../config/database.php';
session_start();

// Verificar si existe el ID de ubicación en la URL
if (!isset($_GET['id_ubicacion'])) {
    header('Location: ubicacion.php');
    exit;
}

$id_ubicacion = $_GET['id_ubicacion'];

try {
    // Obtener los datos de la ubicación y la facultad asociada
    $sql = "SELECT u.id_ubicacion, u.ubicacion, u.id_facultad, f.facultad 
        FROM t_ubicacion u
        LEFT JOIN t_facultad f ON u.id_facultad = f.id_facultad
        WHERE u.id_ubicacion = :id_ubicacion";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_ubicacion', $id_ubicacion, PDO::PARAM_INT);
    $stmt->execute();
    $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no se encuentra la ubicación, redirigir
    if (!$ubicacion) {
        header('Location: ubicacion.php');
        exit;
    }

    // Obtener las facultades para llenar el select
    $sqlFacultades = "SELECT id_facultad, facultad FROM t_facultad";
    $stmtFacultades = $pdo->query($sqlFacultades);
    $facultades = $stmtFacultades->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ubicación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
</head>

<body>
    <div class="container mt-5">
        <div class="form-wrapper">

            <h2>Editar Ubicación</h2>

            <!-- Si $ubicacion tiene los datos -->
            <?php if ($ubicacion): ?>
                <form method="POST" action="update_ubicacion.php">
                    <input type="hidden" name="id_ubicacion" value="<?php echo htmlspecialchars($ubicacion['id_ubicacion']); ?>">

                    <div class="form-group">
                        <label for="ubicacion">Nombre de la Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($ubicacion['ubicacion']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="id_facultad">Facultad</label>
                        <select class="form-control" id="id_facultad" name="id_facultad" required>
                            <?php foreach ($facultades as $facultad): ?>
                                <option value="<?php echo $facultad['id_facultad']; ?>" <?php echo ($ubicacion['id_facultad'] == $facultad['id_facultad']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($facultad['facultad']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="ubicacion.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
        </div>
    <?php else: ?>
        <p class="text-danger">Error: No se encontró la ubicación.</p>
    <?php endif; ?>
    </div>
</body>

</html>