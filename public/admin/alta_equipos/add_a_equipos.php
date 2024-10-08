<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

try {
    // Consultas para obtener datos de la base de datos
    $ubicaciones = $pdo->query("SELECT id_ubicacion, ubicacion FROM t_ubicacion")->fetchAll();
    $tipos_equipo = $pdo->query("SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo")->fetchAll();
    $marcas = $pdo->query("SELECT id_marca, marca FROM t_marca_equipo")->fetchAll();
    $modelos = $pdo->query("SELECT id_modelo, modelo FROM t_modelo_equipo")->fetchAll();
    $procesadores = $pdo->query("SELECT id_procesador, procesador FROM t_procesador")->fetchAll();
    $memorias = $pdo->query("SELECT id_memoria, memoria FROM t_memoria")->fetchAll();
    $tipos_memoria = $pdo->query("SELECT id_tmemoria, tp_memoria FROM tipo_memoria")->fetchAll();

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Handle file uploads
            $foto_disco_duro = isset($_FILES['foto_disco_duro']) && $_FILES['foto_disco_duro']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['foto_disco_duro']['tmp_name']) : null;
            $foto_memoria = isset($_FILES['foto_memoria']) && $_FILES['foto_memoria']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['foto_memoria']['tmp_name']) : null;

            // Obtener la facultad del servidor técnico desde la sesión
            $id_facultad = $_SESSION['user']['id_facultad'];  // Facultades almacenadas en la sesión

            // Insert query for adding a new equipment
            $sql = "INSERT INTO t_alta_equipo (
                inventario, serie, activo, nombre_equipo, ubicacion, tipo_equipo, marca, modelo,
                procesador, memoria_total, disco_duro_1, marca_dd1, serie_dd1, modelo_dd1,
                disco_duro_2, marca_dd2, serie_dd2, modelo_dd2, marca_memoria_1, serie_memoria_1,
                marca_memoria_2, serie_memoria_2, marca_memoria_3, serie_memoria_3,
                marca_memoria_4, serie_memoria_4, marca_monitor, modelo_monitor,
                serie_monitor, foto_disco_duro, foto_memoria, id_facultad
            ) VALUES (
                :inventario, :serie, :activo, :nombre_equipo, :ubicacion, :tipo_equipo, :marca, :modelo,
                :procesador, :memoria_total, :disco_duro_1, :marca_dd1, :serie_dd1, :modelo_dd1,
                :disco_duro_2, :marca_dd2, :serie_dd2, :modelo_dd2, :marca_memoria_1, :serie_memoria_1,
                :marca_memoria_2, :serie_memoria_2, :marca_memoria_3, :serie_memoria_3,
                :marca_memoria_4, :serie_memoria_4, :marca_monitor, :modelo_monitor,
                :serie_monitor, :foto_disco_duro, :foto_memoria, :id_facultad
            )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':inventario' => strtoupper($_POST['inventario']),
                ':serie' => strtoupper($_POST['serie']),
                ':activo' => strtoupper($_POST['activo']),
                ':nombre_equipo' => strtoupper($_POST['nombre_equipo']),
                ':ubicacion' => $_POST['ubicacion'] !== '' ? strtoupper($_POST['ubicacion']) : null,
                ':tipo_equipo' => $_POST['tipo_equipo'] !== '' ? strtoupper($_POST['tipo_equipo']) : null,
                ':marca' => $_POST['marca'] !== '' ? strtoupper($_POST['marca']) : null,
                ':modelo' => $_POST['modelo'] !== '' ? strtoupper($_POST['modelo']) : null,
                ':procesador' => $_POST['procesador'] !== '' ? strtoupper($_POST['procesador']) : null,
                ':memoria_total' => $_POST['memoria_total'] !== '' ? strtoupper($_POST['memoria_total']) : null,
                ':disco_duro_1' => strtoupper($_POST['disco_duro_1']),
                ':marca_dd1' => $_POST['marca_dd1'] !== '' ? strtoupper($_POST['marca_dd1']) : null,
                ':serie_dd1' => strtoupper($_POST['serie_dd1']),
                ':modelo_dd1' => $_POST['modelo_dd1'] !== '' ? strtoupper($_POST['modelo_dd1']) : null,
                ':disco_duro_2' => strtoupper($_POST['disco_duro_2']),
                ':marca_dd2' => $_POST['marca_dd2'] !== '' ? strtoupper($_POST['marca_dd2']) : null,
                ':serie_dd2' => strtoupper($_POST['serie_dd2']),
                ':modelo_dd2' => $_POST['modelo_dd2'] !== '' ? strtoupper($_POST['modelo_dd2']) : null,
                ':marca_memoria_1' => $_POST['marca_memoria_1'] !== '' ? strtoupper($_POST['marca_memoria_1']) : null,
                ':serie_memoria_1' => strtoupper($_POST['serie_memoria_1']),
                ':marca_memoria_2' => $_POST['marca_memoria_2'] !== '' ? strtoupper($_POST['marca_memoria_2']) : null,
                ':serie_memoria_2' => strtoupper($_POST['serie_memoria_2']),
                ':marca_memoria_3' => $_POST['marca_memoria_3'] !== '' ? strtoupper($_POST['marca_memoria_3']) : null,
                ':serie_memoria_3' => strtoupper($_POST['serie_memoria_3']),
                ':marca_memoria_4' => $_POST['marca_memoria_4'] !== '' ? strtoupper($_POST['marca_memoria_4']) : null,
                ':serie_memoria_4' => strtoupper($_POST['serie_memoria_4']),
                ':marca_monitor' => $_POST['marca_monitor'] !== '' ? strtoupper($_POST['marca_monitor']) : null,
                ':modelo_monitor' => $_POST['modelo_monitor'] !== '' ? strtoupper($_POST['modelo_monitor']) : null,
                ':serie_monitor' => strtoupper($_POST['serie_monitor']),
                ':foto_disco_duro' => $foto_disco_duro,
                ':foto_memoria' => $foto_memoria,
                ':id_facultad' => $id_facultad  // Facultades relacionadas del usuario
            ]);

            header('Location: a_equipos.php');
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error general: " . $e->getMessage();
        }
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <title>Agregar Nuevo Equipo</title>
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
            margin-top: 56px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 56px);
            overflow-y: auto;
        }

        .card {
            width: 100%;
            max-width: 1000px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
                <!-- Alta de Equipos -->
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
                <!-- Docentes -->
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
                <!-- Servicios -->
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
            <div class="container mt-5">
                <div class="form-wrapper">

                    <h2 class="mb-4"><i class="fas fa-laptop"></i> Registro de Nuevo Equipo</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form action="add_a_equipos.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="inventario">Inventario:
                            </label>
                            <input type="number" class="form-control" id="inventario" name="inventario" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="serie">Serie:

                            </label>
                            <input type="text" class="form-control" id="serie" name="serie" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="activo">Activo:

                            </label>
                            <input type="number" class="form-control" id="activo" name="activo">
                        </div>
                        <div class="form-group">
                            <label for="nombre_equipo">Nombre del Equipo:
                                <span style="color: red;">*</span>
                            </label>
                            <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="ubicacion">Ubicación:
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="ubicacion" name="ubicacion" required>
                                <?php foreach ($ubicaciones as $ubicacion): ?>
                                    <option value="" disabled selected hidden>Seleccionar una Ubicación</option>

                                    <option value="<?php echo $ubicacion['id_ubicacion']; ?>"><?php echo $ubicacion['ubicacion']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Se elimina el input select para facultad ya que está prellenado desde la sesión -->
                        <div class="form-group">
                            <label for="tipo_equipo">Tipo de Equipo:
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="tipo_equipo" name="tipo_equipo" required>
                                <?php foreach ($tipos_equipo as $tipo_equipo): ?>
                                    <option value="" disabled selected hidden>Seleccionar un Tipo de Equipo</option>

                                    <option value="<?php echo $tipo_equipo['id_tipo_equipo']; ?>"><?php echo $tipo_equipo['tipo_equipo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="marca">Marca:
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="marca" name="marca" required>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="" disabled selected hidden>Seleccionar una Marca</option>

                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo:
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="modelo" name="modelo" required>
                                <?php foreach ($modelos as $modelo): ?>
                                    <option value="" disabled selected hidden>Seleccionar un Modelo</option>

                                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="procesador">Procesador:
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="procesador" name="procesador" required>
                                <?php foreach ($procesadores as $procesador): ?>
                                    <option value="" disabled selected hidden>Seleccionar un Procesador</option>

                                    <option value="<?php echo $procesador['id_procesador']; ?>"><?php echo $procesador['procesador']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="memoria_total">Memoria Total:
                                <span style="color: red;">*</span>
                            </label>
                            <select class="form-control" id="memoria_total" name="memoria_total" required>
                                <?php foreach ($tipos_memoria as $tipo_memoria): ?>
                                    <option value="" disabled selected hidden>Seleccionar Memoria Tota</option>

                                    <option value="<?php echo $tipo_memoria['id_tmemoria']; ?>"><?php echo $tipo_memoria['tp_memoria']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="disco_duro_1">Disco Duro 1:
                                <span style="color: red;">*</span>
                            </label>
                            <input type="text" class="form-control" id="disco_duro_1" name="disco_duro_1" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="marca_dd1">Marca DD1:</label>
                            <select class="form-control" id="marca_dd1" name="marca_dd1">
                                <option value="" disabled selected hidden>Seleccionar Marca DD1</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_dd1">Serie DD1:
                                <span style="color: red;">*</span>
                            </label>
                            <input type="text" class="form-control" id="serie_dd1" name="serie_dd1" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="modelo_dd1">Modelo DD1:</label>
                            <select class="form-control" id="modelo_dd1" name="modelo_dd1">
                                <option value="" disabled selected hidden>Seleccionar Modelo DD1</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($modelos as $modelo): ?>
                                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="disco_duro_2">Disco Duro 2:</label>
                            <input type="text" class="form-control" id="disco_duro_2" name="disco_duro_2" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="marca_dd2">Marca DD2:</label>
                            <select class="form-control" id="marca_dd2" name="marca_dd2">
                                <option value="" disabled selected hidden>Seleccionar una Marca DD2:</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_dd2">Serie DD2:</label>
                            <input type="text" class="form-control" id="serie_dd2" name="serie_dd2" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="modelo_dd2">Modelo DD2:</label>
                            <select class="form-control" id="modelo_dd2" name="modelo_dd2">
                                <option value="" disabled selected hidden>Seleccionar Modelo DD2</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($modelos as $modelo): ?>
                                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="marca_memoria_1">Marca Memoria 1:</label>
                            <select class="form-control" id="marca_memoria_1" name="marca_memoria_1">
                                <option value="" disabled selected hidden>Seleccionar una Marca Memoria 1</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria_1">Serie Memoria 1:</label>
                            <input type="text" class="form-control" id="serie_memoria_1" name="serie_memoria_1" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="marca_memoria_2">Marca Memoria 2:</label>
                            <select class="form-control" id="marca_memoria_2" name="marca_memoria_2">
                                <option value="" disabled selected hidden>Seleccionar una Marca Memoria 2</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria_2">Serie Memoria 2:</label>
                            <input type="text" class="form-control" id="serie_memoria_2" name="serie_memoria_2" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="marca_memoria_3">Marca Memoria 3:</label>
                            <select class="form-control" id="marca_memoria_3" name="marca_memoria_3">
                                <option value="" disabled selected hidden>Seleccionar una Marca Memoria 3</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria_3">Serie Memoria 3:</label>
                            <input type="text" class="form-control" id="serie_memoria_3" name="serie_memoria_3" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="marca_memoria_4">Marca Memoria 4:</label>
                            <select class="form-control" id="marca_memoria_4" name="marca_memoria_4">
                                <option value="" disabled selected hidden>Seleccionar una Marca Memoria 4</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria_4">Serie Memoria 4:</label>
                            <input type="text" class="form-control" id="serie_memoria_4" name="serie_memoria_4" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="marca_monitor">Marca Monitor:</label>
                            <select class="form-control" id="marca_monitor" name="marca_monitor">
                                <option value="" disabled selected hidden>Seleccionar una Marca Monitor</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modelo_monitor">Modelo Monitor:</label>
                            <select class="form-control" id="modelo_monitor" name="modelo_monitor">
                                <option value="" disabled selected hidden>Seleccionar un Modelo Monitor</option>

                                <option value="">Ninguna</option>
                                <?php foreach ($modelos as $modelo): ?>
                                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_monitor">Serie Monitor:</label>
                            <input type="text" class="form-control" id="serie_monitor" name="serie_monitor" required style="text-transform: uppercase;">
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> Guardar</button>
                        <a href="a_equipos.php" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Volver</a>
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