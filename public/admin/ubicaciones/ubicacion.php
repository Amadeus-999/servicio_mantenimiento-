<?php
require_once '../../../config/database.php';
session_start(); // Asegúrate de que la sesión esté iniciada

// Verifica que el usuario esté logueado y tenga un id_facultad en la sesión
if (!isset($_SESSION['user']['id_facultad'])) {
    // Redirige al login si no está logueado
    header('Location: login.php');
    exit;
}

try {
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    // Cambia la consulta para obtener las ubicaciones y facultades, incluyendo la id
    $sql = "SELECT u.id_ubicacion, u.ubicacion, f.facultad 
            FROM t_ubicacion u
            LEFT JOIN t_facultad f ON u.id_facultad = f.id_facultad
            ORDER BY u.ubicacion $order";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $ubicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ubicaciones</title>
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
            min-height: calc(100vh - 56px);
            overflow-y: auto;
        }

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

    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Panel de Administrador</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Menú desplegable de usuario -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['nombre']); ?></span>
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

    <!-- Menú lateral -->
    <div class="sidebar">
        <h4>Secciones</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#docentesSubmenu" role="button" aria-expanded="false" aria-controls="docentesSubmenu">
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
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#equiposSubmenu" role="button" aria-expanded="false" aria-controls="equiposSubmenu">
                    <i class="fas fa-laptop"></i> Tipos de Equipos
                </a>
                <div class="collapse" id="equiposSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../equipos/equipo.php">Ver Tipos de Equipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../equipos/add_equipo.php">Agregar Tipo de Equipo</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#altaEquiposSubmenu" role="button" aria-expanded="false" aria-controls="altaEquiposSubmenu">
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
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#facultadesSubmenu" role="button" aria-expanded="false" aria-controls="facultadesSubmenu">
                    <i class="fas fa-school"></i> Facultades
                </a>
                <div class="collapse" id="facultadesSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../facultades/facultad.php">Ver Facultades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../facultades/add_facultad.php">Agregar Nueva Facultad</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#marcasSubmenu" role="button" aria-expanded="false" aria-controls="marcasSubmenu">
                    <i class="fas fa-tag"></i> Marcas de Equipos
                </a>
                <div class="collapse" id="marcasSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../marca_equipos/marca.php">Ver Marcas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../marca_equipos/add_marca.php">Agregar Nueva Marca</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#memoriasSubmenu" role="button" aria-expanded="false" aria-controls="memoriasSubmenu">
                    <i class="fas fa-memory"></i> Memorias
                </a>
                <div class="collapse" id="memoriasSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../memorias/memoria.php">Ver Memorias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../memorias/add_memoria.php">Agregar Nueva Memoria</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ubicacionesSubmenu" role="button" aria-expanded="false" aria-controls="ubicacionesSubmenu">
                    <i class="fas fa-map-marker-alt"></i> Ubicaciones
                </a>
                <div class="collapse" id="ubicacionesSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../ubicaciones/ubicacion.php">Ver Ubicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../ubicaciones/add_ubicacion.php">Agregar Nueva Ubicación</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#serviciosSubmenu" role="button" aria-expanded="false" aria-controls="serviciosSubmenu">
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
        <div class="d-flex justify-content-between mb-3">
            <a href="add_ubicacion.php" class="btn btn-success">Agregar Nueva Ubicación</a>
            <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            <div>
                <a href="?order=asc" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                <a href="?order=desc" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Facultad</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php if ($ubicaciones): ?>
                    <?php foreach ($ubicaciones as $ubicacion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ubicacion['facultad']); ?></td>
                            <td><?php echo htmlspecialchars($ubicacion['ubicacion']); ?></td>
                            <td>
                                <a href="editar_ubicacion.php?id_ubicacion=<?php echo $ubicacion['id_ubicacion']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="eliminar_ubicacion.php?id=<?php echo $ubicacion['id_ubicacion']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta ubicación?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="1" class="text-center">No se encontraron ubicaciones</td>
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