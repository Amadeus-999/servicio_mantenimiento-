<?php
// conectar a la base de datos
require '../../../config/database.php';

if (isset($_GET['inventario'])) {
    $inventario = $_GET['inventario'];
    $stmt = $pdo->prepare("SELECT * FROM t_alta_equipo WHERE inventario = ?");
    $stmt->execute([$inventario]);
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($equipo);
}
?>
