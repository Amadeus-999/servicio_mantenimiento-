<?php
require_once '../../../config/database.php';

try {
    // Consultas para obtener datos de la base de datos
    $queries = [
        'nombre_equipo' => "SELECT id_modelo, modelo_equipo FROM t_modelo_equipo",
        'ubicacion' => "SELECT id_ubicacion, ubicacion FROM t_ubicacion",
        'tipo_equipo' => "SELECT id_equipo, tipo_equipo FROM t_tipo_equipo",
        'marca' => "SELECT id_marca, marca FROM t_marca_equipo",
        'memoria_total' => "SELECT id_memoria, memoria FROM t_memoria",
        'modelo_dd' => "SELECT id_modelo, modelo_equipo FROM t_modelo_equipo",
        'tipo_memoria' => "SELECT id_tmemoria, tp_memoria FROM tipo_memoria",
        'marca_monitor' => "SELECT id_marca, marca FROM t_marca_equipo",
        'modelo_monitor' => "SELECT id_modelo, modelo_equipo FROM t_modelo_equipo",
        'procesador' => "SELECT id_procesador, procesador FROM t_procesador" // Consulta para Procesador
    ];

    $data = [];
    foreach ($queries as $key => $query) {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data[$key] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Nuevos Equipos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Dar de Alta Nuevos Equipos</h2>
        <form action="procesar_alta_equipo.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="inventario">Inventario</label>
                <input type="text" class="form-control" id="inventario" name="inventario" required>
            </div>

            <div class="form-group">
                <label for="serie">Serie</label>
                <input type="text" class="form-control" id="serie" name="serie" required>
            </div>

            <div class="form-group">
                <label for="activo">Activo</label>
                <input type="text" class="form-control" id="activo" name="activo" required>
            </div>

            <div class="form-group">
                <label for="nombre_equipo">Nombre de equipo</label>
                <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" required>
            </div>

            <div class="form-group">
                <label for="ubicacion">Ubicación</label>
                <select class="form-control" id="ubicacion" name="ubicacion" required>
                    <?php foreach ($data['ubicacion'] as $item): ?>
                        <option value="<?php echo $item['id_ubicacion']; ?>"><?php echo htmlspecialchars($item['ubicacion']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_equipo">Tipo de equipo</label>
                <select class="form-control" id="tipo_equipo" name="tipo_equipo" required>
                    <?php foreach ($data['tipo_equipo'] as $item): ?>
                        <option value="<?php echo $item['id_equipo']; ?>"><?php echo htmlspecialchars($item['tipo_equipo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="marca">Marca</label>
                <select class="form-control" id="marca" name="marca" required>
                    <?php foreach ($data['marca'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="modelo">Modelo</label>
                <select class="form-control" id="modelo" name="modelo" required>
                    <?php foreach ($data['modelo_dd'] as $item): ?>
                        <option value="<?php echo $item['id_modelo']; ?>"><?php echo htmlspecialchars($item['modelo_equipo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="procesador">Procesador</label>
                <select class="form-control" id="procesador" name="procesador" required>
                    <?php foreach ($data['procesador'] as $item): ?>
                        <option value="<?php echo $item['id_procesador']; ?>"><?php echo htmlspecialchars($item['procesador']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="memoria_total">Memoria Total</label>
                <select class="form-control" id="memoria_total" name="memoria_total" required>
                    <?php foreach ($data['memoria_total'] as $item): ?>
                        <option value="<?php echo $item['id_memoria']; ?>"><?php echo htmlspecialchars($item['memoria']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="disco_duro_1">Disco Duro 1</label>
                <input type="text" class="form-control" id="disco_duro_1" name="disco_duro_1">
            </div>

            <div class="form-group">
                <label for="marca_dd1">Marca Disco Duro 1</label>
                <select class="form-control" id="marca_dd1" name="marca_dd1">
                    <?php foreach ($data['marca'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="serie_dd1">Serie Disco Duro 1</label>
                <input type="text" class="form-control" id="serie_dd1" name="serie_dd1">
            </div>

            <div class="form-group">
                <label for="modelo_dd1">Modelo Disco Duro 1</label>
                <select class="form-control" id="modelo_dd1" name="modelo_dd1">
                    <?php foreach ($data['modelo_dd'] as $item): ?>
                        <option value="<?php echo $item['id_modelo']; ?>"><?php echo htmlspecialchars($item['modelo_equipo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="disco_duro_2">Disco Duro 2</label>
                <input type="text" class="form-control" id="disco_duro_2" name="disco_duro_2">
            </div>

            <div class="form-group">
                <label for="marca_dd2">Marca Disco Duro 2</label>
                <select class="form-control" id="marca_dd2" name="marca_dd2">
                    <?php foreach ($data['marca'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="serie_dd2">Serie Disco Duro 2</label>
                <input type="text" class="form-control" id="serie_dd2" name="serie_dd2">
            </div>

            <div class="form-group">
                <label for="modelo_dd2">Modelo Disco Duro 2</label>
                <select class="form-control" id="modelo_dd2" name="modelo_dd2">
                    <?php foreach ($data['modelo_dd'] as $item): ?>
                        <option value="<?php echo $item['id_modelo']; ?>"><?php echo htmlspecialchars($item['modelo_equipo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="marca_memoria_1">Marca Memoria 1</label>
                <select class="form-control" id="marca_memoria_1" name="marca_memoria_1">
                    <?php foreach ($data['marca'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="serie_memoria_1">Serie Memoria 1</label>
                <input type="text" class="form-control" id="serie_memoria_1" name="serie_memoria_1">
            </div>

            <div class="form-group">
                <label for="marca_memoria_2">Marca Memoria 2</label>
                <select class="form-control" id="marca_memoria_2" name="marca_memoria_2">
                    <?php foreach ($data['marca'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="serie_memoria_2">Serie Memoria 2</label>
                <input type="text" class="form-control" id="serie_memoria_2" name="serie_memoria_2">
            </div>

            <div class="form-group">
                <label for="marca_memoria_3">Marca Memoria 3</label>
                <select class="form-control" id="marca_memoria_3" name="marca_memoria_3">
                    <?php foreach ($data['marca'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="serie_memoria_3">Serie Memoria 3</label>
                <input type="text" class="form-control" id="serie_memoria_3" name="serie_memoria_3">
            </div>

            <div class="form-group">
                <label for="marca_memoria_4">Marca Memoria 4</label>
                <select class="form-control" id="marca_memoria_4" name="marca_memoria_4">
                    <?php foreach ($data['marca'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="serie_memoria_4">Serie Memoria 4</label>
                <input type="text" class="form-control" id="serie_memoria_4" name="serie_memoria_4">
            </div>

            <div class="form-group">
                <label for="tipo_memoria">Tipo de Memoria</label>
                <select class="form-control" id="tipo_memoria" name="tipo_memoria">
                    <?php foreach ($data['tipo_memoria'] as $item): ?>
                        <option value="<?php echo $item['id_tmemoria']; ?>"><?php echo htmlspecialchars($item['tp_memoria']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="marca_monitor">Marca Monitor</label>
                <select class="form-control" id="marca_monitor" name="marca_monitor">
                    <?php foreach ($data['marca_monitor'] as $item): ?>
                        <option value="<?php echo $item['id_marca']; ?>"><?php echo htmlspecialchars($item['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="modelo_monitor">Modelo Monitor</label>
                <select class="form-control" id="modelo_monitor" name="modelo_monitor">
                    <?php foreach ($data['modelo_monitor'] as $item): ?>
                        <option value="<?php echo $item['id_modelo']; ?>"><?php echo htmlspecialchars($item['modelo_equipo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="serie_monitor">Serie Monitor</label>
                <input type="text" class="form-control" id="serie_monitor" name="serie_monitor">
            </div>

            <div class="form-group">
                <label for="foto_disco_duro">Foto Disco Duro</label>
                <input type="file" class="form-control-file" id="foto_disco_duro" name="foto_disco_duro">
            </div>

            <div class="form-group">
                <label for="foto_memoria">Foto Memoria</label>
                <input type="file" class="form-control-file" id="foto_memoria" name="foto_memoria">
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>