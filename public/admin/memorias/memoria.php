<?php
require_once '../../../config/database.php';

try {
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    // Consulta para obtener las memorias ordenadas
    $sql = "SELECT memoria FROM t_memoria ORDER BY memoria $order";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $memorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Memorias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .order-btn {
            padding: 2px 10px;
            font-size: 0.8rem;
            margin-left: 5px;
        }
        .order-btn.active {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <a href="add_memoria.php" class="btn btn-success">Agregar Nueva Memoria</a>
            <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            <div>
                <a href="?order=asc" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                <a href="?order=desc" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Memoria</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($memorias): ?>
                    <?php foreach ($memorias as $memoria): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($memoria['memoria']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="1" class="text-center">No se encontraron memorias</td>
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
