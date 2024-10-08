<?php
require_once '../../../config/database.php';

// Verificar si el ID de la facultad existe en la URL
if (!isset($_GET['id_facultad'])) {
    header('Location: facultad.php');
    exit;
}

$id_facultad = $_GET['id_facultad'];

try {
    // Seleccionar la facultad específica por ID
    $sql = "SELECT id_facultad, facultad FROM t_facultad WHERE id_facultad = :id_facultad";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
    $stmt->execute();
    $facultad = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$facultad) {
        header('Location: facultad.php');
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
    <title>Editar Facultad</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="form-wrapper">
            <h2>Editar Facultad</h2>
            <form method="POST" action="update_facultad.php">
                <!-- Campo oculto para enviar el ID de la facultad -->
                <input type="hidden" name="id_facultad" value="<?php echo htmlspecialchars($facultad['id_facultad']); ?>">

                <div class="form-group">
                    <label for="facultad"><i class="fas fa-building"></i> Nombre de la Facultad</label>
                    <input type="text" class="form-control" id="facultad" name="facultad" value="<?php echo htmlspecialchars($facultad['facultad']); ?>" required>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="facultad.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>