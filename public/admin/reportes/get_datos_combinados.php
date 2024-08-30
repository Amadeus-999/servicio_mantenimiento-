<?php
require '../../../config/database.php';

if (isset($_GET['inventario']) && isset($_GET['npesonal'])) {
    $inventario = $_GET['inventario'];
    $npesonal = $_GET['npesonal'];

    // Consulta para obtener los datos del equipo
    $stmt1 = $pdo->prepare("
        SELECT a.*, 
               m1.marca AS marca_dd1, 
               m2.marca AS marca_dd2,
               m3.marca AS marca_memoria_1,
               m4.marca AS marca_memoria_2,
               m5.marca AS marca_memoria_3,
               m6.marca AS marca_memoria_4,
               m7.marca AS marca_monitor,
               mo1.modelo AS modelo_dd1,
               mo2.modelo AS modelo_dd2,
               mo3.modelo AS modelo_memoria_1,
               mo4.modelo AS modelo_memoria_2,
               mo5.modelo AS modelo_memoria_3,
               mo6.modelo AS modelo_memoria_4,
               mo7.modelo AS modelo_monitor,
               tm.tp_memoria
        FROM t_alta_equipo a
        LEFT JOIN t_marca_equipo m1 ON a.marca_dd1 = m1.id_marca
        LEFT JOIN t_marca_equipo m2 ON a.marca_dd2 = m2.id_marca
        LEFT JOIN t_marca_equipo m3 ON a.marca_memoria_1 = m3.id_marca
        LEFT JOIN t_marca_equipo m4 ON a.marca_memoria_2 = m4.id_marca
        LEFT JOIN t_marca_equipo m5 ON a.marca_memoria_3 = m5.id_marca
        LEFT JOIN t_marca_equipo m6 ON a.marca_memoria_4 = m6.id_marca
        LEFT JOIN t_marca_equipo m7 ON a.marca_monitor = m7.id_marca
        LEFT JOIN t_modelo_equipo mo1 ON a.modelo_dd1 = mo1.id_modelo
        LEFT JOIN t_modelo_equipo mo2 ON a.modelo_dd2 = mo2.id_modelo
        LEFT JOIN t_modelo_equipo mo3 ON a.modelo_memoria_1 = mo3.id_modelo
        LEFT JOIN t_modelo_equipo mo4 ON a.modelo_memoria_2 = mo4.id_modelo
        LEFT JOIN t_modelo_equipo mo5 ON a.modelo_memoria_3 = mo5.id_modelo
        LEFT JOIN t_modelo_equipo mo6 ON a.modelo_memoria_4 = mo6.id_modelo
        LEFT JOIN t_modelo_equipo mo7 ON a.modelo_monitor = mo7.id_modelo
        LEFT JOIN tipo_memoria tm ON a.tipo_memoria = tm.id_tmemoria
        WHERE a.inventario = ?");
    $stmt1->execute([$inventario]);
    $data_equipo = $stmt1->fetch(PDO::FETCH_ASSOC);

    // Consulta para obtener los datos del solicitante
    $stmt2 = $pdo->prepare("SELECT * FROM t_docente WHERE npesonal = ?");
    $stmt2->execute([$npesonal]);
    $data_solicitante = $stmt2->fetch(PDO::FETCH_ASSOC);

    // Combinar ambos resultados
    $data = array_merge($data_equipo, $data_solicitante);

    echo json_encode($data);
}
?>
