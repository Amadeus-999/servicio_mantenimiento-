<?php
require_once '../../../config/database.php';

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

    // Validar que el parámetro de ordenamiento sea 'ASC' o 'DESC'
    if (!in_array($order, ['ASC', 'DESC'])) {
        $order = 'ASC';
    }

    if ($search) {
        $sql = "SELECT d.npesonal, d.nombre, d.apellido_p, d.apellido_m, d.extension, d.correo, f.facultad
                FROM t_docente d
                JOIN t_facultad f ON d.id_facultad = f.id_facultad
                WHERE d.npesonal LIKE :search 
                ORDER BY d.nombre $order";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        $sql = "SELECT d.npesonal, d.nombre, d.apellido_p, d.apellido_m, d.extension, d.correo, f.facultad
                FROM t_docente d
                JOIN t_facultad f ON d.id_facultad = f.id_facultad
                ORDER BY d.nombre $order";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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
    <style>
        .btn-order {
            padding: 0.25rem 0.5rem; 
            font-size: 0.875rem; 
        }
        .btn-order.active {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important; 
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <a href="add_docente.php" class="btn btn-success">Agregar Nuevo Docente</a>
                <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            </div>
            <form class="form-inline" method="GET" action="docentes.php">
                <input class="form-control mr-sm-2" type="search" placeholder="Buscar por número personal" aria-label="Buscar" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
            </form>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="docentes.php?order=ASC&search=<?php echo htmlspecialchars($search); ?>" 
               class="btn btn-primary btn-order <?php echo ($order === 'ASC') ? 'active' : ''; ?>">A-Z</a>
            <a href="docentes.php?order=DESC&search=<?php echo htmlspecialchars($search); ?>" 
               class="btn btn-primary btn-order ml-2 <?php echo ($order === 'DESC') ? 'active' : ''; ?>">Z-A</a>
        </div>

        <table class="table table-striped">
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

