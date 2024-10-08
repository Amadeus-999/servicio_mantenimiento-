<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se haya recibido el ID del tipo de equipo
    if (!isset($_POST['id_tipo_equipo'])) {
        header('Location: equipo.php');
        exit;
    }

    $id_tipo_equipo = $_POST['id_tipo_equipo'];
    $tipo_equipo = $_POST['tipo_equipo'];

    try {
        // Actualizar el tipo de equipo en la base de datos
        $sql = "UPDATE t_tipo_equipo SET tipo_equipo = :tipo_equipo WHERE id_tipo_equipo = :id_tipo_equipo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tipo_equipo', $tipo_equipo, PDO::PARAM_STR);
        $stmt->bindParam(':id_tipo_equipo', $id_tipo_equipo, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir después de actualizar
        header('Location: equipo.php');
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: equipo.php');
    exit;
}
