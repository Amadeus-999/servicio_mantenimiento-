<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

// Obtener facultades para el select
try {
    $sqlFacultades = "SELECT id_facultad, facultad FROM t_facultad";
    $stmtFacultades = $pdo->prepare($sqlFacultades);
    $stmtFacultades->execute();
    $facultades = $stmtFacultades->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ubicacion = isset($_POST['ubicacion']) ? strtoupper(trim($_POST['ubicacion'])) : '';
    $id_facultad = isset($_POST['id_facultad']) ? $_POST['id_facultad'] : '';

    if (!empty($ubicacion) && !empty($id_facultad)) {
        try {
            // Inserción en la tabla t_ubicacion con id_facultad
            $sql = "INSERT INTO t_ubicacion (ubicacion, id_facultad) VALUES (:ubicacion, :id_facultad)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_STR);
            $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
            $stmt->execute();

            // Redirección después de la inserción exitosa
            header("Location: ubicacion.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre de la ubicación y la facultad no pueden estar vacíos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ubicación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">

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

        .button-container {
            text-align: center;
        }

        .form-group i {
            margin-right: 5px;
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
        <div class="container">
            <div class="form-wrapper">

                <h2><i class="fas fa-map-marker-alt"></i> Agregar Nueva Ubicación</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="add_ubicacion.php">
                    <div class="form-group">
                        <label for="ubicacion"><i class="fas fa-map-marker-alt"></i> Nombre de la Ubicación
                            <span style="color: red;">*</span>
                        </label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label for="id_facultad"><i class="fas fa-school"></i> Facultad
                            <span style="color: red;">*</span>
                        </label>
                        <select class="form-control" id="id_facultad" name="id_facultad" required>
                        <option value="" disabled selected hidden>Selecciona una facultad</option>
                            <?php foreach ($facultades as $facultad): ?>
                                <option value="<?php echo $facultad['id_facultad']; ?>">
                                    <?php echo htmlspecialchars($facultad['facultad']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                        <a href="ubicacion.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>