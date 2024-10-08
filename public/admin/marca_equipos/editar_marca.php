<?php
require_once '../../../config/database.php';

// Verificar si el ID de la marca existe en la URL
if (!isset($_GET['id_marca'])) {
    header('Location: marca.php');
    exit;
}

$id_marca = $_GET['id_marca'];

try {
    // Seleccionar la marca específica por ID
    $sql = "SELECT id_marca, marca FROM t_marca_equipo WHERE id_marca = :id_marca";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
    $stmt->execute();
    $marca = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$marca) {
        header('Location: marca.php');
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
    <title>Editar Marca</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
</head>

<body>
    <div class="container mt-5">
        <div class="form-wrapper">
            <h2>Editar Marca</h2>
            <form method="POST" action="update_marca.php">
                <!-- Campo oculto para enviar el ID de la marca -->
                <input type="hidden" name="id_marca" value="<?php echo htmlspecialchars($marca['id_marca']); ?>">

                <div class="form-group">
                    <label for="marca"><i class="fas fa-tag"></i> Nombre de la Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" value="<?php echo htmlspecialchars($marca['marca']); ?>" required>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="marca.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>