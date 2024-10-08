<?php
require_once '../../../config/database.php';

// Verificar si existe el ID del procesador en la URL
if (!isset($_GET['id_procesador'])) {
    header('Location: procesador.php');
    exit;
}

$id_procesador = $_GET['id_procesador'];

try {
    // Seleccionar el procesador específico
    $sql = "SELECT id_procesador, procesador FROM t_procesador WHERE id_procesador = :id_procesador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_procesador', $id_procesador, PDO::PARAM_INT);
    $stmt->execute();
    $procesador = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$procesador) {
        header('Location: procesador.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <title>Editar Procesador</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="form-wrapper">

            <h2>Editar Procesador</h2>
            <form method="POST" action="update_procesador.php">
                <!-- Campo oculto para enviar el id del procesador -->
                <input type="hidden" name="id_procesador" value="<?php echo htmlspecialchars($procesador['id_procesador']); ?>">

                <div class="form-group">
                    <label for="procesador"><i class="fas fa-microchip"></i> Nombre del Procesador</label>
                    <input type="text" class="form-control" id="procesador" name="procesador" value="<?php echo htmlspecialchars($procesador['procesador']); ?>" required>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="procesador.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>