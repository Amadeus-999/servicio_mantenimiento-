<?php
// conectar a la base de datos
require '../../../config/database.php';

if (isset($_GET['npesonal'])) {
    $npesonal = $_GET['npesonal'];
    $stmt = $pdo->prepare("SELECT * FROM t_docente WHERE npesonal = ?");
    $stmt->execute([$npesonal]);
    $docente = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($docente);
}
?>
