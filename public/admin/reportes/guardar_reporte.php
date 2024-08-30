<?php
require '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventario = $_POST['inventario'];
    $falla_reportada = $_POST['falla_reportada'];
    $reparacion = $_POST['reparacion'];
    $npesonal = $_POST['npesonal']; // Cambiado de id_docente a npesonal
    $fecha_reportada = date('Y-m-d');

    // Verificar si el npesonal existe en t_docente
    $stmt_check_docente = $pdo->prepare("SELECT id FROM t_docente WHERE npesonal = ?");
    $stmt_check_docente->execute([$npesonal]);
    $id_docente = $stmt_check_docente->fetchColumn();

    if ($id_docente) {
        $stmt = $pdo->prepare("INSERT INTO t_reporte (inventario, fecha_reportada, falla_reportada, reparacion, id_docente) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$inventario, $fecha_reportada, $falla_reportada, $reparacion, $id_docente]);

        // Redirigir o mostrar un mensaje de éxito
        header('Location: reporte_exitoso.php');
        exit();
    } else {
        echo "El número de personal (npesonal) no existe en la base de datos.";
    }
}
?>
