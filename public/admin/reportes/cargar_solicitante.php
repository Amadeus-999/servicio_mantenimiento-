<?php
// Conectar a la base de datos
require '../../../config/database.php';

if (isset($_GET['npesonal'])) {
    $npesonal = $_GET['npesonal'];
    
    // Preparar la consulta con JOIN
    $stmt = $pdo->prepare("
        SELECT d.*, f.facultad
        FROM t_docente d
        LEFT JOIN t_facultad f ON d.id_facultad = f.id_facultad
        WHERE d.npesonal = ?
    ");
    
    $stmt->execute([$npesonal]);
    $docente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Enviar los datos en formato JSON
    echo json_encode($docente);
}
?>

