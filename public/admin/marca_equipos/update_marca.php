<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se haya recibido el ID de la marca
    if (!isset($_POST['id_marca'])) {
        header('Location: marca.php');
        exit;
    }

    $id_marca = $_POST['id_marca'];
    $marca = $_POST['marca'];

    try {
        // Actualizar la marca en la base de datos
        $sql = "UPDATE t_marca_equipo SET marca = :marca WHERE id_marca = :id_marca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':marca', $marca, PDO::PARAM_STR);
        $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir después de actualizar
        header('Location: marca.php');
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: marca.php');
    exit;
}
