<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $modelo = isset($_POST['modelo']) ? trim($_POST['modelo']) : '';
    $tipo_equipo = isset($_POST['tipo_equipo']) ? (int)$_POST['tipo_equipo'] : 0;

    if (!empty($modelo) && $tipo_equipo > 0) {
        try {
            // Inserción en la tabla t_modelo_equipo
            $sql = "INSERT INTO t_modelo_equipo (modelo, id_tipo_equipo) VALUES (:modelo, :tipo_equipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':modelo', $modelo, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_equipo', $tipo_equipo, PDO::PARAM_INT);
            $stmt->execute();

            // Redirección después de la inserción exitosa
            header("Location: modelo.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre del modelo y el tipo de equipo deben ser seleccionados.";
    }
}

// Obtener los tipos de equipo para el desplegable
try {
    $sql = "SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo ORDER BY tipo_equipo ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tipos_equipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Modelo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_form.css">
    <style>.button-container {text-align: center;}</style>
</head>
<body>
    <div class="container mt-5">
        <div class="form-container">
            <h2><i class="fas fa-cogs"></i> Agregar Nuevo Modelo</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="add_modelo.php">
                <div class="form-group">
                    <label for="modelo"><i class="fas fa-cogs"></i> Nombre del Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                </div>
                <div class="form-group">
                    <label for="tipo_equipo"><i class="fas fa-box"></i> Tipo de Equipo</label>
                    <select id="tipo_equipo" name="tipo_equipo" class="form-control" required>
                        <option value="">Selecciona un tipo de equipo</option>
                        <?php foreach ($tipos_equipo as $tipo): ?>
                            <option value="<?php echo $tipo['id_tipo_equipo']; ?>">
                                <?php echo htmlspecialchars($tipo['tipo_equipo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                    <a href="modelo.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
