<?php
require_once '../../../config/database.php';

try {
    // Definir las consultas
    $queries = [
        'modelo' => "SELECT id_modelo, modelo FROM t_modelo_equipo",
        'ubicacion' => "SELECT id_ubicacion, ubicacion FROM t_ubicacion",
        'tipo_equipo' => "SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo",
        'marca' => "SELECT id_marca, marca FROM t_marca_equipo",
        'memoria_total' => "SELECT id_memoria, memoria FROM t_memoria",
        'modelo_dd' => "SELECT id_modelo, modelo FROM t_modelo_equipo", // Modelo de disco duro
        'tipo_memoria' => "SELECT id_tmemoria, tp_memoria FROM tipo_memoria",
        'marca_monitor' => "SELECT id_marca, marca FROM t_marca_equipo",
        'modelo_monitor' => "SELECT id_modelo, modelo FROM t_modelo_equipo", // Modelo de monitor
        'procesador' => "SELECT id_procesador, procesador FROM t_procesador"
    ];

    // Ejecutar las consultas y almacenar los resultados
    $data = [];
    foreach ($queries as $key => $query) {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data[$key] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Preparar la consulta para obtener los equipos si hay búsqueda o no
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    if ($search) {
        $sql = "SELECT * 
                FROM t_alta_equipo 
                WHERE inventario LIKE :search
                ORDER BY nombre_equipo $order";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    } else {
        $sql = "SELECT * 
                FROM t_alta_equipo 
                ORDER BY nombre_equipo $order";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$equipos) {
        throw new Exception("No se encontraron equipos o hubo un error en la consulta.");
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error general: " . $e->getMessage();
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

        <form class="form-inline mb-3" method="GET" action="">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar por inventario" aria-label="Buscar" name="search" value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>Marca DD2</th>
                    <th>Serie DD2</th>
                    <th>Modelo DD2</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($equipos) && $equipos): ?>
                    <?php foreach ($equipos as $equipo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($equipo['inventario']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['serie']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['activo']); ?></td>
                            <td><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                            <td><?php echo isset($data['ubicacion'][$equipo['ubicacion']]) ? htmlspecialchars($data['ubicacion'][$equipo['ubicacion']]['ubicacion']) : 'No disponible'; ?></td>
                            <td><?php echo isset($data['tipo_equipo'][$equipo['tipo_equipo']]) ? htmlspecialchars($data['tipo_equipo'][$equipo['tipo_equipo']]['tipo_equipo']) : 'No disponible'; ?></td>
                            <td><?php echo isset($data['marca'][$equipo['marca']]) ? htmlspecialchars($data['marca'][$equipo['marca']]['marca']) : 'No disponible'; ?></td>
                            <td><?php echo isset($data['modelo'][$equipo['modelo']]) ? htmlspecialchars($data['modelo'][$equipo['modelo']]['modelo']) : 'No disponible'; ?></td>
                            <td><?php echo isset($data['procesador'][$equipo['procesador']]) ? htmlspecialchars($data['procesador'][$equipo['procesador']]['procesador']) : 'No disponible'; ?></td>
                            <td><?php echo isset($data['memoria_total'][$equipo['memoria_total']]) ? htmlspecialchars($data['memoria_total'][$equipo['memoria_total']]['memoria']) : 'No disponible'; ?></td>
                            <td><?php echo htmlspecialchars($equipo['disco_duro_1']); ?></td>
                            <td><?php echo isset($data['marca'][$equipo['marca_dd1']]) ? htmlspecialchars($data['marca'][$equipo['marca_dd1']]['marca']) : 'No disponible'; ?></td>
                            <td><?php echo htmlspecialchars($equipo['serie_dd1']); ?></td>
                            <td><?php echo isset($data['modelo_dd'][$equipo['modelo_dd1']]) ? htmlspecialchars($data['modelo_dd'][$equipo['modelo_dd1']]['modelo']) : 'No disponible'; ?></td>
                            <td><?php echo htmlspecialchars($equipo['disco_duro_2']); ?></td>
                            <td><?php echo isset($data['marca'][$equipo['marca_dd2']]) ? htmlspecialchars($data['marca'][$equipo['marca_dd2']]['marca']) : 'No disponible'; ?></td>
                            <td><?php echo htmlspecialchars($equipo['serie_dd2']); ?></td>
                            <td><?php echo isset($data['modelo_dd'][$equipo['modelo_dd2']]) ? htmlspecialchars($data['modelo_dd'][$equipo['modelo_dd2']]['modelo']) : 'No disponible'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="17" class="text-center">No se encontraron equipos</td>
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
