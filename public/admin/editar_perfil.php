<?php
session_start();
require_once '../../config/database.php';

// Consulta para obtener las facultades disponibles
$sql = "SELECT id_facultad, facultad FROM t_facultad";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$facultades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si el parámetro npesonal está en la URL
if (!isset($_GET['npesonal'])) {
    die("Número personal no especificado.");
}

$npesonal = $_GET['npesonal'];

// Obtener los datos del docente para editar
$sql = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':npesonal', $npesonal);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("No se encontró el usuario con el número personal especificado.");
}

// Procesar la actualización del perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_p = $_POST['apellido_p'];
    $apellido_m = $_POST['apellido_m'];
    $extension = $_POST['extension'];
    $correo = $_POST['correo'];
    $id_facultad = $_POST['id_facultad'];
    $password = $_POST['password'];

    // Actualizar los datos del docente
    $sql = "UPDATE t_docente SET nombre = :nombre, apellido_p = :apellido_p, apellido_m = :apellido_m, 
            extension = :extension, correo = :correo, id_facultad = :id_facultad";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = :password";
    }

    $sql .= " WHERE npesonal = :npesonal";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido_p', $apellido_p);
    $stmt->bindParam(':apellido_m', $apellido_m);
    $stmt->bindParam(':extension', $extension);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':id_facultad', $id_facultad);
    $stmt->bindParam(':npesonal', $npesonal);

    if (!empty($password)) {
        $stmt->bindParam(':password', $hashed_password);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Perfil actualizado con éxito.";
    } else {
        $_SESSION['error_message'] = "Error al actualizar el perfil.";
    }

    header('Location: editar_perfil.php?npesonal=' . $npesonal);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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

        .navbar-brand, .nav-link {
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
            height: calc(100vh - 56px);
            overflow-y: auto;
        }

        .profile-form {
            max-width: 900px;
            margin: 100px auto;
            padding: 50px;
            background: #f7f7f7;
            border-radius: 40px;
            box-shadow: 0 0 300px rgba(0, 0, 0, 0.2);
            margin-left: 250px; /* Ajuste para desplazar a la derecha */
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 10px;
            color: #007bff;
        }

        .btn-custom {
            padding: 10px 15px;
            margin-bottom: 10px;
        }

        .btn-custom-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-custom-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-custom-primary:hover, .btn-custom-secondary:hover {
            opacity: 0.9;
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
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
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
                </li>
            </ul>
        </div>
        <!-- Contenido principal -->
        <div class="container">
        <div class="profile-form">
            <div class="form-title">Editar Perfil</div>
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="npesonal" class="form-label"><i class="fas fa-id-badge"></i> Número de Personal</label>
                    <input type="text" class="form-control" id="npesonal" name="npesonal" value="<?php echo htmlspecialchars($user['npesonal']); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="apellido_p" class="form-label"><i class="fas fa-user-tie"></i> Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="<?php echo htmlspecialchars($user['apellido_p']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="apellido_m" class="form-label"><i class="fas fa-user-tie"></i> Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="<?php echo htmlspecialchars($user['apellido_m']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="extension" class="form-label"><i class="fas fa-phone"></i> Extensión</label>
                    <input type="text" class="form-control" id="extension" name="extension" value="<?php echo htmlspecialchars($user['extension']); ?>">
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="id_facultad" class="form-label"><i class="fas fa-building"></i> Facultad</label>
                    <select class="form-control" id="id_facultad" name="id_facultad" required>
                        <?php foreach ($facultades as $facultad): ?>
                            <option value="<?php echo htmlspecialchars($facultad['id_facultad']); ?>" <?php echo $user['id_facultad'] == $facultad['id_facultad'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($facultad['facultad']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fas fa-lock"></i> Actualizar Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <button type="submit" class="btn btn-custom btn-custom-primary w-100">Guardar Cambios</button>
                <a href="dashboard.php" class="btn btn-custom btn-custom-secondary w-100"><i class="fas fa-arrow-left"></i> Volver</a>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
