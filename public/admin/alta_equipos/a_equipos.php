<?php
session_start();
require_once '../../../config/database.php';

// Asegurarse de que el usuario esté autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

// Obtener el nombre del usuario y el id de la facultad desde la sesión
$user_name = $_SESSION['user']['nombre'];
$id_facultad = $_SESSION['user']['id_facultad']; // ID de la facultad del servidor técnico

// Inicializar los valores de búsqueda
$search_values = [
    'facultad' => '',
    'inventario' => '',
    'serie' => '',
    'activo' => '',
    'nombre_equipo' => '',
    'ubicacion' => '',
    'tipo_equipo' => '',
    'marca' => '',
    'modelo' => '',
    'procesador' => '',
    'tipo_memoria' => '',
    'disco_duro_1' => '',
    'marca_dd1' => '',
    'modelo_dd1' => '',
    'disco_duro_2' => '',
    'marca_dd2' => '',
    'modelo_dd2' => '',
    'marca_memoria_1' => '',
    'serie_memoria_1' => '',
    'marca_memoria_2' => '',
    'serie_memoria_2' => '',
    'marca_memoria_3' => '',
    'serie_memoria_3' => '',
    'marca_memoria_4' => '',
    'serie_memoria_4' => '',
    'memoria_total' => '',
    'marca_monitor' => '',
    'modelo_monitor' => '',
    'serie_monitor' => ''
];

// Obtener los valores de búsqueda del formulario
foreach ($search_values as $key => &$value) {
    $value = isset($_GET[$key]) ? trim($_GET[$key]) : '';
}

// Obtener la dirección de ordenamiento
$order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

// Obtener la columna de ordenación
$order_column = isset($_GET['order_column']) ? $_GET['order_column'] : 'inventario';

// Definir las columnas permitidas para el orden
$allowed_columns = [
    'facultad' => 'f.facultad',
    'inventario' => 'e.inventario',
    'serie' => 'e.serie',
    'activo' => 'e.activo',
    'nombre_equipo' => 'e.nombre_equipo',
    'ubicacion' => 'u.ubicacion',
    'tipo_equipo' => 't.tipo_equipo',
    'marca' => 'm.marca',
    'modelo' => 'mo_eq.modelo',
    'procesador' => 'p.procesador',
    'tipo_memoria' => 'tm.tp_memoria',
    'marca_monitor' => 'mm.marca',
    'modelo_monitor' => 'mo_mon.modelo',
    'disco_duro_1' => 'e.disco_duro_1',
    'marca_dd1' => 'm_dd1.marca',
    'modelo_dd1' => 'mo_dd1.modelo',
    'disco_duro_2' => 'e.disco_duro_2',
    'marca_dd2' => 'm_dd2.marca',
    'modelo_dd2' => 'mo_dd2.modelo',
    'marca_memoria_1' => 'em1.marca',
    'serie_memoria_1' => 'e.serie_memoria_1',
    'marca_memoria_2' => 'em2.marca',
    'serie_memoria_2' => 'e.serie_memoria_2',
    'marca_memoria_3' => 'em3.marca',
    'serie_memoria_3' => 'e.serie_memoria_3',
    'marca_memoria_4' => 'em4.marca',
    'serie_memoria_4' => 'e.serie_memoria_4',
    'serie_monitor' => 'e.serie_monitor'
];

// Validar la columna de orden
$order_column_sql = array_key_exists($order_column, $allowed_columns) ? $allowed_columns[$order_column] : 'e.inventario';

try {
    // Construir la consulta SQL
    $sql = "SELECT 
            id_alta,
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
            e.serie_monitor,
            e.foto_disco_duro,
            e.foto_memoria,
            f.facultad AS nombre_facultad
        FROM t_alta_equipo e
        LEFT JOIN t_ubicacion u ON e.ubicacion = u.id_ubicacion
        LEFT JOIN t_tipo_equipo t ON e.tipo_equipo = t.id_tipo_equipo
        LEFT JOIN t_marca_equipo m ON e.marca = m.id_marca
        LEFT JOIN t_modelo_equipo mo_eq ON e.modelo = mo_eq.id_modelo
        LEFT JOIN t_procesador p ON e.procesador = p.id_procesador
        LEFT JOIN t_memoria mem ON e.memoria_total = mem.id_memoria
        LEFT JOIN tipo_memoria tm ON e.tip_memoria = tm.id_tmemoria
        LEFT JOIN t_marca_equipo mm ON e.marca_monitor = mm.id_marca
        LEFT JOIN t_modelo_equipo mo_mon ON e.modelo_monitor = mo_mon.id_modelo
        LEFT JOIN t_marca_equipo m_dd1 ON e.marca_dd1 = m_dd1.id_marca
        LEFT JOIN t_modelo_equipo mo_dd1 ON e.modelo_dd1 = mo_dd1.id_modelo
        LEFT JOIN t_marca_equipo m_dd2 ON e.marca_dd2 = m_dd2.id_marca
        LEFT JOIN t_modelo_equipo mo_dd2 ON e.modelo_dd2 = mo_dd2.id_modelo
        LEFT JOIN t_marca_equipo em1 ON e.marca_memoria_1 = em1.id_marca
        LEFT JOIN t_marca_equipo em2 ON e.marca_memoria_2 = em2.id_marca
        LEFT JOIN t_marca_equipo em3 ON e.marca_memoria_3 = em3.id_marca
        LEFT JOIN t_marca_equipo em4 ON e.marca_memoria_4 = em4.id_marca
        LEFT JOIN t_facultad f ON e.id_facultad = f.id_facultad
        WHERE e.id_facultad = :id_facultad";

    // Añadir filtros a la consulta
    $conditions = [];
    foreach ($search_values as $key => $value) {
        if ($value !== '') {
            $conditions[] = "$allowed_columns[$key] LIKE :$key";
        }
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    $sql .= " ORDER BY $order_column_sql $order";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);

    // Vincular los parámetros de búsqueda
    foreach ($search_values as $key => $value) {
        if ($value !== '') {
            $stmt->bindValue(":$key", '%' . $value . '%', PDO::PARAM_STR);
        }
    }

    $stmt->execute();
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Equipos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .navbar {
            background-color: #007bff;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .sidebar {
            height: calc(100vh - 56px);
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            position: fixed;
            top: 56px;
            left: 0;
            overflow-y: auto;
        }

        .sidebar a {
            color: #ffffff;
        }

        .sidebar a:hover {
            background-color: #007bff;
            border-radius: 5px;
        }

        .sidebar h4 {
            color: #ffffff;
        }

        .content {
            margin-left: 250px;
            margin-top: 56px;
            padding: 20px;
            height: calc(100vh - 56px);
            overflow-y: auto;
        }

        .collapse {
            background-color: #444b52;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .btn-order {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-order.active {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
        }

        table thead {
            background-color: #343a40;
            color: white;
        }

        .order-btn.active {
            background-color: #28a745;
            color: white;
        }

        /* Estilo para los botones de acción */
        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .action-buttons a {
            width: 48%;
        }
    </style>
</head>

<body>

    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Panel de Servidor T</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">Bienvenido, <?php echo htmlspecialchars($user_name); ?></span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="../editar_perfil.php?npesonal=<?php echo urlencode($_SESSION['user']['npesonal']); ?>">Editar Perfil</a>
                        <a class="dropdown-item" href="../../logout.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="d-flex">
        <!-- Menú lateral -->
        <div class="sidebar">
            <h4>Secciones</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#altaEquiposSubmenu" role="button"
                        aria-expanded="false" aria-controls="altaEquiposSubmenu">
                        <i class="fas fa-plus-circle"></i> Equipos
                    </a>
                    <div class="collapse" id="altaEquiposSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../alta_equipos/a_equipos.php">Mostrar Equipos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../alta_equipos/add_a_equipos.php">Agregar Nuevo Equipo</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#docentesSubmenu" role="button"
                        aria-expanded="false" aria-controls="docentesSubmenu">
                        <i class="fas fa-chalkboard-teacher"></i> Docentes
                    </a>
                    <div class="collapse" id="docentesSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../docente/docentes.php">Mostrar Docentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../docente/add_docente.php">Agregar Docente</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#serviciosSubmenu" role="button"
                        aria-expanded="false" aria-controls="serviciosSubmenu">
                        <i class="fas fa-tools"></i> Servicios
                    </a>
                    <div class="collapse" id="serviciosSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../reportes/reporte.php">Mostrar Servicios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../reportes/g_reportes.php">Generar un Reporte</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="container content">
            <div class="d-flex justify-content-between mb-3">
                <a href="add_a_equipos.php" class="btn btn-success">Agregar Nuevo Equipo</a>
                <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            </div>

            <form method="GET">
                <input type="text" name="facultad" placeholder="Facultad" value="<?php echo htmlspecialchars($search_values['facultad']); ?>">
                <input type="text" name="inventario" placeholder="Inventario" value="<?php echo htmlspecialchars($search_values['inventario']); ?>">
                <input type="text" name="serie" placeholder="Serie" value="<?php echo htmlspecialchars($search_values['serie']); ?>">
                <input type="text" name="activo" placeholder="Activo" value="<?php echo htmlspecialchars($search_values['activo']); ?>">
                <input type="text" name="nombre_equipo" placeholder="Nombre del Equipo" value="<?php echo htmlspecialchars($search_values['nombre_equipo']); ?>">
                <input type="text" name="ubicacion" placeholder="Ubicación" value="<?php echo htmlspecialchars($search_values['ubicacion']); ?>">
                <input type="text" name="tipo_equipo" placeholder="Tipo de Equipo" value="<?php echo htmlspecialchars($search_values['tipo_equipo']); ?>">
                <input type="text" name="marca" placeholder="Marca" value="<?php echo htmlspecialchars($search_values['marca']); ?>">
                <input type="text" name="modelo" placeholder="Modelo" value="<?php echo htmlspecialchars($search_values['modelo']); ?>">
                <input type="text" name="procesador" placeholder="Procesador" value="<?php echo htmlspecialchars($search_values['procesador']); ?>">
                <input type="text" name="tipo_memoria" placeholder="Tipo de Memoria" value="<?php echo htmlspecialchars($search_values['tipo_memoria']); ?>">
                <input type="text" name="disco_duro_1" placeholder="Disco Duro 1" value="<?php echo htmlspecialchars($search_values['disco_duro_1']); ?>">
                <input type="text" name="marca_dd1" placeholder="Marca DD1" value="<?php echo htmlspecialchars($search_values['marca_dd1']); ?>">
                <input type="text" name="modelo_dd1" placeholder="Modelo DD1" value="<?php echo htmlspecialchars($search_values['modelo_dd1']); ?>">
                <input type="text" name="disco_duro_2" placeholder="Disco Duro 2" value="<?php echo htmlspecialchars($search_values['disco_duro_2']); ?>">
                <input type="text" name="marca_dd2" placeholder="Marca DD2" value="<?php echo htmlspecialchars($search_values['marca_dd2']); ?>">
                <input type="text" name="modelo_dd2" placeholder="Modelo DD2" value="<?php echo htmlspecialchars($search_values['modelo_dd2']); ?>">
                <input type="text" name="marca_memoria_1" placeholder="Marca Memoria 1" value="<?php echo htmlspecialchars($search_values['marca_memoria_1']); ?>">
                <input type="text" name="serie_memoria_1" placeholder="Serie Memoria 1" value="<?php echo htmlspecialchars($search_values['serie_memoria_1']); ?>">
                <input type="text" name="marca_memoria_2" placeholder="Marca Memoria 2" value="<?php echo htmlspecialchars($search_values['marca_memoria_2']); ?>">
                <input type="text" name="serie_memoria_2" placeholder="Serie Memoria 2" value="<?php echo htmlspecialchars($search_values['serie_memoria_2']); ?>">
                <input type="text" name="marca_memoria_3" placeholder="Marca Memoria 3" value="<?php echo htmlspecialchars($search_values['marca_memoria_3']); ?>">
                <input type="text" name="serie_memoria_3" placeholder="Serie Memoria 3" value="<?php echo htmlspecialchars($search_values['serie_memoria_3']); ?>">
                <input type="text" name="marca_memoria_4" placeholder="Marca Memoria 4" value="<?php echo htmlspecialchars($search_values['marca_memoria_4']); ?>">
                <input type="text" name="serie_memoria_4" placeholder="Serie Memoria 4" value="<?php echo htmlspecialchars($search_values['serie_memoria_4']); ?>">
                <input type="text" name="memoria_total" placeholder="Memoria Total" value="<?php echo htmlspecialchars($search_values['memoria_total']); ?>">
                <input type="text" name="marca_monitor" placeholder="Marca Monitor" value="<?php echo htmlspecialchars($search_values['marca_monitor']); ?>">
                <input type="text" name="modelo_monitor" placeholder="Modelo Monitor" value="<?php echo htmlspecialchars($search_values['modelo_monitor']); ?>">
                <input type="text" name="serie_monitor" placeholder="Serie Monitor" value="<?php echo htmlspecialchars($search_values['serie_monitor']); ?>">
                <button type="submit">Buscar</button>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>
                                Facultad
                                <br>
                                <a href="?order_column=facultad&order=asc">↑</a>
                                <a href="?order_column=facultad&order=desc">↓</a>
                            </th>
                            <th>
                                Inventario
                                <br>
                                <a href="?order_column=inventario&order=asc">↑</a>
                                <a href="?order_column=inventario&order=desc">↓</a>
                            </th>
                            <th>
                                Activo
                                <br>
                                <a href="?order_column=activo&order=asc">↑</a>
                                <a href="?order_column=activo&order=desc">↓</a>
                            </th>
                            <th>
                                Serie
                                <br>
                                <a href="?order_column=serie&order=asc">↑</a>
                                <a href="?order_column=serie&order=desc">↓</a>
                            </th>

                            <th>
                                Nombre del Equipo
                                <br>
                                <a href="?order_column=nombre_equipo&order=asc">↑</a>
                                <a href="?order_column=nombre_equipo&order=desc">↓</a>
                            </th>
                            <th>
                                Ubicación
                                <br>
                                <a href="?order_column=ubicacion&order=asc">↑</a>
                                <a href="?order_column=ubicacion&order=desc">↓</a>
                            </th>
                            <th>
                                Tipo de Equipo
                                <br>
                                <a href="?order_column=tipo_equipo&order=asc">↑</a>
                                <a href="?order_column=tipo_equipo&order=desc">↓</a>
                            </th>
                            <th>
                                Marca
                                <br>
                                <a href="?order_column=marca&order=asc">↑</a>
                                <a href="?order_column=marca&order=desc">↓</a>
                            </th>
                            <th>
                                Modelo
                                <br>
                                <a href="?order_column=modelo&order=asc">↑</a>
                                <a href="?order_column=modelo&order=desc">↓</a>
                            </th>
                            <th>
                                Procesador
                                <br>
                                <a href="?order_column=procesador&order=asc">↑</a>
                                <a href="?order_column=procesador&order=desc">↓</a>
                            </th>
                            <th>
                                Tipo de Memoria
                                <br>
                                <a href="?order_column=tipo_memoria&order=asc">↑</a>
                                <a href="?order_column=tipo_memoria&order=desc">↓</a>
                            </th>
                            <th>
                                Disco Duro 1
                                <br>
                                <a href="?order_column=disco_duro_1&order=asc">↑</a>
                                <a href="?order_column=disco_duro_1&order=desc">↓</a>
                            </th>
                            <th>
                                Marca DD1
                                <br>
                                <a href="?order_column=marca_dd1&order=asc">↑</a>
                                <a href="?order_column=marca_dd1&order=desc">↓</a>
                            </th>
                            <th>
                                Modelo DD1
                                <br>
                                <a href="?order_column=modelo_dd1&order=asc">↑</a>
                                <a href="?order_column=modelo_dd1&order=desc">↓</a>
                            </th>
                            <th>
                                Disco Duro 2
                                <br>
                                <a href="?order_column=disco_duro_2&order=asc">↑</a>
                                <a href="?order_column=disco_duro_2&order=desc">↓</a>
                            </th>
                            <th>
                                Marca DD2
                                <br>
                                <a href="?order_column=marca_dd2&order=asc">↑</a>
                                <a href="?order_column=marca_dd2&order=desc">↓</a>
                            </th>
                            <th>
                                Modelo DD2
                                <br>
                                <a href="?order_column=modelo_dd2&order=asc">↑</a>
                                <a href="?order_column=modelo_dd2&order=desc">↓</a>
                            </th>
                            <th>
                                Marca Memoria 1
                                <br>
                                <a href="?order_column=marca_memoria_1&order=asc">↑</a>
                                <a href="?order_column=marca_memoria_1&order=desc">↓</a>
                            </th>
                            <th>
                                Serie Memoria 1
                                <br>
                                <a href="?order_column=serie_memoria_1&order=asc">↑</a>
                                <a href="?order_column=serie_memoria_1&order=desc">↓</a>
                            </th>
                            <th>
                                Marca Memoria 2
                                <br>
                                <a href="?order_column=marca_memoria_2&order=asc">↑</a>
                                <a href="?order_column=marca_memoria_2&order=desc">↓</a>
                            </th>
                            <th>
                                Serie Memoria 2
                                <br>
                                <a href="?order_column=serie_memoria_2&order=asc">↑</a>
                                <a href="?order_column=serie_memoria_2&order=desc">↓</a>
                            </th>
                            <th>
                                Marca Memoria 3
                                <br>
                                <a href="?order_column=marca_memoria_3&order=asc">↑</a>
                                <a href="?order_column=marca_memoria_3&order=desc">↓</a>
                            </th>
                            <th>
                                Serie Memoria 3
                                <br>
                                <a href="?order_column=serie_memoria_3&order=asc">↑</a>
                                <a href="?order_column=serie_memoria_3&order=desc">↓</a>
                            </th>
                            <th>
                                Marca Memoria 4
                                <br>
                                <a href="?order_column=marca_memoria_4&order=asc">↑</a>
                                <a href="?order_column=marca_memoria_4&order=desc">↓</a>
                            </th>
                            <th>
                                Serie Memoria 4
                                <br>
                                <a href="?order_column=serie_memoria_4&order=asc">↑</a>
                                <a href="?order_column=serie_memoria_4&order=desc">↓</a>
                            </th>
                            <th>
                                Memoria Total
                                <br>
                                <a href="?order_column=memoria_total_nombre&order=asc">↑</a>
                                <a href="?order_column=memoria_total_nombre&order=desc">↓</a>
                            </th>
                            <th>
                                Marca Monitor
                                <br>
                                <a href="?order_column=marca_monitor&order=asc">↑</a>
                                <a href="?order_column=marca_monitor&order=desc">↓</a>
                            </th>
                            <th>
                                Modelo Monitor
                                <br>
                                <a href="?order_column=modelo_monitor&order=asc">↑</a>
                                <a href="?order_column=modelo_monitor&order=desc">↓</a>
                            </th>
                            <th>
                                Serie Monitor
                                <br>
                                <a href="?order_column=serie_monitor&order=asc">↑</a>
                                <a href="?order_column=serie_monitor&order=desc">↓</a>
                            </th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($equipos) && count($equipos) > 0): ?>
                            <?php foreach ($equipos as $equipo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($equipo['nombre_facultad']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['inventario']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['activo']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['serie']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['ubicacion_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['tipo_equipo_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['marca_equipo']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['modelo_equipo']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['procesador_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['tipo_memoria_nombre']); ?></td>
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
                                    <td><?php echo htmlspecialchars($equipo['memoria_total_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['marca_monitor']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['modelo_monitor']); ?></td>
                                    <td><?php echo htmlspecialchars($equipo['serie_monitor']); ?></td>
                                    <td class="action-buttons">
                                        <a href="editar_equipo.php?id_alta=<?php echo $equipo['id_alta']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                        <a href="eliminar_equipo.php?id=<?php echo $equipo['id_alta']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar a docente?');">Borrar</a>
                                    </td> <!-- Botones con estilo en línea -->
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
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>