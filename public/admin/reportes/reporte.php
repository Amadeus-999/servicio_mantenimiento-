<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

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
    <title>Gestión de Facultades</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .navbar-brand, .nav-link {
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
            margin-left: 300px;
            margin-top: 56px;
            padding: 50px;
            overflow-y: auto;
            position: relative;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            resize: both; /* Permite redimensionar */
            overflow: auto; /* Permite el scroll si el contenido excede */
            min-height: 900px; /* Define una altura mínima */
        }

        .form-group i {
            margin-right: 5px;
        }

        .button-container {
            text-align: center;
        }

        .order-btn {
            padding: 2px 10px;
            font-size: 0.8rem;
            margin-left:px;
        }

        .order-btn.active {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Panel del Servidor Técnico</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" 
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">Bienvenido, <?php echo isset($_SESSION['user']['nombre']) ? htmlspecialchars($_SESSION['user']['nombre']) : 'Invitado'; ?></span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="../editar_perfil.php?npesonal=<?php echo urlencode($_SESSION['user']['npesonal']); ?>">Editar Perfil</a>
                        <!-- Conexión correcta a Cerrar Sesión -->
                        <a class="dropdown-item" href="../../logout.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Menú lateral -->
    <div class="d-flex">
        <div class="sidebar">
            <h4>Secciones</h4>
            <ul class="nav flex-column">

                            <!-- Alta de Equipos -->
                            <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#altaEquiposSubmenu" role="button" 
                       aria-expanded="false" aria-controls="altaEquiposSubmenu">
                        <i class="fas fa-plus-circle"></i> Alta de Equipos
                    </a>
                    <div class="collapse" id="altaEquiposSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../alta_equipos/a_equipos.php">Registrar Equipos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../alta_equipos/add_a_equipos.php">Agregar Nuevo Equipo</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Docentes -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#docentesSubmenu" role="button" 
                       aria-expanded="false" aria-controls="docentesSubmenu">
                        <i class="fas fa-chalkboard-teacher"></i> Docentes
                    </a>
                    <div class="collapse" id="docentesSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../docente/docentes.php">Ver Docentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../docente/add_docente.php">Agregar Docente</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Servicios -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#serviciosSubmenu" role="button" 
                       aria-expanded="false" aria-controls="serviciosSubmenu">
                        <i class="fas fa-tools"></i> Servicios
                    </a>
                    <div class="collapse" id="serviciosSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../reportes/reporte.php">Ver Servicios</a>
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
    <div class="content">
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
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
