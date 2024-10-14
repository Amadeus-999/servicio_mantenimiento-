<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

// Permitir el acceso tanto a servidores técnicos (tipo_usuario = 0) como administradores (tipo_usuario = 1)
if (!in_array((int)$_SESSION['user']['tipo_usuario'], [0, 1])) {
    header('Location: ../../login.php');
    exit;
}

$user_name = $_SESSION['user']['nombre'];

try {
    // Inicializar las variables de búsqueda
    $search_npesonal = isset($_GET['search_npesonal']) ? trim($_GET['search_npesonal']) : '';
    $search_nombre = isset($_GET['search_nombre']) ? trim($_GET['search_nombre']) : '';
    $search_apellido_p = isset($_GET['search_apellido_p']) ? trim($_GET['search_apellido_p']) : '';
    $search_apellido_m = isset($_GET['search_apellido_m']) ? trim($_GET['search_apellido_m']) : '';
    $search_extension = isset($_GET['search_extension']) ? trim($_GET['search_extension']) : '';
    $search_correo = isset($_GET['search_correo']) ? trim($_GET['search_correo']) : '';
    $search_facultad = isset($_GET['search_facultad']) ? trim($_GET['search_facultad']) : '';

    // Orden y columna por defecto
    $order = isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']) ? $_GET['order'] : 'ASC';
    $column = isset($_GET['column']) && in_array($_GET['column'], ['npesonal', 'nombre', 'apellido_p', 'apellido_m', 'extension', 'correo', 'facultad']) ? $_GET['column'] : 'nombre';

    // Preparar consulta SQL con condiciones de búsqueda
    $sql = "SELECT id, d.npesonal, d.nombre, d.apellido_p, d.apellido_m, d.extension, d.correo, f.facultad
            FROM docente d
            JOIN t_facultad f ON d.id_facultad = f.id_facultad
            WHERE 1=1";

    if ($search_npesonal) {
        $sql .= " AND d.npesonal LIKE :search_npesonal";
    }
    if ($search_nombre) {
        $sql .= " AND d.nombre LIKE :search_nombre";
    }
    if ($search_apellido_p) {
        $sql .= " AND d.apellido_p LIKE :search_apellido_p";
    }
    if ($search_apellido_m) {
        $sql .= " AND d.apellido_m LIKE :search_apellido_m";
    }
    if ($search_extension) {
        $sql .= " AND d.extension LIKE :search_extension";
    }
    if ($search_correo) {
        $sql .= " AND d.correo LIKE :search_correo";
    }
    if ($search_facultad) {
        $sql .= " AND f.facultad LIKE :search_facultad";
    }

    $sql .= " ORDER BY $column $order";

    $stmt = $pdo->prepare($sql);

    // Vincular los parámetros de búsqueda
    if ($search_npesonal) {
        $stmt->bindValue(':search_npesonal', "%$search_npesonal%", PDO::PARAM_STR);
    }
    if ($search_nombre) {
        $stmt->bindValue(':search_nombre', "%$search_nombre%", PDO::PARAM_STR);
    }
    if ($search_apellido_p) {
        $stmt->bindValue(':search_apellido_p', "%$search_apellido_p%", PDO::PARAM_STR);
    }
    if ($search_apellido_m) {
        $stmt->bindValue(':search_apellido_m', "%$search_apellido_m%", PDO::PARAM_STR);
    }
    if ($search_extension) {
        $stmt->bindValue(':search_extension', "%$search_extension%", PDO::PARAM_STR);
    }
    if ($search_correo) {
        $stmt->bindValue(':search_correo', "%$search_correo%", PDO::PARAM_STR);
    }
    if ($search_facultad) {
        $stmt->bindValue(':search_facultad', "%$search_facultad%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        a {
            text-decoration: none;
            /* Elimina el subrayado */
            color: inherit;
            /* Hereda el color del texto */
        }

        a:hover {
            text-decoration: underline;
            /* Opcional: subrayado al pasar el cursor */
        }
    </style>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        /* Navbar fija en la parte superior */
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

        /* Sidebar fija en la parte izquierda */
        .sidebar {
            height: calc(100vh - 56px);
            /* Altura total menos la altura del navbar */
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            position: fixed;
            top: 56px;
            /* Altura del navbar */
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

        /* Espacio para el contenido para que no se superponga con la barra superior y lateral */
        .content {
            margin-left: 250px;
            /* Espacio para la barra lateral */
            margin-top: 56px;
            /* Espacio para la barra superior */
            padding: 20px;
            height: calc(100vh - 56px);
            /* Altura total menos la barra superior */
            overflow-y: auto;
            /* Solo el contenido es desplazable */
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
        <a class="navbar-brand" href="#">Panel del Servidor Técnico</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Menú desplegable de usuario -->
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

                <!-- Alta de Equipos -->
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

                <!-- Servicios -->
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
        <div class="content">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="add_docente.php" class="btn btn-success">Agregar Nuevo Docente</a>
                    <a href="../dashboard.php" class="btn btn-secondary">Inicio</a>
                </div>
            </div>

            <!-- Formulario de búsqueda -->
            <form action="" method="get" class="form-inline mb-4">
                <input type="text" name="search_npesonal" class="form-control mr-2" placeholder="Número Personal" value="<?php echo htmlspecialchars($search_npesonal); ?>">
                <input type="text" name="search_nombre" class="form-control mr-2" placeholder="Nombre" value="<?php echo htmlspecialchars($search_nombre); ?>">
                <input type="text" name="search_apellido_p" class="form-control mr-2" placeholder="Apellido Paterno" value="<?php echo htmlspecialchars($search_apellido_p); ?>">
                <input type="text" name="search_apellido_m" class="form-control mr-2" placeholder="Apellido Materno" value="<?php echo htmlspecialchars($search_apellido_m); ?>">
                <input type="text" name="search_extension" class="form-control mr-2" placeholder="Extensión" value="<?php echo htmlspecialchars($search_extension); ?>">
                <input type="text" name="search_correo" class="form-control mr-2" placeholder="Correo" value="<?php echo htmlspecialchars($search_correo); ?>">
                <input type="text" name="search_facultad" class="form-control mr-2" placeholder="Facultad" value="<?php echo htmlspecialchars($search_facultad); ?>">
                <button type="submit" class="btn btn-success">Buscar</button>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>

                        <tr>
                            <th>
                                Número Personal
                                <br>
                                <a href="?column=npesonal&order=ASC" class="btn btn-link btn-sm">↑</a>
                                <a href="?column=npesonal&order=DESC" class="btn btn-link btn-sm">↓</a>
                            </th>
                            <th>
                                Nombre
                                <br>
                                <a href="?column=nombre&order=ASC" class="btn btn-link btn-sm">↑</a>
                                <a href="?column=nombre&order=DESC" class="btn btn-link btn-sm">↓</a>
                            </th>
                            <th>
                                Apellido Paterno
                                <br>
                                <a href="?column=apellido_p&order=ASC" class="btn btn-link btn-sm">↑</a>
                                <a href="?column=apellido_p&order=DESC" class="btn btn-link btn-sm">↓</a>
                            </th>
                            <th>
                                Apellido Materno
                                <br>
                                <a href="?column=apellido_m&order=ASC" class="btn btn-link btn-sm">↑</a>
                                <a href="?column=apellido_m&order=DESC" class="btn btn-link btn-sm">↓</a>
                            </th>
                            <th>
                                Extensión
                                <br>
                                <a href="?column=extension&order=ASC" class="btn btn-link btn-sm">↑</a>
                                <a href="?column=extension&order=DESC" class="btn btn-link btn-sm">↓</a>
                            </th>
                            <th>
                                Correo
                                <br>
                                <a href="?column=correo&order=ASC" class="btn btn-link btn-sm">↑</a>
                                <a href="?column=correo&order=DESC" class="btn btn-link btn-sm">↓</a>
                            </th>
                            <th>
                                Facultad
                                <br>
                                <a href="?column=facultad&order=ASC" class="btn btn-link btn-sm">↑</a>
                                <a href="?column=facultad&order=DESC" class="btn btn-link btn-sm">↓</a>
                            </th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($docentes): ?>
                            <?php foreach ($docentes as $docente): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($docente['npesonal']); ?></td>
                                    <td><?php echo htmlspecialchars($docente['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($docente['apellido_p']); ?></td>
                                    <td><?php echo htmlspecialchars($docente['apellido_m']); ?></td>
                                    <td><?php echo htmlspecialchars($docente['extension']); ?></td>
                                    <td><?php echo htmlspecialchars($docente['correo']); ?></td>
                                    <td><?php echo htmlspecialchars($docente['facultad']); ?></td>
                                    <td class="action-buttons">
                                        <a href="editar_docente.php?npesonal=<?php echo $docente['npesonal']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                        <a href="eliminar_docente.php?id=<?php echo $docente['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar a docente?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No se encontraron docentes</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Popper.js y Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>