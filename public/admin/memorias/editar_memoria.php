<?php
require_once '../../../config/database.php';

// Verificar si existe el ID de memoria en la URL
if (!isset($_GET['id_tmemoria'])) {
    header('Location: memoria.php');
    exit;
}

$id_memoria = $_GET['id_tmemoria'];

try {
    // Seleccionar la memoria específica
    $sql = "SELECT id_tmemoria, tp_memoria FROM  tipo_memoria WHERE id_tmemoria = :id_tmemoria";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_tmemoria', $id_memoria, PDO::PARAM_INT);
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
    <title>Editar Memoria</title>
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="form-wrapper">

            <h2>Editar Memoria</h2>
            <form method="POST" action="update_memoria.php">
                <!-- Campo oculto para enviar el id de la memoria -->
                <input type="hidden" name="id_tmemoria" value="<?php echo htmlspecialchars($memoria['id_tmemoria']); ?>">

                <div class="form-group">
                    <label for="tp_memoria"><i class="fas fa-memory"></i> Nombre de la Memoria</label>
                    <input type="text" class="form-control" id="tp_memoria" name="tp_memoria" value="<?php echo htmlspecialchars($memoria['tp_memoria']); ?>" required>
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