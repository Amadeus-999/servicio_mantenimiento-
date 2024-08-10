<?php
require_once '../config/database.php';

class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate($npesonal, $password) {
        $sql = "SELECT * FROM t_docente WHERE npesonal = :npesonal";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['npesonal' => $npesonal]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>