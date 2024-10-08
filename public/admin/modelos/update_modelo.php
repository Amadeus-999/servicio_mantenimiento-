<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_modelo = isset($_POST['id_modelo']) ? (int)$_POST['id_modelo'] : 0;
    $modelo = isset($_POST['modelo']) ? trim($_POST['modelo']) : '';
    $tipo_equipo = isset($_POST['tipo_equipo']) ? (int)$_POST['tipo_equipo'] : 0;

    if (!empty($modelo) && $tipo_equipo > 0) {
        try {
            // Actualización en la tabla t_modelo_equipo
            $sql = "UPDATE t_modelo_equipo SET modelo = :modelo, id_tipo_equipo = :tipo_equipo WHERE id_modelo = :id_modelo";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':modelo', $modelo, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_equipo', $tipo_equipo, PDO::PARAM_INT);
            $stmt->bindParam(':id_modelo', $id_modelo, PDO::PARAM_INT);
            $stmt->execute();

            // Redirección después de la actualización exitosa
            header("Location: modelo.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "El nombre del modelo y el tipo de equipo deben ser seleccionados.";
    }
}
?>
