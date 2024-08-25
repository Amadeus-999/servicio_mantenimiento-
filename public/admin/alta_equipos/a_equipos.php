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
        $equipos = []; // Asegúrate de que $equipos siempre sea un array
    }

} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
    $equipos = []; // Asegúrate de que $equipos esté definido
} catch (Exception $e) {
    echo "Error general: " . $e->getMessage();
    $equipos = []; // Asegúrate de que $equipos esté definido
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Lista de Equipos</h1>
    <form method="get" class="mb-3">
        <input type="text" name="search" placeholder="Buscar por inventario" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="?order=asc" class="btn btn-secondary">Ordenar A-Z</a>
        <a href="?order=desc" class="btn btn-secondary">Ordenar Z-A</a>
    </form>
    <table class="table table-bordered">
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
                <th>Serie DD1</th>
                <th>Modelo DD1</th>
                <th>Disco Duro 2</th>
                <th>Marca DD2</th>
                <th>Serie DD2</th>
                <th>Modelo DD2</th>
                <th>Marca Memoria 1</th>
                <th>Serie Memoria 1</th>
                <th>Marca Memoria 2</th>
                <th>Serie Memoria 2</th>
                <th>Marca Memoria 3</th>
                <th>Serie Memoria 3</th>
                <th>Marca Memoria 4</th>
                <th>Serie Memoria 4</th>
                <th>Tipo Memoria</th>
                <th>Marca Monitor</th>
                <th>Modelo Monitor</th>
                <th>Serie Monitor</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($equipos) > 0): ?>
                <?php foreach ($equipos as $equipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($equipo['inventario']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['activo']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['ubicacion']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['tipo_equipo']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['procesador']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['memoria_total']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['disco_duro_1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_dd1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_dd1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo_dd1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['disco_duro_2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_dd2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_dd2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo_dd2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_1']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_2']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_3']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_3']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_memoria_4']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_memoria_4']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['tipo_memoria']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['marca_monitor']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['modelo_monitor']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['serie_monitor']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="30" class="text-center">No se encontraron equipos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>


