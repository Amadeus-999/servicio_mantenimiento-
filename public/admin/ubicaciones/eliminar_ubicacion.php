<?php
require_once '../../../config/database.php';
session_start(); // Asegúrate de que la sesión esté iniciada

// Verifica que el usuario esté logueado
if (!isset($_SESSION['user']['id_facultad'])) {
    // Redirige al login si no está logueado
    header('Location: login.php');
    exit;
}

// Verifica si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_ubicacion = $_GET['id'];

    try {
        $pdo->beginTransaction(); // Inicia la transacción
    
        // Actualiza los registros relacionados en t_alta_equipo
        $sqlUpdateUbicacion = "UPDATE t_alta_equipo SET ubicacion = NULL WHERE ubicacion = :id_ubicacion";
        $stmtUpdateUbicacion = $pdo->prepare($sqlUpdateUbicacion);
        $stmtUpdateUbicacion->bindParam(':id_ubicacion', $id_ubicacion, PDO::PARAM_INT);
        $stmtUpdateUbicacion->execute();
    
        // Eliminar la ubicación después de haber actualizado los registros relacionados
        $sqlDeleteUbicacion = "DELETE FROM t_ubicacion WHERE id_ubicacion = :id_ubicacion";
        $stmtDeleteUbicacion = $pdo->prepare($sqlDeleteUbicacion);
        $stmtDeleteUbicacion->bindParam(':id_ubicacion', $id_ubicacion, PDO::PARAM_INT);
        $stmtDeleteUbicacion->execute();
    
        $pdo->commit(); // Confirma la transacción
    
        $_SESSION['mensaje'] = "Ubicación y registros relacionados eliminados con éxito.";
        header('Location: ubicacion.php');
        exit();
    
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se proporciona un ID válido, redirigimos con un mensaje de error
    $_SESSION['error'] = "ID de ubicación inválido.";
    header('Location: ubicacion.php');
    exit();
}
?>
