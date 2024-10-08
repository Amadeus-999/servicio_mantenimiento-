<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ubicacion = isset($_POST['ubicacion']) ? trim($_POST['ubicacion']) : '';

    if (!empty($ubicacion)) {
        try {
            // Inserción en la tabla t_ubicacion
            $sql = "INSERT INTO t_ubicacion (ubicacion) VALUES (:ubicacion)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_STR);
            $stmt->execute();

            // Redirección después de la inserción exitosa
            header("Location: ubicacion.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre de la ubicación no puede estar vacío.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ubicación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_form.css">
    <style>.button-container {text-align: center;}</style>
</head>

<body>
    <div class="container">
        <h2><i class="fas fa-map-marker-alt"></i> Agregar Nueva Ubicación</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="add_ubicacion.php">
            <div class="form-group">
                <label for="ubicacion"><i class="fas fa-map-marker-alt"></i> Nombre de la Ubicación</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                <a href="ubicacion.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>