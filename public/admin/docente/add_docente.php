<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

// Obtener las facultades disponibles para el menú desplegable
$sql = "SELECT id_facultad, facultad FROM t_facultad";
$stmt = $pdo->query($sql);
$facultades = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npesonal = $_POST['npesonal'];

    // Validar que npesonal solo contenga números
    if (!ctype_digit($npesonal)) {
        $error = "El número personal debe contener solo números.";
    } else {
        // Continuar con el proceso de registro si la validación es correcta
        $nombre = strtoupper(trim($_POST['nombre']));
        $apellido_p = strtoupper(trim($_POST['apellido_p']));
        $apellido_m = strtoupper(trim($_POST['apellido_m']));
        $extension = trim($_POST['extension']);
        $correo = trim($_POST['correo']);
        $id_facultad = $_POST['id_facultad'];  // Usar id_facultad

        // Verificar si el número personal ya está registrado
        $check_sql = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([':npesonal' => $npesonal]);
        $existing_user = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            $error = "El número personal ya está registrado.";
        } else {
            // Insertar el nuevo docente en la base de datos
            $sql = "INSERT INTO t_docente (npesonal, nombre, apellido_p, apellido_m, extension, correo, id_facultad)
                    VALUES (:npesonal, :nombre, :apellido_p, :apellido_m, :extension, :correo, :id_facultad)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':npesonal' => $npesonal,
                ':nombre' => $nombre,
                ':apellido_p' => $apellido_p,
                ':apellido_m' => $apellido_m,
                ':extension' => $extension,
                ':correo' => $correo,
                ':id_facultad' => $id_facultad
            ]);

            header('Location: docentes.php');
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Docente</title>
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
            display: flex;
            justify-content: center;
            /* Centra horizontalmente */
            align-items: center;
            /* Centra verticalmente */
            min-height: calc(100vh - 56px);
            overflow-y: auto;
        }

        .card {
            width: 100%;
            max-width: 1000px;
            /* Tamaño máximo del cuadro */
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-left: 300px;
            /* Añadimos margen para desplazar a la derecha */
        }



        .form-group i {
            margin-right: 5px;
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
    <div class="d-flex">
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
                                <a class="nav-link" href="../docente/docentes.php">Mostrar Docentes</a>
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
                                <a class="nav-link" href="../equipos/equipo.php">Mostrar Tipos de Equipos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../equipos/add_equipo.php">Agregar Tipo de Equipo</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#altaEquiposSubmenu" role="button" aria-expanded="false" aria-controls="altaEquiposSubmenu">
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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#facultadesSubmenu" role="button" aria-expanded="false" aria-controls="facultadesSubmenu">
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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#marcasSubmenu" role="button" aria-expanded="false" aria-controls="marcasSubmenu">
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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#memoriasSubmenu" role="button" aria-expanded="false" aria-controls="memoriasSubmenu">
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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#modelosSubmenu" role="button" aria-expanded="false" aria-controls="modelosSubmenu">
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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#procesadoresSubmenu" role="button" aria-expanded="false" aria-controls="procesadoresSubmenu">
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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#ubicacionesSubmenu" role="button" aria-expanded="false" aria-controls="ubicacionesSubmenu">
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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#serviciosSubmenu" role="button" aria-expanded="false" aria-controls="serviciosSubmenu">
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
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    <h4><i class="fas fa-user-plus"></i> Agregar Nuevo Docente</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="add_docente.php">
                        <div class="form-group">
                            <label for="npesonal"><i class="fas fa-id-card"></i> Número Personal
                                <span style="color: red;">*</span>
                            </label>
                            <input type="number" class="form-control" id="npesonal" name="npesonal" required style="text-transform: uppercase;"
                                pattern="\d+" inputmode="numeric" title="Por favor, ingrese solo números">
                        </div>

                        <div class="form-group">
                            <label for="nombre"><i class="fas fa-user"></i> Nombre
                                <span style="color: red;">*</span>
                            </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="apellido_p"><i class="fas fa-user-tag"></i> Apellido Paterno
                                <span style="color: red;">*</span>
                            </label>
                            <input type="text" class="form-control" id="apellido_p" name="apellido_p" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="apellido_m"><i class="fas fa-user-tag"></i> Apellido Materno
                                <span style="color: red;">*</span>
                            </label>
                            <input type="text" class="form-control" id="apellido_m" name="apellido_m" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="extension"><i class="fas fa-phone"></i> Extensión
                                <span style="color: red;">*</span>
                            </label>
                            <input type="number" class="form-control" id="extension" name="extension" required style="text-transform: uppercase;"
                                pattern="\d+" inputmode="numeric" title="Por favor, ingrese solo números">
                        </div>
                        <div class="form-group">
                            <label for="correo"><i class="fas fa-envelope"></i> Correo Electrónico
                                <span style="color: red;">*</span>
                            </label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="facultad"><i class="fas fa-university"></i> Facultad
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="facultad" name="id_facultad" required style="text-transform: uppercase;">
                                <option value="" disabled selected hidden>Seleccionar Facultad</option>
                                <?php foreach ($facultades as $facultad): ?>
                                    <option value="<?php echo htmlspecialchars($facultad['id_facultad']); ?>">
                                        <?php echo htmlspecialchars($facultad['facultad']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipo_usuario"><i class="fas fa-user-cog"></i> Tipo de Usuario
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="tipo_usuario" name="tipo_usuario" required style="text-transform: uppercase;">
                                <option value="" disabled selected hidden>Seleccionar un Tipo de Usuario</option>

                                <option value="0">Docente</option>
                                <option value="1">Servidor Técnico</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock"></i> Contraseña
                                <span style="color: red;">*</span>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> Guardar Docente</button>
                        <a href="docentes.php" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Volver</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>