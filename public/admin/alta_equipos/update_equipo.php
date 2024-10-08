<?php
require_once '../../../config/database.php';

// Verificar si se ha enviado el inventario del equipo
if (!isset($_GET['inventario'])) {
    die("Error: Inventario no proporcionado.");
}

$inventario_original = $_GET['inventario']; // Inventario original
try {
    // Consultas para obtener datos de la base de datos
    $ubicaciones = $pdo->query("SELECT id_ubicacion, ubicacion FROM t_ubicacion")->fetchAll();
    $tipos_equipo = $pdo->query("SELECT id_tipo_equipo, tipo_equipo FROM t_tipo_equipo")->fetchAll();
    $marcas = $pdo->query("SELECT id_marca, marca FROM t_marca_equipo")->fetchAll();
    $modelos = $pdo->query("SELECT id_modelo, modelo FROM t_modelo_equipo")->fetchAll();
    $procesadores = $pdo->query("SELECT id_procesador, procesador FROM t_procesador")->fetchAll();
    $memorias = $pdo->query("SELECT id_memoria, memoria FROM t_memoria")->fetchAll();
    $tipos_memoria = $pdo->query("SELECT id_tmemoria, tp_memoria FROM tipo_memoria")->fetchAll();
    $facultades = $pdo->query("SELECT id_facultad, facultad FROM t_facultad")->fetchAll();

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Obtener datos del formulario
            $inventario_nuevo = isset($_POST['inventario']) ? trim($_POST['inventario']) : $inventario_original;
            
            // Verificar si el nuevo inventario ya existe en la base de datos (evitar duplicados)
            if ($inventario_original !== $inventario_nuevo) {
                $sql_check = "SELECT inventario FROM t_alta_equipo WHERE inventario = :inventario_nuevo";
                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':inventario_nuevo', $inventario_nuevo, PDO::PARAM_STR);
                $stmt_check->execute();
                
                if ($stmt_check->rowCount() > 0) {
                    echo "El inventario ya está registrado. Intenta con otro.";
                    exit;
                }
            }

            // Manejar las subidas de archivos
            $foto_disco_duro = isset($_FILES['foto_disco_duro']) && $_FILES['foto_disco_duro']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['foto_disco_duro']['tmp_name']) : null;
            $foto_memoria = isset($_FILES['foto_memoria']) && $_FILES['foto_memoria']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['foto_memoria']['tmp_name']) : null;

            // Consulta de actualización
            $sql = "UPDATE t_alta_equipo SET 
                inventario = :inventario_nuevo, 
                serie = :serie, 
                activo = :activo, 
                nombre_equipo = :nombre_equipo, 
                ubicacion = :ubicacion, 
                tipo_equipo = :tipo_equipo, 
                marca = :marca, 
                modelo = :modelo,
                procesador = :procesador, 
                memoria_total = :memoria_total, 
                disco_duro_1 = :disco_duro_1, 
                marca_dd1 = :marca_dd1, 
                serie_dd1 = :serie_dd1, 
                modelo_dd1 = :modelo_dd1,
                disco_duro_2 = :disco_duro_2, 
                marca_dd2 = :marca_dd2, 
                serie_dd2 = :serie_dd2, 
                modelo_dd2 = :modelo_dd2, 
                marca_memoria_1 = :marca_memoria_1, 
                serie_memoria_1 = :serie_memoria_1,
                marca_memoria_2 = :marca_memoria_2, 
                serie_memoria_2 = :serie_memoria_2, 
                marca_memoria_3 = :marca_memoria_3, 
                serie_memoria_3 = :serie_memoria_3,
                marca_memoria_4 = :marca_memoria_4, 
                serie_memoria_4 = :serie_memoria_4, 
                marca_monitor = :marca_monitor, 
                modelo_monitor = :modelo_monitor,
                serie_monitor = :serie_monitor, 
                foto_disco_duro = :foto_disco_duro, 
                foto_memoria = :foto_memoria, 
                id_facultad = :id_facultad 
            WHERE inventario = :inventario_original";

            // Preparar la consulta
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':inventario_nuevo' => $inventario_nuevo,
                ':inventario_original' => $inventario_original,
                ':serie' => $_POST['serie'],
                ':activo' => $_POST['activo'],
                ':nombre_equipo' => $_POST['nombre_equipo'],
                ':ubicacion' => $_POST['ubicacion'] !== '' ? $_POST['ubicacion'] : null,
                ':tipo_equipo' => $_POST['tipo_equipo'] !== '' ? $_POST['tipo_equipo'] : null,
                ':marca' => $_POST['marca'] !== '' ? $_POST['marca'] : null,
                ':modelo' => $_POST['modelo'] !== '' ? $_POST['modelo'] : null,
                ':procesador' => $_POST['procesador'] !== '' ? $_POST['procesador'] : null,
                ':memoria_total' => $_POST['memoria_total'] !== '' ? $_POST['memoria_total'] : null,
                ':disco_duro_1' => $_POST['disco_duro_1'],
                ':marca_dd1' => $_POST['marca_dd1'] !== '' ? $_POST['marca_dd1'] : null,
                ':serie_dd1' => $_POST['serie_dd1'],
                ':modelo_dd1' => $_POST['modelo_dd1'] !== '' ? $_POST['modelo_dd1'] : null,
                ':disco_duro_2' => $_POST['disco_duro_2'],
                ':marca_dd2' => $_POST['marca_dd2'] !== '' ? $_POST['marca_dd2'] : null,
                ':serie_dd2' => $_POST['serie_dd2'],
                ':modelo_dd2' => $_POST['modelo_dd2'] !== '' ? $_POST['modelo_dd2'] : null,
                ':marca_memoria_1' => $_POST['marca_memoria_1'] !== '' ? $_POST['marca_memoria_1'] : null,
                ':serie_memoria_1' => $_POST['serie_memoria_1'],
                ':marca_memoria_2' => $_POST['marca_memoria_2'] !== '' ? $_POST['marca_memoria_2'] : null,
                ':serie_memoria_2' => $_POST['serie_memoria_2'],
                ':marca_memoria_3' => $_POST['marca_memoria_3'] !== '' ? $_POST['marca_memoria_3'] : null,
                ':serie_memoria_3' => $_POST['serie_memoria_3'],
                ':marca_memoria_4' => $_POST['marca_memoria_4'] !== '' ? $_POST['marca_memoria_4'] : null,
                ':serie_memoria_4' => $_POST['serie_memoria_4'],
                ':marca_monitor' => $_POST['marca_monitor'] !== '' ? $_POST['marca_monitor'] : null,
                ':modelo_monitor' => $_POST['modelo_monitor'] !== '' ? $_POST['modelo_monitor'] : null,
                ':serie_monitor' => $_POST['serie_monitor'],
                ':foto_disco_duro' => $foto_disco_duro,
                ':foto_memoria' => $foto_memoria,
                ':id_facultad' => !empty($_POST['facultad']) ? $_POST['facultad'] : null,
            ]);

            // Redirigir después de la actualización
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
