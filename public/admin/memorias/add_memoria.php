<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memoria = isset($_POST['memoria']) ? trim($_POST['memoria']) : '';

    if (!empty($memoria)) {
        try {
            // Inserción en la tabla t_memoria
            $sql = "INSERT INTO t_memoria (memoria) VALUES (:memoria)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':memoria', $memoria, PDO::PARAM_STR);
            $stmt->execute();

            // Redirección después de la inserción exitosa
            header("Location: memoria.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre de la memoria no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Memoria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_form.css">
    <style>.button-container {text-align: center;}</style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-memory"></i> Agregar Nueva Memoria</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="add_memoria.php">
            <div class="form-group">
                <label for="memoria"><i class="fas fa-memory"></i> Nombre de la Memoria</label>
                <input type="text" class="form-control" id="memoria" name="memoria" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                <a href="memoria.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
