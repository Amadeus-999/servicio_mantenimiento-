<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se haya recibido el ID de la memoria
    if (!isset($_POST['id_tmemoria'])) {
        header('Location: memoria.php');
        exit;
    }

    $id_memoria = $_POST['id_tmemoria'];
    $tp_memoria = $_POST['tp_memoria'];

    try {
        // Actualizar la memoria en la base de datos
        $sql = "UPDATE tipo_memoria SET tp_memoria = :tp_memoria WHERE id_tmemoria = :id_tmemoria";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tp_memoria', $tp_memoria, PDO::PARAM_STR);
        $stmt->bindParam(':id_tmemoria', $id_memoria, PDO::PARAM_INT);
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
