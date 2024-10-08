<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

try {
    // Definir el orden de la consulta (ASC o DESC)
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    // Inicializar variable de búsqueda
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Consulta SQL para obtener las marcas de equipo filtradas
    $sql = "SELECT id_marca, marca 
            FROM t_marca_equipo 
            WHERE marca LIKE :search 
            ORDER BY marca $order";

    $stmt = $pdo->prepare($sql);
    // Usar wildcards para permitir coincidencias parciales
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $stmt->execute();
    $marcas_equipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Marcas de Equipo</title>
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
            <!-- Docentes -->
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

            <!-- Tipos de Equipos -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#equiposSubmenu" role="button"
                    aria-expanded="false" aria-controls="equiposSubmenu">
                    <i class="fas fa-laptop"></i> Tipos de Equipos
                </a>
                <div class="collapse" id="equiposSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../equipos/equipo.php">Mostrar Tipos de Equipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../equipos/add_equipo.php">Agregar Tipo de Equipo</a>
                        </li>
                    </ul>
                </div>
            </li>


            <!-- Facultades -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#facultadesSubmenu" role="button"
                    aria-expanded="false" aria-controls="facultadesSubmenu">
                    <i class="fas fa-school"></i> Facultades
                </a>
                <div class="collapse" id="facultadesSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../facultades/facultad.php">Mostrar Facultades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../facultades/add_facultad.php">Agregar Nueva Facultad</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Marcas de Equipos -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#marcasSubmenu" role="button"
                    aria-expanded="false" aria-controls="marcasSubmenu">
                    <i class="fas fa-tag"></i> Marcas de Equipos
                </a>
                <div class="collapse" id="marcasSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../marca_equipos/marca.php">Mostrar Marcas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../marca_equipos/add_marca.php">Agregar Nueva Marca</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Memorias -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#memoriasSubmenu" role="button"
                    aria-expanded="false" aria-controls="memoriasSubmenu">
                    <i class="fas fa-memory"></i> Memorias
                </a>
                <div class="collapse" id="memoriasSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../memorias/memoria.php">Mostrar Memorias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../memorias/add_memoria.php">Agregar Nueva Memoria</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Modelos de Equipos -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#modelosSubmenu" role="button"
                    aria-expanded="false" aria-controls="modelosSubmenu">
                    <i class="fas fa-cogs"></i> Modelos de Equipos
                </a>
                <div class="collapse" id="modelosSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../modelos/modelo.php">Mostrar Modelos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../modelos/add_modelo.php">Agregar Nuevo Modelo</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Procesadores -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#procesadoresSubmenu" role="button"
                    aria-expanded="false" aria-controls="procesadoresSubmenu">
                    <i class="fas fa-microchip"></i> Procesadores
                </a>
                <div class="collapse" id="procesadoresSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../procesadores/procesador.php">Mostrar Procesadores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../procesadores/add_procesador.php">Agregar Nuevo Procesador</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Ubicaciones -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ubicacionesSubmenu" role="button"
                    aria-expanded="false" aria-controls="ubicacionesSubmenu">
                    <i class="fas fa-map-marker-alt"></i> Ubicaciones
                </a>
                <div class="collapse" id="ubicacionesSubmenu">
                    <ul class="nav flex-column pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="../ubicaciones/ubicacion.php">Mostrar Ubicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../ubicaciones/add_ubicacion.php">Agregar Nueva Ubicación</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <form method="GET" action="">
            <div class="form-row align-items-center mb-3">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control mb-2" placeholder="Buscar marca" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-2">Buscar</button>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-between mb-3">
            <a href="add_marca.php" class="btn btn-success">Agregar Nueva Marca de Equipo</a>
            <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
            <div>
                <a href="?order=asc" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                <a href="?order=desc" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Marca de Equipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($marcas_equipo): ?>
                    <?php foreach ($marcas_equipo as $marca): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($marca['marca']); ?></td>
                            <td>
                                <a href="editar_marca.php?id_marca=<?php echo $marca['id_marca']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="eliminar_marca.php?id=<?php echo $marca['id_marca']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar la marca?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="1" class="text-center">No se encontraron marcas de equipo</td>
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