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
    $id_memoria = $_GET['id'];

    try {
        $pdo->beginTransaction(); // Inicia la transacción
        // Actualiza los registros relacionados en t_alta_equipo
        $sqlUpdateUbicacion = "UPDATE t_alta_equipo SET memoria_total = NULL WHERE memoria_total = :id_tmemoria";
        $stmtUpdateUbicacion = $pdo->prepare($sqlUpdateUbicacion);
        $stmtUpdateUbicacion->bindParam(':id_tmemoria', $id_memoria, PDO::PARAM_INT);
        $stmtUpdateUbicacion->execute();


        // Eliminar la ubicación después de haber eliminado los registros relacionados
        $sqlDeleteMemoria = "DELETE FROM  tipo_memoria WHERE id_tmemoria = :id_tmemoria";
        $stmtDeleteMemoria = $pdo->prepare($sqlDeleteMemoria);
        $stmtDeleteMemoria->bindParam(':id_tmemoria', $id_memoria, PDO::PARAM_INT);
        $stmtDeleteMemoria->execute();

        $pdo->commit(); // Confirma la transacción

        $_SESSION['mensaje'] = "Memoria y registros relacionados eliminados con éxito.";
        header('Location: memoria.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se proporciona un ID válido, redirigimos con un mensaje de error
    $_SESSION['error'] = "ID de ubicación inválido.";
    header('Location: memoria.php');
    exit();
}
