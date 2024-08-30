<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reporte</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Formulario de Reporte</h2>
        <form action="guardar_reporte.php" method="POST" id="reporteForm">
            <div class="form-group">
                <label for="npesonal">Número Personal:</label>
                <input type="text" id="npesonal" name="npesonal" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="inventario">Inventario:</label>
                <input type="text" id="inventario" name="inventario" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tipo_equipo">Tipo de Equipo:</label>
                <input type="text" id="tipo_equipo" name="tipo_equipo" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" id="modelo" name="modelo" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca_dd1">Marca Disco Duro 1:</label>
                <input type="text" id="marca_dd1" name="marca_dd1" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="modelo_dd1">Modelo Disco Duro 1:</label>
                <input type="text" id="modelo_dd1" name="modelo_dd1" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca_dd2">Marca Disco Duro 2:</label>
                <input type="text" id="marca_dd2" name="marca_dd2" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="modelo_dd2">Modelo Disco Duro 2:</label>
                <input type="text" id="modelo_dd2" name="modelo_dd2" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca_memoria_1">Marca Memoria 1:</label>
                <input type="text" id="marca_memoria_1" name="marca_memoria_1" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca_memoria_2">Marca Memoria 2:</label>
                <input type="text" id="marca_memoria_2" name="marca_memoria_2" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca_memoria_3">Marca Memoria 3:</label>
                <input type="text" id="marca_memoria_3" name="marca_memoria_3" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca_memoria_4">Marca Memoria 4:</label>
                <input type="text" id="marca_memoria_4" name="marca_memoria_4" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="tipo_memoria">Tipo de Memoria:</label>
                <input type="text" id="tipo_memoria" name="tipo_memoria" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="marca_monitor">Marca Monitor:</label>
                <input type="text" id="marca_monitor" name="marca_monitor" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="modelo_monitor">Modelo Monitor:</label>
                <input type="text" id="modelo_monitor" name="modelo_monitor" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="falla_reportada">Falla Reportada:</label>
                <input type="text" id="falla_reportada" name="falla_reportada" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reparacion">Reparación:</label>
                <input type="text" id="reparacion" name="reparacion" class="form-control" required>
            </div>
            <input type="hidden" id="id_docente" name="id_docente">
            <button type="submit" class="btn btn-primary">Guardar Reporte</button>
        </form>
    </div>

    <script>
    document.getElementById('inventario').addEventListener('blur', function() {
        const inventario = this.value;
        const npesonal = document.getElementById('npesonal').value;

        if (inventario && npesonal) {
            fetch(`get_datos_combinados.php?inventario=${inventario}&npesonal=${npesonal}`)
                .then(response => response.json())
                .then(data => {
                    // Rellenar campos del equipo
                    document.getElementById('tipo_equipo').value = data.tipo_equipo;
                    document.getElementById('marca').value = data.marca;
                    document.getElementById('modelo').value = data.modelo;
                    document.getElementById('ubicacion').value = data.ubicacion;
                    document.getElementById('marca_dd1').value = data.marca_dd1;
                    document.getElementById('modelo_dd1').value = data.modelo_dd1;
                    document.getElementById('marca_dd2').value = data.marca_dd2;
                    document.getElementById('modelo_dd2').value = data.modelo_dd2;
                    document.getElementById('marca_memoria_1').value = data.marca_memoria_1;
                    document.getElementById('marca_memoria_2').value = data.marca_memoria_2;
                    document.getElementById('marca_memoria_3').value = data.marca_memoria_3;
                    document.getElementById('marca_memoria_4').value = data.marca_memoria_4;
                    document.getElementById('tipo_memoria').value = data.tipo_memoria;
                    document.getElementById('marca_monitor').value = data.marca_monitor;
                    document.getElementById('modelo_monitor').value = data.modelo_monitor;

                    // Rellenar campos del solicitante
                    document.getElementById('id_docente').value = data.id_docente;
                })
                .catch(error => console.error('Error:', error));
        }
    });
    </script>
</body>
</html>
