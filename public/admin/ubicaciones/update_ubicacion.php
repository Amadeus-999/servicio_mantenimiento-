<?php
require_once '../../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar que los campos requeridos están presentes
    if (isset($_POST['id_ubicacion'], $_POST['ubicacion'], $_POST['id_facultad'])) {
        $id_ubicacion = $_POST['id_ubicacion'];
        $ubicacion = $_POST['ubicacion'];
        $id_facultad = $_POST['id_facultad'];

        try {
            // Actualizar la ubicación en la base de datos
            $sql = "UPDATE t_ubicacion 
                    SET ubicacion = :ubicacion, id_facultad = :id_facultad 
                    WHERE id_ubicacion = :id_ubicacion";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_STR);
            $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
            $stmt->bindParam(':id_ubicacion', $id_ubicacion, PDO::PARAM_INT);
            $stmt->execute();

            // Redirigir con un mensaje de éxito
            $_SESSION['mensaje'] = "Ubicación actualizada con éxito.";
            header('Location: ubicacion.php');
            exit();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Faltan campos requeridos.";
    }
} else {
    header('Location: ubicacion.php');
    exit;
}
?>
