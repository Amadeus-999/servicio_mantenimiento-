<?php
require_once '../../../config/database.php';

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $order = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'ASC';

    $sql = "SELECT r.id_reporte, r.inventario, r.fecha_reportada, r.falla_reportada, r.reparacion, d.npesonal 
            FROM t_reporte r 
            JOIN t_docente d ON r.id_docente = d.id 
            WHERE r.inventario LIKE :search
            ORDER BY r.fecha_reportada $order";

    $stmt = $pdo->prepare($sql);

    $searchParam = "%$search%";

    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    $stmt->execute();

    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .order-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .order-btn.active {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
        }

        .short-input {
            width: 200px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <a href="g_reportes.php" class="btn btn-success">Generar un Reporte</a>
            <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            <div>
                <a href="?order=ASC" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                <a href="?order=DESC" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
            </div>
        </div>

        <form class="form-inline mb-3" method="GET" action="reporte.php">
            <input class="form-control mr-sm-2 short-input" type="search" placeholder="Buscar por inventario" aria-label="Buscar" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success my-2 my-sm-3" type="submit">Buscar</button>
        </form>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID Reporte</th>
                    <th>Inventario</th>
                    <th>Fecha Reportada</th>
                    <th>Falla Reportada</th>
                    <th>Reparación</th>
                    <th>Número Personal Docente</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reportes): ?>
                    <?php foreach ($reportes as $reporte): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reporte['id_reporte']); ?></td>
                            <td><?php echo htmlspecialchars($reporte['inventario']); ?></td>
                            <td><?php echo htmlspecialchars($reporte['fecha_reportada']); ?></td>
                            <td><?php echo htmlspecialchars($reporte['falla_reportada']); ?></td>
                            <td><?php echo htmlspecialchars($reporte['reparacion']); ?></td>
                            <td><?php echo htmlspecialchars($reporte['npesonal']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron reportes</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>