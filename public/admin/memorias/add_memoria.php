<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memoria = isset($_POST['tp_memoria']) ? strtoupper(trim($_POST['tp_memoria'])) : '';


    if (!empty($memoria)) {
        try {
            // Inserción en la tabla tipo_memoria
            $sql = "INSERT INTO tipo_memoria (tp_memoria) VALUES (:tp_memoria)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':tp_memoria', $memoria, PDO::PARAM_STR);
            $stmt->execute();

            // Redirección después de la inserción exitosa
            header("Location: memoria.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre de la memoria no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Memoria</title>
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
            margin-left: 500px;
            margin-top: 60px;
            padding: 20px;
            overflow-y: auto;
            position: relative;
            width: 100%;
            /* Abarca todo el ancho del contenedor */
        }

        .card {
            width: 100%;
            max-width: 700px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .form-group i {
            margin-right: 5px;
        }

        .button-container {
            text-align: center;
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
        <div class="card">
            <div class="card-header text-center bg-primary text-white">
                <h4><i class="fas fa-memory"></i> Agregar Nueva Memoria</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="add_memoria.php">
                    <div class="form-group">
                        <label for="tp_memoria"><i class="fas fa-memory"></i> Nombre de la Memoria
                            <span style="color: red;">*</span>
                        </label>
                        <input type="text" class="form-control" id="tp_memoria" name="tp_memoria" required style="text-transform: uppercase;">
                    </div>

                    <div class="button-container">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                        <a href="memoria.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
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