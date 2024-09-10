<?php
// conectar a la base de datos
require '../../../config/database.php';

if (isset($_GET['inventario'])) {
    $inventario = $_GET['inventario'];
    $stmt = $pdo->prepare("
        SELECT 
            e.inventario, e.activo, e.serie,
            e.disco_duro_1, e.serie_dd1, e.marca_dd1, e.modelo_dd1,
            e.disco_duro_2, e.serie_dd2, e.marca_dd2, e.modelo_dd2,
            e.marca_memoria_1, e.serie_memoria_1, e.marca_memoria_2, e.serie_memoria_2, 
            e.marca_memoria_3, e.serie_memoria_3, e.marca_memoria_4, e.serie_memoria_4, 
            e.tipo_equipo, e.ubicacion, e.marca, e.modelo,
            m.marca AS marca_equipo, 
            t.tipo_equipo AS tipo_equipo_nombre,
            u.ubicacion AS ubicacion_nombre,
            mo.modelo AS modelo_equipo
        FROM t_alta_equipo e
        LEFT JOIN t_marca_equipo m ON e.marca = m.id_marca
        LEFT JOIN t_tipo_equipo t ON e.tipo_equipo = t.id_tipo_equipo
        LEFT JOIN t_ubicacion u ON e.ubicacion = u.id_ubicacion
        LEFT JOIN t_modelo_equipo mo ON e.modelo = mo.id_modelo
        WHERE e.inventario = ?
");
    $stmt->execute([$inventario]);
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($equipo);
}
?>
