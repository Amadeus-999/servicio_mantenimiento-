<?php
// Incluir archivo de configuración de base de datos
require '../../../config/database.php';


// Recibir datos del formulario
$inventario = $_POST['inventario'];
$falla_reportada = $_POST['falla_reportada'];
$reparacion = $_POST['acciones_realizadas'];
$id_docente = $_POST['npesonal']; // Asegúrate de que esto corresponde al id_docente

// Verificar si el id_docente existe en la tabla t_docente
try {
    $checkDocente = $pdo->prepare("SELECT COUNT(*) FROM t_docente WHERE id = :id_docente");
    $checkDocente->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);
    $checkDocente->execute();
    $docenteCount = $checkDocente->fetchColumn();

    if ($docenteCount == 0) {
        throw new Exception("El id_docente proporcionado no existe en la base de datos.");
    }

    // Preparar y ejecutar la consulta
    $sql = "INSERT INTO t_reporte (inventario, falla_reportada, reparacion, id_docente) VALUES (:inventario, :falla_reportada, :reparacion, :id_docente)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':inventario', $inventario, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_reportada', $fecha_reportada);
    $stmt->bindParam(':falla_reportada', $falla_reportada);
    $stmt->bindParam(':reparacion', $reparacion);
    $stmt->bindParam(':id_docente', $id_docente, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();
    
    echo "Reporte guardado correctamente.";
} catch (PDOException $e) {
    echo "Error al guardar el reporte: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>


