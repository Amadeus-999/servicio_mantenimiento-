<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se haya recibido el ID de la facultad
    if (!isset($_POST['id_facultad'])) {
        header('Location: facultad.php');
        exit;
    }

    $id_facultad = $_POST['id_facultad'];
    $facultad = $_POST['facultad'];

    try {
        // Actualizar la facultad en la base de datos
        $sql = "UPDATE t_facultad SET facultad = :facultad WHERE id_facultad = :id_facultad";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':facultad', $facultad, PDO::PARAM_STR);
        $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir después de actualizar
        header('Location: facultad.php');
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: facultad.php');
    exit;
}
