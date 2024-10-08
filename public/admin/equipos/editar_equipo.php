<?php
require_once '../../../config/database.php';

// Verificar si el ID del tipo de equipo existe en la URL
if (!isset($_GET['id_tipo_equipo'])) {
    header('Location: equipo.php');
    exit;
}

$id_tipo_equipo = $_GET['id_tipo_equipo'];

try {
    // Seleccionar el tipo de equipo específico por ID
    $sql = "SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo WHERE id_tipo_equipo = :id_tipo_equipo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_tipo_equipo', $id_tipo_equipo, PDO::PARAM_INT);
    $stmt->execute();
    $tipo_equipo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tipo_equipo) {
        header('Location: equipo.php');
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
    <title>Editar Tipo de Equipo</title>
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="form-wrapper">
            <h2>Editar Tipo de Equipo</h2>
            <form method="POST" action="update_equipo.php">
                <!-- Campo oculto para enviar el ID del tipo de equipo -->
                <input type="hidden" name="id_tipo_equipo" value="<?php echo htmlspecialchars($tipo_equipo['id_tipo_equipo']); ?>">

                <div class="form-group">
                    <label for="tipo_equipo"><i class="fas fa-laptop"></i> Nombre del Tipo de Equipo</label>
                    <input type="text" class="form-control" id="tipo_equipo" name="tipo_equipo" value="<?php echo htmlspecialchars($tipo_equipo['tipo_equipo']); ?>" required>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                    <a href="equipo.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>