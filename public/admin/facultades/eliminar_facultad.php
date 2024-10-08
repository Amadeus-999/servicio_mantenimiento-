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
    $id_facultad = $_GET['id'];

    try {
        $pdo->beginTransaction(); // Inicia la transacción
        
        // Actualiza los registros de t_ubicacion que están asociados a la facultad eliminada
        $sqlUpdateUbicacion = "UPDATE t_ubicacion SET id_facultad = 6 WHERE id_facultad = :id_facultad";
        $stmtUpdateUbicacion = $pdo->prepare($sqlUpdateUbicacion);
        $stmtUpdateUbicacion->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
        $stmtUpdateUbicacion->execute();
    
        // Luego de actualizar los registros, elimina la facultad
        $sqlDeleteFacultad = "DELETE FROM t_facultad WHERE id_facultad = :id_facultad";
        $stmtDeleteFacultad = $pdo->prepare($sqlDeleteFacultad);
        $stmtDeleteFacultad->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
        $stmtDeleteFacultad->execute();
    
        $pdo->commit(); // Confirma la transacción
    
        $_SESSION['mensaje'] = "Facultad eliminada y registros relacionados actualizados con éxito.";
        header('Location: facultad.php');
        exit();
    
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se proporciona un ID válido, redirigimos con un mensaje de error
    $_SESSION['error'] = "ID de facultad inválido.";
    header('Location: facultad.php');
    exit();
}
