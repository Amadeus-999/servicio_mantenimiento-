<?php
// Asegurarse de que el usuario esté autenticado y sea un administrador
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

// Verificar si el usuario es un administrador (tipo_usuario = 1)
if ((int)$_SESSION['user']['tipo_usuario'] !== 1) {
    header('Location: ../login.php');
    exit;
}

$user_name = $_SESSION['user']['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            position: fixed;
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
            margin-left: 260px;
            padding: 20px;
        }

        .collapse {
            background-color: #444b52;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card-text {
            font-size: 14px;
            color: #777;
        }

        .card i {
            color: #007bff;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Panel de Administrador</a>
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
                        <a class="dropdown-item" href="editar_perfil.php?npesonal=<?php echo urlencode($_SESSION['user']['npesonal']); ?>">Editar Perfil</a>
                        <a class="dropdown-item" href="../logout.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex">
        <div class="sidebar">
            <h4>Secciones</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#docentes" role="button" aria-expanded="false" aria-controls="docentes">
                        <i class="fas fa-chalkboard-teacher"></i> Docentes
                    </a>
                    <div class="collapse" id="docentes">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/docente/docentes.php">Ver Docentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/docente/add_docente.php">Agregar Docente</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#equipos" role="button" aria-expanded="false" aria-controls="equipos">
                        <i class="fas fa-laptop"></i> Tipos de Equipos
                    </a>
                    <div class="collapse" id="equipos">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/equipos/equipo.php">Ver Tipos de Equipos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/equipos/add_equipo.php">Agregar Tipo de Equipo</a>
                            </li>
                        </ul>
                    </div>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#facultades" role="button" aria-expanded="false" aria-controls="facultades">
                        <i class="fas fa-school"></i> Facultades
                    </a>
                    <div class="collapse" id="facultades">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/facultades/facultad.php">Ver Facultades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/facultades/add_facultad.php">Agregar Nueva Facultad</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#marcas" role="button" aria-expanded="false" aria-controls="marcas">
                        <i class="fas fa-tag"></i> Marcas de Equipos
                    </a>
                    <div class="collapse" id="marcas">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/marca_equipos/marca.php">Ver Marcas de Equipos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/marca_equipos/add_marca.php">Agregar Nueva Marca de Equipo</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#memorias" role="button" aria-expanded="false" aria-controls="memorias">
                        <i class="fas fa-memory"></i> Memorias
                    </a>
                    <div class="collapse" id="memorias">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/memorias/memoria.php">Ver Memorias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/memorias/add_memoria.php">Agregar Nueva Memoria</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#modelos" role="button" aria-expanded="false" aria-controls="modelos">
                        <i class="fas fa-cogs"></i> Modelos de Equipos
                    </a>
                    <div class="collapse" id="modelos">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/modelos/modelo.php">Ver Modelos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/modelos/add_modelo.php">Agregar Nuevo Modelo</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#procesadores" role="button" aria-expanded="false" aria-controls="procesadores">
                        <i class="fas fa-microchip"></i> Procesadores
                    </a>
                    <div class="collapse" id="procesadores">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/procesadores/procesador.php">Ver Procesadores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/procesadores/add_procesador.php">Agregar Nuevo Procesador</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#ubicaciones" role="button" aria-expanded="false" aria-controls="ubicaciones">
                        <i class="fas fa-map-marker-alt"></i> Ubicaciones
                    </a>
                    <div class="collapse" id="ubicaciones">
                        <ul class="nav flex-column pl-3">
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/ubicaciones/ubicacion.php">Ver Ubicaciones</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/ubicaciones/add_ubicacion.php">Agregar Nueva Ubicación</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="content">
            <!-- Aquí va el contenido de cada sección -->
            <h1>Bienvenido al Panel de Administración</h1>
            <p>Aquí puedes administrar diferentes secciones de la plataforma.</p>
            <!-- Más contenido... -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
