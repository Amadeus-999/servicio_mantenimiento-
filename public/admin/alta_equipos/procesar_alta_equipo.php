<?php
require_once '../../../config/database.php';

try {
    // Recoger datos del formulario
    $inventario = $_POST['inventario'];
    $serie = $_POST['serie'];
    $activo = $_POST['activo'];
    $nombre_equipo = $_POST['nombre_equipo'];
    $ubicacion = $_POST['ubicacion'];
    $tipo_equipo = $_POST['tipo_equipo'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $procesador = $_POST['procesador'];
    $memoria_total = $_POST['memoria_total'];
    $disco_duro_1 = $_POST['disco_duro_1'];
    $marca_dd1 = $_POST['marca_dd1'];
    $serie_dd1 = $_POST['serie_dd1'];
    $modelo_dd1 = $_POST['modelo_dd1'];
    $disco_duro_2 = $_POST['disco_duro_2'];
    $marca_dd2 = $_POST['marca_dd2'];
    $serie_dd2 = $_POST['serie_dd2'];
    $modelo_dd2 = $_POST['modelo_dd2'];
    $marca_memoria_1 = $_POST['marca_memoria_1'];
    $serie_memoria_1 = $_POST['serie_memoria_1'];
    $marca_memoria_2 = $_POST['marca_memoria_2'];
    $serie_memoria_2 = $_POST['serie_memoria_2'];
    $marca_memoria_3 = $_POST['marca_memoria_3'];
    $serie_memoria_3 = $_POST['serie_memoria_3'];
    $marca_memoria_4 = $_POST['marca_memoria_4'];
    $serie_memoria_4 = $_POST['serie_memoria_4'];
    $tipo_memoria = $_POST['tipo_memoria'];
    $marca_monitor = $_POST['marca_monitor'];
    $modelo_monitor = $_POST['modelo_monitor'];
    $serie_monitor = $_POST['serie_monitor'];

    // Preparar la consulta SQL
    $sql = "INSERT INTO alta_equipo (
                inventario, serie, activo, nombre_equipo, ubicacion, tipo_equipo, marca, modelo, procesador, 
                memoria_total, disco_duro_1, marca_dd1, serie_dd1, modelo_dd1, disco_duro_2, marca_dd2, 
                serie_dd2, modelo_dd2, marca_memoria_1, serie_memoria_1, marca_memoria_2, serie_memoria_2, 
                marca_memoria_3, serie_memoria_3, marca_memoria_4, serie_memoria_4, tipo_memoria, marca_monitor, 
                modelo_monitor, serie_monitor, foto_disco_duro, foto_memoria
            ) VALUES (
                :inventario, :serie, :activo, :nombre_equipo, :ubicacion, :tipo_equipo, :marca, :modelo, :procesador, 
                :memoria_total, :disco_duro_1, :marca_dd1, :serie_dd1, :modelo_dd1, :disco_duro_2, :marca_dd2, 
                :serie_dd2, :modelo_dd2, :marca_memoria_1, :serie_memoria_1, :marca_memoria_2, :serie_memoria_2, 
                :marca_memoria_3, :serie_memoria_3, :marca_memoria_4, :serie_memoria_4, :tipo_memoria, :marca_monitor, 
                :modelo_monitor, :serie_monitor, :foto_disco_duro, :foto_memoria
            )";

    $stmt = $pdo->prepare($sql);

    // Vincular los parámetros
    $stmt->bindParam(':inventario', $inventario, PDO::PARAM_INT);
    $stmt->bindParam(':serie', $serie, PDO::PARAM_STR);
    $stmt->bindParam(':activo', $activo, PDO::PARAM_STR);
    $stmt->bindParam(':nombre_equipo', $nombre_equipo, PDO::PARAM_STR);
    $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_equipo', $tipo_equipo, PDO::PARAM_INT);
    $stmt->bindParam(':marca', $marca, PDO::PARAM_INT);
    $stmt->bindParam(':modelo', $modelo, PDO::PARAM_INT);
    $stmt->bindParam(':procesador', $procesador, PDO::PARAM_INT);
    $stmt->bindParam(':memoria_total', $memoria_total, PDO::PARAM_INT);
    $stmt->bindParam(':disco_duro_1', $disco_duro_1, PDO::PARAM_STR);
    $stmt->bindParam(':marca_dd1', $marca_dd1, PDO::PARAM_INT);
    $stmt->bindParam(':serie_dd1', $serie_dd1, PDO::PARAM_STR);
    $stmt->bindParam(':modelo_dd1', $modelo_dd1, PDO::PARAM_INT);
    $stmt->bindParam(':disco_duro_2', $disco_duro_2, PDO::PARAM_STR);
    $stmt->bindParam(':marca_dd2', $marca_dd2, PDO::PARAM_INT);
    $stmt->bindParam(':serie_dd2', $serie_dd2, PDO::PARAM_STR);
    $stmt->bindParam(':modelo_dd2', $modelo_dd2, PDO::PARAM_INT);
    $stmt->bindParam(':marca_memoria_1', $marca_memoria_1, PDO::PARAM_INT);
    $stmt->bindParam(':serie_memoria_1', $serie_memoria_1, PDO::PARAM_STR);
    $stmt->bindParam(':marca_memoria_2', $marca_memoria_2, PDO::PARAM_INT);
    $stmt->bindParam(':serie_memoria_2', $serie_memoria_2, PDO::PARAM_STR);
    $stmt->bindParam(':marca_memoria_3', $marca_memoria_3, PDO::PARAM_INT);
    $stmt->bindParam(':serie_memoria_3', $serie_memoria_3, PDO::PARAM_STR);
    $stmt->bindParam(':marca_memoria_4', $marca_memoria_4, PDO::PARAM_INT);
    $stmt->bindParam(':serie_memoria_4', $serie_memoria_4, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_memoria', $tipo_memoria, PDO::PARAM_INT);
    $stmt->bindParam(':marca_monitor', $marca_monitor, PDO::PARAM_INT);
    $stmt->bindParam(':modelo_monitor', $modelo_monitor, PDO::PARAM_INT);
    $stmt->bindParam(':serie_monitor', $serie_monitor, PDO::PARAM_STR);

    // Manejo de archivos
    if (isset($_FILES['foto_disco_duro']) && $_FILES['foto_disco_duro']['error'] === UPLOAD_ERR_OK) {
        $stmt->bindParam(':foto_disco_duro', file_get_contents($_FILES['foto_disco_duro']['tmp_name']), PDO::PARAM_LOB);
    } else {
        $stmt->bindValue(':foto_disco_duro', null, PDO::PARAM_NULL);
    }

    if (isset($_FILES['foto_memoria']) && $_FILES['foto_memoria']['error'] === UPLOAD_ERR_OK) {
        $stmt->bindParam(':foto_memoria', file_get_contents($_FILES['foto_memoria']['tmp_name']), PDO::PARAM_LOB);
    } else {
        $stmt->bindValue(':foto_memoria', null, PDO::PARAM_NULL);
    }

    $stmt->execute();
    header('Location: a_equipos.php');
} catch (PDOException $e) {
    echo "Error al registrar el equipo: " . $e->getMessage();
}
?>
