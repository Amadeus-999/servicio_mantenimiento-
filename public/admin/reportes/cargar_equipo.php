<?php
// conectar a la base de datos
require '../../../config/database.php';

if (isset($_GET['inventario'])) {
    $inventario = $_GET['inventario'];
    $stmt = $pdo->prepare("
       SELECT 
            e.inventario, 
            e.serie AS serie, 
            e.activo AS activo,
            e.nombre_equipo AS nombre_equipo,
            u.ubicacion AS ubicacion_nombre,
            t.tipo_equipo AS tipo_equipo_nombre,
            m.marca AS marca_equipo,
            mo_eq.modelo AS modelo_equipo,
            p.procesador AS procesador_nombre,
            mem.memoria AS memoria_total_nombre,
            tm.tp_memoria AS tipo_memoria_nombre,
            mm.marca AS marca_monitor,
            mo_mon.modelo AS modelo_monitor,
            e.disco_duro_1, 
            m_dd1.marca AS marca_dd1, 
            e.serie_dd1, 
            mo_dd1.modelo AS modelo_dd1,
            e.disco_duro_2,
            m_dd2.marca AS marca_dd2,
            e.serie_dd2,
            mo_dd2.modelo AS modelo_dd2,
            em1.marca AS marca_memoria_1,
            e.serie_memoria_1,
            em2.marca AS marca_memoria_2,
            e.serie_memoria_2,
            em3.marca AS marca_memoria_3,
            e.serie_memoria_3,
            em4.marca AS marca_memoria_4,
            e.serie_memoria_4,
            tm.tp_memoria AS tipo_memoria,
            e.serie_monitor,
            e.foto_disco_duro,
            e.foto_memoria
        FROM t_alta_equipo e
        LEFT JOIN t_ubicacion u ON e.ubicacion = u.id_ubicacion
        LEFT JOIN t_tipo_equipo t ON e.tipo_equipo = t.id_tipo_equipo
        LEFT JOIN t_marca_equipo m ON e.marca = m.id_marca
        LEFT JOIN t_modelo_equipo mo_eq ON e.modelo = mo_eq.id_modelo -- Modelo del equipo
        LEFT JOIN t_procesador p ON e.procesador = p.id_procesador
        LEFT JOIN t_memoria mem ON e.memoria_total = mem.id_memoria
        LEFT JOIN tipo_memoria tm ON e.tipo_memoria = tm.id_tmemoria
        LEFT JOIN t_marca_equipo mm ON e.marca_monitor = mm.id_marca
        LEFT JOIN t_modelo_equipo mo_mon ON e.modelo_monitor = mo_mon.id_modelo -- Modelo del monitor
        LEFT JOIN t_marca_equipo m_dd1 ON e.marca_dd1 = m_dd1.id_marca -- Marca Disco Duro 1
        LEFT JOIN t_modelo_equipo mo_dd1 ON e.modelo_dd1 = mo_dd1.id_modelo -- Modelo Disco Duro 1
        LEFT JOIN t_marca_equipo m_dd2 ON e.marca_dd2 = m_dd2.id_marca -- Marca Disco Duro 2
        LEFT JOIN t_modelo_equipo mo_dd2 ON e.modelo_dd2 = mo_dd2.id_modelo -- Modelo Disco Duro 2
        LEFT JOIN t_marca_equipo em1 ON e.marca_memoria_1 = em1.id_marca -- Marca Memoria 1
        LEFT JOIN t_marca_equipo em2 ON e.marca_memoria_2 = em2.id_marca -- Marca Memoria 2
        LEFT JOIN t_marca_equipo em3 ON e.marca_memoria_3 = em3.id_marca -- Marca Memoria 3
        LEFT JOIN t_marca_equipo em4 ON e.marca_memoria_4 = em4.id_marca -- Marca Memoria 4
        WHERE e.inventario = ?

    ");
    $stmt->execute([$inventario]);
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($equipo);
}
