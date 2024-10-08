<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado y es administrador (tipo_usuario = 1)
if (!isset($_SESSION['user']) || (int)$_SESSION['user']['tipo_usuario'] !== 1) {
    header('Location: ../../login.php');
    exit;
}

try {
    // Obtener el orden de la consulta
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    // Obtener el término de búsqueda
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Consulta SQL para obtener procesadores con búsqueda
    $sql = "SELECT id_procesador, procesador 
            FROM t_procesador 
            WHERE procesador LIKE :search 
            ORDER BY procesador $order";

    $stmt = $pdo->prepare($sql);
    // Usar el término de búsqueda con wildcards
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $stmt->execute();
    $procesadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Procesadores</title>
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

        /* Estilos de la barra de navegación */
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

        /* Estilos del sidebar */
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

        /* Contenido principal */
        .content {
            margin-left: 250px;
            margin-top: 56px;
            padding: 20px;
            overflow-y: auto;
        }

        /* Estilos del botón de orden */
        .order-btn {
            padding: 2px 10px;
            font-size: 0.8rem;
            margin-left: 5px;
        }

        .order-btn.active {
            background-color: #28a745;
            color: white;
        }

        table thead {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Panel del Administrador</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['nombre']); ?></span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    <div class="d-flex">
        <div class="sidebar">
            <h4>Secciones</h4>
            <ul class="nav flex-column">
                <!-- Tipos de Equipos -->
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

                <!-- Facultades -->
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

                <!-- Marcas de Equipos -->
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

                <!-- Memorias -->
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

                <!-- Modelos de Equipos -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#modelosSubmenu" role="button" aria-expanded="false" aria-controls="modelosSubmenu">
                        <i class="fas fa-cogs"></i> Modelos de Equipos
                    </a>
                    <div class="collapse" id="modelosSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../modelos/modelo.php">Ver Modelos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../modelos/add_modelo.php">Agregar Nuevo Modelo</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Procesadores -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#procesadoresSubmenu" role="button" aria-expanded="false" aria-controls="procesadoresSubmenu">
                        <i class="fas fa-microchip"></i> Procesadores
                    </a>
                    <div class="collapse" id="procesadoresSubmenu">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../procesadores/procesador.php">Ver Procesadores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../procesadores/add_procesador.php">Agregar Nuevo Procesador</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Ubicaciones -->
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
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <div class="container mt-5">

                <form method="GET" action="">
                    <div class="form-row align-items-center mb-3">
                        <div class="col-auto">
                            <input type="text" name="search" class="form-control" placeholder="Buscar procesador" value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                        <div class="col-auto">
                            <a href="add_procesador.php" class="btn btn-success">Agregar Nuevo Procesador</a>
                        </div>
                        <div class="col-auto">
                            <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
                        </div>
                    </div>
                </form>

                <!-- Botones para ordenar -->
                <div class="d-flex justify-content-end mb-3">
                    <div>
                        <a href="?order=asc" class="btn order-btn <?php echo $order === 'ASC' ? 'active' : ''; ?>">ASC</a>
                        <a href="?order=desc" class="btn order-btn <?php echo $order === 'DESC' ? 'active' : ''; ?>">DESC</a>
                    </div>
                </div>
                

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Procesador</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($procesadores): ?>
                            <?php foreach ($procesadores as $procesador): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($procesador['procesador']); ?></td>
                                    <td>
                                        <a href="editar_procesador.php?id_procesador=<?php echo $procesador['id_procesador']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                        <a href="eliminar_procesador.php?id=<?php echo $procesador['id_procesador']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar el procesador?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="1" class="text-center">No se encontraron procesadores</td>
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