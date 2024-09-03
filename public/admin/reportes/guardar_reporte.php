<?php
// Incluir archivo de configuración de base de datos
require '../../../config/database.php';

// Recibir datos del formulario
$inventario = $_POST['inventario'];
$falla_reportada = $_POST['falla_reportada'];
$reparacion = $_POST['acciones_realizadas'];
$npesonal = $_POST['npesonal']; // Número personal del docente

// Verificar si el npesonal existe en la tabla t_docente
try {
    // Obtener el ID del docente basado en npesonal
    $checkDocente = $pdo->prepare("SELECT id FROM t_docente WHERE npesonal = :npesonal");
    $checkDocente->bindParam(':npesonal', $npesonal, PDO::PARAM_STR);
    $checkDocente->execute();
    $docente = $checkDocente->fetch(PDO::FETCH_ASSOC);

    if (!$docente) {
        throw new Exception("El npesonal proporcionado no existe en la base de datos.");
    }

    $id_docente = $docente['id']; // ID del docente obtenido

    // Preparar y ejecutar la consulta
    $sql = "INSERT INTO t_reporte (inventario, fecha_reportada, falla_reportada, reparacion, id_docente) VALUES (:inventario, NOW(), :falla_reportada, :reparacion, :id_docente)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':inventario', $inventario, PDO::PARAM_INT);
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

