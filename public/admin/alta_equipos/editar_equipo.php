<?php
session_start();
require_once '../../../config/database.php';
try {

    $id = isset($_GET['inventario']) ? intval($_GET['inventario']) : 0;
    $equipo = $pdo->query("SELECT * FROM t_alta_equipo WHERE inventario = $id")->fetch();
    $ubicaciones = $pdo->query("SELECT id_ubicacion, ubicacion FROM t_ubicacion")->fetchAll();
    $tipos_equipo = $pdo->query("SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo")->fetchAll();
    $marcas = $pdo->query("SELECT id_marca, marca FROM t_marca_equipo")->fetchAll();
    $modelos = $pdo->query("SELECT id_modelo, modelo FROM t_modelo_equipo")->fetchAll();
    $procesadores = $pdo->query("SELECT id_procesador, procesador FROM t_procesador")->fetchAll();
    $memorias = $pdo->query("SELECT id_memoria, memoria FROM t_memoria")->fetchAll();
    $tipos_memoria = $pdo->query("SELECT id_tmemoria, tp_memoria FROM tipo_memoria")->fetchAll();
    $facultades = $pdo->query("SELECT id_facultad, facultad FROM t_facultad")->fetchAll();

    // Obtener los datos actuales del equipo a editar
    if (isset($_GET['inventario'])) {
        $stmt = $pdo->prepare("SELECT * FROM t_alta_equipo WHERE inventario = :inventario");
        $stmt->execute([':inventario' => $_GET['inventario']]);
        $equipo = $stmt->fetch();

        if (!$equipo) {
            echo "Equipo no encontrado";
            exit;
        }
    } else {
        echo "ID de equipo no proporcionado";
        exit;
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/shadow-fowm.css">
    <title>Editar Equipos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .content {
            margin-left: 250px;
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
    <div class="container mt-5">
        <div class="form-wrapper">

            <h2 class="mb-4"><i class="fas fa-edit"></i> Editar Equipo</h2>
            <form action="update_equipo.php?inventario=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="inventario_original" value="<?php echo htmlspecialchars($equipo['inventario']); ?>">

                <div class="form-group">
                    <label for="inventario">Inventario:</label>
                    <input type="number" class="form-control" id="inventario" name="inventario" value="<?php echo $equipo['inventario']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="serie">Serie:</label>
                    <input type="text" class="form-control" id="serie" name="serie" value="<?php echo $equipo['serie']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="activo">Activo:</label>
                    <input type="number" class="form-control" id="activo" name="activo" value="<?php echo $equipo['activo']; ?>">
                </div>
                <div class="form-group">
                    <label for="nombre_equipo">Nombre del Equipo:</label>
                    <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" value="<?php echo $equipo['nombre_equipo']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicación:</label>
                    <select class="form-control" id="ubicacion" name="ubicacion" required>
                        <?php foreach ($ubicaciones as $ubicacion): ?>
                            <option value="<?php echo $ubicacion['id_ubicacion']; ?>" <?php echo ($equipo['ubicacion'] == $ubicacion['id_ubicacion']) ? 'selected' : ''; ?>>
                                <?php echo $ubicacion['ubicacion']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="facultad">Facultad:</label>
                    <select class="form-control" id="facultad" name="facultad" required>
                        <?php foreach ($facultades as $facultad): ?>
                            <option value="<?php echo $facultad['id_facultad']; ?>" <?php echo ($equipo['id_facultad'] == $facultad['id_facultad']) ? 'selected' : ''; ?>>
                                <?php echo $facultad['facultad']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo_equipo">Tipo de Equipo:</label>
                    <select class="form-control" id="tipo_equipo" name="tipo_equipo" required>
                        <?php foreach ($tipos_equipo as $tipo_equipo): ?>
                            <option value="<?php echo $tipo_equipo['id_tipo_equipo']; ?>" <?php echo ($equipo['tipo_equipo'] == $tipo_equipo['id_tipo_equipo']) ? 'selected' : ''; ?>>
                                <?php echo $tipo_equipo['tipo_equipo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="marca">Marca:</label>
                    <select class="form-control" id="marca" name="marca" required>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <select class="form-control" id="modelo" name="modelo" required>
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="procesador">Procesador:</label>
                    <select class="form-control" id="procesador" name="procesador" required>
                        <?php foreach ($procesadores as $procesador): ?>
                            <option value="<?php echo $procesador['id_procesador']; ?>" <?php echo ($equipo['procesador'] == $procesador['id_procesador']) ? 'selected' : ''; ?>>
                                <?php echo $procesador['procesador']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="memoria_total">Memoria Total:</label>
                    <select class="form-control" id="memoria_total" name="memoria_total" required>
                        <?php foreach ($tipos_memoria as $tipo_memoria): ?>
                            <option value="<?php echo $tipo_memoria['id_tmemoria']; ?>" <?php echo ($equipo['memoria_total'] == $tipo_memoria['id_tmemoria']) ? 'selected' : ''; ?>>
                                <?php echo $tipo_memoria['tp_memoria']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="disco_duro_1">Disco Duro 1:</label>
                    <input type="text" class="form-control" id="disco_duro_1" name="disco_duro_1" value="<?php echo $equipo['disco_duro_1']; ?>">
                </div>
                <div class="form-group">
                    <label for="marca_dd1">Marca DD1:</label>
                    <select class="form-control" id="marca_dd1" name="marca_dd1">
                        <option value="">Ninguna</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_dd1'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_dd1">Serie DD1:</label>
                    <input type="text" class="form-control" id="serie_dd1" name="serie_dd1" value="<?php echo $equipo['serie_dd1']; ?>">
                </div>
                <div class="form-group">
                    <label for="modelo_dd1">Modelo DD1:</label>
                    <select class="form-control" id="modelo_dd1" name="modelo_dd1">
                        <option value="">Ninguna</option>
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo_dd1'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="disco_duro_2">Disco Duro 2:</label>
                    <input type="text" class="form-control" id="disco_duro_2" name="disco_duro_2" value="<?php echo $equipo['disco_duro_2']; ?>">
                </div>
                <div class="form-group">
                    <label for="marca_dd2">Marca DD2:</label>
                    <select class="form-control" id="marca_dd2" name="marca_dd2">
                        <option value="">Ninguna</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_dd2'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_dd2">Serie DD2:</label>
                    <input type="text" class="form-control" id="serie_dd2" name="serie_dd2" value="<?php echo $equipo['serie_dd2']; ?>">
                </div>
                <div class="form-group">
                    <label for="modelo_dd2">Modelo DD2:</label>
                    <select class="form-control" id="modelo_dd2" name="modelo_dd2">
                        <option value="">Ninguna</option>
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo_dd2'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="marca_memoria_1">Marca Memoria 1:</label>
                    <select class="form-control" id="marca_memoria_1" name="marca_memoria_1">
                        <option value="">Ninguna</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_1'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_memoria_1">Serie Memoria 1:</label>
                    <input type="text" class="form-control" id="serie_memoria_1" name="serie_memoria_1" value="<?php echo $equipo['serie_memoria_1']; ?>">
                </div>
                <div class="form-group">
                    <label for="marca_memoria_2">Marca Memoria 2:</label>
                    <select class="form-control" id="marca_memoria_2" name="marca_memoria_2">
                        <option value="">Ninguna</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_2'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_memoria_2">Serie Memoria 2:</label>
                    <input type="text" class="form-control" id="serie_memoria_2" name="serie_memoria_2" value="<?php echo $equipo['serie_memoria_2']; ?>">
                </div>
                <div class="form-group">
                    <label for="marca_memoria_3">Marca Memoria 3:</label>
                    <select class="form-control" id="marca_memoria_3" name="marca_memoria_3">
                        <option value="">Ninguna</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_3'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_memoria_3">Serie Memoria 3:</label>
                    <input type="text" class="form-control" id="serie_memoria_3" name="serie_memoria_3" value="<?php echo $equipo['serie_memoria_3']; ?>">
                </div>
                <div class="form-group">
                    <label for="marca_memoria_4">Marca Memoria 4:</label>
                    <select class="form-control" id="marca_memoria_4" name="marca_memoria_4">
                        <option value="">Ninguna</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_4'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_memoria_4">Serie Memoria 4:</label>
                    <input type="text" class="form-control" id="serie_memoria_4" name="serie_memoria_4" value="<?php echo $equipo['serie_memoria_4']; ?>">
                </div>
                <div class="form-group">
                    <label for="marca_monitor">Marca Monitor:</label>
                    <select class="form-control" id="marca_monitor" name="marca_monitor">
                        <option value="">Ninguna</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_monitor'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modelo_monitor">Modelo Monitor:</label>
                    <select class="form-control" id="modelo_monitor" name="modelo_monitor">
                        <option value="">Ninguna</option>
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo_monitor'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_monitor">Serie Monitor:</label>
                    <input type="text" class="form-control" id="serie_monitor" name="serie_monitor" value="<?php echo $equipo['serie_monitor']; ?>">
                </div>
                
                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> Guardar</button>
                <a href="a_equipos.php" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Volver</a>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>