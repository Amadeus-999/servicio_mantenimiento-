<?php
require '../../../config/database.php';

$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
$docente = null;
$numero_reporte = null;

// Obtener el próximo número de reporte
$stmt_numero_reporte = $pdo->query("SELECT MAX(id_reporte) AS last_report FROM t_reporte");
$row = $stmt_numero_reporte->fetch(PDO::FETCH_ASSOC);
$ultimo_numero_reporte = $row['last_report'] ? $row['last_report'] + 1 : 1;
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
                        <input type="text" class="form-control" id="numero_reporte" name="numero_reporte" value="<?php echo $ultimo_numero_reporte; ?>" readonly>
                    </div>

                </div>
            </div>

            <!-- APARTADO  DE DATOS SOIICITANTE-->
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
                    <input type="text" class="form-control" id="num_serie" name="num_serie" readonly>
                </div>
                <div class="form-group">
                    <label for="tipo_equipo">Tipo de Equipo</label>
                    <input type="text" class="form-control" id="tipo_equipo" name="tipo_equipo" readonly>
                </div>
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" readonly>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" readonly>
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicación</label>
                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" readonly>
                </div>
            </div>

            <div class="section-title">Datos del Disco Duro 1</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="capacidad_dd1">Capacidad</label>
                    <input type="text" class="form-control" id="capacidad_dd1" name="capacidad_dd1" readonly>
                </div>
                <div class="form-group">
                    <label for="marca_dd1">Marca</label>
                    <input type="text" class="form-control" id="marca_dd1" name="marca_dd1" readonly>
                </div>
                <div class="form-group">
                    <label for="serie_dd1">Número de Serie</label>
                    <input type="text" class="form-control" id="serie_dd1" name="serie_dd1" readonly>
                </div>
                <div class="form-group">
                    <label for="modelo_dd1">Modelo</label>
                    <input type="text" class="form-control" id="modelo_dd1" name="modelo_dd1" readonly>
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
                    <input type="text" class="form-control" id="marca_dd2" name="marca_dd2" readonly>
                </div>
                <div class="form-group">
                    <label for="serie_dd2">Número de Serie</label>
                    <input type="text" class="form-control" id="serie_dd2" name="serie_dd2" value="<?php echo $equipo['serie_dd2'] ?? ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="modelo_dd2">Modelo</label>
                    <input type="text" class="form-control" id="modelo_dd2" name="modelo_dd2" readonly>
                </div>
            </div>

            <div class="section-title">Datos de Memorias</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="memoria1">Memoria 1</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria1">Marca</label>
                            <input type="text" class="form-control" id="marca_memoria1" name="marca_memoria1" readonly>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria1">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria1" name="serie_memoria1" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="memoria2">Memoria 2</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria2">Marca</label>
                            <input type="text" class="form-control" id="marca_memoria2" name="marca_memoria2" readonly>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria2">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria2" name="serie_memoria2" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="memoria3">Memoria 3</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria3">Marca</label>
                            <input type="text" class="form-control" id="marca_memoria3" name="marca_memoria3" readonly>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria3">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria3" name="serie_memoria3" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="memoria4">Memoria 4</label>
                    <div class="horizontal-group">
                        <div class="form-group">
                            <label for="marca_memoria4">Marca</label>
                            <input type="text" class="form-control" id="marca_memoria4" name="marca_memoria4" readonly>
                        </div>
                        <div class="form-group">
                            <label for="serie_memoria4">Número de Serie</label>
                            <input type="text" class="form-control" id="serie_memoria4" name="serie_memoria4" value="<?php echo $equipo['serie_memoria_4'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tipo_memoria">Tipo de Memoria</label>
                            <input type="text" class="form-control" id="tipo_memoria" name="tipo_memoria" readonly>
                        </div>
                    </div>
                </div>
            </div>


            <div class="section-title">Datos del Monitor</div>
            <div class="horizontal-group">
                <div class="form-group">
                    <label for="marca_monitor">Marca</label>
                    <input type="text" class="form-control" id="marca_monitor" name="marca_monitor" readonly>
                </div>
                <div class="form-group">
                    <label for="serie_monitor">Número de Serie</label>
                    <input type="text" class="form-control" id="serie_monitor" name="serie_monitor" readonly>
                </div>
                <div class="form-group">
                    <label for="modelo_monitor">Modelo</label>
                    <input type="text" class="form-control" id="modelo_monitor" name="modelo_monitor" readonly>
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
            <!-- FIRMAS DE RECIBIDO -->
            <div class="signature-section">
                <div class="form-group">
                    <input type="text" class="form-control" id="nombre1" name="nombre1" value="<?php echo $docente['nombre'] ?? ''; ?>" readonly>
                    <hr>
                    <label>Nombre y Firma de Recibido</label>

                </div>
                <div class="form-group">
                    <div class="signature-box">
                        <input type="text" class="form-control" id="entregado" name="entregado" value="<?php echo $docente['nombre'] ?? ''; ?>" readonly>
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
                        $('#facultad').val(docente.facultad);
                        $('#nombre1').val(docente.nombre);
                        $('#entregado').val(docente.nombre);

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
                        /* Sect 1 */
                        $('#activo').val(equipo.activo);
                        $('#num_serie').val(equipo.serie);
                        $('#tipo_equipo').val(equipo.tipo_equipo_nombre);
                        $('#marca').val(equipo.marca_equipo);
                        $('#modelo').val(equipo.modelo_equipo);
                        $('#ubicacion').val(equipo.ubicacion_nombre);
                        /* Sect Disc 1 */
                        $('#capacidad_dd1').val(equipo.disco_duro_1);
                        $('#marca_dd1').val(equipo.marca_dd1);
                        $('#serie_dd1').val(equipo.serie_dd1);
                        $('#modelo_dd1').val(equipo.modelo_dd1);
                        /* Sect Disc 2 */
                        $('#capacidad_dd2').val(equipo.disco_duro_2);
                        $('#marca_dd2').val(equipo.marca_dd2);
                        $('#serie_dd2').val(equipo.serie_dd2);
                        $('#modelo_dd2').val(equipo.modelo_dd2);
                        /* Sect memoria */

                        $('#marca_memoria1').val(equipo.marca_memoria_1);
                        $('#serie_memoria1').val(equipo.serie_memoria_1);
                        $('#marca_memoria2').val(equipo.marca_memoria_2);
                        $('#serie_memoria2').val(equipo.serie_memoria_2);
                        $('#marca_memoria3').val(equipo.marca_memoria_3);
                        $('#serie_memoria3').val(equipo.serie_memoria_3);
                        $('#marca_memoria4').val(equipo.marca_memoria_4);
                        $('#serie_memoria4').val(equipo.serie_memoria_4);
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