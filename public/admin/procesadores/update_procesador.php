<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se haya recibido el ID del procesador
    if (!isset($_POST['id_procesador'])) {
        header('Location: procesador.php');
        exit;
    }

    $id_procesador = $_POST['id_procesador'];
    $procesador = $_POST['procesador'];

    try {
        // Actualizar el procesador en la base de datos
        $sql = "UPDATE t_procesador SET procesador = :procesador WHERE id_procesador = :id_procesador";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':procesador', $procesador, PDO::PARAM_STR);
        $stmt->bindParam(':id_procesador', $id_procesador, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir después de actualizar
        header('Location: procesador.php');
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: procesador.php');
    exit;
}
