<?php

require_once '../../../config/database.php';

try {
    // Verificar si hay un término de búsqueda
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Preparar la consulta SQL para incluir el filtro por número personal si hay una búsqueda
    if ($search) {
        $sql = "SELECT npesonal, nombre, apellido_p, apellido_m, extension, correo, facultad 
                FROM t_docente 
                WHERE npesonal LIKE :search";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        $sql = "SELECT npesonal, nombre, apellido_p, apellido_m, extension, correo, facultad 
                FROM t_docente";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
                                        
    $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <a href="add_docente.php" class="btn btn-success">Agregar Nuevo Docente</a>
                <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            </div>
            <form class="form-inline" method="GET" action="docentes.php">
                <input class="form-control mr-sm-2" type="search" placeholder="Buscar por nombre" aria-label="Buscar" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
            </form>
        </div>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Número Personal</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Extensión</th>
                    <th>Correo</th>
                    <th>Facultad</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($docentes): ?>
                    <?php foreach ($docentes as $docente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($docente['npesonal']); ?></td>
                            <td><?php echo htmlspecialchars($docente['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($docente['apellido_p']); ?></td>
                            <td><?php echo htmlspecialchars($docente['apellido_m']); ?></td>
                            <td><?php echo htmlspecialchars($docente['extension']); ?></td>
                            <td><?php echo htmlspecialchars($docente['correo']); ?></td>
                            <td><?php echo htmlspecialchars($docente['facultad']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron docentes</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>