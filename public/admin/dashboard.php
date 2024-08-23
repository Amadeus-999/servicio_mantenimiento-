<?php
// Asegurarse de que el usuario esté autenticado y sea un administrador
session_start();
if (!isset($_SESSION['user']) || strpos($_SESSION['user']['npesonal'], 'ADM1') !== 0) {
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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Bienvenido al Panel de Administrador</a>
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
                        <a class="dropdown-item" href="editar_perfil.php">Editar Perfil</a>
                        <a class="dropdown-item" href="../logout.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Panel de Administración</h1>
        </div>
        <div class="dashboard-menu">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                            <h5 class="card-title">Docentes</h5>
                            <p class="card-text">Administrar los docentes.</p>
                            <a href="../admin/docente/docentes.php" class="btn btn-primary">Ir a Docentes</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-laptop fa-2x"></i>
                            <h5 class="card-title">Tipos de Equipos</h5>
                            <p class="card-text">Gestionar los tipos de equipos.</p>
                            <a href="../admin/equipos/equipo.php" class="btn btn-primary">Ir a Tipos de Equipos</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-plus-circle fa-2x"></i>
                            <h5 class="card-title">Alta de Equipos</h5>
                            <p class="card-text">Registrar nuevos equipos.</p>
                            <a href="../admin/alta_equipos/a_equipos.php" class="btn btn-primary">Ir a Alta de Equipos</a>
                        </div>
                    </div>
                </div>
                <!-- Agrega más tarjetas aquí siguiendo el mismo formato -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-school fa-2x"></i>
                            <h5 class="card-title">Facultades</h5>
                            <p class="card-text">Administrar las facultades.</p>
                            <a href="../admin/facultades/facultad.php" class="btn btn-primary">Ir a Facultades</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-tag fa-2x"></i>
                            <h5 class="card-title">Marcas de Equipos</h5>
                            <p class="card-text">Gestionar las marcas de equipos.</p>
                            <a href="../admin/marca_equipos/marca.php" class="btn btn-primary">Ir a Marcas de Equipos</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-memory fa-2x"></i>
                            <h5 class="card-title">Memorias</h5>
                            <p class="card-text">Gestionar las memorias.</p>
                            <a href="../admin/memorias/memoria.php" class="btn btn-primary">Ir a Memorias</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-cogs fa-2x"></i>
                            <h5 class="card-title">Modelos de Equipos</h5>
                            <p class="card-text">Administrar los modelos de equipos.</p>
                            <a href="modelos_equipos.php" class="btn btn-primary">Ir a Modelos de Equipos</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-microchip fa-2x"></i>
                            <h5 class="card-title">Procesadores</h5>
                            <p class="card-text">Gestionar los procesadores.</p>
                            <a href="../admin/procesadores/procesador.php" class="btn btn-primary">Ir a Procesadores</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                            <h5 class="card-title">Ubicaciones</h5>
                            <p class="card-text">Administrar las ubicaciones.</p>
                            <a href="../admin/ubicaciones/ubicacion.php" class="btn btn-primary">Ir a Ubicaciones</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-tools fa-2x"></i>
                            <h5 class="card-title">Servicios</h5>
                            <p class="card-text">Gestionar los servicios de mantenimiento.</p>
                            <a href="servicios.php" class="btn btn-primary">Ir a Servicios</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>