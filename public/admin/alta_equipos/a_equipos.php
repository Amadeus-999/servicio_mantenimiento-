<?php
require_once '../../../config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

try {
    if ($search) {
        $sql = "SELECT 
                    e.inventario, 
                    e.serie AS serie, 
                    e.activo AS activo,
                    e.nombre_equipo AS nombre_equipo,
                    u.ubicacion AS ubicacion_nombre,
                    t.tipo_equipo AS tipo_equipo_nombre,
                    m.marca AS marca_equipo,
                    mo_eq.modelo AS modelo_equipo,
                    p.procesador AS procesador_nombre,
                    mem.memoria AS memoria_total_nombre,
                    tm.tp_memoria AS tipo_memoria_nombre,
                    mm.marca AS marca_monitor,
                    mo_mon.modelo AS modelo_monitor,
                    e.disco_duro_1, 
                    m_dd1.marca AS marca_dd1, 
                    e.serie_dd1, 
                    mo_dd1.modelo AS modelo_dd1,
                    e.disco_duro_2,
                    m_dd2.marca AS marca_dd2,
                    e.serie_dd2,
                    mo_dd2.modelo AS modelo_dd2,
                    em1.marca AS marca_memoria_1,
                    e.serie_memoria_1,
                    em2.marca AS marca_memoria_2,
                    e.serie_memoria_2,
                    em3.marca AS marca_memoria_3,
                    e.serie_memoria_3,
                    em4.marca AS marca_memoria_4,
                    e.serie_memoria_4,
                    tm.tp_memoria AS tipo_memoria,
                    e.serie_monitor,
                    e.foto_disco_duro,
                    e.foto_memoria,
                    f.facultad AS nombre_facultad -- Se ajusta al nombre correcto de la columna
                FROM t_alta_equipo e
                LEFT JOIN t_ubicacion u ON e.ubicacion = u.id_ubicacion
                LEFT JOIN t_tipo_equipo t ON e.tipo_equipo = t.id_tipo_equipo
                LEFT JOIN t_marca_equipo m ON e.marca = m.id_marca
                LEFT JOIN t_modelo_equipo mo_eq ON e.modelo = mo_eq.id_modelo -- Modelo del equipo
                LEFT JOIN t_procesador p ON e.procesador = p.id_procesador
                LEFT JOIN t_memoria mem ON e.memoria_total = mem.id_memoria
                LEFT JOIN tipo_memoria tm ON e.tip_memoria = tm.id_tmemoria
                LEFT JOIN t_marca_equipo mm ON e.marca_monitor = mm.id_marca
                LEFT JOIN t_modelo_equipo mo_mon ON e.modelo_monitor = mo_mon.id_modelo -- Modelo del monitor
                LEFT JOIN t_marca_equipo m_dd1 ON e.marca_dd1 = m_dd1.id_marca -- Marca Disco Duro 1
                LEFT JOIN t_modelo_equipo mo_dd1 ON e.modelo_dd1 = mo_dd1.id_modelo -- Modelo Disco Duro 1
                LEFT JOIN t_marca_equipo m_dd2 ON e.marca_dd2 = m_dd2.id_marca -- Marca Disco Duro 2
                LEFT JOIN t_modelo_equipo mo_dd2 ON e.modelo_dd2 = mo_dd2.id_modelo -- Modelo Disco Duro 2
                LEFT JOIN t_marca_equipo em1 ON e.marca_memoria_1 = em1.id_marca -- Marca Memoria 1
                LEFT JOIN t_marca_equipo em2 ON e.marca_memoria_2 = em2.id_marca -- Marca Memoria 2
                LEFT JOIN t_marca_equipo em3 ON e.marca_memoria_3 = em3.id_marca -- Marca Memoria 3
                LEFT JOIN t_marca_equipo em4 ON e.marca_memoria_4 = em4.id_marca -- Marca Memoria 4
                LEFT JOIN t_facultad f ON e.id_facultad = f.id_facultad
                WHERE e.inventario LIKE :search
                ORDER BY a.nombre_equipo $order";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    } else {
        $sql = "SELECT 
                    e.inventario, 
                    e.serie AS serie, 
                    e.activo AS activo,
                    e.nombre_equipo AS nombre_equipo,
                    u.ubicacion AS ubicacion_nombre,
                    t.tipo_equipo AS tipo_equipo_nombre,
                    m.marca AS marca_equipo,
                    mo_eq.modelo AS modelo_equipo,
                    p.procesador AS procesador_nombre,
                    mem.memoria AS memoria_total_nombre,
                    tm.tp_memoria AS tipo_memoria_nombre,
                    mm.marca AS marca_monitor,
                    mo_mon.modelo AS modelo_monitor,
                    e.disco_duro_1, 
                    m_dd1.marca AS marca_dd1, 
                    e.serie_dd1, 
                    mo_dd1.modelo AS modelo_dd1,
                    e.disco_duro_2,
                    m_dd2.marca AS marca_dd2,
                    e.serie_dd2,
                    mo_dd2.modelo AS modelo_dd2,
                    em1.marca AS marca_memoria_1,
                    e.serie_memoria_1,
                    em2.marca AS marca_memoria_2,
                    e.serie_memoria_2,
                    em3.marca AS marca_memoria_3,
                    e.serie_memoria_3,
                    em4.marca AS marca_memoria_4,
                    e.serie_memoria_4,
                    tm.tp_memoria AS tipo_memoria,
                    e.serie_monitor,
                    e.foto_disco_duro,
                    e.foto_memoria,
                    f.facultad AS nombre_facultad -- Se ajusta al nombre correcto de la columna
                FROM t_alta_equipo e
                LEFT JOIN t_ubicacion u ON e.ubicacion = u.id_ubicacion
                LEFT JOIN t_tipo_equipo t ON e.tipo_equipo = t.id_tipo_equipo
                LEFT JOIN t_marca_equipo m ON e.marca = m.id_marca
                LEFT JOIN t_modelo_equipo mo_eq ON e.modelo = mo_eq.id_modelo -- Modelo del equipo
                LEFT JOIN t_procesador p ON e.procesador = p.id_procesador
                LEFT JOIN t_memoria mem ON e.memoria_total = mem.id_memoria
                LEFT JOIN tipo_memoria tm ON e.tip_memoria = tm.id_tmemoria
                LEFT JOIN t_marca_equipo mm ON e.marca_monitor = mm.id_marca
                LEFT JOIN t_modelo_equipo mo_mon ON e.modelo_monitor = mo_mon.id_modelo -- Modelo del monitor
                LEFT JOIN t_marca_equipo m_dd1 ON e.marca_dd1 = m_dd1.id_marca -- Marca Disco Duro 1
                LEFT JOIN t_modelo_equipo mo_dd1 ON e.modelo_dd1 = mo_dd1.id_modelo -- Modelo Disco Duro 1
                LEFT JOIN t_marca_equipo m_dd2 ON e.marca_dd2 = m_dd2.id_marca -- Marca Disco Duro 2
                LEFT JOIN t_modelo_equipo mo_dd2 ON e.modelo_dd2 = mo_dd2.id_modelo -- Modelo Disco Duro 2
                LEFT JOIN t_marca_equipo em1 ON e.marca_memoria_1 = em1.id_marca -- Marca Memoria 1
                LEFT JOIN t_marca_equipo em2 ON e.marca_memoria_2 = em2.id_marca -- Marca Memoria 2
                LEFT JOIN t_marca_equipo em3 ON e.marca_memoria_3 = em3.id_marca -- Marca Memoria 3
                LEFT JOIN t_marca_equipo em4 ON e.marca_memoria_4 = em4.id_marca -- Marca Memoria 4
                LEFT JOIN t_facultad f ON e.id_facultad = f.id_facultad
                ORDER BY e.inventario $order";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <a href="add_a_equipos.php" class="btn btn-success">Agregar Nuevo Equipo</a>
            <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            <div>
                <a href="?order=asc<?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?>" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                <a href="?order=desc<?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?>" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
            </div>
        </div>
        <form class="form-inline mb-3" method="GET" action="">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar por inventario" aria-label="Buscar" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre de la Facultad</th>
                        <th>Inventario</th>
                        <th>Serie</th>
                        <th>Activo</th>
                        <th>Nombre del Equipo</th>
                        <th>Ubicación</th>
                        <th>Tipo de Equipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Procesador</th>
                        <th>Tipo de Memoria</th>
                        <th>Disco Duro 1</th>
                        <th>Marca DD1</th>
                        <th>Modelo DD1</th>
                        <th>Disco Duro 2</th>
                        <th>Marca DD2</th>
                        <th>Modelo DD2</th>
                        <th>Marca Memoria 1</th>
                        <th>Serie Memoria 1</th>
                        <th>Marca Memoria 2</th>
                        <th>Serie Memoria 2</th>
                        <th>Marca Memoria 3</th>
                        <th>Serie Memoria 3</th>
                        <th>Marca Memoria 4</th>
                        <th>Serie Memoria 4</th>
                        <th>Memoria Total</th>
                        <th>Marca Monitor</th>
                        <th>Modelo Monitor</th>
                        <th>Serie Monitor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($equipos) && count($equipos) > 0): ?>
                        <?php foreach ($equipos as $equipo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($equipo['nombre_facultad']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['inventario']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['serie']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['activo']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['ubicacion_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['tipo_equipo_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_equipo']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['modelo_equipo']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['procesador_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['memoria_total_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['disco_duro_1']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_dd1']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['modelo_dd1']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['disco_duro_2']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_dd2']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['modelo_dd2']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_memoria_1']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['serie_memoria_1']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_memoria_2']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['serie_memoria_2']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_memoria_3']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['serie_memoria_3']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_memoria_4']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['serie_memoria_4']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['tipo_memoria_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['marca_monitor']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['modelo_monitor']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['serie_monitor']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="28" class="text-center">No se encontraron equipos</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>