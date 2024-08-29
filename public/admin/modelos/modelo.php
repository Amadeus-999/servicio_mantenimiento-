<?php
require_once '../../../config/database.php';

// Inicializa la variable para evitar el warning
$modelos = [];

try {
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    // Cambia la consulta para obtener los modelos y sus tipos de equipo
    $sql = "SELECT m.id_modelo, m.modelo, t.tipo_equipo 
            FROM t_modelo_equipo m 
            JOIN t_tipo_equipo t ON m.id_tipo_equipo = t.id_tipo_equipo
            ORDER BY m.modelo $order";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Modelos de Equipo</title>
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
            <a href="add_modelo.php" class="btn btn-success">Agregar Nuevo Modelo</a>
            <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            <div>
                <a href="?order=asc" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                <a href="?order=desc" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Modelo</th>
                    <th>Tipo de Equipo</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($modelos): ?>
                    <?php foreach ($modelos as $modelo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($modelo['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($modelo['tipo_equipo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">No se encontraron modelos</td>
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
