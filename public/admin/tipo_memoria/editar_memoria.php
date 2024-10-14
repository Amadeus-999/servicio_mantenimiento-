<?php
require_once '../../../config/database.php';

// Verificar si existe el ID de memoria en la URL
if (!isset($_GET['id_memoria'])) {
    header('Location: memoria.php');
    exit;
}

$id_memoria = $_GET['id_memoria'];

try {
    // Seleccionar la memoria específica
    $sql = "SELECT id_memoria, memoria FROM  t_memoria WHERE id_memoria = :id_memoria";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_memoria', $id_memoria, PDO::PARAM_INT);
    $stmt->execute();
    $memoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$memoria) {
        header('Location: memoria.php');
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
    <title>Editar Tipo de Memoria</title>
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="form-wrapper">

            <h2>Editar Tipo de Memoria</h2>
            <form method="POST" action="update_memoria.php">
                <!-- Campo oculto para enviar el id de la memoria -->
                <input type="hidden" name="id_memoria" value="<?php echo htmlspecialchars($memoria['id_memoria']); ?>">

                <div class="form-group">
                    <label for="memoria"><i class="fas fa-memory"></i> Nombre de la Memoria</label>
                    <input type="text" class="form-control" id="memoria" name="memoria" value="<?php echo htmlspecialchars($memoria['memoria']); ?>" required>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="memoria.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>