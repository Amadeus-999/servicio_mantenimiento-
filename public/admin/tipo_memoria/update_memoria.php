<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se haya recibido el ID de la memoria
    if (!isset($_POST['id_memoria'])) {
        header('Location: memoria.php');
        exit;
    }

    $id_memoria = $_POST['id_memoria'];
    $tp_memoria = $_POST['memoria'];

    try {
        // Actualizar la memoria en la base de datos
        $sql = "UPDATE t_memoria SET memoria = :memoria WHERE id_memoria = :id_memoria";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':memoria', $tp_memoria, PDO::PARAM_STR);
        $stmt->bindParam(':id_memoria', $id_memoria, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir después de actualizar
        header('Location: memoria.php');
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: memoria.php');
    exit;
}
