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
    $id_marca = $_GET['id'];

    try {
        $pdo->beginTransaction(); // Inicia la transacción
    
       
    
        // Eliminar la ubicación después de haber eliminado los registros relacionados
        $sqlDeleteMarca = "DELETE FROM t_marca_equipo WHERE id_marca = :id_marca";
        $stmtDeleteMarca = $pdo->prepare($sqlDeleteMarca);
        $stmtDeleteMarca->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
        $stmtDeleteMarca->execute();
    
        $pdo->commit(); // Confirma la transacción
    
        $_SESSION['mensaje'] = "Marca y registros relacionados eliminados con éxito.";
        header('Location: marca.php');
        exit();
    
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage();
    }
    catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se proporciona un ID válido, redirigimos con un mensaje de error
    $_SESSION['error'] = "ID de Marca inválido.";
    header('Location: marca.php');
    exit();
}
