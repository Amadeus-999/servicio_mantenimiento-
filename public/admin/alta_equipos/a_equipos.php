<?php
$host = 'localhost';
$port = 3306;
$db = 'trabajo_social';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

try {
    if ($search) {
        $sql = "SELECT
                    a.inventario,
                    a.serie,
                    a.activo,
                    a.nombre_equipo,
                    u.ubicacion AS ubicacion_nombre,
                    te.tipo_equipo AS tipo_equipo_nombre,
                    m.marca AS marca_nombre,
                    mo.modelo AS modelo_nombre,
                    p.procesador AS procesador_nombre,
                    me.memoria AS memoria_total_nombre,
                    a.disco_duro_1,
                    m1.marca AS marca_dd1_nombre,
                    mo1.modelo AS modelo_dd1_nombre,
                    a.disco_duro_2,
                    m2.marca AS marca_dd2_nombre,
                    mo2.modelo AS modelo_dd2_nombre,
                    mm1.marca AS marca_memoria_1_nombre,
                    a.serie_memoria_1,
                    mm2.marca AS marca_memoria_2_nombre,
                    a.serie_memoria_2,
                    mm3.marca AS marca_memoria_3_nombre,
                    a.serie_memoria_3,
                    mm4.marca AS marca_memoria_4_nombre,
                    a.serie_memoria_4,
                    tm.tp_memoria AS tipo_memoria_nombre,
                    m_monitor.marca AS marca_monitor,
                    mo_monitor.modelo AS modelo_monitor_nombre,
                    a.serie_monitor
                FROM t_alta_equipo a
                LEFT JOIN t_ubicacion u ON a.ubicacion = u.id_ubicacion
                LEFT JOIN t_tipo_equipo te ON a.tipo_equipo = te.id_tipo_equipo
                LEFT JOIN t_marca_equipo m ON a.marca = m.id_marca
                LEFT JOIN t_modelo_equipo mo ON a.modelo = mo.id_modelo
                LEFT JOIN t_procesador p ON a.procesador = p.id_procesador
                LEFT JOIN t_memoria me ON a.memoria_total = me.id_memoria
                LEFT JOIN t_marca_equipo m1 ON a.marca_dd1 = m1.id_marca
                LEFT JOIN t_modelo_equipo mo1 ON a.modelo_dd1 = mo1.id_modelo
                LEFT JOIN t_marca_equipo m2 ON a.marca_dd2 = m2.id_marca
                LEFT JOIN t_modelo_equipo mo2 ON a.modelo_dd2 = mo2.id_modelo
                LEFT JOIN t_marca_equipo mm1 ON a.marca_memoria_1 = mm1.id_marca
                LEFT JOIN t_marca_equipo mm2 ON a.marca_memoria_2 = mm2.id_marca
                LEFT JOIN t_marca_equipo mm3 ON a.marca_memoria_3 = mm3.id_marca
                LEFT JOIN t_marca_equipo mm4 ON a.marca_memoria_4 = mm4.id_marca
                LEFT JOIN tipo_memoria tm ON a.tipo_memoria = tm.id_tmemoria
                LEFT JOIN t_marca_equipo m_monitor ON a.marca_monitor = m_monitor.id_marca
                LEFT JOIN t_modelo_equipo mo_monitor ON a.modelo_monitor = mo_monitor.id_modelo
                WHERE a.inventario LIKE :search
                ORDER BY a.nombre_equipo $order";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    } else {
        $sql = "SELECT
                    a.inventario,
                    a.serie,
                    a.activo,
                    a.nombre_equipo,
                    u.ubicacion AS ubicacion_nombre,
                    te.tipo_equipo AS tipo_equipo_nombre,
                    m.marca AS marca_nombre,
                    mo.modelo AS modelo_nombre,
                    p.procesador AS procesador_nombre,
                    me.memoria AS memoria_total_nombre,
                    a.disco_duro_1,
                    m1.marca AS marca_dd1_nombre,
                    mo1.modelo AS modelo_dd1_nombre,
                    a.disco_duro_2,
                    m2.marca AS marca_dd2_nombre,
                    mo2.modelo AS modelo_dd2_nombre,
                    mm1.marca AS marca_memoria_1_nombre,
                    a.serie_memoria_1,
                    mm2.marca AS marca_memoria_2_nombre,
                    a.serie_memoria_2,
                    mm3.marca AS marca_memoria_3_nombre,
                    a.serie_memoria_3,
                    mm4.marca AS marca_memoria_4_nombre,
                    a.serie_memoria_4,
                    tm.tp_memoria AS tipo_memoria_nombre,
                    m_monitor.marca AS marca_monitor,
                    mo_monitor.modelo AS modelo_monitor_nombre,
                    a.serie_monitor
                FROM t_alta_equipo a
                LEFT JOIN t_ubicacion u ON a.ubicacion = u.id_ubicacion
                LEFT JOIN t_tipo_equipo te ON a.tipo_equipo = te.id_tipo_equipo
                LEFT JOIN t_marca_equipo m ON a.marca = m.id_marca
                LEFT JOIN t_modelo_equipo mo ON a.modelo = mo.id_modelo
                LEFT JOIN t_procesador p ON a.procesador = p.id_procesador
                LEFT JOIN t_memoria me ON a.memoria_total = me.id_memoria
                LEFT JOIN t_marca_equipo m1 ON a.marca_dd1 = m1.id_marca
                LEFT JOIN t_modelo_equipo mo1 ON a.modelo_dd1 = mo1.id_modelo
                LEFT JOIN t_marca_equipo m2 ON a.marca_dd2 = m2.id_marca
                LEFT JOIN t_modelo_equipo mo2 ON a.modelo_dd2 = mo2.id_modelo
                LEFT JOIN t_marca_equipo mm1 ON a.marca_memoria_1 = mm1.id_marca
                LEFT JOIN t_marca_equipo mm2 ON a.marca_memoria_2 = mm2.id_marca
                LEFT JOIN t_marca_equipo mm3 ON a.marca_memoria_3 = mm3.id_marca
                LEFT JOIN t_marca_equipo mm4 ON a.marca_memoria_4 = mm4.id_marca
                LEFT JOIN tipo_memoria tm ON a.tipo_memoria = tm.id_tmemoria
                LEFT JOIN t_marca_equipo m_monitor ON a.marca_monitor = m_monitor.id_marca
                LEFT JOIN t_modelo_equipo mo_monitor ON a.modelo_monitor = mo_monitor.id_modelo
                ORDER BY a.nombre_equipo $order";
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
    <title>Alta de Equipos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Listado de Equipos</h1>

    <form method="get" class="mb-4">
        <div class="form-group">
            <label for="search">Buscar por Inventario:</label>
            <input type="text" id="search" name="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="form-group">
            <label for="order">Ordenar por:</label>
            <select id="order" name="order" class="form-control">
                <option value="asc" <?php echo $order === 'ASC' ? 'selected' : ''; ?>>Ascendente</option>
                <option value="desc" <?php echo $order === 'DESC' ? 'selected' : ''; ?>>Descendente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
        <!-- Tabla de Equipos -->
        <table class="table table-striped">
        <thead>
            <tr>
                <th>Inventario</th>
                <th>Serie</th>
                <th>Activo</th>
                <th>Nombre del Equipo</th>
                <th>Ubicación</th>
                <th>Tipo de Equipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Procesador</th>
                <th>Memoria Total</th>
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
                <th>Tipo de Memoria</th>
                <th>Marca Monitor</th>
                <th>Modelo Monitor</th>
                <th>Serie Monitor</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($equipos) && count($equipos) > 0): ?>
                <?php foreach ($equipos as $equipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($equipo['inventario']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['activo']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['ubicacion_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['tipo_equipo_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['procesador_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['memoria_total_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['disco_duro_1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_dd1_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo_dd1_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['disco_duro_2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_dd2_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo_dd2_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_1_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_2_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_3_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_3']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_4_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_4']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['tipo_memoria_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_monitor']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo_monitor_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_monitor']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="27">No se encontraron equipos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>







