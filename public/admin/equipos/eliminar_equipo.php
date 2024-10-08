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
    $id_tipo = $_GET['id'];

    try {
        $pdo->beginTransaction(); // Inicia la transacción

        // Actualiza los registros de t_ubicacion que están asociados a la facultad eliminada
        $sqlUpdateUbicacion = "UPDATE t_modelo_equipo SET id_tipo_equipo = NULL  WHERE id_tipo_equipo = :id_tipo_equipo";
        $stmtUpdateUbicacion = $pdo->prepare($sqlUpdateUbicacion);
        $stmtUpdateUbicacion->bindParam(':id_tipo_equipo', $id_tipo, PDO::PARAM_INT);
        $stmtUpdateUbicacion->execute();
    

        // Eliminar la ubicación después de haber eliminado los registros relacionados
        $sqlDeleteTipo = "DELETE FROM t_tipo_equipo WHERE id_tipo_equipo = :id_tipo_equipo";
        $stmtDeleteTipo = $pdo->prepare($sqlDeleteTipo);
        $stmtDeleteTipo->bindParam(':id_tipo_equipo', $id_tipo, PDO::PARAM_INT);
        $stmtDeleteTipo->execute();

        $pdo->commit(); // Confirma la transacción

        $_SESSION['mensaje'] = "Tipo equipo y registros relacionados eliminados con éxito.";
        header('Location: equipo.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage(); // Considera registrar el error en un log
    }
} else {
    // Si no se proporciona un ID válido, redirigimos con un mensaje de error
    $_SESSION['error'] = "ID de equipo inválido.";
    header('Location: equipo.php');
    exit();
}

