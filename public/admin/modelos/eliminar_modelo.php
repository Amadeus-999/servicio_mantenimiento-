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
    $id = $_GET['id'];

    try {
        $pdo->beginTransaction(); // Inicia la transacción

        

        // Eliminar el tipo de equipo en t_tipo_equipo
        $sqlDeletModelo = "DELETE FROM t_modelo_equipo WHERE id_modelo = :id_modelo";
        $stmtDeletModelo = $pdo->prepare($sqlDeletModelo);
        $stmtDeletModelo->bindParam(':id_modelo', $id, PDO::PARAM_INT);
        $stmtDeletModelo->execute();

        $pdo->commit(); // Confirma la transacción

        $_SESSION['mensaje'] = "modelo y registros relacionados actualizados con éxito.";
        header('Location: modelo.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage(); // Considera registrar el error en un log
    }
} else {
    // Si no se proporciona un ID válido, redirigimos con un mensaje de error
    $_SESSION['error'] = "ID de modelo inválido.";
    header('Location: modelo.php');
    exit();
}