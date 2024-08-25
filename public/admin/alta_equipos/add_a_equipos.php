<?php
$host = 'localhost';
$port = 3306;  
$db = 'trabajo_social';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Fetching necessary data for the dropdowns
    $ubicaciones = $pdo->query("SELECT id_ubicacion, ubicacion FROM t_ubicacion")->fetchAll();
    $tipos_equipo = $pdo->query("SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo")->fetchAll();
    $marcas = $pdo->query("SELECT id_marca, marca FROM t_marca_equipo")->fetchAll();
    $modelos = $pdo->query("SELECT id_modelo, modelo FROM t_modelo_equipo")->fetchAll();
    $procesadores = $pdo->query("SELECT id_procesador, procesador FROM t_procesador")->fetchAll();
    $memorias = $pdo->query("SELECT id_memoria, memoria FROM t_memoria")->fetchAll();
    $tipos_memoria = $pdo->query("SELECT id_tmemoria, tp_memoria FROM tipo_memoria")->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Handle file uploads
            $foto_disco_duro = isset($_FILES['foto_disco_duro']) && $_FILES['foto_disco_duro']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['foto_disco_duro']['tmp_name']) : null;
            $foto_memoria = isset($_FILES['foto_memoria']) && $_FILES['foto_memoria']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['foto_memoria']['tmp_name']) : null;

            // Insert query for adding a new equipment
            $sql = "INSERT INTO t_alta_equipo (
                inventario, serie, activo, nombre_equipo, ubicacion, tipo_equipo, marca, modelo,
                procesador, memoria_total, disco_duro_1, marca_dd1, serie_dd1, modelo_dd1,
                disco_duro_2, marca_dd2, serie_dd2, modelo_dd2, marca_memoria_1, serie_memoria_1,
                marca_memoria_2, serie_memoria_2, marca_memoria_3, serie_memoria_3,
                marca_memoria_4, serie_memoria_4, tipo_memoria, marca_monitor, modelo_monitor,
                serie_monitor, foto_disco_duro, foto_memoria
            ) VALUES (
                :inventario, :serie, :activo, :nombre_equipo, :ubicacion, :tipo_equipo, :marca, :modelo,
                :procesador, :memoria_total, :disco_duro_1, :marca_dd1, :serie_dd1, :modelo_dd1,
                :disco_duro_2, :marca_dd2, :serie_dd2, :modelo_dd2, :marca_memoria_1, :serie_memoria_1,
                :marca_memoria_2, :serie_memoria_2, :marca_memoria_3, :serie_memoria_3,
                :marca_memoria_4, :serie_memoria_4, :tipo_memoria, :marca_monitor, :modelo_monitor,
                :serie_monitor, :foto_disco_duro, :foto_memoria
            )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':inventario' => $_POST['inventario'],
                ':serie' => $_POST['serie'],
                ':activo' => $_POST['activo'],
                ':nombre_equipo' => $_POST['nombre_equipo'],
                ':ubicacion' => $_POST['ubicacion'],
                ':tipo_equipo' => $_POST['tipo_equipo'],
                ':marca' => $_POST['marca'],
                ':modelo' => $_POST['modelo'],
                ':procesador' => $_POST['procesador'],
                ':memoria_total' => $_POST['memoria_total'],
                ':disco_duro_1' => $_POST['disco_duro_1'],
                ':marca_dd1' => $_POST['marca_dd1'],
                ':serie_dd1' => $_POST['serie_dd1'],
                ':modelo_dd1' => $_POST['modelo_dd1'],
                ':disco_duro_2' => $_POST['disco_duro_2'],
                ':marca_dd2' => $_POST['marca_dd2'],
                ':serie_dd2' => $_POST['serie_dd2'],
                ':modelo_dd2' => $_POST['modelo_dd2'],
                ':marca_memoria_1' => $_POST['marca_memoria_1'],
                ':serie_memoria_1' => $_POST['serie_memoria_1'],
                ':marca_memoria_2' => $_POST['marca_memoria_2'],
                ':serie_memoria_2' => $_POST['serie_memoria_2'],
                ':marca_memoria_3' => $_POST['marca_memoria_3'],
                ':serie_memoria_3' => $_POST['serie_memoria_3'],
                ':marca_memoria_4' => $_POST['marca_memoria_4'],
                ':serie_memoria_4' => $_POST['serie_memoria_4'],
                ':tipo_memoria' => $_POST['tipo_memoria'],
                ':marca_monitor' => $_POST['marca_monitor'],
                ':modelo_monitor' => $_POST['modelo_monitor'],
                ':serie_monitor' => $_POST['serie_monitor'],
                ':foto_disco_duro' => $foto_disco_duro,
                ':foto_memoria' => $foto_memoria
            ]);

            echo "Equipo registrado exitosamente.";
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
    <title>Alta de Equipos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Registro de Nuevo Equipo</h2>
    <form action="add_a_equipos.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inventario">Inventario:</label>
            <input type="text" class="form-control" id="inventario" name="inventario" required>
        </div>
        <div class="form-group">
            <label for="serie">Serie:</label>
            <input type="text" class="form-control" id="serie" name="serie" required>
        </div>
        <div class="form-group">
            <label for="activo">Activo:</label>
            <input type="text" class="form-control" id="activo" name="activo" required>
        </div>
        <div class="form-group">
            <label for="nombre_equipo">Nombre del Equipo:</label>
            <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" required>
        </div>
        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <select class="form-control" id="ubicacion" name="ubicacion" required>
                <?php foreach ($ubicaciones as $ubicacion): ?>
                    <option value="<?php echo $ubicacion['id_ubicacion']; ?>"><?php echo $ubicacion['ubicacion']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo_equipo">Tipo de Equipo:</label>
            <select class="form-control" id="tipo_equipo" name="tipo_equipo" required>
                <?php foreach ($tipos_equipo as $tipo_equipo): ?>
                    <option value="<?php echo $tipo_equipo['id_tipo_equipo']; ?>"><?php echo $tipo_equipo['tipo_equipo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="marca">Marca:</label>
            <select class="form-control" id="marca" name="marca" required>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="modelo">Modelo:</label>
            <select class="form-control" id="modelo" name="modelo" required>
                <?php foreach ($modelos as $modelo): ?>
                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                <?php endforeach; ?>
           
                </select>
        </div>
        <div class="form-group">
            <label for="procesador">Procesador:</label>
            <select class="form-control" id="procesador" name="procesador" required>
                <?php foreach ($procesadores as $procesador): ?>
                    <option value="<?php echo $procesador['id_procesador']; ?>"><?php echo $procesador['procesador']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="memoria_total">Memoria Total:</label>
            <select class="form-control" id="memoria_total" name="memoria_total" required>
                <?php foreach ($memorias as $memoria): ?>
                    <option value="<?php echo $memoria['id_memoria']; ?>"><?php echo $memoria['memoria']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="disco_duro_1">Disco Duro 1:</label>
            <input type="text" class="form-control" id="disco_duro_1" name="disco_duro_1">
        </div>
        <div class="form-group">
            <label for="marca_dd1">Marca DD1:</label>
            <select class="form-control" id="marca_dd1" name="marca_dd1">
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serie_dd1">Serie DD1:</label>
            <input type="text" class="form-control" id="serie_dd1" name="serie_dd1">
        </div>
        <div class="form-group">
            <label for="modelo_dd1">Modelo DD1:</label>
            <select class="form-control" id="modelo_dd1" name="modelo_dd1">
                <?php foreach ($modelos as $modelo): ?>
                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="disco_duro_2">Disco Duro 2:</label>
            <input type="text" class="form-control" id="disco_duro_2" name="disco_duro_2">
        </div>
        <div class="form-group">
            <label for="marca_dd2">Marca DD2:</label>
            <select class="form-control" id="marca_dd2" name="marca_dd2">
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serie_dd2">Serie DD2:</label>
            <input type="text" class="form-control" id="serie_dd2" name="serie_dd2">
        </div>
        <div class="form-group">
            <label for="modelo_dd2">Modelo DD2:</label>
            <select class="form-control" id="modelo_dd2" name="modelo_dd2">
                <?php foreach ($modelos as $modelo): ?>
                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="marca_memoria_1">Marca Memoria 1:</label>
            <select class="form-control" id="marca_memoria_1" name="marca_memoria_1">
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serie_memoria_1">Serie Memoria 1:</label>
            <input type="text" class="form-control" id="serie_memoria_1" name="serie_memoria_1">
        </div>
        <div class="form-group">
            <label for="marca_memoria_2">Marca Memoria 2:</label>
            <select class="form-control" id="marca_memoria_2" name="marca_memoria_2">
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serie_memoria_2">Serie Memoria 2:</label>
            <input type="text" class="form-control" id="serie_memoria_2" name="serie_memoria_2">
        </div>
        <div class="form-group">
            <label for="marca_memoria_3">Marca Memoria 3:</label>
            <select class="form-control" id="marca_memoria_3" name="marca_memoria_3">
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serie_memoria_3">Serie Memoria 3:</label>
            <input type="text" class="form-control" id="serie_memoria_3" name="serie_memoria_3">
        </div>
        <div class="form-group">
            <label for="marca_memoria_4">Marca Memoria 4:</label>
            <select class="form-control" id="marca_memoria_4" name="marca_memoria_4">
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serie_memoria_4">Serie Memoria 4:</label>
            <input type="text" class="form-control" id="serie_memoria_4" name="serie_memoria_4">
        </div>
        <div class="form-group">
            <label for="tipo_memoria">Tipo de Memoria:</label>
            <select class="form-control" id="tipo_memoria" name="tipo_memoria" required>
                <?php foreach ($tipos_memoria as $tipo_memoria): ?>
                    <option value="<?php echo $tipo_memoria['id_tmemoria']; ?>"><?php echo $tipo_memoria['tp_memoria']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="marca_monitor">Marca Monitor:</label>
            <select class="form-control" id="marca_monitor" name="marca_monitor">
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="modelo_monitor">Modelo Monitor:</label>
            <select class="form-control" id="modelo_monitor" name="modelo_monitor">
                <?php foreach ($modelos as $modelo): ?>
                    <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serie_monitor">Serie Monitor:</label>
            <input type="text" class="form-control" id="serie_monitor" name="serie_monitor">
        </div>
        <div class="form-group">
            <label for="foto_disco_duro">Foto Disco Duro:</label>
            <input type="file" class="form-control" id="foto_disco_duro" name="foto_disco_duro">
        </div>
        <div class="form-group">
            <label for="foto_memoria">Foto Memoria:</label>
            <input type="file" class="form-control" id="foto_memoria" name="foto_memoria">
        </div>
        <button type="submit" class="btn btn-primary">Registrar Equipo</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

