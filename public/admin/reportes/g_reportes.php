<?php
require '../../../config/database.php';

$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
$docente = null;
if (isset($_GET['npesonal'])) {
    $npesonal = $_GET['npesonal'];

    // Obtener datos del docente
    $stmt_docente = $pdo->prepare("SELECT * FROM t_docente WHERE npesonal = ?");
    $stmt_docente->execute([$npesonal]);
    $docente = $stmt_docente->fetch(PDO::FETCH_ASSOC);
}
if ($docente) {
    // Si $docente no es null, accede a sus elementos de manera segura
    $nombre_solicitante = $docente['nombre'] . ' ' . $docente['apellido_p'] . ' ' . $docente['apellido_m'];
} else {
    // Manejo del caso cuando $docente es null
    $nombre_solicitante = 'Docente no encontrado'; // O cualquier mensaje predeterminado que prefieras
}



if (isset($_GET['inventario'])) {
    $inventario = $_GET['inventario'];

    // Obtener datos del equipo
    $stmt_equipo = $pdo->prepare("SELECT * FROM t_alta_equipo WHERE inventario = ?");
    $stmt_equipo->execute([$inventario]);
    $equipo = $stmt_equipo->fetch(PDO::FETCH_ASSOC);

    // Obtener datos para los desplegables
    $stmt_tipo = $pdo->query("SELECT * FROM t_tipo_equipo");
    $tipos = $stmt_tipo->fetchAll(PDO::FETCH_ASSOC);

    $stmt_marca = $pdo->query("SELECT * FROM t_marca_equipo");
    $marcas = $stmt_marca->fetchAll(PDO::FETCH_ASSOC);

    $stmt_modelo = $pdo->query("SELECT * FROM t_modelo_equipo");
    $modelos = $stmt_modelo->fetchAll(PDO::FETCH_ASSOC);

    $stmt_ubicacion = $pdo->query("SELECT * FROM t_ubicacion");
    $ubicaciones = $stmt_ubicacion->fetchAll(PDO::FETCH_ASSOC);

    $stmt_tipo_memoria = $pdo->query("SELECT * FROM tipo_memoria");
    $tipos_memoria = $stmt_tipo_memoria->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reporte de Servicio Técnico</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
        }

        .header-logo {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-logo img {
            width: 100px;
        }

        .section-title {
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .horizontal-group {
            display: flex;
            justify-content: space-between;
        }

        .horizontal-group .form-group {
            flex: 1;
            margin-right: 10px;
        }

        .form-divider {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
        }

        .signature-section div {
            width: 48%;
        }

        .signature-section hr {
            margin-top: 10px;
        }
    </style>
</head>

<body>
<?php if ($mensaje): ?>
            <div class="alert alert-info">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
    <div class="container">

        <form action="guardar_reporte.php" method="POST">
            <div class="header-logo">
                <img src="../../../assets/images/logo.webp" alt="Logo de la Institución">
                <div>
                    <div class="form-group">
                        <label for="fecha_reportada">Fecha de Solicitud</label>
                        <input type="date" class="form-control" id="fecha_reportada" value="<?php echo date('Y-m-d'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="numero_reporte">Número de Reporte</label>
                        <input type="text" class="form-control" id="numero_reporte" name="numero_reporte">
                    </div>
                </div>
            </div>

            <div class="section-title">Datos del Solicitante</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="npesonal">Número Personal</label>
                    <input type="text" class="form-control" id="npesonal" name="npesonal" value="<?php echo $docente['npesonal'] ?? ''; ?>" oninput="loadDocenteData(this.value)">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $docente['nombre'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="apellido_p">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="<?php echo $docente['apellido_p'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="apellido_m">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="<?php echo $docente['apellido_m'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $docente['correo'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="extension">Extensión</label>
                    <input type="text" class="form-control" id="extension" name="extension" value="<?php echo $docente['extension'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="facultad">Facultad</label>
                    <input type="text" class="form-control" id="facultad" name="facultad" value="<?php echo $docente['id_facultad'] ?? ''; ?>" readonly>
                </div>
            </div>

            <div class="section-title">Datos del Equipo</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="inventario">Inventario</label>
                    <input type="text" class="form-control" id="inventario" name="inventario" value="<?php echo $equipo['inventario'] ?? ''; ?>" oninput="loadEquipoData(this.value)">
                </div>
                <div class="form-group">
                    <label for="activo">Activo</label>
                    <input type="text" class="form-control" id="activo" name="activo" value="<?php echo $equipo['activo'] ?? ''; ?>" readonly>
                </div>
            </div>

            <div class="horizontal-group">
                <div class="form-group">
                    <label for="num_serie">Número de Serie</label>
                    <input type="text" class="form-control" id="num_serie" name="num_serie" value="<?php echo $equipo['serie'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="tipo_equipo">Tipo de Equipo</label>
                    <select class="form-control" id="tipo_equipo" name="tipo_equipo">
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?php echo $tipo['id_tipo_equipo']; ?>" <?php echo ($equipo['tipo_equipo'] == $tipo['id_tipo_equipo']) ? 'selected' : ''; ?>>
                                <?php echo $tipo['tipo_equipo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <select class="form-control" id="marca" name="marca">
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <select class="form-control" id="modelo" name="modelo">
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicación</label>
                    <select class="form-control" id="ubicacion" name="ubicacion">
                        <?php foreach ($ubicaciones as $ubicacion): ?>
                            <option value="<?php echo $ubicacion['id_ubicacion']; ?>" <?php echo ($equipo['ubicacion'] == $ubicacion['id_ubicacion']) ? 'selected' : ''; ?>>
                                <?php echo $ubicacion['ubicacion']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="section-title">Datos del Disco Duro 1</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="capacidad_dd1">Capacidad</label>
                    <input type="text" class="form-control" id="capacidad_dd1" name="capacidad_dd1" value="<?php echo $equipo['disco_duro_1'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="marca_dd1">Marca</label>
                    <select class="form-control" id="marca_dd1" name="marca_dd1">
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_dd1'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_dd1">Número de Serie</label>
                    <input type="text" class="form-control" id="serie_dd1" name="serie_dd1" value="<?php echo $equipo['serie_dd1'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="modelo_dd1">Modelo</label>
                    <select class="form-control" id="modelo_dd1" name="modelo_dd1">
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo_dd1'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="section-title">Datos del Disco Duro 2</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="capacidad_dd2">Capacidad</label>
                    <input type="text" class="form-control" id="capacidad_dd2" name="capacidad_dd2" value="<?php echo $equipo['disco_duro_2'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="marca_dd2">Marca</label>
                    <select class="form-control" id="marca_dd2" name="marca_dd2">
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_dd2'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_dd2">Número de Serie</label>
                    <input type="text" class="form-control" id="serie_dd2" name="serie_dd2" value="<?php echo $equipo['serie_dd2'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="modelo_dd2">Modelo</label>
                    <select class="form-control" id="modelo_dd2" name="modelo_dd2">
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo_dd2'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="section-title">Datos de Memorias</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="memoria1">Memoria 1</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria1">Marca</label>
                            <select class="form-control" id="marca_memoria1" name="marca_memoria1">
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_1'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                        <?php echo $marca['marca']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria1">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria1" name="serie_memoria1" value="<?php echo $equipo['serie_memoria_1'] ?? ''; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="memoria2">Memoria 2</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria2">Marca</label>
                            <select class="form-control" id="marca_memoria2" name="marca_memoria2">
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_2'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                        <?php echo $marca['marca']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria2">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria2" name="serie_memoria2" value="<?php echo $equipo['serie_memoria_2'] ?? ''; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="memoria3">Memoria 3</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria3">Marca</label>
                            <select class="form-control" id="marca_memoria3" name="marca_memoria3">
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_3'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                        <?php echo $marca['marca']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria3">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria3" name="serie_memoria3" value="<?php echo $equipo['serie_memoria_3'] ?? ''; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="memoria4">Memoria 4</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria4">Marca</label>
                            <select class="form-control" id="marca_memoria4" name="marca_memoria4">
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_memoria_4'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                        <?php echo $marca['marca']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria4">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria4" name="serie_memoria4" value="<?php echo $equipo['serie_memoria_4'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tipo_memoria">Tipo de Memoria</label>
                            <select class="form-control" id="tipo_memoria" name="tipo_memoria">
                                <?php foreach ($tipos_memoria as $tipo_memoria): ?>
                                    <option value="<?php echo $tipo_memoria['id_tmemoria']; ?>" <?php echo ($equipo['tipo_memoria'] == $tipo_memoria['id_tmemoria']) ? 'selected' : ''; ?>>
                                        <?php echo $tipo_memoria['tp_memoria']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="section-title">Datos del Monitor</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="marca_monitor">Marca</label>
                    <select class="form-control" id="marca_monitor" name="marca_monitor">
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" <?php echo ($equipo['marca_monitor'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo $marca['marca']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serie_monitor">Número de Serie</label>
                    <input type="text" class="form-control" id="serie_monitor" name="serie_monitor" value="<?php echo $equipo['serie_monitor'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="modelo_monitor">Modelo</label>
                    <select class="form-control" id="modelo_monitor" name="modelo_monitor">
                        <?php foreach ($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>" <?php echo ($equipo['modelo_monitor'] == $modelo['id_modelo']) ? 'selected' : ''; ?>>
                                <?php echo $modelo['modelo']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="section-title">Descripción de La Falla por el Usuario</div>
            <div class="form-group">
                <label for="falla_reportada">Descripción</label>
                <textarea class="form-control" id="falla_reportada" name="falla_reportada" rows="4" required></textarea>
            </div>

            <div class="section-title">Acciones Realizadas</div>
            <div class="form-group">
                <label for="acciones_realizadas">Acciones</label>
                <textarea class="form-control" id="acciones_realizadas" name="acciones_realizadas" rows="4"></textarea>
            </div>

            <div class="signature-section">
                <div class="form-group">
                    <label for="nombre_solicitante">Nombre del Solicitante</label>
                    <input type="text" class="form-control" id="nombre_solicitante" name="nombre_solicitante"
                        value="<?php echo isset($docente) ? $docente['nombre'] . ' ' . $docente['apellido_p'] . ' ' . $docente['apellido_m'] : 'Docente no encontrado'; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="firma_recibido">Nombre y Firma de Recibido</label>
                    <div class="signature-box">
                        <input type="text" class="form-control" id="firma_recibido" name="firma_recibido">
                        <hr>
                        <label>Nombre y Firma de Recibido</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="entregado">Entregado</label>
                    <div class="signature-box">
                        <input type="text" class="form-control" id="entregado" name="entregado">
                        <hr>
                        <label>Entregado</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Guardar Reporte</button>
            </div>
        </form>
    </div>

    <!-- Scripts necesarios -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Lógica para cargar datos del solicitante al ingresar número personal
            $('#npesonal').on('change', function() {
                var npesonal = $(this).val();
                $.ajax({
                    url: 'cargar_solicitante.php',
                    type: 'GET',
                    data: {
                        npesonal: npesonal
                    },
                    success: function(response) {
                        console.log(response); // Verifica la respuesta aquí
                        var docente = JSON.parse(response);
                        $('#nombre').val(docente.nombre);
                        $('#apellido_p').val(docente.apellido_p);
                        $('#apellido_m').val(docente.apellido_m);
                        $('#correo').val(docente.correo);
                        $('#extension').val(docente.extension);
                        $('#facultad').val(docente.id_facultad);
                    }
                });

            });

            // Lógica para cargar datos del equipo al ingresar número de inventario
            $('#inventario').on('change', function() {
                var inventario = $(this).val();
                $.ajax({
                    url: 'cargar_equipo.php',
                    type: 'GET',
                    data: {
                        inventario: inventario
                    },
                    success: function(response) {
                        var equipo = JSON.parse(response);
                        $('#serie').val(equipo.serie);
                        $('#activo').val(equipo.activo);
                        $('#tipo_equipo').val(equipo.tipo_equipo);
                        $('#marca').val(equipo.marca);
                        $('#modelo').val(equipo.modelo);
                        $('#ubicacion').val(equipo.ubicacion);

                        $('#disco_duro_1').val(equipo.disco_duro_1);
                        $('#marca_dd1').val(equipo.marca_dd1);
                        $('#serie_dd1').val(equipo.serie_dd1);
                        $('#modelo_dd1').val(equipo.modelo_dd1);

                        $('#disco_duro_2').val(equipo.disco_duro_2);
                        $('#marca_dd2').val(equipo.marca_dd2);
                        $('#serie_dd2').val(equipo.serie_dd2);
                        $('#modelo_dd2').val(equipo.modelo_dd2);

                        $('#marca_memoria_1').val(equipo.marca_memoria_1);
                        $('#serie_memoria_1').val(equipo.serie_memoria_1);
                        $('#marca_memoria_2').val(equipo.marca_memoria_2);
                        $('#serie_memoria_2').val(equipo.serie_memoria_2);
                        $('#marca_memoria_3').val(equipo.marca_memoria_3);
                        $('#serie_memoria_3').val(equipo.serie_memoria_3);
                        $('#marca_memoria_4').val(equipo.marca_memoria_4);
                        $('#serie_memoria_4').val(equipo.serie_memoria_4);
                        $('#tipo_memoria').val(equipo.tipo_memoria);

                        $('#marca_monitor').val(equipo.marca_monitor);
                        $('#serie_monitor').val(equipo.serie_monitor);
                        $('#modelo_monitor').val(equipo.modelo_monitor);
                    }
                });
            });
        });
    </script>

</body>

</html>