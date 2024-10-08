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
        $sqlDeletDocente = "DELETE FROM t_alta_equipo WHERE inventario = :inventario";
        $stmtDeletDocente = $pdo->prepare($sqlDeletDocente);
        $stmtDeletDocente->bindParam(':inventario', $id, PDO::PARAM_INT);
        $stmtDeletDocente->execute();

        $pdo->commit(); // Confirma la transacción

        $_SESSION['mensaje'] = "Equipo y registros relacionados actualizados con éxito.";
        header('Location: a_equipos.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage(); // Considera registrar el error en un log
    }
} else {
    // Si no se proporciona un ID válido, redirigimos con un mensaje de error
    $_SESSION['error'] = "ID de equipo inválido.";
    header('Location: a_equipos.php');
    exit();
}