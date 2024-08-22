<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $facultad = isset($_POST['facultad']) ? trim($_POST['facultad']) : '';

    if (!empty($facultad)) {
        try {
            $sql = "INSERT INTO t_facultad (facultad) VALUES (:facultad)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':facultad', $facultad, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: facultad.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre de la facultad no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Facultad</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2><i class="fas fa-university"></i> Agregar Nueva Facultad</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="add_facultad.php">
            <div class="form-group">
                <label for="facultad"><i class="fas fa-building"></i> Nombre de la Facultad</label>
                <input type="text" class="form-control" id="facultad" name="facultad" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
            <a href="facultad.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
