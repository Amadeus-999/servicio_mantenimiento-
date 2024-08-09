<?php
require_once '../src/models/UserModel.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npesonal = $_POST['npesonal'];
    $password = $_POST['password'];

    $userModel = new UserModel($pdo);
    $user = $userModel->authenticate($npesonal, $password);

    if ($user) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Credenciales incorrectas';
    }
}
?>

