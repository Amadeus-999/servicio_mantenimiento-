<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado y es administrador (tipo_usuario = 1)
if (!isset($_SESSION['user']) || (int)$_SESSION['user']['tipo_usuario'] !== 1) {
    header('Location: ../../login.php');
    exit;
}


$user_name = $_SESSION['user']['nombre'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $modelo = isset($_POST['modelo']) ? strtoupper(trim($_POST['modelo'])) : '';
    $tipo_equipo = isset($_POST['tipo_equipo']) ? (int)$_POST['tipo_equipo'] : 0;


    if (!empty($modelo) && $tipo_equipo > 0) {
        try {
            // Inserción en la tabla t_modelo_equipo
            $sql = "INSERT INTO t_modelo_equipo (modelo, id_tipo_equipo) VALUES (:modelo, :tipo_equipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':modelo', $modelo, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_equipo', $tipo_equipo, PDO::PARAM_INT);
            $stmt->execute();

            // Redirección después de la inserción exitosa
            header("Location: modelo.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre del modelo y el tipo de equipo deben ser seleccionados.";
    }
}

// Obtener los tipos de equipo para el desplegable
try {
    $sql = "SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo ORDER BY tipo_equipo ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tipos_equipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Modelo</title>
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
            margin-left: 300px;
            margin-top: 12px;
            padding: 20px;
            overflow-y: auto;
            position: relative;
            width: 100%;
            /* Abarca todo el ancho del contenedor */
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }

        .form-group i {
            margin-right: 5px;
        }

        .button-container {
            text-align: center;
        }

        .alert {
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Panel del Administrador</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">Bienvenido, <?php echo htmlspecialchars($user_name); ?></span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="../../user_norm/editar_perfil.php?npesonal=<?php echo urlencode($_SESSION['user']['npesonal']); ?>">Editar Perfil</a>
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

                <!-- Tipos de Equipos -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#equiposSubmenu" role="button"
                        aria-expanded="false" aria-controls="equiposSubmenu">
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
                    <a class="nav-link" data-toggle="collapse" href="#facultadesSubmenu" role="button"
                        aria-expanded="false" aria-controls="facultadesSubmenu">
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
                    <a class="nav-link" data-toggle="collapse" href="#marcasSubmenu" role="button"
                        aria-expanded="false" aria-controls="marcasSubmenu">
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
                    <a class="nav-link" data-toggle="collapse" href="#memoriasSubmenu" role="button"
                        aria-expanded="false" aria-controls="memoriasSubmenu">
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
                    <a class="nav-link" data-toggle="collapse" href="#modelosSubmenu" role="button"
                        aria-expanded="false" aria-controls="modelosSubmenu">
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
                    <a class="nav-link" data-toggle="collapse" href="#procesadoresSubmenu" role="button"
                        aria-expanded="false" aria-controls="procesadoresSubmenu">
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
                    <a class="nav-link" data-toggle="collapse" href="#ubicacionesSubmenu" role="button"
                        aria-expanded="false" aria-controls="ubicacionesSubmenu">
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
                <div class="form-container">
                    <h2><i class="fas fa-cogs"></i> Agregar Nuevo Modelo</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="add_modelo.php">
                        <div class="form-group">
                            <label for="modelo"><i class="fas fa-cogs"></i> Nombre del Modelo
                    <span style="color: red;">*</span>
                    </label>
                            <input type="text" class="form-control" id="modelo" name="modelo" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="tipo_equipo"><i class="fas fa-box"></i> Tipo de Equipo
                    <span style="color: red;">*</span>
                    </label>
                            <select id="tipo_equipo" name="tipo_equipo" class="form-control" required style="text-transform: uppercase;">
                                <option value="" disabled selected hidden>Selecciona un tipo de equipo</option>
                                <?php foreach ($tipos_equipo as $tipo): ?>
                                    <option value="<?php echo $tipo['id_tipo_equipo']; ?>">
                                        <?php echo htmlspecialchars($tipo['tipo_equipo']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="button-container">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                            <a href="modelo.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>