<?php
require_once '../../../config/database.php';

$personalData = [];
$equipmentData = [];

if (isset($_GET['npesonal']) && !empty($_GET['npesonal'])) {
    $npesonal = $_GET['npesonal'];
    $sqlPersonal = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
    $stmtPersonal = $pdo->prepare($sqlPersonal);
    $stmtPersonal->bindParam(':npesonal', $npesonal, PDO::PARAM_STR);
    $stmtPersonal->execute();
    $personalData = $stmtPersonal->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['inventario']) && !empty($_GET['inventario'])) {
    $inventario = $_GET['inventario'];
    $sqlEquipment = "SELECT * FROM t_alta_equipo WHERE inventario = :inventario";
    $stmtEquipment = $pdo->prepare($sqlEquipment);
    $stmtEquipment->bindParam(':inventario', $inventario, PDO::PARAM_INT);
    $stmtEquipment->execute();
    $equipmentData = $stmtEquipment->fetch(PDO::FETCH_ASSOC);
}

// Datos para dropdowns
$tipoEquipos = $pdo->query("SELECT * FROM t_tipo_equipo")->fetchAll(PDO::FETCH_ASSOC);
$marcas = $pdo->query("SELECT * FROM t_marca_equipo")->fetchAll(PDO::FETCH_ASSOC);
$modelos = $pdo->query("SELECT * FROM t_modelo_equipo")->fetchAll(PDO::FETCH_ASSOC);
$ubicaciones = $pdo->query("SELECT * FROM t_ubicacion")->fetchAll(PDO::FETCH_ASSOC);
$tiposMemoria = $pdo->query("SELECT * FROM tipo_memoria")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/g_reporte.css">
    <style>
        .form-container {
            margin-top: 20px;
        }

        .section-title {
            margin-top: 20px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .divider {
            margin: 20px 0;
            border-top: 1px solid #ccc;
        }

        .form-inline {
            display: flex;
            align-items: center;
        }

        .form-inline .form-control {
            width: auto;
            margin-right: 10px;
        }

        .horizontal-group {
            display: flex;
            gap: 15px;
        }
    </style>
</head>

<body>
    <div class="container form-container">
        <div class="d-flex align-items-center mb-4">
            <img src="logo.png" alt="Logo Institucional" class="mr-3" style="width: 150px;">
            <div class="ml-auto">
                <form class="form-inline" method="GET" action="">
                    <label for="fecha_solicitud" class="mr-sm-2">Fecha de Solicitud:</label>
                    <input class="form-control" type="date" id="fecha_solicitud" name="fecha_solicitud" value="<?php echo date('Y-m-d'); ?>" readonly>

                    <label for="numero_reporte" class="mr-sm-2">Número de Reporte:</label>
                    <input class="form-control" type="text" id="numero_reporte" name="numero_reporte">
                </form>
            </div>
        </div>

        <div class="section-title">Datos del Solicitante</div>
        <form class="form">
            <div class="form-group horizontal-group">
                <label for="npesonal">Num. Personal:</label>
                <input type="text" id="npesonal" name="npesonal" class="form-control" value="<?php echo htmlspecialchars($personalData['npesonal'] ?? ''); ?>">
                <input type="text" class="form-control" placeholder="Nombre" value="<?php echo htmlspecialchars($personalData['nombre'] ?? ''); ?>" readonly>
                <input type="text" class="form-control" placeholder="Apellido P" value="<?php echo htmlspecialchars($personalData['apellido_p'] ?? ''); ?>" readonly>
                <input type="text" class="form-control" placeholder="Apellido M" value="<?php echo htmlspecialchars($personalData['apellido_m'] ?? ''); ?>" readonly>
                <input type="text" class="form-control" placeholder="Correo" value="<?php echo htmlspecialchars($personalData['correo'] ?? ''); ?>" readonly>
                <input type="text" class="form-control" placeholder="Extensión" value="<?php echo htmlspecialchars($personalData['extension'] ?? ''); ?>" readonly>
                <select class="form-control" disabled>
                    <option value="">Facultad</option>
                    <?php // Populate facultad options 
                    ?>
                </select>
            </div>

            <div class="section-title">Datos del Equipo</div>
            <div class="form-group horizontal-group">
                <label for="inventario">Inventario:</label>
                <input type="text" id="inventario" name="inventario" class="form-control" value="<?php echo htmlspecialchars($equipmentData['inventario'] ?? ''); ?>">
                <input type="text" class="form-control" placeholder="Activo" value="<?php echo htmlspecialchars($equipmentData['activo'] ?? ''); ?>" readonly>
            </div>
            <div class="form-group horizontal-group">
                <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie'] ?? ''); ?>" readonly>
                <select class="form-control" name="tipo_equipo">
                    <option value="">Tipo de Equipo</option>
                    <?php foreach ($tipoEquipos as $tipo): ?>
                        <option value="<?php echo $tipo['id_tipo_equipo']; ?>" <?php echo (isset($equipmentData['tipo_equipo']) && $tipo['id_tipo_equipo'] == $equipmentData['tipo_equipo']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tipo['tipo_equipo']); ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control" name="marca">
                    <option value="">Marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca']) && $marca['id_marca'] == $equipmentData['marca']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control" name="modelo">
                    <option value="">Modelo</option>
                    <?php foreach ($modelos as $modelo): ?>
                        <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo (isset($equipmentData['modelo']) && $modelo['id_modelo'] == $equipmentData['modelo']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($modelo['modelo']); ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control" name="ubicacion">
                    <option value="">Ubicación</option>
                    <?php foreach ($ubicaciones as $ubicacion): ?>
                        <option value="<?php echo $ubicacion['id_ubicacion']; ?>" <?php echo (isset($equipmentData['ubicacion']) && $ubicacion['id_ubicacion'] == $equipmentData['ubicacion']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($ubicacion['ubicacion']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="section-title">Datos del Disco Duro 1</div>
            <div class="form-group horizontal-group">
                <input type="text" class="form-control" placeholder="Capacidad" value="<?php echo htmlspecialchars($equipmentData['disco_duro_1'] ?? ''); ?>" readonly>
                <select class="form-control" name="marca_dd1">
                    <option value="">Marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca_dd1']) && $marca['id_marca'] == $equipmentData['marca_dd1']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($marca['marca']); ?>
                        </option>
                    <?php endforeach; ?>

                    <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie_dd1'] ?? ''); ?>" readonly>
                    <select class="form-control" name="modelo_dd1">
                        <option value="">Modelo</option>
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo (isset($equipmentData['modelo_dd1']) && $modelo['id_modelo'] == $equipmentData['modelo_dd1']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($modelo['modelo']); ?></option>
                        <?php endforeach; ?>
                    </select>
            </div>

            <div class="section-title">Datos del Disco Duro 2</div>
            <div class="form-group horizontal-group">
                <input type="text" class="form-control" placeholder="Capacidad" value="<?php echo htmlspecialchars($equipmentData['disco_duro_2'] ?? ''); ?>" readonly>
                <select class="form-control" name="marca_dd2">
                    <option value="">Marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca_dd2']) && $marca['id_marca'] == $equipmentData['marca_dd2']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie_dd2'] ?? ''); ?>" readonly>
                <select class="form-control" name="modelo_dd2">
                    <option value="">Modelo</option>
                    <?php foreach ($modelos as $modelo): ?>
                        <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo (isset($equipmentData['modelo_dd2']) && $modelo['id_modelo'] == $equipmentData['modelo_dd2']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($modelo['modelo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="section-title">Datos de Memorias</div>
            <div class="form-group">
                <div class="horizontal-group">
                    <div class="form-group">
                        <label>Memoria 1:</label>
                        <select class="form-control" name="marca_memoria_1">
                            <option value="">Marca</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca_memoria_1']) && $marca['id_marca'] == $equipmentData['marca_memoria_1']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['marca']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie_memoria_1'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Memoria 2:</label>
                        <select class="form-control" name="marca_memoria_2">
                            <option value="">Marca</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca_memoria_2']) && $marca['id_marca'] == $equipmentData['marca_memoria_2']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['marca']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie_memoria_2'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Memoria 3:</label>
                        <select class="form-control" name="marca_memoria_3">
                            <option value="">Marca</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca_memoria_3']) && $marca['id_marca'] == $equipmentData['marca_memoria_3']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['marca']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie_memoria_3'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Memoria 4:</label>
                        <select class="form-control" name="marca_memoria_4">
                            <option value="">Marca</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca_memoria_4']) && $marca['id_marca'] == $equipmentData['marca_memoria_4']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['marca']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie_memoria_4'] ?? ''); ?>" readonly>
                        <select class="form-control" name="tipo_memoria">
                            <option value="">Tipo de Memoria</option>
                            <?php foreach ($tiposMemoria as $tipo): ?>
                                <option value="<?php echo $tipo['id_tmemoria']; ?>" <?php echo (isset($equipmentData['tipo_memoria']) && $tipo['id_tmemoria'] == $equipmentData['tipo_memoria']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tipo['tp_memoria']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="section-title">Datos del Monitor</div>
            <div class="form-group horizontal-group">
                <select class="form-control" name="marca_monitor">
                    <option value="">Marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['id_marca']; ?>" <?php echo (isset($equipmentData['marca_monitor']) && $marca['id_marca'] == $equipmentData['marca_monitor']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['marca']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" class="form-control" placeholder="Número de Serie" value="<?php echo htmlspecialchars($equipmentData['serie_monitor'] ?? ''); ?>" readonly>
                <select class="form-control" name="modelo_monitor">
                    <option value="">Modelo</option>
                    <?php foreach ($modelos as $modelo): ?>
                        <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo (isset($equipmentData['modelo_monitor']) && $modelo['id_modelo'] == $equipmentData['modelo_monitor']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($modelo['modelo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="section-title">Descripción de La Falla por el Usuario</div>
            <div class="form-group">
                <textarea class="form-control" rows="4" name="falla_reportada"></textarea>
            </div>

            <div class="section-title">Acciones Realizadas</div>
            <div class="form-group">
                <div class="horizontal-group">
                    <div>
                        <label>Nombre y Firma de Recibido:</label>
                        <input type="text" class="form-control" placeholder="Nombre del Solicitante">
                        <div class="divider"></div>
                        <label>Nombre:</label>
                        <input type="text" class="form-control" placeholder="Nombre">
                        <div class="divider"></div>
                        <label>Firma:</label>
                        <input type="text" class="form-control" placeholder="Firma">
                    </div>
                    <div>
                        <label>Entregado:</label>
                        <input type="text" class="form-control" placeholder="Nombre del Solicitante">
                        <div class="divider"></div>
                        <label>Nombre:</label>
                        <input type="text" class="form-control" placeholder="Nombre">
                        <div class="divider"></div>
                        <label>Firma:</label>
                        <input type="text" class="form-control" placeholder="Firma">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Enviar Reporte</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm
    