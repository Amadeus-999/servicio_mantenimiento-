<?php
require_once '../../../config/database.php';

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    // Construir la consulta SQL
    if ($search) {
        $sql = "SELECT Inventario, serie, activo, nombre_equipo, ubicacion, tipo_equipo, marca, modelo, procesador, memoria_total, dd_1, marca_dd_1, serie_dd_1, modelo_dd_1, dd_2 
                FROM t_alta_equipo 
                WHERE serie LIKE :search
                ORDER BY nombre_equipo $order";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    } else {
        $sql = "SELECT Inventario, serie, activo, nombre_equipo, ubicacion, tipo_equipo, marca, modelo, procesador, memoria_total, dd_1, marca_dd_1, serie_dd_1, modelo_dd_1, dd_2 
                FROM t_alta_equipo 
                ORDER BY nombre_equipo $order";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Equipos</title>
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
            <a href="add_a_equipos.php" class="btn btn-success">Agregar Nuevo Equipo</a>
            <div>
                <a href="?order=asc<?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?>" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                <a href="?order=desc<?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?>" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
            </div>
        </div>

        <form class="form-inline mb-3" method="GET" action="a_equipos.php">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar por serie" aria-label="Buscar" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Inventario</th>
                    <th>Serie</th>
                    <th>Activo</th>
                    <th>Nombre Equipo</th>
                    <th>Ubicación</th>
                    <th>Tipo de Equipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Procesador</th>
                    <th>Memoria Total</th>
                    <th>Disco Duro 1</th>
                    <th>Marca DD1</th>
                    <th>Serie DD1</th>
                    <th>Modelo DD1</th>
                    <th>Disco Duro 2</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($equipos): ?>
                    <?php foreach ($equipos as $equipo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($equipo['Inventario']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['serie']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['activo']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['ubicacion']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['tipo_equipo']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['marca']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['procesador']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['memoria_total']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['dd_1']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['marca_dd_1']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['serie_dd_1']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['modelo_dd_1']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['dd_2']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="15" class="text-center">No se encontraron equipos</td>
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