<?php
require_once '../../../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $npesonal_original = isset($_POST['npesonal_original']) ? trim($_POST['npesonal_original']) : ''; // número original
    $npesonal_nuevo = isset($_POST['npesonal']) ? trim($_POST['npesonal']) : ''; // número nuevo
    $nombre = isset($_POST['nombre']) ? strtoupper(trim($_POST['nombre'])) : '';
    $apellido_p = isset($_POST['apellido_p']) ? strtoupper(trim($_POST['apellido_p'])) : '';
    $apellido_m = isset($_POST['apellido_m']) ? strtoupper(trim($_POST['apellido_m'])) : '';
    $extension = isset($_POST['extension']) ? trim($_POST['extension']) : null;
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $id_facultad = isset($_POST['id_facultad']) ? $_POST['id_facultad'] : null;
    $tipo_usuario = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : 0;
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Verificar si el npesonal nuevo ya existe en la base de datos (evitar duplicados)
    if ($npesonal_original !== $npesonal_nuevo) {
        $sql_check = "SELECT npesonal FROM t_docente WHERE npesonal = :npesonal_nuevo";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':npesonal_nuevo', $npesonal_nuevo, PDO::PARAM_STR);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            // El nuevo npesonal ya existe, manejar el error
            echo "El número personal ya está registrado. Intenta con otro.";
            exit;
        }
    }

    // Preparar la consulta para actualizar el docente
    $sql = "UPDATE t_docente SET 
                npesonal = :npesonal_nuevo, 
                nombre = :nombre,
                apellido_p = :apellido_p,
                apellido_m = :apellido_m,
                extension = :extension,
                correo = :correo,
                id_facultad = :id_facultad,
                tipo_usuario = :tipo_usuario";

    // Solo actualizar la contraseña si se ha proporcionado una nueva
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = :password";
    }

    $sql .= " WHERE npesonal = :npesonal_original";

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);
    
    // Asignar los parámetros
    $stmt->bindParam(':npesonal_nuevo', $npesonal_nuevo, PDO::PARAM_STR);
    $stmt->bindParam(':npesonal_original', $npesonal_original, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':apellido_p', $apellido_p, PDO::PARAM_STR);
    $stmt->bindParam(':apellido_m', $apellido_m, PDO::PARAM_STR);
    $stmt->bindParam(':extension', $extension, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_usuario', $tipo_usuario, PDO::PARAM_INT);
    
    // Solo enlazar el parámetro de la contraseña si se va a actualizar
    if (!empty($password)) {
        $stmt->bindParam(':password', $password_hashed, PDO::PARAM_STR);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de docentes tras la actualización
        header('Location: docentes.php?success=1');
        exit;
    } else {
        // Manejo de errores
        echo "Error al actualizar el docente.";
    }
} else {
    header('Location: docentes.php');
    exit;
}
?>
