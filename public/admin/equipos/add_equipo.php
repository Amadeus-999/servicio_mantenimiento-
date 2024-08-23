<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_equipo = isset($_POST['tipo_equipo']) ? trim($_POST['tipo_equipo']) : '';

    if (!empty($tipo_equipo)) {
        try {
            $sql = "INSERT INTO t_tipo_equipo (tipo_equipo) VALUES (:tipo_equipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':tipo_equipo', $tipo_equipo, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: equipo.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre del tipo de equipo no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tipo de Equipo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_form.css">
    <style>.button-container {text-align: center;}</style>
</head>

<body>
    <div class="container mt-5">
        <h2><i class="fas fa-plus-circle"></i> Agregar Nuevo Tipo de Equipo</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="add_equipo.php">
            <div class="form-group">
                <label for="tipo_equipo"><i class="fas fa-laptop"></i> Nombre del Tipo de Equipo</label>
                <input type="text" class="form-control" id="tipo_equipo" name="tipo_equipo" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Agregar</button>
                <a href="equipo.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
